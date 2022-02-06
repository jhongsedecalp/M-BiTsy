<div class="text-center">Add Section</div>

<div class='ttform'>
<form method='post' action='<?php echo URLROOT ?>/adminfaq/newsection'>
<input type=hidden name=action value=addnewsect>

    <div class="form-group row">
        <label for="title" class="col-form-label col-3">Title:</label>
        <div class="col-9">
        <input class="form-control" type="text" name="title" value="">
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="flag" class="col-form-label col-3">Status:</label>
        <div class="col-9">
        <select name="flag" style="width: 110px;">
        <option value="0" style="color: #ff0000;">Hidden</option>
        <option value="1" style="color: #000000;">Normal</option>
         </select>
        </div>
    </div><br>

    <div class="text-center">
        <input class='btn btn-sm ttbtn' type="submit" name="edit" value="Add" >
	</div>

</form>
</div>