<form method="post" name="comment" action="<?php echo URLROOT ?>/comment/edit?type=<?php echo $data['type'] ?>&save=1&amp;id=<?php echo $data['id'] ?>">
    <?php print textbbcode("comment", "text", htmlspecialchars($data["text"])); ?>
<p class='text-center'><input type="submit"  class='btn btn-sm ttbtn'  value="Submit Changes" /></p>
</form>