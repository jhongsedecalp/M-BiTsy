<a name="<?php echo $data['name'] ?>"></a>
<center><b><?php echo Lang::T("_BLC_USE_SITE_SET_") ?></b></center><hr />
<div class="row justify-content-center">
<div class="col-6">
<?php
include APPROOT . "/views/user/block/" . $data['name'] . "_block.php";
?>
</div>
</div>
<center><a href="<?php echo URLROOT ?>/adminblock"><?php echo Lang::T("_CLS_WIN_") ?></a></center>