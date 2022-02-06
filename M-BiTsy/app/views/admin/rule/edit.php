<div class='ttform'>
<form method="post" action="<?php echo URLROOT; ?>/adminrule/edit?save=1">
<input type="hidden" value="<?php echo $data['id']; ?>" name="id" /> <?php
while ($res1 = $data['res']->fetch(PDO::FETCH_ASSOC)) { ?>

    <div class="form-group row">
        <label for="title" class="col-form-label col-3">Section Title:</label>
        <div class="col-9">
        <input class="form-control" type="text" name="title" value="<?php echo $res1['title']; ?>" />
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="text" class="col-form-label col-3">Rules:</label>
        <div class="col-9">
        <textarea cols="60" rows="15" class="form-control" name="text"><?php echo stripslashes($res1["text"]); ?></textarea><br>NOTE: Remember that BB can be used (NO HTML)
        </div>
    </div><br>

    <div class="form-group row">
        <label for="public" class="col-form-label col-3">For everybody :</label>
        <div class="col-9">
        <input type="radio" name='public' value="yes" <?php echo ($res1["public"] == "yes" ? "checked='checked'" : ""); ?>/>&nbsp;Yes&nbsp;&nbsp;
        <input type="radio" name='public' value="no" <?php echo ($res1["public"] == "no" ? "checked='checked'" : ""); ?> />&nbsp;No
        </div>
    </div><br>

    <div class="form-group row">
        <label for="class" class="col-form-label col-3">Min User Class:</label>
        <div class="col-9">
        <input type="text" name='class' value="<?php echo $res1['class']; ?>" size="1" />
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("SAVE"); ?>' />&nbsp;&nbsp;
	</div> <?php
} ?>
</table>
</form>
</div>