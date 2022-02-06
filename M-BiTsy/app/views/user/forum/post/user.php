<?php
foreach ($data['res'] as $row) {
    $postername = Users::coloredname($row["username"]);
    if ($postername == "") {
        $postername = Lang::T("DELUSER");
        $title = Lang::T("DELETED_ACCOUNT");
        $avatar = "";
        $usersignature = "";
        $userdownloaded = "";
        $useruploaded = "";
    } else {
        $privacylevel = $row["privacy"];
        $avatar = htmlspecialchars($row["avatar"]);
        $title = format_comment($row["title"]);
        $usersignature = stripslashes(format_comment($row["signature"]));
        $userdownloaded = mksize($row["downloaded"]);
        $useruploaded = mksize($row["uploaded"]);
    }
    if ($row["downloaded"] > 0) {
        $userratio = number_format($row["uploaded"] / $row["downloaded"], 2);
    } else {
        $userratio = "---";
    }
    if (!$avatar) {
        $avatar = URLROOT . "/assets/images/misc/default_avatar.png";
    }
    $commenttext = format_comment($row["body"]);
    $edit = null;
    if (Users::get("edit_torrents") == "yes" || Users::get("edit_news") == "yes" || Users::get('id') == $row['user']) {
        $edit = '[<a href="' . URLROOT . '/post/edit&amp;postid=' . $row['id'] . '">Edit</a>]&nbsp;';
    }
    $delete = null;
    if (Users::get("delete_torrents") == "yes" || Users::get("delete_news") == "yes") {
        $delete = '[<a href="' . URLROOT . '/post/delete&amp;postid=' . $row['id'] . '&amp;sure=0">Delete</a>]&nbsp;';
    }

    print('<div class="table"><table class="table table-striped" style="border: 1px solid black" >');
    print('<thead><tr">');
    print('<th align="center" width="150"></th>');
    print('<th align="right">' . $edit . $delete . '[<a href="' . URLROOT . '/report/forum?forumid=' . $row['topicid'] . '&amp;forumpost=' . $row['id'] . '">Report</a>] Posted: ' . date("d-m-Y \\a\\t H:i:s", TimeDate::utc_to_tz_time($row["added"])) . '<a id="comment' . $row["id"] . '"></a></th>');
    print('</tr></thead>');
    print('<tr valign="top">');
    if (Users::get('edit_users') == 'no' && $privacylevel == 'strong') {
        print('<td align="left" width="150"><center><a href="' . URLROOT . '/profile?id=' . $row['id'] . '"><b>' . $postername . '</b></a><br /><i>' . $title . '</i><br /><img width="80" height="80" src="' . $avatar . '" alt="" /><br /><br />Uploaded: ---<br />Downloaded: ---<br />Ratio: ---<br /><br /><a href="' . URLROOT . '/profile?id=' . $row["user"] . '"><i class="fa fa-user tticon" title="Profile"></i></a> <a href="' . URLROOT . '/message/create?id=' . $row["user"] . '"><i class="fa fa-comment" title="Send PM"></i></a></center></td>');
    } else {
        print('<td align="left" width="150"><center><a href="' . URLROOT . '/profile?id=' . $row['id'] . '"><b>' . $postername . '</b></a><br /><i>' . $title . '</i><br /><img width="80" height="80" src="' . $avatar . '" alt="" /><br /><br />Uploaded: ' . $useruploaded . '<br />Downloaded: ' . $userdownloaded . '<br />Ratio: ' . $userratio . '<br /><br /><a href="' . URLROOT . '/profile?id=' . $row["user"] . '"><i class="fa fa-user" title="Profile"></i></a> <a href="/message/create?id=' . $row["user"] . '"><i class="fa fa-comment" title="Send PM"></i></a></center></td>');
    }

    print('<td>' . $commenttext . '<hr />' . $usersignature . '</td>');
    print('</tr>');
    print('</table></div>');
}
print($data['pagerbuttons']);