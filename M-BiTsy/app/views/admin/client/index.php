<center><b>Current Clients Connected</b></center>
<form id="ban" method="post" action="<?php echo URLROOT; ?>/adminclient">
    <table class='table table-striped table-bordered table-hover'><thead>
       <tr><th class=table_head>Client</th>
       <th class=table_head>Peer ID</th>
       <th class=table_head>To ban use</th>
       <th class=table_head>Is Banned</th>
       </th></tr></thead><tbody></tbody>
    <?php while ($arr12 = $data['res11']) {
        $peer = $arr12['peer_id'];
        $peer = substr($peer, 0, 8);
        $peer2 = $peer;
        $arr3 = DB::raw('clients', 'hits', ['agent_name'=>$peer2])->fetch();
        $isbanned = "<font color='green'><b>Yes</b></font>";
        if ($arr3 == 0) {
            $isbanned = "<font color='red'><b>No</b></font>";
        }
        ?>
        <tr>
        <td class=table_col1>&nbsp; <?php echo $arr12['client']; ?> &nbsp;</td>
        <td class=table_col2>&nbsp; <?php echo $arr12['peer_id']; ?> &nbsp;</td>
        <td class=table_col2>&nbsp; <?php echo $peer2; ?> &nbsp;</td>
        <td class=table_col2>&nbsp; <?php echo $isbanned; ?> &nbsp;</td></tr>
    <?php }?>

    </tbody></table>
    <div class="text-center">
	    <label for="name"><b>Enter Ban Code :</b></label>
        <input id="name" type="text" class="form-control" name="ban" minlength="3" maxlength="25"><br>
        <a href='<?php echo URLROOT; ?>/adminclient/banned'><button type="submit" class="btn ttbtn btn-sm">Ban</button></a>&nbsp;
        <a href='<?php echo URLROOT; ?>/adminclient/banned' class="btn ttbtn btn-sm">View Banned</button></a>
    </div>
    </form>