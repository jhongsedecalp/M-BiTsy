<?php
if ($_GET['type'] == "news") { ?>
    <div class="ttform">
        <?php echo htmlspecialchars($data['newstitle']) . "<br /><br />" . format_comment($data['newsbody']) . "<br />"; ?><br>
    </div><br> <?php
}
if ($_GET['type'] == "torrent") {
    echo torrentmenu($data['id']);
} ?>
<p class='text-center'><a href='<?php echo URLROOT ?>/comment/add?type=<?php echo $data['type'] ?>&id=<?php echo $data['id'] ?>'><b>Add Comment</b></a></p>
<?php
if ($data['commcount']) {
    commenttable($data['commres'], $data['type']);
    print($data['pagerbuttons']);
} else {
    print("<br><center><b>" . Lang::T("NOCOMMENTS") . "</b></center><br>\n");
} ?>
<form name='comment' method='post' action="<?php echo URLROOT ?>/comment/take?type=<?php echo $data['type'] ?>&id=<?php echo $data['id'] ?>">
<div class='text-center'>
    <?php echo textbbcode("comment", "body") . "<br>"; ?>
    <input type="submit" class="btn ttbtn" value="<?php echo Lang::T("ADDCOMMENT") ?>">
</div>
</form>