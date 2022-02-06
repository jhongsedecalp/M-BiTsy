<?php usermenu($data['id']); ?>
<div class="ttform">
<form action="<?php echo URLROOT; ?>/account/email?id=<?php echo $data['id']; ?>" method="post">
    <div class="text-center">
	    <label for="name"><?php echo Lang::T("EMAIL"); ?>:</label><br><br>
        <input id="name" type="email" class="form-control" name="email" value='<?php echo htmlspecialchars($data["email"]); ?>' minlength="3" maxlength="25" required autofocus><br>
    </div>
    <div class="text-center">
	    <button type="submit" class="btn ttbtn"><?php echo Lang::T("Submit"); ?></button>
    </div>
</form>
</div>