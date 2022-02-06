<?php
if ($_GET["subact"] == "edit") {
    $poll = $data['res']->fetch(PDO::FETCH_LAZY);
} ?>
<form method="post" action="<?php echo URLROOT; ?>/adminpoll/save">
<table border="0" cellspacing="0" class="table_table" align="center">
<tr><td>Question <font class="error">*</font></td><td><input name="question" size="60" maxlength="255" value="<?php echo $poll['question']; ?>" /></td></tr>
<tr><td>Option 1 <font class="error">*</font></td><td><input name="option0" size="60" maxlength="40" value="<?php echo $poll['option0']; ?>" /><br /></td></tr>
<tr><td>Option 2 <font class="error">*</font></td><td><input name="option1" size="60" maxlength="40" value="<?php echo $poll['option1']; ?>" /><br /></td></tr>
<tr><td>Option 3</td><td><input name="option2" size="60" maxlength="40" value="<?php echo $poll['option2']; ?>" /><br /></td></tr>
<tr><td>Option 4</td><td><input name="option3" size="60" maxlength="40" value="<?php echo $poll['option3']; ?>" /><br /></td></tr>
<tr><td>Option 5</td><td><input name="option4" size="60" maxlength="40" value="<?php echo $poll['option4']; ?>" /><br /></td></tr>
<tr><td>Option 6</td><td><input name="option5" size="60" maxlength="40" value="<?php echo $poll['option5']; ?>" /><br /></td></tr>
<tr><td>Option 7</td><td><input name="option6" size="60" maxlength="40" value="<?php echo $poll['option6']; ?>" /><br /></td></tr>
<tr><td>Option 8</td><td><input name="option7" size="60" maxlength="40" value="<?php echo $poll['option7']; ?>" /><br /></td></tr>
<tr><td>Option 9</td><td><input name="option8" size="60" maxlength="40" value="<?php echo $poll['option8']; ?>" /><br /></td></tr>
<tr><td>Option 10</td><td><input name="option9" size="60" maxlength="40" value="<?php echo $poll['option9']; ?>" /><br /></td></tr>
 <tr><td>Option 11</td><td><input name="option10" size="60" maxlength="40" value="<?php echo $poll['option10']; ?>" /><br /></td></tr>
<tr><td>Option 12</td><td><input name="option11" size="60" maxlength="40" value="<?php echo $poll['option11']; ?>" /><br /></td></tr>
<tr><td>Option 13</td><td><input name="option12" size="60" maxlength="40" value="<?php echo $poll['option12']; ?>" /><br /></td></tr>
<tr><td>Option 14</td><td><input name="option13" size="60" maxlength="40" value="<?php echo $poll['option13']; ?>" /><br /></td></tr>
<tr><td>Option 15</td><td><input name="option14" size="60" maxlength="40" value="<?php echo $poll['option14']; ?>" /><br /></td></tr>
<tr><td>Option 16</td><td><input name="option15" size="60" maxlength="40" value="<?php echo $poll['option15']; ?>" /><br /></td></tr>
<tr><td>Option 17</td><td><input name="option16" size="60" maxlength="40" value="<?php echo $poll['option16']; ?>" /><br /></td></tr>
<tr><td>Option 18</td><td><input name="option17" size="60" maxlength="40" value="<?php echo $poll['option17']; ?>" /><br /></td></tr>
<tr><td>Option 19</td><td><input name="option18" size="60" maxlength="40" value="<?php echo $poll['option18']; ?>" /><br /></td></tr>
<tr><td>Sort</td><td class="table_col2">
<input type="radio" name="sort" value="yes" <?php echo $poll["sort"] != "no" ? " checked='checked'" : "" ?> />Yes
<input type="radio" name="sort" value="no" <?php echo $poll["sort"] == "no" ? " checked='checked'" : "" ?> /> No
</td></tr>
</table>
<div class="text-center">
    <input type="submit" class='btn btn-sm ttbtn' value="<?php echo $data['id'] ? "Edit poll" : "Create poll"; ?>" />
</div>
<p><font class="error">*</font> required</p>
<input type="hidden" name="pollid" value="<?php echo $poll["id"] ?>" />
<input type="hidden" name="subact" value="<?php echo $data['id'] ? 'edit' : 'create' ?>" />
</form>