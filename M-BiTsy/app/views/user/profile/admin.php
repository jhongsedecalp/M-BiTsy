<?php
usermenu($data['id']);
foreach ($data['selectuser'] as $selectedid):
    $uploaded = $selectedid["uploaded"];
    $downloaded = $selectedid["downloaded"];
    $enabled = $selectedid["enabled"] == 'yes';
    $warned = $selectedid["warned"] == 'yes';
    $forumbanned = $selectedid["forumbanned"] == 'yes';
    $downloadbanned = $selectedid["downloadbanned"] == 'yes';
    $shoutboxpos = $selectedid["shoutboxpos"] == 'yes';
    $modcomment = htmlspecialchars($selectedid["modcomment"]);
?>

<div class="jumbotron">
<form action="<?php echo URLROOT; ?>/profile/submited?id=<?php echo $data['id']; ?>" method="post">
<div class="row">
    <div class="col-4">
    <?php  
    print("<br>&nbsp;&nbsp;" . Lang::T("UPLOADED") . ": <input type='text' class='form-control' size='30' name='uploaded' value=\"" . mksize($selectedid["uploaded"], 9) . "\" />");
    print("<br>&nbsp;&nbsp;" . Lang::T("DOWNLOADED") . ": <input type='text' class='form-control' size='30' name='downloaded' value=\"" . mksize($selectedid["downloaded"], 9) . "\" />");
    print("<br>&nbsp;&nbsp;" . Lang::T("EMAIL") . ": <input type='text' class='form-control' size='40' name='email' value=\"$selectedid[email]\" />");
    print("<br>&nbsp;&nbsp;" . Lang::T("IP_ADDRESS") . ": <input type='text' class='form-control' size='20' name='ip' value=\"$selectedid[ip]\" />");
    print("<br>&nbsp;&nbsp;" . Lang::T("INVITES") . ": <input type='text' class='form-control' size='4' name='invites' value='" . $selectedid["invites"] . "' />");
    print("<br>&nbsp;&nbsp;" .Lang::T("CLASS") . ": <select class='form-control' name='class'>");
        $maxclass = Users::get("class") + 1;
        for ($i = 1; $i < $maxclass; ++$i) {
            print("<option value='$i' " . ($selectedid["class"] == $i ? " selected='selected'" : "") . ">" . Groups::get_user_class_name($i) . "");//$prefix" . Groups::get_user_class_name($i)
        }
        print("</select>");
    print("<br>&nbsp;&nbsp;" . Lang::T("DONATED_US") . ": </br><input type='text' class='form-control' size='4' name='donated' value='$selectedid[donated]' />");
    print("<br>&nbsp;&nbsp;" . Lang::T("SEEDING_BONUS") . ": <br><input type='text' class='form-control' size='10' name='bonus' value='$selectedid[seedbonus]'>");
    ?>
    </div>

    <div class="col-4"><br>
    <?php  
    print("".Lang::T("ACCOUNT_STATUS") . ": <br>&nbsp;&nbsp;<input name='enabled' value='yes' type='radio' " . ($enabled ? " checked='checked'" : "") . " />Enabled <input name='enabled' value='no' type='radio' " . (!$enabled ? " checked='checked' " : "") . " />Disabled<br>\n");
    print("<br>".Lang::T("WARNED") . ": <br>&nbsp;&nbsp;<input name='warned' value='yes' type='radio' " . ($warned ? " checked='checked'" : "") . " />Yes <input name='warned' value='no' type='radio' " . (!$warned ? " checked='checked'" : "") . " />No<br>\n");
    print("<br>".Lang::T("FORUM_BANNED") . ": <br>&nbsp;&nbsp;<input name='forumbanned' value='yes' type='radio' " . ($forumbanned ? " checked='checked'" : "") . " />Yes <input name='forumbanned' value='no' type='radio' " . (!$forumbanned ? " checked='checked'" : "") . " />No<br>\n");
    print("<br>Download Banned: <br>&nbsp;&nbsp;<input name='downloadbanned' value='yes' type='radio' " . ($downloadbanned ? " checked='checked'" : "") . " />Yes <input name='downloadbanned' value='no' type='radio' " . (!$downloadbanned ? " checked='checked'" : "") . " />No<br>\n");
    print("<br>Shoutbox Banned: <br>&nbsp;&nbsp;<input name='shoutboxpos' value='yes' type='radio' " . ($shoutboxpos ? " checked='checked'" : "") . " />Yes <input name='shoutboxpos' value='no' type='radio' " . (!$shoutboxpos ? " checked='checked'" : "") . " />No<br>\n");
    ?>
    </div>

    <div class="col-4"><br>
    <?php  
    print(Lang::T("MOD_COMMENT") . ": <textarea cols='40' class='form-control' rows='10' name='modcomment'>$modcomment</textarea>");
    print("<br>" . Lang::T("PASSWORD") . ": <input type='password' class='form-control' name='password' value=\"$selectedid[password]\" />");
    print("" . Lang::T("CHANGE_PASS") . ": <input type='checkbox' name='chgpasswd' value='yes'/><br>");
    print("<br>" . Lang::T("PASSKEY") . ": $selectedid[passkey]<br /><input name='resetpasskey' value='yes' type='checkbox' />&nbsp;" . Lang::T("RESET_PASSKEY") . "<br>(" . Lang::T("RESET_PASSKEY_MSG") . ")");
    ?>
    </div>
</div>

<?php
print("<center><button type='submit' class='btn btn-sm ttbtn' value='" . Lang::T("SUBMIT") . "' />Submit</button></center><br>");
 endforeach;?>
</form>
</div>