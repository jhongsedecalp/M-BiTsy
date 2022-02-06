<p class="text-center"><?php echo Lang::T("CLEAR_SHOUTBOX_TEXT"); ?></p>
<form enctype="multipart/form-data" method="post" action="<?php echo URLROOT; ?>/adminshoutbox/clear?do=delete">
<input type="hidden" name="action" value="clearshout" />
<input type="hidden" name="do" value="delete" />
<div class="text-center">
    <input type="submit" class='btn btn-sm ttbtn' value="<?php echo Lang::T("CLEAR_SHOUTBOX"); ?>" />
</div>
</form>