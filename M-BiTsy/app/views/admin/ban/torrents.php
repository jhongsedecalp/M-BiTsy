<?php echo $data['pagerbuttons']; ?>
<table class='table table-striped table-bordered table-hover'><thead>
<tr>
<th><?php echo Lang::T("NAME"); ?></th>
<th>Visible</th>
<th>Seeders</th>
<th>Leechers</th>
<th>External?</th>
<th>Edit?</th>
</tr></thead><tbody>
<?php
while ($row = $data['resqq']->fetch(PDO::FETCH_ASSOC)) { 
    $smallname = substr(htmlspecialchars($row["name"]), 0, 35); ?>
    <tr>
    <td><?php echo $smallname ?></td>
    <td><?php echo $row['visible'] ?></td>
    <td><?php echo number_format($row["seeders"]) ?></td>
    <td><?php echo number_format($row["leechers"]) ?></td>
    <td><?php echo $row['external'] ?></td>
    <td><a href="<?php echo URLROOT ?>/torrent/edit?id=<?php echo $row["id"] ?>"><font size='1' face='verdana'>EDIT</font></a></td></tr>
    <?php
} ?>
</tbody></table> <?php
$data['pagerbuttons'];