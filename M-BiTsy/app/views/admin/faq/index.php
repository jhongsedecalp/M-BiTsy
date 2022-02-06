<form method="post" action="<?php echo URLROOT ?>/adminfaq/reorder">

<p class="text-center"><a href='<?php echo URLROOT ?>/adminfaq/newsection'>Add new section</a></p> <?php

foreach ($data['faq_categ'] as $id => $temp) { ?>
    <br><table class='table table-striped table-bordered table-hover'><thead><tr>
    <th class="table_head" colspan="2">Position</th>
    <th class="table_head">Section/Item <?php echo Lang::T("TITLE") ?>: </th>
    <th class="table_head">Status</th><th class="table_head">Actions</th>
    </tr></thead><tbody>
    <tr>
    <td class="table_col1" align="center" width="40px"><select name="order[<?php echo $id ?>]">
    <?php
    for ($n = 1; $n <= count($data['faq_categ']); $n++) {
        $sel = ($n == $data['faq_categ'][$id]['order']) ? " selected=\"selected\"" : "";
        print("<option value=\"$n\"" . $sel . ">" . $n . "</option>");
    }
    $status = ($data['faq_categ'][$id]['flag'] == "0") ? "<font color=\"red\">Hidden</font>" : "Normal";
    ?>
    </select></td>
    <td class="table_col2" align="center" width="40px">&nbsp;</td>
    <td class="table_col1"><b><?php echo stripslashes($data['faq_categ'][$id]['title']) ?></b></td>
    <td class="ttable_col2" align="center" width="60px"><?php echo $status  ?></td>
    <td class="ttable_col1" align="center" width="60px"><a href="<?php echo URLROOT ?>/adminfaq/edit?action=editsect&id=<?php echo  $id ?>\">edit</a><br>
    <a href="<?php echo URLROOT ?>/adminfaq/delete?id=<?php echo $id ?>">delete</a></td></tr>
    <?php
    if (array_key_exists("items", $data['faq_categ'][$id])) {
        foreach ($data['faq_categ'][$id]['items'] as $id2 => $temp) {
            print("<tr><td class=\"ttable_col1\" align=\"center\" width=\"40px\">&nbsp;</td><td class=\"table_col2\" align=\"center\" width=\"40px\"><select name=\"order[" . $id2 . "]\">");
            for ($n = 1; $n <= count($data['faq_categ'][$id]['items']); $n++) {
                $sel = ($n == $data['faq_categ'][$id]['items'][$id2]['order']) ? " selected=\"selected\"" : "";
                print("<option value=\"$n\"" . $sel . ">" . $n . "</option>");
            }
            if ($data['faq_categ'][$id]['items'][$id2]['flag'] == "0") {
                $status = "<font color=\"#ff0000\">Hidden</font>";
            } elseif ($data['faq_categ'][$id]['items'][$id2]['flag'] == "2") {
                $status = "<font color=\"#0000FF\">Updated</font>";
            } elseif ($data['faq_categ'][$id]['items'][$id2]['flag'] == "3") {
                $status = "<font color=\"#008000\">New</font>";
            } else {
                $status = "Normal";
            }
            print("</select></td><td class=\"ttable_col1\">" . stripslashes($data['faq_categ'][$id]['items'][$id2]['question']) . "</td><td class=\"table_col2\" align=\"center\" width=\"60px\">" . $status . "</td>
            
            <td class=\"ttable_col1\" align=\"center\" width=\"60px\"><a href=\"".URLROOT."/adminfaq/edit?action=edititem&id=" . $id2 . "\">edit</a>
             <a href=\"".URLROOT."/adminfaq/delete?id=" . $id2 . "\">delete</a></td></tr>\n");
        }
    }
    print("<tr><td colspan=\"5\" align=\"center\"><a href=\"".URLROOT."/adminfaq/additem?id=" . $id . "\">Add new item</a></td></tr>\n");
    print("</tbody></table>\n");
}

if (isset($data['faq_orphaned'])) {
    print("<br />\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"95%\">\n");
    print("<tr><td align=\"center\" colspan=\"3\"><b style=\"color: #ff0000\">Orphaned Items</b></td>\n");
    print("<tr><td  align=\"left\">Item " . Lang::T("TITLE") . ": </td><td  align=\"center\">Status</td><td  align=\"center\">Actions</td></tr>\n");
    foreach ($data['faq_orphaned'] as $id => $temp) {
        if ($data['faq_orphaned'][$id]['flag'] == "0") {
            $status = "<font color=\"#ff0000\">Hidden</font>";
        } elseif ($data['faq_orphaned'][$id]['flag'] == "2") {
            $status = "<font color=\"#0000FF\">Updated</font>";
        } elseif ($data['faq_orphaned'][$id]['flag'] == "3") {
            $status = "<font color=\"#008000\">New</font>";
        } else {
            $status = "Normal";
        }

        print("<tr><td>" . stripslashes($data['faq_orphaned'][$id]['question']) . "</td><td align=\"center\" width=\"60px\">" . $status . "</td>
        <td align=\"center\" width=\"60px\"><a href=\"".URLROOT."/adminfaq/edit?action=edititem&id=" . $id . "\">edit</a>
        <a href=\"".URLROOT."/adminfaq/delete?id=" . $id . "\">delete</a></td></tr>\n");
    }
    print("</table>\n");
}

print("<p align=\"center\"><input type=\"submit\" class='btn btn-sm ttbtn' name=\"reorder\" value=\"Reorder\" /></p>\n");
print("</form>\n");
print("When the position numbers don't reflect the position in the table, it means the order id is bigger than the total number of sections/items and you should check all the order id's in the table and click \"reorder\"\n");