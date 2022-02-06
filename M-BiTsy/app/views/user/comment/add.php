<form name='comment' method='post' action='<?php echo URLROOT ?>/comment/take?type=<?php echo $data['type'] ?>&amp;id=<?php echo $data['id'] ?>'>
<div class='text-center'> <?php
    echo textbbcode("comment", "body"); ?>
    <input type="submit" class='btn btn-sm ttbtn'  value="<?php echo Lang::T("ADDCOMMENT") ?>" />
</div>
</form>