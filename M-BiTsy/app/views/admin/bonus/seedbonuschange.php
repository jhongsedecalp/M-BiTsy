<form method="post" action="<?php echo URLROOT; ?>/adminbonus/change">
    <?php if ($data['row'] != null): ?>
    <input type="hidden" name="id" value="<?php echo $data['row']->id; ?>" />
    <?php endif;?>
    <div class='table-responsive'> <table class='table table-striped'><thead><tr>
        <td><b>Title:</b></td>
        <td><input type="text" name="title" value="<?php echo ($data['row'] != null ? $data['row']->title : null); ?>" size="50" /></td>
    </tr>
    <tr>
        <td><b>Points:</b></td>
        <td><input type="text" name="cost" value="<?php echo ($data['row'] != null ? $data['row']->cost : null); ?>" size="5" /></td>
    </tr>
    <tr>
        <td><b>Type:</b></td>
        <td>
        <select name="type">
        <?php foreach (array('invite', 'traffic', 'VIP', 'other', 'HnR') as $type): ?>
        <option value="<?php echo $type; ?>" <?php echo ($data['row'] != null && $data['row']->type == $type ? 'selected="selected"' : null); ?>><?php echo $type; ?></option>
        <?php endforeach;?>
        </select>
        </td>
    </tr>
    <tr>
        <td><b>Value:</b></td>
        <td><input type="text" name="value" value="<?php echo ($data['row'] != null ? $data['row']->value : null); ?>" size="10" /></td>
    </tr>
    <tr>
        <td><b>Description:</b></td>
        <td><textarea name="descr" rows="5" cols="38"><?php echo ($data['row'] != null ? $data['row']->descr : null); ?></textarea></td>
    </tr></tbody>
    </table>
    <div class="text-center">
        <a href="<?php echo URLROOT; ?>/adminbonus" class="btn btn-sm ttbtn">Cancel</a>
        <input type="submit" class="btn btn-sm ttbtn" value="To send" />
        </div>
    </form>
    </div>