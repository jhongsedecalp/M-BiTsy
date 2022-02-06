<form name='shoutboxform' action='<?php echo URLROOT ?>/shoutbox/edit?id=<?php echo $data['id'] ?>&user=<?php echo $data['user'] ?>' method='post'>
<input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
<br>
<div class="row">
    <div class="col-md-12">
        <?php
        echo textbbcode("shoutboxform", "message", $data['message']); ?>
    </div>
</div>
<center>
<button type='submit' class='btn btn-sm ttbtn'>Edit</button></center>
</form>