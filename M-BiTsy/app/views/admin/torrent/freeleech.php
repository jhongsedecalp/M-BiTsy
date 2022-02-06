<center><form method='get' action='?'>
<input type='hidden' name='action' value='freetorrents' />
<?php echo Lang::T("SEARCH") ?>: <input type='text' size='30' name='search' />
<input type='submit' value='Search' />
</form></center>
<?php echo $data['pagerbuttons']; ?>
<table class='table table-striped table-bordered table-hover'><thead>
<tr>
    <th class="table_head"><?php echo Lang::T("NAME"); ?></th>
    <th class="table_head"><?php echo Lang::T("VISIBLE"); ?></th>
    <th class="table_head"><?php echo Lang::T("BANNED"); ?></th>
    <th class="table_head"><?php echo Lang::T("SEEDERS"); ?></th>
    <th class="table_head"><?php echo Lang::T("LEECHERS"); ?></th>
    <th class="table_head"><?php echo Lang::T("EDIT"); ?></th>
</tr></thead><tbody>
<?php
 while ($row = $data['resqq']->fetch(PDO::FETCH_LAZY)) {
    $char1 = 35; //cut name length
    $smallname = CutName(htmlspecialchars($row["name"]), $char1); ?>
    <tr>
    <td class='table_col1'><?php echo $smallname ?></td>
    <td class='table_col2'><?php echo $row['visible'] ?></td>
    <td class='table_col1'><?php echo $row['banned'] ?></td>
    <td class='table_col2'><?php echo number_format($row["seeders"]) ?></td>
    <td class='table_col1'><?php echo number_format($row["leechers"]) ?></td>
    <td class='table_col2'><a href="<?php echo URLROOT ?>/torrent/edit?returnto=<?php echo urlencode($_SERVER["REQUEST_URI"]) ?>&amp;id=<?php echo $row["id"] ?>"><font size='1' face='verdana'>EDIT</font></a></td></tr>
    <?php
} ?>
</tbody></table>
<?php
$data['pagerbuttons'];