<center><b>Current Banned Clients</b></center>
<form id="unban" method="post" action="<?php echo URLROOT; ?>/adminclient/banned">
    <input type="hidden" name="unban" value="unban" />
    <table class='table table-striped table-bordered table-hover'><thead>
    <tr><th class=table_head>Name</th>
    <th class=table_head>Banned</th>
    <th class=table_head>Added</th>
    <th class='table_head'><input type='checkbox' name='checkall' onclick='checkAll(this.form.id);' /></th>
    </th></tr></thead><tbody></tbody>
<?php foreach ($data['sql'] as $arr14) {
    $isbanned = "<font color='green'><b>Yes</b></font>";
    if ($arr14['hits'] == 0) {
        $isbanned = "<font color='red'><b>No</b></font>";
    } ?>
    <tr>
    <td class=table_col1>&nbsp; <?php echo $arr14['agent_name']; ?> &nbsp;</td>
    <td class=table_col2>&nbsp; <?php echo $isbanned; ?> &nbsp;</td>
    <td class=table_col2>&nbsp; <?php echo $arr14['ins_date']; ?> &nbsp;</td>
    <td class=table_col1><input type='checkbox' name='unban[]' value='<?php echo $arr14['agent_id']; ?>' /></td></tr>
<?php }?>
    </tbody></table>
    <center><a href='<?php echo URLROOT; ?>/adminclient'><button type="button" class="btn btn-sm ttbtn">View Current Clients</button></a>&nbsp;
    &nbsp;
    <button type="input" class="btn btn-sm ttbtn">Unban</button></center>
</form>