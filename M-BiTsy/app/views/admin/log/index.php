<form method='get' action='?'><center>
<input type='hidden' name='action' value='sitelog' />
<?php echo Lang::T("SEARCH"); ?>: <input type='text' size='30' name='search' />
<input type='submit' value='Search' />
</center></form><br>
<form id='sitelog' action='<?php echo URLROOT; ?>/adminlog/delete' method='post'>
<table class='table table-striped table-bordered table-hover'><thead>
<tr>
    <th><input type="checkbox" name="checkall" onclick="checkAll(this.form.id)" /></th>
    <th>Date</th>
    <th>Time</th>
    <th>Event</th>
</tr></thead<tbody>
<?php
while ($arr = $data['res']->fetch(PDO::FETCH_ASSOC)) {
    $arr['added'] = TimeDate::utc_to_tz($arr['added']);
    $date = substr($arr['added'], 0, strpos($arr['added'], " "));
    $time = substr($arr['added'], strpos($arr['added'], " ") + 1);
    ?>
    <tr><td><input type='checkbox' name='del[]' value='<?php echo $arr['id']; ?>' /></td>
    <td><?php echo $date; ?></td>
    <td><?php echo $time; ?></td>
    <td><?php echo stripslashes($arr["txt"]); ?></td>
    <?php
} ?>
</tbody></table>
<div class="text-center">
<input type='submit' class='btn btn-sm ttbtn' value='Delete Checked' /> 
<input type='submit' class='btn btn-sm ttbtn' value='Delete All' name='delall' />
</div>
</form>
<?php
echo $data['pagerbuttons'];