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

if ($zid != $auth_zid) {
	die("not your page");
}

$zones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

$javascript_enabled = http_post_bool("javascript_enabled", array("numeric" => true));
$wysiwyg_enabled = http_post_bool("wysiwyg_enabled", array("numeric" => true));
$time_zone = http_post_string("time_zone", array("len" => 50, "valid" => "[a-z][A-Z]-_/"));
$hide_threshold = http_post_string("hide_threshold", array("valid" => "[0-9]-"));
$expand_threshold = http_post_string("expand_threshold", array("valid" => "[0-9]-"));
$real_name = http_post_string("real_name", array("len" => 50, "required" => false, "valid" => "[a-z][A-Z]- "));
$email = http_post_string("email", array("len" => 50, "valid" => "[a-z][A-Z][0-9]@.-_+"));
$list_enabled = http_post_bool("list_enabled", array("numeric" => true));
$story_image_style = http_post_int("story_image_style");

if (!in_array($time_zone, $zones)) {
	die("invalid time zone [$time_zone]");
}

$user_conf["javascript_enabled"] = $javascript_enabled;
$user_conf["wysiwyg_enabled"] = $wysiwyg_enabled;
$user_conf["time_zone"] = $time_zone;
$user_conf["story_image_style"] = $story_image_style;
$user_conf["hide_threshold"] = $hide_threshold;
$user_conf["expand_threshold"] = $expand_threshold;
$user_conf["real_name"] = $real_name;
$user_conf["email"] = $email;
$user_conf["list_enabled"] = $list_enabled;

db_set_conf("user_conf", $user_conf, $auth_zid);

header("Location: /menu/");
