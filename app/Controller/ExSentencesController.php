<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/23
 * Time: 午後4:30
 */

class ExSentencesController extends AppController {

    public function index(){
        $this->set('ex_sentences', $this->ExSentence->find('all')); //$ex_sentences::view用変数, 取り出し操作

        //view指定
        $this->ext = '.html';
        $this->render('index');

    }

    public function json(){
        $result = $this->ExSentence->find('all');
        $jsonResult = json_encode($result);

        //参考 [CakePHP2] json形式のデータを手軽に出力する
        $this->viewClass = 'Json';
        $this->set(compact('jsonResult'));
        $this->set('_serialize', 'jsonResult');
    }

}