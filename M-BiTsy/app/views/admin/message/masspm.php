<div class='ttform'>
<form name='masspm' method='post' action='<?php echo URLROOT ?>/adminmessage/send'>
<p class='text-center'><b>Send to:</b></p> <?php
while ($row = $data['res']->fetch(PDO::FETCH_LAZY)) { ?>

    <div class="form-group row">
        <label for="clases" class="col-form-label col-3">Send To:</label>
        <div class="col-9">
        <input type='checkbox' name='clases[]' value='<?php echo $row['group_id']; ?>?' /><?php echo $row['level']; ?><br />
        </div>
    </div><br>  <?php
} ?>

    <div class="form-group row">
        <label for="subject" class="col-form-label col-3">Subject:</label>
        <div class="col-9">
        <input class="form-control" type='text' name='subject'><br />
        </div>
    </div><br>
    
    <div class="form-group row">
        <label for="msg" class="col-form-label col-3">Message:</label>
        <div class="col-9">
        <textarea cols="60" rows="15" class="form-control" name="msg"></textarea>
        </div>
    </div><br>


    <div class="form-group row">
        <label for="class" class="col-form-label col-3"><?php echo Lang::T("SENDER"); ?></label>
        <div class="col-9">
        <?php echo Users::get('username') ?>
        <input name="sender" type="radio" value="self" checked="checked" />
        System
        <input name="sender" type="radio" value="system" />
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("Send"); ?>' />&nbsp;&nbsp;
	</div>

</table>
</form>
</div>