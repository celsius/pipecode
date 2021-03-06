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

include("feed.php");

if ($zid == $auth_zid) {
	print_header("", array("Edit"), array("news"), array("/feed/edit"));
	print_feed_page($zid);
} else {
	print_header();
	print_left_bar("user", "overview");
	beg_main("cell");

	writeln('<h1>' . $zid . '</h1>');

	writeln('<table style="border: 1px #d3d3d3 solid; margin-bottom: 8px;">');
	writeln('	<tr>');
	writeln('		<td style="background-color: #eeeeee; padding: 8px;"><img style="width: 128px\" src="' . profile_picture($zid, 256) . '"/></td>');
	writeln('	</tr>');
	writeln('</table>');

	end_main();
}

print_footer();

