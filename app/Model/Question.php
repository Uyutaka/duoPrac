<?php
/**
 * Created by PhpStorm.
 * User: Yutaka
 * Date: 15/03/23
 * Time: 午後6:08
 */


class Question extends AppModel{
    public $useTable = 'ex_sentences';



    //アソシエーション
    public $name = 'Question';
    public $hasOne = array('EnInfo' =>
        array('className' => 'EnInfo',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'id'
        )
    );


    public $validate = array(
        'answer' => array(
        'rule' => 'check',
        'message' => '単語数が異なります'
        )
        );



    public function checkWordCount($postAnswer,$id){
        $postWordCount = explode(" ", $postAnswer);
        if($postWordCount == $this->getWordCount($id)){
            return true;
        }else{
            return false;
        }



    }

    public function check($postAnswer, $id){
        $postWords = explode(" ", $postAnswer);


        var_dump('-------');
        var_dump($postWords);

        if(count($postWords) == $this->getWordCount($id)){
            echo 'success';
            return true;
        }else{
            echo 'error';
            return false;
        }



    }

    ////////////////////
    //EnInfosから情報取得
    ////////////////////
    public function getWordCount($id){
        $wordCountOption = array('fields' => array('EnInfo.word_count'),
            'conditions' =>array('EnInfo.id' => $id)
        );
        $word_count = $this->find('all', $wordCountOption);

        return (int)$word_count[0]['EnInfo']['word_count'];
    }

    public function getEnglish($id){
        $engOption = array('fields' => array('Question.english'),
            'conditions' => array('Question.id' => $id)
        );
        $english = $this->find('all', $engOption);
        return $english[0]['Question']['english'];
    }

    public function getJapanese($id){
        $jpOption = array('fields' => array('Question.id','Question.japanese'),
            'conditions' =>array('Question.id' => $id)
        );
        $jp = $this->find('all',$jpOption);
        return $jp[0]['Question']['japanese'];
    }

}