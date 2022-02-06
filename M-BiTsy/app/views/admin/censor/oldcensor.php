<form method="post" action="<?php echo URLROOT; ?>/admincensor">
<table width='100%' cellspacing='3' cellpadding='3' align='center'>
<tr>
<td bgcolor='#eeeeee'><font face="verdana" size="1">Word:  <input type="text" name="word" id="word" size="50" maxlength="255" value="" /></font></td></tr>
<tr><td bgcolor='#eeeeee'><font face="verdana" size="1">Censor With:  <input type="text" name="censor" id="censor" size="50" maxlength="255" value="" /></font></td></tr>
<tr><td bgcolor='#eeeeee' align='left'>
<font size="1" face="verdana"><input type="submit" name="submit" value="Add Censor" /></font></td>
</tr>
</table>
</form>

<form method="post" action="<?php echo URLROOT; ?>/admincensor">
<table>
<tr>
<td bgcolor='#eeeeee'><font face="verdana" size="1">Remove Censor For: <select name="censor">
<?php
while ($srow = $data['sres']->fetch()) {
    echo "<option>" . $srow[0] . "</option>\n";
}
echo '</select></font></td></tr><tr><td bgcolor="#eeeeee" align="left">
<font size="1" face="verdana"><input type="submit" name="submit" value="Delete Censor" /></font></td>
</tr></table></form>';