<?php
class Faqs
{

    public static function select()
    {
        $res = DB::raw('faq', 'id, question, flag', ['type'=>'categ'], 'ORDER BY `order` ASC');
        while ($arr = $res->fetch(PDO::FETCH_BOTH)) {
            $faq_categ[$arr['id']]['title'] = $arr['question'];
            $faq_categ[$arr['id']]['flag'] = $arr['flag'];
        }
        $res = DB::raw('faq', 'id, question, answer, flag, categ', ['type'=>'item'], 'ORDER BY `order` ASC');
        while ($arr = $res->fetch(PDO::FETCH_BOTH)) {
            $faq_categ[$arr['categ']]['items'][$arr['id']]['question'] = $arr['question'];
            $faq_categ[$arr['categ']]['items'][$arr['id']]['answer'] = $arr['answer'];
            $faq_categ[$arr['categ']]['items'][$arr['id']]['flag'] = $arr['flag'];
        }
        // gather orphaned items
        foreach ($faq_categ as $id => $temp) {
            if (!array_key_exists("title", $faq_categ[$id])) {
                foreach ($faq_categ[$id]['items'] as $id2 => $temp) {
                    $faq_orphaned[$id2]['question'] = $faq_categ[$id]['items'][$id2]['question'];
                    $faq_orphaned[$id2]['answer'] = $faq_categ[$id]['items'][$id2]['answer'];
                    $faq_orphaned[$id2]['flag'] = $faq_categ[$id]['items'][$id2]['flag'];
                    unset($faq_categ[$id]);
                }
            }
        }

        return $faq_categ;
    }

}