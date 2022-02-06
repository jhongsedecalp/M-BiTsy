<?php $arr = $data['res']->fetch(PDO::FETCH_ASSOC); ?>

<a href='<?php echo URLROOT ?>/request'><button  class='btn btn-sm ttbtn'>All Request</button></a>&nbsp;
<a href='<?php echo URLROOT ?>/request?requestorid=<?php echo Users::get('id') ?>'><button  class='btn btn-sm ttbtn'>View my requests</button></a>

<div class="ttform">
<form name="form" action="edit&id=<?php echo $arr['id']; ?>" method="post">
<div class="text-center">
<label for="cat">Change Cat id:</label><br>
<input type="text" class="form-control" name="cat" value="<?php echo $arr['id']; ?>" id="cat"><br>
<label for="request">Request Tilte:</label><br>
<input type="text" class="form-control" name="request" value="<?php echo $arr['request']; ?>" id="request"><br>
<label for="descr">Description:</label><br>
<input type="text" class="form-control" name="descr" value="<?php echo $arr['descr']; ?>" id="descr"><br>
<label for="filled">Url To Torrent:</label><br>
<input type="text"  class="form-control" name="filled" value="<?php echo $arr['filled']; ?>" id="filled"><br>
<label for="filledby">Fiiled By User ID:</label><br>
<input type="text" class="form-control" name="filledby" value="<?php echo $arr['filledby']; ?>" id="filledby"><br>

<input type="submit"  class='btn btn-sm ttbtn' value="Update"><br>
</div>
</form>
 </div>