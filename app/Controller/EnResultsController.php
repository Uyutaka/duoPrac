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

        $dateArr = $this->EnResult->getDateArr($this->action);
        $this->set('dateArr', $dateArr);

        for($i = 0; $i < count($dateArr); $i ++) {
            $info[$i] = $this->EnResult->getIdQuestIdScoreArr($dateArr[$i], $this->action);
        }
        $this->set('info', $info);

        $this->ext = '.html';
        $this->render('basic');
    }



    public function rearrange($date = null){
        $dateArr = $this->EnResult->getDateArr($this->action);
        $this->set('dateArr', $dateArr);
        for($i = 0; $i < count($dateArr); $i ++) {
            $info[$i] = $this->EnResult->getIdQuestIdScoreArr($dateArr[$i], $this->action);
        }
        $this->set('info', $info);


        $this->ext = '.html';
        $this->render('rearrange');
    }


    //////////
    //JSON関連
    //////////

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

    public function trySumJson($type = null){
        $jsonResult = $this->EnResult->getTrySumJson($type);

        //参考 [CakePHP2] json形式のデータを手軽に出力する
        $this->viewClass = 'Json';
        $this->set(compact('jsonResult'));
        $this->set('_serialize', 'jsonResult');
    }

}