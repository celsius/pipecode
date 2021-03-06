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

include("render.php");

$comment_id = $s2;
if (!string_uses($comment_id, "[a-z][0-9]_")) {
	die("invalid comment_id [$comment_id]");
}
$comment = db_get_rec("comment", $comment_id);
$can_moderate = false;
$a = article_info($comment);

print_header($comment["subject"]);

print_left_bar("main", "stories");
beg_main("cell");

writeln('<h1>' . ucwords($a["type"]) . '</h1>');
writeln('<a href="' . $a["link"] . '">' . $a["title"] . '</a>');

writeln('<h2>Preview</h2>');

$list = array($comment);
while ($comment["parent_id"] != "") {
	$comment = db_get_rec("comment", $comment["parent_id"]);
	$list[] = $comment;
}

$s = "";
for ($i = count($list) - 1; $i >= 0; $i--) {
	$s .= render_comment($list[$i]["subject"], $list[$i]["zid"], $list[$i]["time"], $list[$i]["comment_id"], $list[$i]["body"], 0, $list[$i]["short_id"]);
}
$s .= str_repeat("</div>\n</article>\n", count($list));
writeln($s);

end_main();
