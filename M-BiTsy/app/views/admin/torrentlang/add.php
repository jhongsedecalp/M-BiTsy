<div class="ttform">
<form method="post" class="form-horizontal" action="<?php echo URLROOT; ?>/admintorrentlang/takeadd" autocomplete="off"><br>

    <div class="form-group row">
        <label for="name" class="col-form-label col-3"><?php echo Lang::T("NAME"); ?>:</label>
        <div class="col-9">
            <input id="name" type="text" class="form-control" name="name" minlength="3" maxlength="25" required autofocus placeholder="">
        </div>
    </div><br>

    <div class="form-group row">
        <label for="sort_index" class="col-form-label col-3"><?php echo Lang::T("SORT"); ?>:</label>
        <div class="col-9">
            <input id="sort_index" type="text" class="form-control" name="sort_index">
        </div>
    </div><br>


    <div class="form-group row">
        <label for="image" class="col-form-label col-3"><?php echo Lang::T("IMAGE"); ?>:</label>
        <div class="col-9">
            <input id="image" type="text" class="form-control" name="image">
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("SUBMIT"); ?>' />
    </div>

</form>
</div>