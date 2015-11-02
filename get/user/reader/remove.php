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

require_mine();

$feed = item_request(TYPE_READER);

print_header("Remove Feed", [], [], [], ["Reader", $feed["name"], "Remove Feed"], ["/reader/", "/reader/" . $feed["slug"], "/reader/" . $feed["slug"] . "/remove"]);
beg_main();
beg_form();
writeln('<h1>' . get_text('Remove Feed') . '</h1>');
writeln('<p>' . get_text('Are you sure you want to remove the [<b>$1</b>] feed?', $feed["name"]) . '</p>');

box_left("Remove");

end_form();
end_main();
print_footer();

