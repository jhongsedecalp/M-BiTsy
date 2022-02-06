
<table align="center" width="70%"><tr><td>

<center><?php echo Lang::T("_BLC_ENABLED_") ?></center>

<table class='table table-striped table-bordered table-hover'><thead><tr>
<th><?php echo Lang::T("NAME") ?></th>
<th>Description</th>
<th>Position</th>
<th>Order</th>
<th>Preview</th>
</tr></thead><tbody> <?php
while ($blocks = $data['enabled']->fetch(PDO::FETCH_LAZY)) { ?>
    <tr>
    <td  valign=\"top\"><?php echo $blocks["named"] ?></td>
    <td><?php echo $blocks["description"] ?></td>
    <td><?php echo $blocks["position"] ?></td>
    <td><?php echo $blocks["sort"] ?></td>
    <td>[<a href="<?php echo URLROOT ?>/adminpreview?name=<?php echo $blocks["name"] ?>#<?php echo $blocks["name"] ?>" target="_blank">preview</a>]</td>
    </tr>
<?php } ?>
</tbody></table>
<form action='<?php echo URLROOT ?>/adminblock/edit'>
<div class="text-center">
<input type='submit' class='btn btn-sm ttbtn' value='Edit' /></form>
</div>
</td></tr></table>

<hr />
<center>Disabled Blocks</center>

<table align="center" width="70%"><tr><td>
<table class='table table-striped table-bordered table-hover'><thead><tr>
    <th><?php echo Lang::T("NAME") ?></th>
    <th>Description</th>
    <th>Position</th>
    <th>Order</th>
    <th>Preview</th>
    </tr></thead><tbody> <?php
    while ($blocks = $data['disabled']->fetch(PDO::FETCH_LAZY)) {
        print("<tr>" .
            "<td valign=\"top\">" . $blocks["named"] . "</td>" .
            "<td>" . $blocks["description"] . "</td>" .
            "<td>" . $blocks["position"] . "</td>" .
            "<td>" . $blocks["sort"] . "</td>" .
            "<td>[<a href=\"".URLROOT."/adminblock/preview?name=" . $blocks["name"] . "#" . $blocks["name"] . "\" target=\"_blank\">preview</a>]</td>" .
            "</tr>");
    }
    print("<tr><td colspan=\"5\" align=\"center\" valign=\"bottom\" class=\"table_head\"><form action='".URLROOT."/adminblock/upload'><input type='submit' class='btn btn-sm ttbtn' value='Upload new Block' /></form></td></tr>");
    print("</tbody></table>");
    print("</td></tr></table>");