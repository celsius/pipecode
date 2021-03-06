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
include("poll.php");

$date = $s2;
$slug = $s3;
$time_beg = strtotime("$date GMT");
if ($time_beg === false) {
	die("invalid date [$date]");
}
$time_end = $time_beg + 86400;
$row = sql("select poll_id from poll where time > ? and time < ? and slug = ? order by time", $time_beg, $time_end, $slug);
if (count($row) == 0) {
	die("poll not found - date [$date] title [$slug]");
}
$poll_id = $row[0]["poll_id"];

$poll = db_get_rec("poll", $poll_id);
$clean = clean_url($poll["question"]);
$type_id = $poll["type_id"];

if ($auth_zid == "") {
	$can_moderate = true;
	$hide_value = $auth_user["hide_threshold"];
	$expand_value = $auth_user["expand_threshold"];
} else {
	$can_moderate = false;
	$hide_value = -1;
	$expand_value = 0;
}

print_header("Poll");
print_left_bar("main", "poll");
beg_main("cell");

vote_box($poll_id, false);

if ($auth_user["javascript_enabled"]) {
	render_sliders("poll", $poll_id);
	print_noscript();
} else {
	render_page("poll", $poll_id, false);
}

end_main();

$last_seen = update_view_time("poll", $poll_id);

if ($auth_user["javascript_enabled"]) {
//	if ($auth_zid == "") {
//		$last_seen = 0;
//	} else {
//		if (db_has_rec("poll_view", array("poll_id" => $poll_id, "zid" => $auth_zid))) {
//			$view = db_get_rec("poll_view", array("poll_id" => $poll_id, "zid" => $auth_zid));
//			$view["last_time"] = $view["time"];
//			$last_seen = $view["time"];
//		} else {
//			$view = array();
//			$view["poll_id"] = $poll_id;
//			$view["zid"] = $auth_zid;
//			$view["last_time"] = 0;
//			$last_seen = 0;
//		}
//		$view["time"] = time();
//		db_set_rec("poll_view", $view);
//	}

	writeln('<script>');
	writeln();
	writeln('var hide_value = ' . $hide_value . ';');
	writeln('var expand_value = ' . $expand_value . ';');
	writeln('var auth_zid = "' . $auth_zid . '";');
	writeln('var last_seen = ' . $last_seen . ';');
	writeln();
	writeln('get_comments("poll", "' . $poll_id . '");');
	writeln('render_page();');
	writeln();
	writeln('</script>');
}

print_footer();
