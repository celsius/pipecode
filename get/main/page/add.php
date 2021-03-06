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

if (!$auth_user["admin"]) {
	die("not an admin");
}

print_header("Add Page");
beg_main();
beg_form();

beg_tab("Add Page");
print_row(array("caption" => "Title", "text_key" => "title"));
print_row(array("caption" => "Slug", "text_key" => "slug"));
end_tab();

right_box("Add");

end_form();
end_main();
print_footer();
