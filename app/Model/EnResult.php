<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/26
 * Time: 午前11:47
 */

class EnResult extends AppModel{
    public $useTable = 'en_results';


    // 　$typeの問題の結果をsetする。
    public function getResult($type){


    }

    public function getTryNumJson($type = null){
        $option = array('conditions' => array('EnResult.type' => $type),
            'field' => array('EnResult.date', 'EnResult.quest_id', 'EnResult.flag'));
        $allResults = $this->find('all', $option);

        for($i = 0; $i < count($allResults); $i++){
            $jsonResult[$i] = ['date' => strtotime($allResults[$i]['EnResult']['date']), 'value' => 10];
        }
        return $jsonResult;
    }

}