<?php
if ($_SESSION['loggedin'] == true || !Config::get('MEMBERSONLY')) {
	Style::block_begin(Lang::T("BROWSE_TORRENTS"));
    $catsquery = DB::run("SELECT distinct parent_cat FROM categories ORDER BY parent_cat");?>
	<div class="list-group">
		<a href="<?php echo URLROOT; ?>/search/browse" class="list-group-item"><i class="fa fa-folder-open tticon"></i> <?php echo Lang::T("SHOW_ALL"); ?></a>
	    <?php
		while ($catsrow = $catsquery->fetch(PDO::FETCH_ASSOC)) { ?>
		<a href="<?php echo URLROOT; ?>/search/browse?parent_cat=<?php echo urlencode($catsrow["parent_cat"]); ?>" class="list-group-item"><i class="fa fa-folder-open tticon"></i> <?php echo $catsrow["parent_cat"]; ?></a>
	    <?php
	    } ?>
	</div><?php
	Style::block_end();
}