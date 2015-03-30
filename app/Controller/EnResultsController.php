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

        //view指定
        $this->ext = '.html';
        $this->render('index');


    }

    public function basic(){

        $this->layout = ""; //defaultのスタイルを削除

        $this->EnResult->getResult('basic');
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