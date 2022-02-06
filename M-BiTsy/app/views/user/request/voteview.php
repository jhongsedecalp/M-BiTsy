<a href='<?php echo URLROOT ?>/request'><button  class='btn btn-sm ttbtn'>All Request</button></a>&nbsp;
<a href='<?php echo URLROOT ?>/request?requestorid=<?php echo Users::get('id') ?>'><button  class='btn btn-sm ttbtn'>View my requests</button></a>

<p><center><a href='<?php echo URLROOT ?>/request/addvote?id=<?php echo $data['requestid'] ?>'><b><?php echo Lang::T('VOTE_FOR_THIS') ?>   <?php echo Lang::T('REQUEST') ?></b></a></center></p>

<div class='table-responsive'> <table class='table table-striped' width='60%'><thead><tr>
<th><?php echo Lang::T('USERNAME') ?></th>
<th><?php echo Lang::T('UPLOADED') ?></td>
<th><?php echo Lang::T('DOWNLOADED') ?></th>
<th><?php echo Lang::T('RATIO') ?></th></tr></thead>
<?php
while ($arr = $data['res']->fetch(PDO::FETCH_ASSOC)) {
   if ($arr["downloaded"] > 0) {
        $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
        $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
    } elseif ($arr["uploaded"] > 0) {
        $ratio = "Inf.";
    } else {
        $ratio = "---";
    }
    $uploaded = mksize($arr["uploaded"]);
    $downloaded = mksize($arr["downloaded"]);
   
    if ($arr["enabled"] == 'no') {
        $enabled = "<font color = red>No</font>";
    } else {
        $enabled = "<font color = green>Yes</font>";
    }
    ?>
    <tr><td><a href='<?php echo URLROOT ?>/profile?id=<?php echo $arr['userid'] ?>'><b><?php echo $arr['username'] ?></b></a></td>
    <td><?php echo $uploaded ?></td>
    <td><?php echo $downloaded ?></td>
    <td><?php echo $ratio ?></td></tr>
    <?php
}
print("</table></div>");