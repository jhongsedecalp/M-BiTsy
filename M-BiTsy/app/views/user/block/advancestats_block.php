<?php
$date_time = TimeDate::get_date_time(TimeDate::gmtime() - (3600 * 24)); // the 24hrs is the hours you want listed
$registered = number_format(get_row_count("users"));
$ncomments = number_format(get_row_count("comments"));
$nmessages = number_format(get_row_count("messages"));
$ntor = number_format(get_row_count("torrents"));
$totaltoday = number_format(get_row_count("users", "WHERE users.last_access>='$date_time'"));
$regtoday = number_format(get_row_count("users", "WHERE users.added>='$date_time'"));
$todaytor = number_format(get_row_count("torrents", "WHERE torrents.added>='$date_time'"));
$guests = number_format(Guests::getGuests());
$seeders = get_row_count("peers", "WHERE seeder='yes'");
$leechers = get_row_count("peers", "WHERE seeder='no'");
$members = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . TimeDate::get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900"));
$totalonline = $members + $guests;

$result = DB::run("SELECT SUM(downloaded) AS totaldl FROM users");
while ($row = $result->fetch(PDO::FETCH_LAZY)) {
    $totaldownloaded = $row["totaldl"];
}

$result = DB::run("SELECT SUM(uploaded) AS totalul FROM users");
while ($row = $result->fetch(PDO::FETCH_LAZY)) {
    $totaluploaded = $row["totalul"];
}

$localpeers = $leechers + $seeders;
if ($_SESSION['loggedin'] === true && Users::get("edit_users") == "yes") {
	Style::block_begin(Lang::T("STATS"));
    ?>
	<ul class="list-unstyled">
	<p><strong><?php echo Lang::T("TORRENTS"); ?></strong></p>
	<li><i class="fa fa-folder-open-o"></i> <?php echo Lang::T("TRACKING"); ?>: <strong><?php echo $ntor; ?> <?php echo Lang::N("TORRENT", $ntor); ?></strong></li>
	<li><i class="fa fa-calendar-o"></i> <?php echo Lang::T("NEW_TODAY"); ?>: <strong><?php echo $todaytor; ?></strong></li>
	<li><i class="fa fa-refresh"></i> <?php echo Lang::T("SEEDERS"); ?>: <strong><?php echo number_format($seeders); ?></strong></li>
	<li><i class="fa fa-arrow-circle-down"></i> <?php echo Lang::T("LEECHERS"); ?>: <strong><?php echo number_format($leechers); ?></strong></li>
	<li><i class="fa fa-arrow-circle-up"></i> <?php echo Lang::T("PEERS"); ?>: <strong><?php echo number_format($localpeers); ?></strong></li>
	<li><i class="fa fa-download"></i> <?php echo Lang::T("DOWNLOADED"); ?>: <strong><span class="label label-danger"><?php echo mksize($totaldownloaded); ?></span></strong></li>
	<li><i class="fa fa-upload"></i> <?php echo Lang::T("UPLOADED"); ?>: <strong><span class="label label-success"><?php echo mksize($totaluploaded); ?></span></strong></li>
	<hr />
	<p><strong><?php echo Lang::T("MEMBERS"); ?></strong></p>
	<li><?php echo Lang::T("WE_HAVE"); ?>: <strong><?php echo $registered; ?> <?php echo Lang::N("MEMBER", $registered); ?></strong></li>
	<li><?php echo Lang::T("NEW_TODAY"); ?>: <strong><?php echo $regtoday; ?></strong></li>
	<li><?php echo Lang::T("VISITORS_TODAY"); ?>: <strong><?php echo $totaltoday; ?></strong></li>
	<hr />
	<p><strong><?php echo Lang::T("ONLINE"); ?></strong></p>
	<li><?php echo Lang::T("TOTAL_ONLINE"); ?>: <strong><?php echo $totalonline; ?></strong></li>
	<li><?php echo Lang::T("MEMBERS"); ?>: <strong><?php echo $members; ?></strong></li>
	<li><?php echo Lang::T("GUESTS_ONLINE"); ?>: <strong><?php echo $guests; ?></strong></li>
	<li><?php echo Lang::T("COMMENTS_POSTED"); ?>: <strong><?php echo $ncomments; ?></strong></li>
	<li><?php echo Lang::T("MESSAGES_SENT"); ?>: <strong><?php echo $nmessages; ?></strong></li>
    </ul>
    <?php
	Style::block_end();
}
if (Users::get("edit_users") == "no") {
    Style::block_begin(Lang::T("STATS"));
	?>
    <ul class="list-unstyled">
	<p><strong><?php echo Lang::T("TORRENTS"); ?></strong></p>
	<li><i class="fa fa-folder-open-o"></i> <?php echo Lang::T("TRACKING"); ?>: <strong><?php echo $ntor; ?> <?php echo Lang::N("TORRENT", $ntor); ?></strong></li>
	<li><i class="fa fa-calendar-o"></i> <?php echo Lang::T("NEW_TODAY"); ?>: <strong><?php echo $todaytor; ?></strong></li>
    </ul>
	<?php
	Style::block_end();
}