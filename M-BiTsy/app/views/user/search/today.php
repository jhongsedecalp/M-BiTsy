<?php
while ($cat = $data['catresult']->fetch(PDO::FETCH_ASSOC)) {
    $orderby = "ORDER BY torrents.sticky ASC, torrents.id DESC"; //Order
    $where = "WHERE banned = 'no' AND category='$cat[id]' AND visible='yes'";
    $limit = "LIMIT 10"; //Limit

    $res = Torrents::getCatSortAll($where, $data['date_time'], $orderby, $limit);
    $numtor = $res->rowCount();
    if ($numtor != 0) {
        echo "<b><a href=" . URLROOT . "/torrent/browse?cat=" . $cat["id"] . "'>$cat[name]</a></b>";
        torrenttable($res);
        echo "<br />";
    }
}