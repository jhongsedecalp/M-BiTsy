<?php
torrentmenu($data['id'], $row['external']);
echo "<center><b>" . Lang::T("FILE_LIST") . ":</b></center>";
echo '<div class="table-responsive"><table class="table table-striped"><thead><tr>';
echo "
<th>&nbsp;" . Lang::T("Type") . "</th>
<th>&nbsp;" . Lang::T("FILE") . "</th>
<th>&nbsp;" . Lang::T("SIZE") . "</th>
</tr>";
if ($data['fres']->rowCount()) {
    while ($frow = $data['fres']->fetch(PDO::FETCH_ASSOC)) {
        $ext = pathinfo($frow['path'], PATHINFO_EXTENSION);
        $filetype_icon = getexttype($ext);
    echo "<tr><td class='table_col1'>" . $filetype_icon . "</td><td class='table_col2'>" . htmlspecialchars($frow['path']) . "</td><td class='table_col1'>" . mksize($frow['filesize']) . "</td></tr>";
    }
} else {
     echo "<tr><td class='table_col1'>" . htmlspecialchars($data["name"]) . "</td><td class='table_col2'>" . mksize($data["size"]) . "</td></tr>";
}
echo "</table></div>";
/* for future
$array = unserialize($data['list']);
foreach ($array as $subarray){
   echo $subarray."<br>";
}
*/