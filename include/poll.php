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

function vote_box($poll_id, $vote)
{
	global $auth_zid;

	$poll = db_get_rec("poll", $poll_id);
	$poll_code = crypt_crockford_encode($poll_id);
	$clean = clean_url($poll["question"]);
	$type_id = $poll["type_id"];
	$time = $poll["publish_time"];
	$day = gmdate("Y-m-d", $time);
	writeln('<div class="dialog-title">Poll</div>');
	writeln('<div class="dialog-body">');

	$poll_answer = db_get_list("poll_answer", "position", array("poll_id" => $poll["poll_id"]));
	$k = array_keys($poll_answer);

	$comments = count_comments("poll", $poll_id);

	if ($vote) {
		beg_form("/poll/$poll_code/vote");
		writeln('	<div class="poll-question">' . $poll["question"] . '</div>');

		writeln('	<table class="poll-table">');
		for ($i = 0; $i < count($poll_answer); $i++) {
			$answer = $poll_answer[$k[$i]];
			$aid = str_replace(".", "_", $answer["answer_id"]);
			$aid = str_replace("-", "_", $aid);
			writeln('		<tr>');
			if ($type_id == 1) {
				$units = "votes";
				writeln('			<td><input id="a_' . $aid . '" name="answer_id" value="' . $answer["answer_id"] . '" type="radio"/></td>');
			} else if ($type_id == 2) {
				$units = "votes";
				writeln('			<td><input id="a_' . $aid . '" name="answer_id[]" value="' . $answer["answer_id"] . '" type="checkbox"/></td>');
			} else if ($type_id == 3) {
				$units = "points";
				writeln('			<td><input id="a_' . $aid . '" name="answer_id[' . $answer["answer_id"] . ']" type="text"/></td>');
			} else {
				die("unknown poll type [$type_id]");
			}
			writeln('			<td><label for="a_' . $aid . '">' . $answer["answer"] . '</label></td>');
			writeln('		</tr>');
		}
		writeln('	</table>');

		if ($type_id == 1 || $type_id == 2) {
			$row = sql("select count(zid) as votes from poll_vote where poll_id = ?", $poll_id);
			$votes = $row[0]["votes"];
		} else {
			$row = sql("select sum(points) as votes from poll_vote where poll_id = ?", $poll_id);
			$votes = (int) $row[0]["votes"];
		}

		writeln('	<table class="fill">');
		writeln('		<tr>');
		writeln('			<td style="width: 40px"><input type="submit" value="Vote"/></td>');
		writeln('			<td style="white-space: nowrap;"><a href="/poll/' . $day . '/' . $clean . '">' . $comments["tag"] . '</a></td>');
		writeln('			<td class="right" style="white-space: nowrap;"><b>' . $votes . '</b> ' . $units . '</td>');
		writeln('		</tr>');
		writeln('	</table>');

		end_form();
	} else {
		$total = 0;
		$votes = array();
		writeln('	<table style="width: 100%">');
		writeln('		<tr>');
		writeln('			<td class="poll-question">' . $poll["question"] . '</td>');
		writeln('		</tr>');
		if ($type_id == 1 || $type_id == 2) {
			for ($i = 0; $i < count($poll_answer); $i++) {
				$answer = $poll_answer[$k[$i]];

				$row = sql("select count(*) as votes from poll_vote where poll_id = ? and answer_id = ?", $poll_id, $answer["answer_id"]);
				$votes[] = $row[0]["votes"];
				$total += $row[0]["votes"];
			}
			//if ($total == 1) {
				$units = "votes";
			//} else {
			//	$units = "votes";
			//}
		} else if ($type_id == 3) {
			for ($i = 0; $i < count($poll_answer); $i++) {
				$answer = $poll_answer[$k[$i]];

				$row = sql("select sum(points) as votes from poll_vote where poll_id = ? and answer_id = ?", $poll_id, $answer["answer_id"]);
				$votes[] = $row[0]["votes"];
				$total += $row[0]["votes"];
			}
			//if ($total == 1) {
				$units = "points";
			//} else {
			//	$units = "points";
			//}
		}

		for ($i = 0; $i < count($poll_answer); $i++) {
			$answer = $poll_answer[$k[$i]];
			if ($total == 0) {
				$percent = 0;
			} else {
				$percent = round(($votes[$i] / $total) * 100);
			}

			writeln('		<tr>');
			writeln('			<td class="poll-answer">' . $answer["answer"] . '</td>');
			writeln('		</tr>');
			writeln('		<tr>');
			writeln('			<td><table class="poll-result"><tr><th style="width: ' . $percent . '%"></th><td style="width: ' . (100 - $percent) . '%">' . $votes[$i] . " $units ($percent%)" . '</td></tr></table></td>');
			writeln('		</tr>');
		}
		writeln('	</table>');

		//$poll_code = crypt_crockford_encode($poll["poll_id"]);

		writeln('	<div class="poll-footer">');
		writeln('		<div><a href="/poll/' . $day . '/' . $clean . '">' . $comments["tag"] . '</a></div>');
		writeln('		<div class="poll-short">(<a href="/' . $poll_code . '">#' . $poll_code . '</a>)</div>');
		if ($auth_zid === "") {
			writeln('		<div class="right"><b>' . $total . '</b> ' . $units . '</div>');
		} else {
			writeln('		<div class="right"><a href="/poll/' . $poll_code . '/vote"><b>' . $total . '</b> ' . $units . '</a></div>');
		}
		writeln('	</div>');
	}
	writeln('</div>');
}

