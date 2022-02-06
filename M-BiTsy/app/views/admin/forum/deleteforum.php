<form class='a-form' action="<?php echo URLROOT; ?>/adminforum/deleteforumok" method="post">
<input type="hidden" name="do" value="delete_forum" />
<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
<?php echo Lang::T("CP_FORUM_REALY_DEL"); ?> <?php echo "<b>$data[name] with ID$data[catid] ???</b>"; ?> <?php echo Lang::T("CP_FORUM_THIS_WILL_REALLY_DEL"); ?>.
<input type="submit" name="delcat" class="button" value="Delete" />
</form>