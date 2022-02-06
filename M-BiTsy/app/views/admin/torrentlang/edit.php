<div class="ttform">
<form method="post" class="form-horizontal" action="<?php echo URLROOT; ?>/admintorrentlang/edit?id=<?php echo $data['id'] ?>&amp;save=1">
<?php
while ($arr = $data['res']->fetch(PDO::FETCH_LAZY)) { ?>

    <div class="form-group row">
        <label for="name" class="col-form-label col-3"><?php echo Lang::T("NAME"); ?>:</label>
        <div class="col-9">
            <input id="name" type="text" class="form-control" name="name"  value="<?php echo $arr['name'] ?>">
        </div>
    </div><br>

    <div class="form-group row">
        <label for="sort_index" class="col-form-label col-3"><?php echo Lang::T("SORT"); ?>:</label>
        <div class="col-9">
            <input id="sort_index" type="text" class="form-control" name="sort_index" value="<?php echo $arr['sort_index'] ?>">single filename
        </div>
    </div><br>


    <div class="form-group row">
        <label for="image" class="col-form-label col-3"><?php echo Lang::T("IMAGE"); ?>:</label>
        <div class="col-9">
            <input id="image" type="text" class="form-control" name="image" value="<?php echo $arr['image'] ?>">
        </div>
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("SUBMIT"); ?>' />
    </div> <?php
} ?>
</form>
</div>