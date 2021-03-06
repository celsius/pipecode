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

include("clean.php");
include("story.php");
include("publish.php");

$pipe_id = $s2;
if (!string_uses($pipe_id, "[a-z][0-9]_")) {
	die("invalid pipe_id [$pipe_id]");
}

if (!$auth_user["editor"]) {
	die("you are not an editor");
}

$pipe = db_get_rec("pipe", $pipe_id);
$zid = $pipe["author_zid"];
$title = $pipe["title"];
$tid = $pipe["tid"];
$icon = $pipe["icon"];
$clean_body = $pipe["body"];
$dirty_body = dirty_html($clean_body);

print_publish_box($pipe_id, $tid, $icon, $title, $clean_body, $dirty_body, $zid);
