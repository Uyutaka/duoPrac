<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/23
 * Time: 午後4:30
 */

class ExSentencesController extends AppController {


//    public $helpers = array('Html', 'Form');


    public function index(){



        $this->set('ex_sentences', $this->ExSentence->find('all')); //$ex_sentences::view用変数, 取り出し操作

        //view指定
        $this->ext = '.html';
        $this->render('index');

    }
    
}