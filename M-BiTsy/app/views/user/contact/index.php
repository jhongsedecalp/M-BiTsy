<p class='text-center'><b><?php echo Lang::T("ACCOUNT_SEND_MSG"); ?></b></pr>
<p class='text-center'>Please leave your name & email</p>
<form method=post name=message action='<?php echo URLROOT; ?>/contact/submit'>
<div class="ttform">
<div class="text-center">
	<label for="subject"><?php echo Lang::T("FORUMS_SUBJECT"); ?>: </label>
	<input id="subject" type="text" class="form-control" name="subject" minlength="3" maxlength="200" required autofocus>
</div><br>
<div class="text-center">
    <label for="msg"><?php echo Lang::T("MESSAGE"); ?>: </label>
    <textarea class="form-control" id="msg" name="msg" rows="15"></textarea>
</div><br>
<div class="text-center">
    <?php (new Captcha)->html(); ?>
	<button type="submit" class="btn ttbtn btn-sm"><?php echo Lang::T("Submit"); ?></button>
</div>

</div>
</form>