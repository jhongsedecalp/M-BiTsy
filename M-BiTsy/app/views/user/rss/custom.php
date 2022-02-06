What is RSS? Take a look at the <a href="http://wikipedia.org/wiki/RSS_%28file_format%29">Wiki</a> to <a href="http://wikipedia.org/wiki/RSS_%28file_format%29">learn more</a>.<br /><br />

<form action="<?php echo URLROOT; ?>/rss/submit" method="post">
<table border="0" cellpadding="5" cellspacing="0" class="table_table">
	<tr>
	<td class="browsebg" valign="top">Categories:</td>
	<td class="browsebg" valign="top">(Leave blank for All)<br /><br />
	<?php
	while ($row = $data['resqn']->fetch(PDO::FETCH_LAZY)) {
        echo '<input type="checkbox" name="cats[]" value="' . $row['id'] . '" /> ' . htmlspecialchars("$row[parent_cat] - $row[name]") . '<br />';
    } ?>
	</td>
    </tr>
    <tr>
	<td class="browsebg"><?php echo Lang::T("FEED_TYPE"); ?>:</td>
	<td class="browsebg">
	<input type="radio" name="dllink" value="0" checked="checked" />Details link<br />
	<input type="radio" name="dllink" value="1" /> Download link<br />
	</td>
	</tr>
	<tr>
	<td class="browsebg"><?php echo Lang::T("LOGIN_TYPE"); ?>:</td>
	<td class="browsebg">
	<input type="radio" name="cookies" value="1" checked="checked" /> Standard (cookies)<br/>
	<input type="radio" name="cookies" value="0" /> Alternative (no cookies)<br/>
    </td>
	</tr>
	<tr>
	<td class="browsebg"><?php echo Lang::T("INCLUDE_DEAD"); ?>:</td>
	<td class="browsebg"><input type="checkbox" name="incldead" value="1" /></td>
	</tr>
	<tr>
	<td class='browsebg' colspan="2" align="center"><input type="submit" value="Get Link" /></td>
    </tr>
</table>
</form>
<br />
<div align="left">
	Quick information regarding our RSS:
    <ul>
    <li>Our RSS feeds are properly validated by true RSS 2.0 XML Parsing Standards. Visit FeedValidator.org to validate.</li>
    <li>Our feeds display only the latest 50 uploaded Torrents as default.</li>
    </ul>
</div>