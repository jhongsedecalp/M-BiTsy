<center><a href='<?php echo URLROOT; ?>/admintorrentlang/add'><b>Add New Language</b></a></center>
<i>Please note that language image is optional</i><br />
<center>
<table class='table table-striped table-bordered table-hover'><thead><tr>
<th><b>Sort</b></th>
<th><b><?php echo Lang::T("NAME"); ?></b></th>
<th><b>Image</b></th>
<th></th>
</tr></thead><tbody>
<?php
while ($row = $data['sql']->fetch(PDO::FETCH_LAZY)) {
    $id = $row['id'];
    $name = $row['name'];
    $priority = $row['sort_index'];
    ?>
    <tr>
    <td class='table_col1' align='center'><?php echo $priority; ?></td>
    <td class='table_col2'><?php echo $name; ?></td>
    <td class='table_col1' width='50' align='center'>
    <?php
    if (isset($row["image"]) && $row["image"] != "") {
        print("<img border=\"0\" src=\"" . URLROOT . "/assets/images/languages/" . $row["image"] . "\" alt=\"" . $row["name"] . "\" />");
    } else {
        print("-");
    }
    ?>
    </td>
    <td><a href='<?php echo  URLROOT; ?>/admintorrentlang/edit?id=<?php echo $id; ?>'>[EDIT]</a> <a href='<?php echo URLROOT; ?>/admintorrentlang/delete?id=<?php echo $id; ?>'>[DELETE]</a></td>
    </tr>
    <?php
}
echo ("</tbody></table></center>");