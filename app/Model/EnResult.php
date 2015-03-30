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

    //  param  :: Quest type
    //  return :: EX array (size=126) 1427339834(unixTime) => int 10(value)
    public function getTryNumJson($type = null){
        $option = array('conditions' => array('EnResult.type' => $type),
            'field' => array('EnResult.date', 'EnResult.quest_id', 'EnResult.flag'));
        $allResults = $this->find('all', $option);
        $jsonResult = array();
        for($i = 0; $i < count($allResults); $i++){
            $jsonResult += [strtotime($allResults[$i]['EnResult']['date']) => 10];
        }
        return $jsonResult;
    }

}