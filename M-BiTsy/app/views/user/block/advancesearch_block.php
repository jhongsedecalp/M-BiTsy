<?php
if ($_SESSION['loggedin']) {
    Style::block_begin(Lang::T("SEARCH"));
	$search = $_GET['search'] ?? '';
    ?>
	<form method="get" action="<?php echo URLROOT; ?>/search">
		<input type="text" name="search" style="width: 95%" value="<?php echo htmlspecialchars($search); ?>" /><br />
		    <select name="cat"  style="width: 95%" >
			<option value="0">(<?php echo Lang::T("ALL_TYPES"); ?>)</option>
			<?php echo Catagories::dropdown(); ?>
		    </select><br />
		    <select name="incldead" style="width: 95%" >
			<option value="0"><?php echo Lang::T("ACTIVE"); ?></option>
			<option value="1"><?php echo Lang::T("INCLUDE_DEAD"); ?></option>
			<option value="2"><?php echo Lang::T("ONLY_DEAD"); ?></option>
		    </select><br />
	<?php if (Config::get('ALLOWEXTERNAL')) {?>
		    <select name="inclexternal" style="width: 95%" >
			<option value="0"><?php echo Lang::T("LOCAL"); ?>/<?php echo Lang::T("EXTERNAL"); ?></option>
			<option value="1"><?php echo Lang::T("LOCAL_ONLY"); ?></option>
			<option value="2"><?php echo Lang::T("EXTERNAL_ONLY"); ?></option>
		    </select><br />
	<?php }?>
		<center><button type="submit" class="btn ttbtn center-block" /><?php echo Lang::T("SEARCH"); ?></button></center>
	</form>
	<?php
	Style::block_end();
}