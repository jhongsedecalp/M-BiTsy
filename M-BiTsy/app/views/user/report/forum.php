<div class='ttform'>
<b>Are you sure you would like to report the following forum post:</b><br />
<a href='<?php echo URLROOT; ?>/topic?topicid=<?php echo $data['forumid']; ?>&amp;page=p#post<?php echo $data['forumpost']; ?>'>
<br><b><?php echo $data['subject']; ?></b></a><br />
<b>Reason</b> (required): <form method='post' action='<?php echo URLROOT; ?>/report/forum'>
<input type='hidden' name='forumid' value='<?php echo $data['forumid']; ?>' />
<input type='hidden' name='forumpost' value='<?php echo $data['forumpost']; ?>'>
<input class="form-control" type='text' size='100' name='reason' /><br>
<p class='text-center'><input class="btn btn-sm ttbtn" type='submit'  value='Confirm' /></p>
</form>
</div>