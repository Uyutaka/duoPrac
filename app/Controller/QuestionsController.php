<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/23
 * Time: 午後6:09
 */

class QuestionsController extends AppController{
    public function index(){
        //view指定
        $this->ext = '.html';
        $this->render('index');
    }

    public function enBasic($id){


        $ansOption = array('fields' => array('Question.id','Question.japanese'),
                        'conditions' =>array('Question.id' => $id)
            );
        $question = $this->Question->find('all',$ansOption);
        $this->set('question', $question); //$ex_sentences::view用変数, 取り出し操作


        $ansOption = array('fields' => array('Question.id','Question.english'),
                           'conditions' => array('Question.id' => $id)
            );
        $answer = $this->Question->find('all', $ansOption);

        $this->set('answer', $answer);



        $wordCountOption = array('fields' => array('EnInfo.id','EnInfo.word_count'),
            'conditions' =>array('EnInfo.id' => $id)
            );
        $word_count = $this->Question->find('all', $wordCountOption);
        $this->set('word_count', $word_count);




        //view指定
        $this->ext = '.html';
        $this->render('en_basic');
    }
}