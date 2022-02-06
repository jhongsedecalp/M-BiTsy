<div class='ttform'>
<b>Are you sure you would like to report torrent: ?</b><br />
<a href='<?php echo URLROOT; ?>/torrent?id=$torrent'><b><?php echo $data['name']; ?></b></a><br />
<b>Reason</b> (required): 
<form method='post' action='<?php echo URLROOT; ?>/report/torrent'>
<input type='hidden' name='torrent' value='<?php echo $data['torrent']; ?>' />
<input class="form-control" type='text' size='100' name='reason' /><br>
<p class='text-center'><input class="btn btn-sm ttbtn" type='submit' value='Confirm' /></p>
</form>
</div>