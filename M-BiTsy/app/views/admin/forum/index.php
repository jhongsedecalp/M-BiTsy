<?php
while ($groupsrow = $data['groupsres']->fetch()) {
    $groups[$groupsrow[0]] = $groupsrow[1];
}
$query = DB::raw('forumcats', '*', '', 'ORDER BY sort, name');
$allcat = $query->rowCount();
$forumcat = array();
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $forumcat[] = $row;
}
$query1 = DB::raw('forum_forums', '*', ['sub'=>0], 'ORDER BY sort, name');
$allforum = $query1->rowCount();
$forumforum = array();
while ($row = $query1->fetch(PDO::FETCH_ASSOC)) {
        $forumforum[] = $row;
}
?>
<div class='border ttborder'>
    <form action='<?php echo URLROOT ?>/adminforum/addforum' method='post'>
    <input type='hidden' name='sid' value='<?php echo $sid ?>' />
    <input type='hidden' name='action' value='forum' />
    <div class='table-responsive'>
        <table class='table'>
        <tr>
        <td class='table_col1'><?php echo Lang::T("CP_FORUM_NEW_NAME") ?>:</td>
        <td class='table_col2' align='right'><input type='text' name='new_forum_name' size='90' maxlength='30'  value='<?php echo $new_forum_name ?>' /></td>
        </tr>
        <tr>
        <td class='table_col1'><?php echo  Lang::T("CP_FORUM_SORT_ORDER") ?>:</td>
        <td class='table_col2' align='right'><input type='text' name='new_forum_sort' size='30' maxlength='10'  value='<?php echo $new_forum_sort ?>' /></td>
        </tr>
        <tr>
        <td class='table_col1'><?php echo Lang::T("CP_FORUM_NEW_DESC") ?>:</td>
        <td class='table_col2' align='right'><textarea cols='50%' rows='5' name='new_desc'><?php echo $new_desc ?></textarea></td>
        </tr>
        <tr>
        <td class='table_col1'><?php echo Lang::T("CP_FORUM_NEW_CAT") ?>:</td>
        <td class='table_col2' align='right'><select name='new_forum_cat'>";
        <?php
        foreach ($forumcat as $row) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
        </select>
        </tr>
        <tr>
        <td class='table_col1'><?php echo Lang::T("Sub Forum To (If not sub Forum Leave as none)") ?>:</td>
        <td class='table_col2' align='right'><select name='new_forum_forum'>";
        <?php
            echo "<option value=0>None</option>";
        foreach ($forumforum as $row) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>"; // sub forum mod
        }
        ?>
        </select>
        </tr>
        <tr>
        <td class='table_col1'>Mininum Class Needed to Read:</td>
        <td class='table_col2' align='right'><select name='minclassread'>
        <option value='<?php echo _USER; ?>'>User</option>
        <option value='<?php echo _POWERUSER; ?>'>Power User</option>
        <option value='<?php echo _VIP; ?>'>VIP</option>
        <option value='<?php echo _UPLOADER; ?>'>Uploader</option>
        <option value='<?php echo _MODERATOR; ?>'>Moderator</option>
        <option value='<?php echo _SUPERMODERATOR; ?>'>Super Moderator</option>
        <option value='<?php echo _ADMINISTRATOR; ?>'>Administrator</option>
        </select></td>
        </tr>
        <tr>
        <td class='table_col1'>Mininum Class Needed to Post:</td>
        <td class='table_col2' align='right'><select name='minclasswrite'>
        <option value='<?php echo _USER; ?>'>User</option>
        <option value='<?php echo _POWERUSER; ?>'>Power User</option>
        <option value='<?php echo _VIP; ?>'>VIP</option>
        <option value='<?php echo _UPLOADER; ?>'>Uploader</option>
        <option value='<?php echo _MODERATOR; ?>'>Moderator</option>
        <option value='<?php echo _SUPERMODERATOR; ?>'>Super Moderator</option>
        <option value='<?php echo _ADMINISTRATOR; ?>'>Administrator</option>
        </select></td>
        </tr>
        <tr>
        <td class='table_col1'><?php echo Lang::T("FORUM_ALLOW_GUEST_READ") ?>:</td>
        <td class='table_col2' align='right'><input type="radio" name="guest_read" value="yes" checked='checked' />Yes, <input type="radio" name="guest_read" value="no" />No</td></tr>
        <tr>
        <th class='table_head' colspan='2' align='center'>
        <input type='submit' class='btn btn-sm ttbtn' value='Add new forum' />
        <input type='reset' class='btn btn-sm ttbtn' value='<?php echo Lang::T("RESET") ?>' />
        </th>
        </tr>
        <?php
        #if($error_ac != "") echo "<tr><td colspan='2' align='center' style='background:#eeeeee;border:2px red solid'><b>COULD  NOT ADD NEW forum:</b><br /><ul>$error_ac</ul></td></tr>\n";
        ?>
       </table>
    </form>
    </div>
</div><br>

<b><?php echo Lang::T("FORUM_CURRENT") ?>:</b><br>
<div class='table-responsive'>
<table class='table'><thead>
<tr><th class='table_head' width='60'><font size='2'><b><?php echo Lang::T("ID") ?></b></font></th>
<th class='table_head' width='120'><?php echo Lang::T("NAME") ?></th>
<th class='table_head' width='250'>DESC</th>
<th class='table_head' width='45'><?php echo Lang::T("SORT") ?></th>
<th class='table_head' width='45'>Sub Forum</th>
<th class='table_head' width='45'>CATEGORY</th>
<th class='table_head' width='18'><?php echo Lang::T("EDIT") ?></th>
<th class='table_head' width='18'><?php echo Lang::T("DEL") ?></th></tr>
</thead><tbody>
<?php
$query = DB::raw('forum_forums', '*', '', 'ORDER BY category, sub, sort');
$allforums = $query->rowCount();
if ($allforums == 0) {
    echo "<tr><td class='table_col1' colspan='7' align='center'>No Forums found</td></tr>\n";
} else {
    while ($row = $query->fetch()) {
        foreach ($forumcat as $cat) {
            if ($cat['id'] == $row['category']) {
                $category = $cat['name'];
            }
        }
        if ($row['sub'] != 0) {
            $getsub = DB::raw('forum_forums', 'name', ['id'=>$row["sub"]])->fetch();
            $row['sub'] = $getsub['name'];
        } else {
            $row['sub'] = 'Parent';
        }
        ?>
        <tr><td class='table_col1' width='60' align='center'><font size='2'><b>ID(<?php echo $row['id'] ?>)</b></font></td>
        <td class='table_col2' width='120'><?php echo  $row['name'] ?></td>
        <td class='table_col1'  width='250'><?php echo $row['description'] ?></td>
        <td class='table_col2' width='45' align='center'><?php echo $row['sort'] ?></td>
        <td class='table_col1' width='45'><?php echo $row['sub'] ?></td>
        <td class='table_col1' width='45'><?php echo $category ?></td>
        <td class='table_col2' width='18' align='center'><a href='<?php echo URLROOT ?>/adminforum/editforum?id=<?php echo $row['id'] ?>'>[<?php echo Lang::T("EDIT") ?>]</a></td>
        <td class='table_col1' width='18' align='center'><a href='<?php echo URLROOT ?>/adminforum/deleteforum?id=<?php echo $row['id'] ?>'><i class='fa fa-trash-o tticon-red' title='<?php echo Lang::T("FORUM_DELETE_CATEGORY") ?>'></i></a></td></tr>
        <?php
    }
} ?>
</tbody></table>
</div>

<b><?php echo Lang::T("FORUM_CURRENT_CATS") ?>:</b><div class='table-responsive'>
<table class='table'><thead>
<tr><th class='table_head' width='60'><font size='2'><b><?php echo Lang::T("ID") ?></b></font></th>
<th class='table_head' width='120'><?php echo Lang::T("NAME") ?></th>
<th class='table_head' width='18'><?php echo Lang::T("SORT") ?></th>
<th class='table_head' width='18'><?php echo Lang::T("EDIT") ?></th>
<th class='table_head' width='18'><?php echo Lang::T("DEL") ?></th></tr>
</thead><tbody>
<?php
if ($allcat == 0) {
    echo "<tr class='table_col1'><td class='f-border' colspan='7' align='center'>" . Lang::T("FORUM_NO_CAT_FOUND") . "</td></tr>\n";
} else {
    foreach ($forumcat as $row) {
        echo "<tr><td class='table_col1' width='60'><font size='2'><b>ID($row[id])</b></font></td><td class='table_col2' width='120'> $row[name]</td><td class='table_col1' width='18'>$row[sort]</td>\n";
        echo "<td class='table_col2' width='18'><a href='".URLROOT."/adminforum/editcat?id=$row[id]'>[" . Lang::T("EDIT") . "]</a></td>\n";
        echo "<td class='table_col1' width='18'><a href='".URLROOT."/adminforum/delcat?id=$row[id]'><i class='fa fa-trash-o tticon-red' title='" . Lang::T("FORUM_DELETE_CATEGORY") . "'></i></a></td></tr>\n";
    }
} ?>
</tbody></table>
</div>

<div class='border ttborder'>
<form action='<?php echo URLROOT ?>/adminforum/addcat' method='post'>
<div class='table-responsive'>
<table class='table'>
<tr>
<td class='table_col1'><?php echo Lang::T("FORUM_NAME_OF_NEW_CAT") ?>:</td>
<td class='table_col2' align='right' class='f-form'><input type='text' name='new_forumcat_name' size='60' maxlength='30'  value='<?php echo $new_forumcat_name ?>' /></td>
</tr>
<tr>
<td class='table_col1'><?php echo Lang::T("FORUM_CAT_SORT_ORDER") ?>:</td>
<td class='table_col2' align='right'><input type='text' name='new_forumcat_sort' size='20' maxlength='10'  value='<?php echo $new_forumcat_sort ?>' /></td>
</tr>
<tr>
<th class='table_head' colspan='2' align='center'>
<input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("FORUM_ADD_NEW_CAT") ?>' />
<input type='reset' class='btn btn-sm ttbtn' value='<?php echo Lang::T("RESET") ?>' />
</th>
</tr>
</table></div>
</form></div>