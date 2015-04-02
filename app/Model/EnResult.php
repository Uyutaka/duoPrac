<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/26
 * Time: 午前11:47
 */

class EnResult extends AppModel{
    public $useTable = 'en_results';



    /*
     *  param  問題のタイプ
     *  return date(例：2015/03/26)の配列
     */
    public function getDateArr($type){
        $option = array('conditions' => array('EnResult.type' => $type),
            'field' => array('EnResult.date', 'EnResult.quest_id', 'EnResult.flag'));
        $allResults = $this->find('all', $option);
        for($i = 0; $i < count($allResults); $i ++){
                $dateArr[$i] = date('Y/m/d', strtotime($allResults[$i]['EnResult']['date'])); // => EX: 2015/03/26
        }
        $dateUniqueArr = array_merge(array_unique($dateArr));
        return  $dateUniqueArr;
    }

    /*
     * param $date(EX 2015/03/26), $type（問題の種類）
     * return EX
     *
     *        0 =>
     *          array (size=3)
     *              'id' => string '1' (length=1)
     *              'quest_id' => string '1' (length=1)
     *              'flag' => string '1' (length=1)
     *
     */
    public function getIdQuestIdFlagArr($date, $type){

        $option = array('conditions' => array('EnResult.date BETWEEN ? AND ?' => array($date." 00:00:00", $date." 23:59:59"), 'EnResult.type' => $type));
        $findResult = $this->find('all', $option);

        for($i = 0; $i < count($findResult); $i ++){
            $result[$i]['id'] = $res=$findResult[$i]['EnResult']['id'];
            $result[$i]['quest_id'] = $findResult[$i]['EnResult']['quest_id'];
            $result[$i]['flag'] = $findResult[$i]['EnResult']['flag'];
        }
        return $result;

    }



    /*
     * param  quest type
     * return date=>id
     *
     */
    public function getDate_QuestIdArr($type){
//        $dateArr = $this->getDateArr($type);
//        var_dump($dateArr);
//
//        $test = $this->getIdQuestIdFlagArr($dateArr[0]);
//        var_dump($test);


    }





    //  param  :: Quest type
    //  return :: EX array (size=126) 1427339834(unixTime) =>  0〜1(EnResult.flag / 100)
    public function getTryNumJson($type = null){
        $option = array('conditions' => array('EnResult.type' => $type),
            'field' => array('EnResult.date', 'EnResult.quest_id', 'EnResult.flag'));
        $allResults = $this->find('all', $option);

        $jsonResult = array();
        $valueArr = array();
        for($i = 0; $i < count($allResults); $i++){
            $valueArr[$i] = $allResults[$i]['EnResult']['flag'] / 100;
            $jsonResult += [strtotime($allResults[$i]['EnResult']['date']) => $valueArr[$i]];
        }
        return $jsonResult;
    }



}