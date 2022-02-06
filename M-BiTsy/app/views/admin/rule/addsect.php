<div class='ttform'>
<form method="post" action="<?php echo URLROOT; ?>/adminrule/addsect?save=1">

    <div class="form-group row">
        <label for="title" class="col-form-label col-3">Section Title:</label>
        <div class="col-9">
        <input class="form-control" type="text" name="title" />
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="text" class="col-form-label col-3">Rules:</label>
        <div class="col-9">
        <textarea cols="60" rows="15" class="form-control" name="text"></textarea><br>NOTE: Remember that BB can be used (NO HTML)
        </div>
    </div><br>

    <div class="form-group row">
        <label for="public" class="col-form-label col-3">For everybody :</label>
        <div class="col-9">
        <input type="radio" name='public' value="yes" />&nbsp;Yes&nbsp;&nbsp;
        <input type="radio" name='public' value="no" />&nbsp;No
        </div>
    </div><br>

    <div class="form-group row">
        <label for="class" class="col-form-label col-3">Min User Class:</label>
        <div class="col-9">
        <input type="text" name='class' value="0" size="1" />
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("Add"); ?>' />&nbsp;&nbsp;
	</div>

</form>
</div>