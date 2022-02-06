<table class='table table-striped table-bordered table-hover'><thead><tr><td>     
From <b><?php echo $data['sender']; ?></b> at <?php echo $data["added"]; ?> (<?php $data['elapsed']; ?> ago) GMT <br><br>
<b>Subject: <?php echo $data['subject']; ?></b><br>
<b>Answered:</b> <?php echo $data['answered']; ?>&nbsp;&nbsp;<?php echo $data['setanswered']; ?><br><br>
<?php echo format_comment($data["msg"]); ?><br><br>
<?php
print(($data["sender1"] ? "<a href=" . URLROOT . "/admincontact/reply?receiver=" . $data["sender1"] . "&answeringto=$data[iidee]><b>Reply</b></a>" : "<font class=gray><b>Guest</b></font>") . " | <a href=" . URLROOT . "/admincontact/deletestaffmessage?id=" . $data["id"] . "><b>Delete</b></a></td>");
?>
</td></tr></table>