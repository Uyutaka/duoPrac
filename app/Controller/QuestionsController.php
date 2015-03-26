<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/23
 * Time: 午後6:09
 */

class QuestionsController extends AppController{


    public $helpers = array('Html', 'Form', 'Session');

    //使うモデルを選択
    public $uses = array('Question', 'EnResult');

    public function index(){
        //view指定
        $this->ext = '.html';
        $this->render('index');
    }

    public function enBasic($id){

        $this->set('question', $this->Question->getJapanese($id));
        $this->set('answer', $this->Question->getEnglish($id));
        $this->set('word_count', $this->Question->getWordCount($id));
        $this->set('id', $id);

        $msg = null;
        //TODO modelに引っ越し
        if ($this->request->is('post')) {
            $judge = null;
            $this->Question->set($this->request->data);
            $this->Question->validates();

            if ($this->request->data['Question']['answer']) {
                $postAnswer = $this->request->data['Question']['answer'];
                $judge = $this->Question->enBasic_checkWord($postAnswer);
            }

            $now = date("Y/m/d H:i:s", time());
            $data = array(
                'type' => 'basic',
                'quest_id' => $id,
                'date' => $now,
            );

            if ($judge == true) {
//                echo 'true';
                $data += array('flag' => 1);
                $msg = '正解です！';
            }else{ //不正解の時
                $data += array('flag' => 0);
                $msg = '不正解です！';
            }

            $this->EnResult->save($data);

        }



        $this->set('msg', $msg);

         //view指定
        $this->ext = '.html';
        $this->render('en_basic');


    }
}