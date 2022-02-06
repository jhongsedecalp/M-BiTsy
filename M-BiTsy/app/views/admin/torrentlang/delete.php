<div class="ttform">
<form method="post" class="form-horizontal" action="<?php echo URLROOT; ?>/admintorrentlang/delete?id=<?php echo $data['id'] ?>&amp;sure=1">

    <div class="form-group row">
        <label for="newlangid"><?php echo Lang::T("Language ID to move all Languages To:"); ?>:</label>
        <input id="newlangid" type="text" class="form-control" name="newlangid"  value="<?php echo $arr['name'] ?>">
    </div><br>

    <div class="text-center">
        <input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("SUBMIT"); ?>' />
    </div>

</form>
</div>