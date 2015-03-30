<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2015 Bryan Beicker <bryan@pipedot.org>
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

include("clean.php");
include("story.php");

if (!$auth_user["editor"]) {
	die("you are not an editor");
}

$story = item_request("story");
$zid = $story["author_zid"];

$title = clean_subject();
list($clean_body, $dirty_body) = clean_body(true, "story");
$icon = http_post_string("icon", array("len" => 50, "valid" => "[a-z][0-9]-_"));
$keywords = http_post_string("keywords", ["required" => false, "len" => 100, "valid" => "[A-Z][a-z][0-9]-_+ "]);
$keywords = strtolower($keywords);
$tid = http_post_int("tid");
$time = time();

if (http_post("publish")) {
	db_set_rec("story_edit", $story);

	$story["body"] = $clean_body;
	$story["edit_time"] = $time;
	$story["edit_zid"] = $auth_zid;
	$story["icon"] = $icon;
	$story["keywords"] = $keywords;
	$story["slug"] = clean_url($title);
	$story["tid"] = $tid;
	$story["title"] = $title;
	db_set_rec("story", $story);

	header("Location: /story/{$story["short_code"]}");
	die();
}

print_story_box($story["story_id"], $tid, $icon, $title, $clean_body, $dirty_body, $zid);
