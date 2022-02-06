<?php
if ((Config::get('INVITEONLY') || Config::get('ENABLEINVITES')) && $_SESSION['loggedin'] == true) {
   $invites = Users::get("invites");
   Style::block_begin(Lang::T("INVITES"));
   ?>
   <div class="text-center">
	<?php printf(Lang::N("YOU_HAVE_INVITES", $invites), $invites);?> <br> <?php
   if ($invites > 0) {  ?>
      <a href="<?php echo URLROOT ?>/invite" class="btn ttbtn"><?php echo Lang::T("Send"); ?></a> <?php
   }
   if (Users::get("invitees") > 0) { ?>
      <a href="<?php echo URLROOT ?>/invite/invitetree" class="btn ttbtn"><?php echo Lang::T("Invited"); ?></a> <?php
   } ?>
   </div>
	<?php
   Style::block_end();
}