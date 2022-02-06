<center><a href='<?php echo URLROOT; ?>/adminstylesheet/add'><?php echo Lang::T("THEME_ADD"); ?></a></center>
<p class="text-center">To Install Theme Please Upload It First Via FTP</p>
<center><?php echo Lang::T("THEME_CURRENT"); ?>: </center>

<form id='deltheme' method='post' action='<?php echo URLROOT; ?>/adminstylesheet/delete'>
<table class='table table-striped table-bordered table-hover'><thead>
<tr><th>ID</th><th><?php echo Lang::T("NAME"); ?></th>
<th><?php echo Lang::T("THEME_FOLDER_NAME"); ?></th>
<th><input type='checkbox' name='checkall' onclick='checkAll(this.form.id);' /></th>
</tr></thead<tbody>
<?php
while ($row = $data['sql']->fetch(PDO::FETCH_ASSOC)) {
    if (!is_dir("assets/themes/$row[uri]")) {
        $row['uri'] .= " <b>- " . Lang::T("THEME_DIR_DONT_EXIST") . "</b>";
    }
    ?>
    <tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['uri']; ?></td>
    <td><input name='ids[]' type='checkbox' value='<?php echo $row['id']; ?>' /></td>
    </tr>
    <?php
}
?>
</tbody></table>
<center><input type='submit' class='btn btn-sm ttbtn' value='<?php echo Lang::T("SELECTED_DELETE"); ?>' /><center>
</form><br>