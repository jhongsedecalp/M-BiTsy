<?php
$level = $data['rlevel']->fetch(PDO::FETCH_ASSOC);
?>
<form action="<?php echo URLROOT; ?>/admingroup/update?group_id=<?php echo $level["group_id"]; ?>" name="level" method="post">
<table width="100%" align="center">
	<tr><td>Name:</td><td><input type="text" name="gname" value="<?php echo $level["level"]; ?>" size="40" /></td></tr>
	<tr><td>Group Colour:</td><td><input type="text" name="gcolor" value="<?php echo $level["Color"]; ?>" size="10" /></td></tr>
	<tr><td>View Torrents:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="vtorrent" value="yes" <?php if ($level["view_torrents"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="vtorrent" value="no" <?php if ($level["view_torrents"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Edit Torrents:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="etorrent" value="yes" <?php if ($level["edit_torrents"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="etorrent" value="no" <?php if ($level["edit_torrents"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Delete Torrents:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="dtorrent" value="yes" <?php if ($level["delete_torrents"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="dtorrent" value="no" <?php if ($level["delete_torrents"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>View Users:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="vuser" value="yes" <?php if ($level["view_users"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="vuser" value="no" <?php if ($level["view_users"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Edit Users:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="euser" value="yes" <?php if ($level["edit_users"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="euser" value="no" <?php if ($level["edit_users"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Delete Users:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="duser" value="yes" <?php if ($level["delete_users"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="duser" value="no" <?php if ($level["delete_users"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>View News:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="vnews" value="yes" <?php if ($level["view_news"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="vnews" value="no" <?php if ($level["view_news"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Edit News:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="enews" value="yes" <?php if ($level["edit_news"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="enews" value="no" <?php if ($level["edit_news"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Delete News:</td><td> <?php echo Lang::T("YES"); ?> <input type="radio" name="dnews" value="yes" <?php if ($level["delete_news"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="dnews" value="no" <?php if ($level["delete_news"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>View Forums:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="vforum" value="yes" <?php if ($level["view_forum"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="vforum" value="no" <?php if ($level["view_forum"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Edit In Forums:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="eforum" value="yes" <?php if ($level["edit_forum"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="eforum" value="no" <?php if ($level["edit_forum"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Delete In Forums:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="dforum" value="yes" <?php if ($level["delete_forum"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="dforum" value="no" <?php if ($level["delete_forum"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Can Upload:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="upload" value="yes" <?php if ($level["can_upload"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="upload" value="no" <?php if ($level["can_upload"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Can Download:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="down" value="yes" <?php if ($level["can_download"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="down" value="no" <?php if ($level["can_download"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Download  Slots:</td><td><input type="text" name="downslots"  value="<?php echo number_format($level["maxslots"]); ?>" size="40"  /></td></tr>
	<tr><td>Can View CP:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="admincp" value="yes" <?php if ($level["control_panel"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="admincp" value="no" <?php if ($level["control_panel"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
	<tr><td>Staff Page:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="staffpage" value="yes" <?php if ($level["staff_page"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="staffpage" value="no" <?php if ($level["staff_page"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
    <tr><td>Staff Public:</td><td>  <?php echo Lang::T("YES"); ?> <input type="radio" name="staffpublic" value="yes" <?php if ($level["staff_public"] == "yes") {
        echo "checked = 'checked'";
    }
    ?> />&nbsp;&nbsp; <?php echo Lang::T("NO"); ?> <input type="radio" name="staffpublic" value="no" <?php if ($level["staff_public"] == "no") {
        echo "checked = 'checked'";
    }
    ?> /></td></tr>
    <tr><td>Staff Sort:</td><td><input type='text' name='sort' size='3' value='<?php echo $level["staff_sort"]; ?>' /></td></tr>
    <?php
    print("\n<tr><td align=\"center\" ><input type=\"submit\" class='btn ttbtn btn-sm' name=\"write\" value=\"Confirm\" /></td></tr>");
    print("</table></form><br /><br />");