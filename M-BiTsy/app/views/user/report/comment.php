<div class='ttform'>
<b>Are you sure you would like to report Comment: ?</b><br /><br />
<b><?php echo format_comment($data["text"]); ?></b><br />
<p>Please note, this is <b>not</b> to be used to report leechers, we have scripts in place to deal with them</p>
<b>Reason</b> (required): 
<form method='post' action='<?php echo URLROOT; ?>/report/comment?type=<?php echo $data["type"]; ?>'>
<input type='hidden' name='comment' value='<?php echo $data['comment']; ?>' /><br>
<p class='text-center'>
    <input type='text' class="form-control" size='100' name='reason' />
    <input type='submit' class="btn btn-sm ttbtn" value='Confirm' /></p>
</form>
</div>