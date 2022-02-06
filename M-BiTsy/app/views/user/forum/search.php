<?php
forumheader('search');
?>
<div class="ttform">
    <form method='get' action='<?php echo URLROOT; ?>/forum/result'>
        <p class="text-center"><?php echo Lang::T("SEARCH") ?></p>
        <div>
            <input id="keywords" type="text" class="form-control" name="keywords" minlength="3" maxlength="25" required autofocus>
        </div><br>
        <div class="text-center">
            <button type='submit' class='btn btn-sm ttbtn' value='Search'>Search Topics</button>&nbsp;&nbsp;
            <button  type='Submit' class='btn btn-sm ttbtn' name='type' value='deep'>Search Posts</button>
        </div>
    </form>
</div>