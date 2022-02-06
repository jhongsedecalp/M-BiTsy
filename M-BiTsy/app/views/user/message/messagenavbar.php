<center>
<a href="<?php echo URLROOT; ?>/message/overview"><button type="button" class="<?php echo activelink('messages/overview'); ?>"><?php echo Lang::T("Over View"); ?></button></a>&nbsp;
<a href="<?php echo URLROOT; ?>/message?type=inbox"><button type="button" class="<?php echo activelink('inbox', true); ?>"><?php echo Lang::T("INBOX"); ?></button></a>&nbsp;
<a href="<?php echo URLROOT; ?>/message?type=outbox"><button type="button" class="<?php echo activelink('outbox', true); ?>"><?php echo Lang::T("OUTBOX"); ?></button></a>&nbsp;
<a href="<?php echo URLROOT; ?>/message?type=draft"><button type="button" class="<?php echo activelink('draft', true); ?>"><?php echo Lang::T("DRAFT"); ?></button></a>&nbsp;
<a href="<?php echo URLROOT; ?>/message?type=templates"><button type="button" class="<?php echo activelink('templates', true); ?>"><?php echo Lang::T("TEMPLATES"); ?></button></a>&nbsp;
<a href="<?php echo URLROOT; ?>/message/create"><button type="button" class="<?php echo activelink('messages/create'); ?>"><?php echo Lang::T("COMPOSE"); ?></button></a>
</center><br>