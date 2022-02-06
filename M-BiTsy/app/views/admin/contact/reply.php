<?php
$user = $data['res']->fetch(PDO::FETCH_ASSOC);
$array = $data['res2']->fetch(PDO::FETCH_ASSOC);
?>
<center><b>Answering to <a href='<?php echo URLROOT; ?>/admincontact/viewpm?pmid=<?php echo $array['id']; ?>'>
<i><?php echo $array["subject"]; ?></i></a> sent by <i><?php echo $user["username"]; ?></i></b></center>

<form method=post name=message action='<?php echo URLROOT; ?>/admincontact/takeanswer'>
<div class="text-center"> 
    <b>Message:</b><br>
    <textarea name=msg cols=90 rows=15><?php echo htmlspecialchars($body); ?></textarea>
    <?php
    if ($spam == 1) {
        print("<center><a href=#><font color=red><b>--- </a> ---</b></font color></center>");
    }
    echo $replyto ? " colspan=2" : "";
    ?><br>
    <button type="submit" class="btn ttbtn">Send it!</a>
    <input type=hidden name=receiver value=<?php echo $data['receiver']; ?>>
    <input type=hidden name=answeringto value=<?php echo $data['answeringto']; ?>>
</div>
</form>