<?php
Style::begin($title);
?>
<div class='table-responsive'><table class='table table-striped'>
    <thead><tr>
    <th>Rank</th>
    <th>Country&nbsp;Flag</th>
    <th>Country&nbsp;Name</th>
    <th>Uploaded</th>
    </tr></thead>
     <?php
    $num = 0;
    while ($a = $res->fetch(PDO::FETCH_ASSOC)) {
        ++$num;
        $value = mksize($a["ul"]);
        if ($a['flagpic']) {
            $flag = "<img align=center src=".URLROOT."/assets/images/languages/$a[flagpic]>";
        } else {
            $flag = "<img align=center src=".URLROOT."/assets/images/languages/unknown.gif>";
        }
        if ($a['name']) {
            $name = "<b>$a[name]</b>";
        } else {
            $name = "<b>Land of Homeless!</b>";
        }
        print("<tbody><tr>
            <td align=center class=table_col1>$num</td>
            <td align=center class=table_col2>" . "$flag</td>
            <td class=table_col1>$name</td>" . "
            <td align=right class=table_col2>$value</td>
            </tr></tbody>");
    }
print("</table></div>");
 Style::end();