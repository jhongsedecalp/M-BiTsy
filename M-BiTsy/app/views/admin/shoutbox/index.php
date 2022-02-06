<p id="shoutboxstaff"></p>
<form name='shoutboxform' action='<?php echo URLROOT ?>/adminshoutbox/add' method='post'>
<div class="row">
    <div class="col-md-12">
    <?php
    echo shoutbbcode("shoutboxform", "message");
    ?>
    </div>
</div>
</form>