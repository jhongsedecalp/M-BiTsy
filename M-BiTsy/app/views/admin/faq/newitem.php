<div class="text-center">Add Item</div>

<div class='ttform'>
<form method='post' action='<?php echo URLROOT ?>/adminfaq/additem'>

    <div class="form-group row">
        <label for="question" class="col-form-label col-3">Question:</label>
        <div class="col-9">
        <input class="form-control" type="text" name="question" value="">
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="answer" class="col-form-label col-3">Answer:</label>
        <div class="col-9">
        <textarea rows='3' cols='35' name="answer"></textarea>
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="flag" class="col-form-label col-3">Status:</label>
        <div class="col-9">
        <select name="flag" style="width: 110px;">
        <option value="0" style="color: #ff0000;">Hidden</option>
        <option value="1" style="color: #000000;">Normal</option>
        <option value="2" style="color: #0000FF;">Updated</option>
        <option value="3" style="color: #008000;" selected="selected">New</option>
         </select>
        </div>
    </div><br>

    <div class="form-group row">
        <label for="categ" class="col-form-label col-3">Category:</label>
        <div class="col-9">
        <select name="categ" size="1">
            <?php
            while ($arr = $data['res']->fetch(PDO::FETCH_BOTH)) {
                $selected = ($arr['id'] == $_GET['inid']) ? " selected=\"selected\"" : "";
                print("<option value=\"$arr[id]\"" . $selected . ">$arr[question]</option>");
            } ?>
        </select></br>
        </div>
    </div><br>

    <div class="text-center">
        <input class='btn btn-sm ttbtn' type="submit" name="edit" value="Add" >
	</div>

</form>
</div>