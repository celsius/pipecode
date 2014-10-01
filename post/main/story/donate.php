<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

if ($auth_zid === "") {
	die("sign in to donate");
}
if (string_uses($s2, "[A-Z][a-z][0-9]")) {
	$short_id = crypt_crockford_decode($s2);
	$short = db_get_rec("short", $short_id);
	if ($short["type"] != "story") {
		die("invalid short code [$s2]");
	}
	$story_id = $short["item_id"];
} else if (string_uses($s2, "[a-z][0-9]_")) {
	$story_id = $s2;
} else {
	die("unknown story [$s2]");
}

$story = db_get_rec("story", $story_id);
$pipe = db_get_rec("pipe", $story["pipe_id"]);
$link = item_link("story", $story_id);

$amount = http_post_string("amount", array("len" => 20, "valid" => "[0-9]."));
$amount = (int) ((float) $amount * 100);
if ($amount <= 0) {
	die("donation too small");
}
if ($story["author_zid"] === "") {
	$amount_author = 0;
} else {
	$amount_author = (int) floor($amount * 0.6);
}
$amount_editor = (int) floor($amount * 0.2);
$amount_server = $amount - $amount_author - $amount_editor;

if (http_post("confirm")) {
	$password = http_post_string("password", array("len" => 64, "valid" => "[KEYBOARD]"));
	if ($auth_user["password"] != crypt_sha256($password . $auth_user["salt"])) {
		die("wrong password");
	}
	die("post");
}

print_header("Confirm Donation");
print_left_bar("main", "stories");
beg_main("cell");
beg_form();

writeln('<h1>Story</h1>');
writeln('<a class="icon_16 news_16" href="' . $link . '" style="margin-bottom: 8px;">' . $story["title"] . '</a>');

writeln('<h2>Your Donation</h2>');
writeln('<div class="icon_32 coins_32">$' . format_money($amount) . '</div>');
writeln('<input type="hidden" name="amount" value="' . $_POST["amount"] . '"/>');

writeln('<h2>Recipients</h2>');
beg_tab();
if ($amount_author > 0) {
	writeln('	<tr>');
	writeln('		<td>Author</td>');
	writeln('		<td class="center">' . user_page_link($story["author_zid"], true) . '</td>');
	writeln('		<td class="right">$' . format_money($amount_author) . '</td>');
	writeln('	</tr>');
}
if ($amount_editor > 0) {
	writeln('	<tr>');
	writeln('		<td>Editor</td>');
	writeln('		<td class="center">' . user_page_link($pipe["edit_zid"], true) . '</td>');
	writeln('		<td class="right">$' . format_money($amount_editor) . '</td>');
	writeln('	</tr>');
}
if ($amount_server > 0) {
	writeln('	<tr>');
	writeln('		<td>Server</td>');
	writeln('		<td class="center">' . user_page_link($server_zid, true) . '</td>');
	writeln('		<td class="right">$' . format_money($amount_server) . '</td>');
	writeln('	</tr>');
}
end_tab();

writeln('<h2>Enter your password to confirm donation</h2>');
beg_tab();
print_row(array("caption" => "Password", "password_key" => "password"));
end_tab();

right_box("Confirm");

end_form();
end_main();
print_footer();
