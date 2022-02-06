<form method='post' action='<?php echo URLROOT; ?>/adminnews/submit' name='news'>
<center><b><?php echo Lang::T("CP_NEWS_TITLE"); ?>:</b> <input type='text' name='title' /><br /></center>
<br /><?php echo textbbcode("news", "body"); ?><br />
<center><input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("SUBMIT"); ?>' /></center>
</form>