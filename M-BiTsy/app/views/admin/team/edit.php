<div class='ttform'>
<form name='smolf3d' method='post' action='<?php echo URLROOT ?>/adminteam/edit'>
    <input type='hidden' name='id' value='<?php echo $data['editid']; ?>' />
    <input type='hidden' name='edited' value='1' />

    <div class="form-group row">
        <label for="team_name" class="col-form-label col-3"><?php echo Lang::T("TEAM_NAME"); ?>:</label>
        <div class="col-9">
            <input id="team_name" type="text" class="form-control" name="team_name" value='<?php echo $data['name']; ?>'>
        </div>
    </div><br>

    <div class="form-group row">
        <label for="team_owner" class="col-form-label col-3"><?php echo Lang::T("TEAM_OWNER_NAME"); ?>:</label>
        <div class="col-9">
            <input id="team_owner" type="text" class="form-control" name="team_owner" value='<?php echo $data['owner']; ?>'>
        </div>
    </div><br>

    <div class="form-group row">
        <label for="team_info" class="col-form-label col-3"><?php echo Lang::T("DESCRIPTION"); ?>:</label>
        <div class="col-9">
            <input id="team_info" type="text" class="form-control" name="team_info" value='<?php echo $data['info']; ?>'>
        </div>
    </div><br>

    <div class="form-group row">
        <label for="team_image" class="col-form-label col-3"><?php echo Lang::T("TEAM_LOGO_URL"); ?>:</label>
        <div class="col-9">
            <input id="team_image" type="text" class="form-control" name="team_image" value='<?php echo $data['image']; ?>'>
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("Update"); ?>' />
    </div>

</form>
</div>