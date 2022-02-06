<center>
<form method='get' action='<?php echo URLROOT; ?>/admintorrent'>
<input type='hidden' name='action' value='torrentmanage' />
Search: <input type='text' name='search' value='<?php echo $data['search']; ?>' size='30' />
<input type='submit' value='Search' />
</form>
<center><a href='<?php echo URLROOT; ?>/admintorrent/dead'>Dead Torrents</a></center>
<br>
<form id="myform" method='post' action='<?php echo URLROOT; ?>/admintorrent'>
<input type='hidden' name='do' value='delete' />
<table class='table table-striped table-bordered table-hover'><thead>
<tr>
<th><?php echo Lang::T("NAME"); ?></th>
<th>Visible</th>
<th>Banned</th>
<th>Seeders</th>
<th>Leechers</th>
<th>External</th>
<th>Edit</th>
<th><input type='checkbox' name='checkall' onclick='checkAll(this.form.id);' /></th>
</tr></thead><tbody>
<?php
while ($row = $data['res']->fetch(PDO::FETCH_LAZY)) {?>
    <tr>
    <td><a href='<?php echo URLROOT; ?>/torrent?id=<?php echo $row["id"]; ?>'><?php echo CutName(htmlspecialchars($row["name"]), 40); ?></a></td>
    <td><?php echo $row["visible"]; ?></td>
    <td><?php echo $row["banned"]; ?></td>
    <td><?php echo number_format($row["seeders"]); ?></td>
    <td><?php echo number_format($row["leechers"]); ?></td>
    <td><?php echo $row["external"]; ?></td>
    <td><a href='<?php echo URLROOT; ?>/torrent/edit?id=<?php echo $row["id"]; ?>&amp;returnto=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>'>Edit</a></td>
    <td><input type='checkbox' name='torrentids[]' value='<?php echo $row["id"]; ?>' /></td>
    </tr>
    <?php
}?>
</tbody></table>
<input type='submit' class='btn btn-sm ttbtn' value='Delete checked' />
</form>
<?php echo $data['pagerbuttons']; ?>
</center>