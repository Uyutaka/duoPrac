<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/23
 * Time: 午後6:09
 */

class QuestionsController extends AppController{


    public $helpers = array('Html', 'Form', 'Session');


    public function index(){
        //view指定
        $this->ext = '.html';
        $this->render('index');
    }

    public function enBasic($id){



        $this->set('question', $this->Question->getJapanese($id));
        $this->set('answer', $this->Question->getEnglish($id));
        $this->set('word_count', $this->Question->getWordCount($id));



        if ($this->request->is('post')) {
            $this->Question->set($this->request->data);
            $this->Question->validates();
        }


        $postAnswer = $this->request->data['Question']['answer'];
        if($postAnswer == true){
            $this->Question->check($postAnswer, $id);
        }




        //view指定
        $this->ext = '.html';
        $this->render('en_basic');
    }
}