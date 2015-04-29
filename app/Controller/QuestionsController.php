<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/23
 * Time: 午後6:09
 */

class QuestionsController extends AppController{


    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');


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

            if ($this->Question->validates(array('fieldList' => array('answer')))) { //validate通った時
                $postAnswer = $this->request->data['Question']['answer'];
                $judge = $this->Question->enBasic_checkWord($postAnswer);
                $score = $this->Question->getScore($id, $postAnswer);


                $now = date("Y/m/d H:i:s", time());
                $data = array(
                    'type' => 'basic',
                    'quest_id' => $id,
                    'date' => $now,
                    'score' => $score
                );


                if ($judge == true) {
                    $msg = '正解です！';
                }else{ //不正解の時
                    $incorrectMsg = $this->Question->getIncorrectMsg($id, $postAnswer);
                    $msg = '不正解('.$score.'点)です！'."<br>".$incorrectMsg;
                    $data += array('incorrect_words' => $this->Question->getIncorrectWords($id, $postAnswer));
                }

                $this->EnResult->save($data);


            }else{
                $msg = '';
            }

        }
        $this->set('msg', $msg);

        //view指定
        $this->ext = '.html';
        $this->render('en_basic');
    }

    public function enRearrange($id = null){

        $this->set('question', $this->Question->getJapanese($id));
        $this->set('answer', $this->Question->getEnglish($id));
        $this->set('word_count', $this->Question->getWordCount($id));
        $this->set('id', $id);
        $this->set('enHint', $this->Question->getEnHint($id, 1));
        $this->set('question', $this->Question->getJapanese($id));
        $this->set('answer', $this->Question->getEnglish($id));

        $enWordsArr = $this->Question->getEnWordsArr($id);

        $score = '';
        $isCorrect = '';
        $msg = '';

        if ($this->request->is('post')) { //解答する！のボタンを押した時
            $this->Question->set($this->request->data);
            if ($this->Question->validates(array('fieldList' => array('rearrangeAnswer')))) { //validate通った時

                $shuffledWordArr = $this->Session->read('shuffledWordArr');


                //postをチェック
                $postAnswer = $this->request->data['Question']['rearrangeAnswer'];


                $isCorrect = $this->Question->enRearrange_checkWord($postAnswer, $shuffledWordArr, $id);

                $this->set('shuffleWords', $shuffledWordArr);

                $score = $this->Question->getRearrangeScore($postAnswer, $shuffledWordArr, $id);


                //記録をINSERT
                $now = date("Y/m/d H:i:s", time());
                $data = array(
                    'type' => 'rearrange',
                    'quest_id' => $id,
                    'date' => $now,
                    'score' => $score,
                    'incorrect_words' => $this->Question->getRearrangeIncorrectWords($postAnswer, $shuffledWordArr, $id)
                );

                $this->EnResult->save($data);
                $score = '点数:'. $score. '点';


                //正解の時
                if($isCorrect){
                    $msg = '正解です！';
                }else{
                    $msg = '不正解です。';
                }

            }
        }else{
            shuffle($enWordsArr);
            $this->Session->write('shuffledWordArr', $enWordsArr);
            $this->set('shuffleWords', $enWordsArr);
        }



        $this->set('msg', $msg);
        $this->set('score', $score);







        // Viewへ
        $this->ext = '.html';
        $this->render('en_rearrange');
    }
}