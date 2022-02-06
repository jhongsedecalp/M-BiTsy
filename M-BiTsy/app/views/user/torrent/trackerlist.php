<?php
$row = $data['res']->fetch(PDO::FETCH_ASSOC);
torrentmenu($data['id'], $row['external'])
?>
<?php
if ($row["external"] == 'yes') {
    print("<br><b>Tracker:</b>&nbsp;$row[announce]<br><br>");
    $array = unserialize($row['announcelist']);
    foreach ($array as $subarray){
       echo $subarray."<br>";
     }
}
if ($data['res']->rowCount() > 1) {
    echo "<br /><b>" . Lang::T("THIS_TORRENT_HAS_BACKUP_TRACKERS") . "</b><br />";
    echo '<table cellpadding="1" cellspacing="2" class="table_table"><tr>';
    echo '<th class="table_head">URL</th><th class="table_head">' . Lang::T("SEEDERS") . '</th><th class="table_head">' . Lang::T("LEECHERS") . '</th><th class="table_head">' . Lang::T("COMPLETED") . '</th></tr>';
    $x = 1;
    while ($trow = $data['res']->fetch(PDO::FETCH_ASSOC)) {
        $colour = $trow["online"] == "yes" ? "green" : "red";
        echo "<tr class=\"table_col$x\"><td><font color=\"$colour\"><b>" . htmlspecialchars($trow['url']) . "</b></font></td><td align=\"center\">" . number_format($trow["seeders"]) . "</td><td align=\"center\">" . number_format($trow["leechers"]) . "</td><td align=\"center\">" . number_format($trow["times_completed"]) . "</td></tr>";
        $x = $x == 1 ? 2 : 1;
    }
    echo '</table>';
}