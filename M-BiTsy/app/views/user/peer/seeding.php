<?php usermenu($data['id']);
if ($data["privacy"] != "strong" || (Users::get("control_panel") == "yes") || (Users::get("id") == $data["uid"])) {
    if ($data['seeding']) {
        print("<br><b>" . Lang::T("CURRENTLY_SEEDING") . ":</b><br />$data[seeding]<br /><br />");
    }
    if ($data['leeching']) {
        print("<br><b>" . Lang::T("CURRENTLY_LEECHING") . ":</b><br />$data[leeching]<br /><br />");
    }
    if (!$data['leeching'] && !$data['seeding']) {
        print("<br><b>" . Lang::T("NO_ACTIVE_TRANSFERS") . "</b><br />");
    }
}