<center><a href='<?php echo URLROOT; ?>/adminpoll/add'>Add New Poll</a>&nbsp;/&nbsp;
<a href='<?php echo URLROOT; ?>/adminpoll/results'>View Poll Results</a></center>
<b>Polls</b> (Top poll is current)<br />
<div class='border ttborder'><br>
<?php
while ($row = $data['query']->fetch(PDO::FETCH_ASSOC)) { ?>
   <a href='<?php echo URLROOT; ?>/adminpoll/add?subact=edit&amp;pollid=<?php echo $row['id']; ?>'>
   <?php echo stripslashes($row['question']); ?></a> - 
   <?php echo TimeDate::utc_to_tz($row['added']); ?> - 
   <a href='<?php echo URLROOT; ?>/adminpoll/delete?id=<?php echo $row['id']; ?>'>Delete</a><br /><br>
   <?php 
} ?>
</div>