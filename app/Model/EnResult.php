<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/26
 * Time: 午前11:47
 */

class EnResult extends AppModel{
    public $useTable = 'en_results';


    public function getResult($type){
        $resultOption = array('field' => array('id'),
            'conditions' =>array('type' => $type)
        );
        $result = $this->find('all', $resultOption);

        var_dump($result);
//        return (int)$word_count[0]['EnInfo']['word_count'];
    }


}