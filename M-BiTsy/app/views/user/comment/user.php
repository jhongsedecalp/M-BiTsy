<?php
$commcount = DB::column('comments', 'count(*)', ['user'=>$data['id']]);
if ($commcount) {
    list($pagerbuttons, $limit) = Pagination::pager(20, $commcount, URLROOT."/comment/user?id=$data[id]&");
    $commres = DB::run("SELECT comments.id, text, user, comments.added, avatar, signature, username, title, class, uploaded, downloaded, privacy, donated FROM comments LEFT JOIN users ON comments.user = users.id WHERE user = $data[id] ORDER BY comments.id $limit"); // $limit
} else {
    unset($commres);
}
if ($commcount) {
    print($pagerbuttons);
    commenttable($commres);
    print($pagerbuttons);
} else {
    print("<br><b>" . Lang::T("NOCOMMENTS") . "</b><br>\n");
}