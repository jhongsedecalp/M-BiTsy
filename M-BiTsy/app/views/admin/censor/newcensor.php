<center><b>You must have at least one censored word</b></center>
<form action='<?php echo URLROOT ?>/admincensor&to=write' method="post" enctype="multipart/form-data">
<div class="container justify-content-md-center-2">
<div class="row">
    <div class='text-center'>
    <textarea name="badwords" rows="20" cols="60"><?php echo $data['badwords'] ?></textarea>
    </div>
    <div class="text-center">
    <input type="submit" class="btn btn-sm ttbtn" name="write" value="<?php echo Lang::T("CONFIRM") ?>" />&nbsp;&nbsp;
    <input type="submit" class="btn btn-sm ttbtn" name="write" value="<?php echo Lang::T("CANCEL") ?>" />
    </div>
</div>
</div>
</form><br />