<?php
print("<a name='top'></a>");
foreach ($data['faq_categ'] as $id => $temp) {
    if ($data['faq_categ'][$id]['flag'] == "1") {
        print("<ul style='list-style: none'>\n<li><a href=\"#section" . $id . "\"><b>" . stripslashes($data['faq_categ'][$id]['title']) . "</b></a>\n<ul style='list-style: none'><br>\n");
        if (array_key_exists("items", $data['faq_categ'][$id])) {
            foreach ($data['faq_categ'][$id]['items'] as $id2 => $temp) {
                if ($data['faq_categ'][$id]['items'][$id2]['flag'] == "1") {
                    print("<li><a href=\"#section" . $id2 . "\">" . stripslashes($data['faq_categ'][$id]['items'][$id2]['question']) . "</a></li>\n");
                } elseif ($data['faq_categ'][$id]['items'][$id2]['flag'] == "2") {
                    print("<li><a href=\"#section" . $id2 . "\">" . stripslashes($data['faq_categ'][$id]['items'][$id2]['question']) . "</a> <img src=\"" . URLROOT . "/assets/images/misc/updated.png\" alt=\"Updated\" width=\"46\" height=\"13\" align=\"bottom\" /></li>\n");
                } elseif ($data['faq_categ'][$id]['items'][$id2]['flag'] == "3") {
                    print("<li><a href=\"#section" . $id2 . "\">" . stripslashes($data['faq_categ'][$id]['items'][$id2]['question']) . "</a> <img src=\"" . URLROOT . "/assets/images/misc/new.png\" alt=\"New\" width=\"25\" height=\"12\" align=\"bottom\" /></li>\n");
                }

            }
        }
        print("</ul>\n</li>\n</ul>\n<br />\n");
    }
}

?><br><hr><br><?php

foreach ($data['faq_categ'] as $id => $temp) {
    if ($data['faq_categ'][$id]['flag'] == "1") {
        $frame = $data['faq_categ'][$id]['title'] . " - <a href=\"#top\">Top</a>";
        print("<a id=\"section" . $id . "\"></a>\n");
        if (array_key_exists("items", $data['faq_categ'][$id])) {
            foreach ($data['faq_categ'][$id]['items'] as $id2 => $temp) {
                if ($data['faq_categ'][$id]['items'][$id2]['flag'] != "0") {
                    print("<br />\n<b>" . stripslashes($data['faq_categ'][$id]['items'][$id2]['question']) . "</b><a id=\"section" . $id2 . "\"></a>\n<br />\n");
                    print("<br />\n" . stripslashes($data['faq_categ'][$id]['items'][$id2]['answer']) . "\n<br /><br />\n");
                }
            }
        }
    }
}