<?php
Style::begin(Lang::T("SHOUTBOX"));
if ($_SESSION['loggedin']) {
    ?>
<p id="shoutbox"></p>
<form name='shoutboxform' action='<?php echo URLROOT ?>/shoutbox/add' method='post'>
<div class="row">
    <div class="col-md-12">
        <?php
        echo shoutbbcode("shoutboxform", "message"); ?>
    </div>
</div>
</form>
<?php
} else {
    echo '<p id="shoutbox"></p>';
}
Style::end();