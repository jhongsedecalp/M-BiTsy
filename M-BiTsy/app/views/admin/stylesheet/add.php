<div class='ttform'>
<form action='<?php echo URLROOT; ?>/adminstylesheet/add' method='post'>
    <input type='hidden' name='action' value='style' />
    <input type='hidden' name='do' value='add' />

    <div class="form-group row">
        <label for="name" class="col-form-label col-3"><?php echo Lang::T("THEME_NAME_OF_NEW"); ?>:</label>
        <div class="col-9">
            <input id="name" type="text" class="form-control" name="name"  value='<?php echo $name; ?>'>
        </div>
    </div><br>

    <div class="form-group row">
        <label for="uri" class="col-form-label col-3"><?php echo Lang::T("THEME_FOLDER_NAME_CASE_SENSITIVE"); ?>:</label>
        <div class="col-9">
            <input id="uri" type="text" class="form-control" name="uri" value='<?php echo $uri; ?>'>
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("Add new theme"); ?>' />&nbsp;&nbsp;
		<a href="<?php echo URLROOT; ?>/adminstylesheet/add" class='btn btn-sm ttbtn'><?php echo Lang::T("RESET") ?></a>
    </div>

</form>
<center>You must upload theme to public_html/assets/themename</center>
</div>