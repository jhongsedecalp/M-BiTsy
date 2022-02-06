<?php 
Style::begin(Lang::T("LATEST_TORRENTS")); ?>
<center><a href='<?php echo URLROOT; ?>/search/browse'><?php echo Lang::T("BROWSE_TORRENTS") ?></a> - <a href='<?php echo URLROOT; ?>/search'><?php echo Lang::T("SEARCH_TORRENTS"); ?></a></center><br />
<?php torrenttable($data['torrtable']);
Style::end();