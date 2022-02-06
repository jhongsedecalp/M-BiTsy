<?php
foreach ($data['res'] as $row) {
    if ($row['public'] == "yes") { ?>
        <div class="row justify-content-center">
            <div class="col-8 border ttborder">
            <center><?php echo $row['title']; ?></center>
            <br>
            <?php echo format_comment($row['text']); ?><br><br>
            </div>
        </div><br>
        <?php
    } else if ($row['public'] == "no" && $row['class'] <= Users::get("class")) { ?>
        <div class="row justify-content-center">
            <div class="col-8 border ttborder">
            <center><?php echo $row['title']; ?></center>
            <br>
            <?php echo format_comment($row['text']); ?><br><br>
            
            </div>
        </div><br>
        <?php
    }
}