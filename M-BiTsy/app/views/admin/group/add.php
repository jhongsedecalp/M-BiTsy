<div class="text-center">
    Add Group
</div>

<div class='ttform'>
<form action="<?php echo URLROOT; ?>/admingroup/addnew" name="level" method="post">

    <div class="form-group row">
        <label for="gname" class="col-form-label col-3">Group Name:</label>
        <div class="col-9">
        <input class="form-control" type="text" name="gname" />
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="gcolor" class="col-form-label col-3">Group colour:</label>
        <div class="col-9">
        <input class="form-control" type="text" name="gcolor" />
        </div>
    </div><br>

    <div class="form-group row">
        <label for="public" class="col-form-label col-3">Copy Settings From:</label>
        <div class="col-9">
        <select name="getlevel" size="1">
            <?php
            while ($level = $data['rlevel']->fetch(PDO::FETCH_ASSOC)) {
                print("\n<option value='" . $level["group_id"] . "'>" . htmlspecialchars($level["level"]) . "</option>");
            }
            ?>
        </select></br>
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn'  name="confirm" value="Confirm" />&nbsp;&nbsp;
	</div>

</form>
</div>