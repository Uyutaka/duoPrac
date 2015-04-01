<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/26
 * Time: 午後1:29
 */

class EnResultsController extends AppController{
    public $scaffold;

    public function index(){
        $this->ext = '.html';
        $this->render('index');
    }

    public function basic($date = null){
        $this->autoLayout = false;

        $dateArr = $this->EnResult->getDateArr('basic');
//        var_dump($dateArr);
        $this->set('dateArr', $dateArr);

        for($i = 0; $i < count($dateArr); $i ++) {
            $info[$i] = $this->EnResult->getIdQuestIdFlagArr($dateArr[$i], 'basic');
        }
        $this->set('info', $info);
//        var_dump($info);





            $this->ext = '.html';
            $this->render('basic');





    }


    //cal-heatmap用のJson出力
    //param  \ $type => 問題のタイプ
    //return \ Json date: unixtimestamp, value: ??}
    public function tryNumJson($type = null){
        $jsonResult = $this->EnResult->getTryNumJson($type);

        //参考 [CakePHP2] json形式のデータを手軽に出力する
        $this->viewClass = 'Json';
        $this->set(compact('jsonResult'));
        $this->set('_serialize', 'jsonResult');
    }
}