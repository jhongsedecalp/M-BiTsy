<div class='ttform'>
<form action="<?php echo URLROOT; ?>/adminforum/saveeditforum" method="post">
<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />

    <div class="form-group row">
        <label for="changed_forum" class="col-form-label col-3">New Name for Forum:</label>
        <div class="col-9">
        <input type="text" name="changed_forum" class="option" size="35" value="<?php echo $data["name"]; ?>" />
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="changed_sort" class="col-form-label col-3">Sort Order:</label>
        <div class="col-9">
        <input type="text" name="changed_sort" class="option" size="35" value="<?php echo $data["sort"]; ?>" />
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="changed_forum_desc" class="col-form-label col-3">Description:</label>
        <div class="col-9">
        <textarea cols='50' rows='5' name='changed_forum_desc'><?php echo $data["description"]; ?></textarea>
        </div>
    </div><br>

    <div class="form-group row">
        <label for="changed_forum_cat" class="col-form-label col-3">New Category:</label>
        <div class="col-9">
        <select name='changed_forum_cat'> <?php
            while ($row = $data['query']->fetch()) {
               echo "<option value={$row['id']}>{$row['name']}</option>";
            } ?>
        </select>
        </div>
    </div><br>

    <div class="form-group row">
        <label for="changed_sub" class="col-form-label col-3">Parent Forum (If main Forum 0):</label>
        <div class="col-9">
        <input type="text" name="changed_sub" class="option" size="35" value="<?php echo $data["sub"]; ?>" />
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="minclassread" class="col-form-label col-3">Mininum Class Needed to Read:</label>
        <div class="col-9">
        <select name='minclassread'>
            <option value='<?echo _USER; ?>'>User</option>
            <option value='<?echo _POWERUSER; ?>'>Power User</option>
            <option value='<?echo _UPLOADER; ?>'>Uploader</option>
            <option value='<?echo _MODERATOR; ?>'>Moderator</option>
            <option value='<?echo _SUPERMODERATOR; ?>'>Super Moderator</option>
            <option value='<?echo _ADMINISTRATOR; ?>'>Administrator</option>
        </select>
        </div>
    </div><br>

    <div class="form-group row">
        <label for="minclasswrite" class="col-form-label col-3">Mininum Class Needed to Write:</label>
        <div class="col-9">
        <select name='minclasswrite'>
            <option value='<?echo _USER; ?>'>User</option>
            <option value='<?echo _POWERUSER; ?>'>Power User</option>
            <option value='<?echo _VIP; ?>'>VIP</option>
            <option value='<?echo _UPLOADER; ?>'>Uploader</option>
            <option value='<?echo _MODERATOR; ?>'>Moderator</option>
            <option value='<?echo _SUPERMODERATOR; ?>'>Super Moderator</option>
            <option value='<?echo _ADMINISTRATOR; ?>'>Administrator</option>
        </select>
        </div>
    </div><br>  

    <div class="form-group row">
        <label for="guest_read" class="col-form-label col-3">Allow Guest Read:</label>
        <div class="col-9">
        <input type="radio" name="guest_read" value="yes" <?php echo $data["guest_read"] == "yes" ? "checked='checked'" : "" ?> />Yes,
	    <input type="radio" name="guest_read" value="no" <?php echo $data["guest_read"] != "yes" ? "checked='checked'" : "" ?> />No
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn'  value="Change" />&nbsp;&nbsp;
	</div>

</form>
</div>