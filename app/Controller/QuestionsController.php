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

    public function enBasic($id = null){


        $this->set('question', $this->Question->getJapanese($id));
        $this->set('answer', $this->Question->getEnglish($id));
        $this->set('word_count', $this->Question->getWordCount($id));
        $this->set('id', $id);
        $this->set('enHint', $this->Question->getEnHint($id, 0));


        $msg = null;
        //TODO modelに引っ越し
        if ($this->request->is('post')) { //解答する！のボタンを押した時
            $judge = null;
            $this->Question->set($this->request->data);
            $this->Question->validates();

            if ($this->request->data['Question']['answer']) { //postデータがあるとき
                $postAnswer = $this->request->data['Question']['answer'];
                $judge = $this->Question->enBasic_checkWord($postAnswer);
                $score = $this->Question->getScore($id, $postAnswer);

            }

            $now = date("Y/m/d H:i:s", time());
            $data = array(
                'type' => 'basic',
                'quest_id' => $id,
                'date' => $now,
            );



//            switch($score){
//                case 0:
//                    $data += array('score' => $score);
//                    $msg = $score.'点　一つも正解はありません！';
//                    break;
//                case 100:
//                    $data += array('score' => $score);
//                    $msg = '全問正解！　満点！';
//                    break;
//
//                default:
//                    $data += array('score' => $score);
//                    $msg = $score.'点です！';
//                    break;
//
//            }



            if ($judge == true) {
                $data += array('score' => 100);
                $msg = '正解です！';
            }else{ //不正解の時
                $data += array('score' => 0);
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