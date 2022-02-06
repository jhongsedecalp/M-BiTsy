<?php
if ($_SESSION['loggedin'] == true) {
    Style::block_begin("Powered By");
    ?>
    <center>
    <a href="https://getbootstrap.com/" target="_blank"><img
      src="<?php echo URLROOT; ?>/assets/images/misc/boot.png" alt="Bootstrap" title="Bootstrap" height="40" width="40" /></a>
    <a href="https://phpdelusions.net/pdo" target="_blank"><img
      src="<?php echo URLROOT; ?>/assets/images/misc/pdo.png" alt="PDO" title="PDO" height="40" width="40" /></a>
    <a href="https://www.php.net/" target="_blank"><img
      src="<?php echo URLROOT; ?>/assets/images/misc/php.png" alt="PHP" title="PHP" height="40" width="40" /></a>
    </center>
    <?php
    Style::block_end();
}