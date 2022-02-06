<center><a href='<?php echo URLROOT ?>/admingroup/add'><?php echo Lang::T("Add New Group") ?></a></center>
<div class='table-responsive'>
    <table class='table'><thead><tr>
    <th><?php echo  Lang::T("NAME") ?></th>
    <th><?php echo Lang::T("TORRENTS") ?><br /><?php echo Lang::T("GROUPS_VIEW_EDIT_DEL") ?></th>
    <th><?php echo Lang::T("MEMBERS") ?><br /><?php echo Lang::T("GROUPS_VIEW_EDIT_DEL") ?></th>
    <th><?php echo Lang::T("NEWS") ?><br /><?php echo Lang::T("GROUPS_VIEW_EDIT_DEL") ?></th>
    <th><?php echo Lang::T("FORUM") ?><br /><?php echo Lang::T("GROUPS_VIEW_EDIT_DEL") ?></th>
    <th><?php echo Lang::T("UPLOAD") ?></th>
    <th><?php echo Lang::T("DOWNLOAD") ?></th>
    <th><?php echo Lang::T("SLOTS") ?></th>
    <th><?php echo Lang::T("CP_VIEW") ?></th>
    <th><?php echo Lang::T("CP_STAFF_PAGE") ?></th>
    <th><?php echo Lang::T("CP_STAFF_PUBLIC") ?></th>
    <th><?php echo Lang::T("CP_STAFF_SORT") ?></th>
    <th><?php echo Lang::T("DEL") ?></th>
    </tr></thead><tbody>
<?php
foreach ($data['getlevel'] as $level) { ?>
        <tr>
        <td><a href='<?php echo URLROOT ?>/admingroup/edit?group_id=<?php echo $level["group_id"] ?>'><font color="<?php echo $level['Color'] ?>"><?php echo $level["level"] ?></font></td>
        <td><?php echo $level["view_torrents"] ?>/<?php echo $level["edit_torrents"] ?>/<?php echo $level["delete_torrents"] ?></td>
        <td><?php echo $level["view_users"] ?>/<?php echo $level["edit_users"] ?>/<?php echo $level["delete_users"] ?></td>
        <td><?php echo $level["view_news"] ?>/<?php echo $level["edit_news"] ?>/<?php echo $level["delete_news"] ?></td>
        <td><?php echo $level["view_forum"] ?>/<?php echo $level["edit_forum"] ?>/<?php echo $level["delete_forum"] ?></td>
        <td><?php echo $level["can_upload"] ?></td>
        <td><?php echo $level["can_download"] ?></td>
        <td><?php echo $level["maxslots"] ?></td>
        <td><?php echo $level["control_panel"] ?></td>
        <td><?php echo $level["staff_page"] ?></td>
        <td><?php echo  $level["staff_public"] ?></td>
        <td><?php echo $level["staff_sort"] ?></td>
        <td><a href='<?php echo URLROOT ?>/admingroup/delete?group_id=<?php echo $level["group_id"]; ?>'>Del</a></td>
        </tr>
        <?php
}
?>
</tbody></table></div><br />