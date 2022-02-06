<center><font size="4">Please Report any exceptions to help improve TT. Please keep this file blank</font></center>
<form method="post">
<div class="form-group">
    <center><textarea  class="form-control" name="newcontents" rows="12">
    <?php echo $data['filecontents']; ?></textarea></center>
</div>
<center><font size="4">Please Double Click</font></center>
<center><input type="submit" class="btn ttbtn btn-sm" value="Save"></center><br>
</form>

<div class="form-group">
    <center><textarea  class="form-control" rows="12" readonly='readonly'>
    <?php echo stripslashes($data['errorlog']); ?></textarea></center>
</div><br>