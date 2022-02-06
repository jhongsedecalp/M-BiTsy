<?php
if ($_SESSION['loggedin'] || !Config::get('MEMBERSONLY')) {
    $keyword = $_GET['keyword'] ?? '';
    Style::block_begin(Lang::T("SEARCH"));
    ?>
	<form method="get" action="<?php echo URLROOT; ?>/search" class="form-inline">
		<div class="input-group">
			<input type="text" name="keyword" class="form-control" value="<?php echo htmlspecialchars($keyword); ?>" />
			<span class="input-group-btn">
				<button type="submit" class="btn ttbtn"/><?php echo Lang::T("SEARCH"); ?></button>
			</span>
		</div>
	</form>
	<?php
    Style::block_end();
}