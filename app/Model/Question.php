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
            'wordCountRule' => array(
                'rule' => array('val_postWord',1),
                'message' => '単語数が異なるります。'
            ),
            'contailsRule' => array(
                'rule' => array('val_postWord',2),
                'message' => '半角英数字で入力してください。'
            )
        )
    );


    public function val_postWord($postAnswer, $check){
        $postWords = explode(" ", $postAnswer['answer']);

        $id = $this->getUrlParam(3); //urlの三番目の値　改良したい。。

        switch($check){

            case 1: //単語数の判定
                if (count($postWords) == $this->getWordCount($id)) {
                    return true;
                    break;
                } else {
                    return false;
                    break;
                }

            case 2: // TODO 半角英数字の判定
                return true;
                break;
        }

    }


    //正誤判定
    //param $postAnswer postした文字列
    //return 正解かどうか
    public function enBasic_checkWord($postAnswer){
        $id = $this->getUrlParam(3);
        $correct = $this->getEnglish($id);
        if($postAnswer == $correct){
            return true;
        }else{
            return false;
        }
    }
    //スコアを判定
    //param $id=>問題のID $postAnswer=>postデータ
    //return $score　
    //正解の例文の単語（. ! ?を含む）と postデータを比べて同じものをカウント
    //score = 正解単語数/総単語数 * 100
    public function getScore($id, $postAnswer){
        $postEnWords = explode(" ", $postAnswer);
        $correctEnWords = explode(" ", $this->getEnglish($id));



        $correctCount = 0;
        if(count($correctEnWords) >= count($postEnWords)){
            for($i = 0; $i < count($postEnWords); $i ++){
                if($correctEnWords[$i] == $postEnWords[$i]){
                    $correctCount += 1;
                }
            }
            $score = $correctCount / $this->getWordCount($id) * 100;
        }else{
            $score = 0;
        }
        return $score;
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

    public function getUrlParam($num){
        $url = Router::url();
        $params = explode('/', $url);
        if($num) {
            return $params[$num];
        }else{
            return $params;
        }
    }

    /////////////////////
    ////HINT関連/////////
    ///////////////////

    public function getEnHint($id, $option){
        $enDivWords = explode(" ", $this->getEnglish($id));

        switch ($option){
            case 0:
                for($i = 0; $i < count($enDivWords); $i++){
                    $enHintArr[$i] = $this->createEnBasicHint($enDivWords[$i]);
                }
                $enHint = implode(' ', $enHintArr);
                break;

        }

        return $enHint;
    }


    //param $文字列
    //return 半角は□に　記号はそのままの文字列で返す ex: "□□! □□."　のようなヒントを表示
    public function createEnBasicHint($str){
        $strArr = str_split($str); //文字列を配列に
        $hint = '';

        for($i = 0; $i < count($strArr); $i++){
            if(preg_match("/^[a-zA-Z0-9]+$/", $strArr[$i])){
                $hint .= '□';
            }else{
                $hint .= $strArr[$i];
            }
        }
        return $hint;
    }
}