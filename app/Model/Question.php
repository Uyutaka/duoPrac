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
                'rule' => array('val_postWord'),
                'message' => '単語数が多いです。'
            )
        )
    );


    public function val_postWord($postAnswer){
        $postWords = explode(" ", $postAnswer['answer']);

        $id = $this->getUrlParam(3); //urlの三番目の値　改良したい。。


                if (count($postWords) <= $this->getWordCount($id)) {
                    return true;

                } else {
                    return false;

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
        if(count($correctEnWords) >= count($postEnWords)){ //POSTの単語数が答え以下の時
            for($i = 0; $i < count($postEnWords); $i ++){
                if($correctEnWords[$i] == $postEnWords[$i]){
                    $correctCount += 1;
                }
            }
            $score = $correctCount / $this->getWordCount($id) * 100;
        }else{ //POSTの単語数が答え以上の時
            $score = 0;
        }
        return $score;
    }


    public function getIncorrectMsg($id, $postAnswer){
        $postEnWords = explode(" ", $postAnswer);
        $correctEnWords = explode(" ", $this->getEnglish($id));
        $incorrectMsgArr = explode(" ", $this->getEnHint($id, 0));
        if(count($correctEnWords) >= count($postEnWords)){ //POSTの単語数が答え以下の時
            for($i = 0; $i < count($postEnWords); $i ++){
                if($correctEnWords[$i] == $postEnWords[$i]){
                    $incorrectMsgArr[$i] = $correctEnWords[$i];
                }
            }
        }
        return implode(" ", $incorrectMsgArr);
    }

    public function getIncorrectWords($id, $postAnswer){


        $postEnWords = explode(" ", $postAnswer);
        $correctEnWords = explode(" ", $this->getEnglish($id));



        $incorrectWordsArr = array();
        if(count($correctEnWords) >= count($postEnWords)){ //POSTの単語数が答え以下の時
            for($i = 0; $i < count($correctEnWords); $i ++){
                if($postEnWords[$i]  !== $correctEnWords[$i]){
                    array_push($incorrectWordsArr, $correctEnWords[$i]);
                }
            }

        }else{ //POSTの単語数が答え以上の時
            $incorrectWordsArr = $correctEnWords;
        }
        return implode(",", $incorrectWordsArr);
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



    //英文を単語に分け、配列で返す。
    //記号(.' " ,など)は消す
    //
    public function getEnWordsArr($id){
        $divEnWords = explode(' ', $this->getEnglish($id));

        // 分けた単語に記号含まれていたら要素番号を$containSignArrに代入
        $containSignArr = array();//省略も含む
        for($i = 0; $i < count($divEnWords); $i++){
            if(preg_match('/^[a-zA-Z0-9]+$/', $divEnWords[$i])){
            }else{
                array_push($containSignArr, $i);
            }
        }

        for($i = 0; $i < count($containSignArr); $i++){
            $startStr = $divEnWords[$containSignArr[$i]][0];
            $lastStr = $divEnWords[$containSignArr[$i]][strlen($divEnWords[$containSignArr[$i]]) - 1];

            //単語の最初に記号あった場合なくなるまで削除
            while(1) {
                if (!preg_match('/^[a-zA-Z0-9]+$/', $startStr)) {
                    $divEnWords[$containSignArr[$i]] = str_replace($startStr, '', $divEnWords[$containSignArr[$i]]);
                    $startStr = $divEnWords[$containSignArr[$i]][0];
                } else {
                    break;
                }
            }

            //単語の最後に記号があった場合なくなるまで削除
            while(1) {
                if (!preg_match('/^[a-zA-Z0-9]+$/', $lastStr)) {
                    $divEnWords[$containSignArr[$i]] = str_replace($lastStr, '', $divEnWords[$containSignArr[$i]]);
                    $lastStr = $divEnWords[$containSignArr[$i]][strlen($divEnWords[$containSignArr[$i]]) - 1];
                } else {
                    break;
                }
            }
        }
        return $divEnWords;
    }





    /////////////////////
    ////HINT関連/////////
    ///////////////////

    public function getEnHint($id, $option){
        $enDivWords = explode(" ", $this->getEnglish($id));
        $enHint = array();

        switch ($option){
            case 0: //enBasic
                for($i = 0; $i < count($enDivWords); $i++){
                    $enHintArr[$i] = $this->createEnBasicHint($enDivWords[$i]);
                }
                $enHint = implode(' ', $enHintArr);
                break;
            case 1:
                var_dump($enDivWords);

                for($i = 0; $i < count($enDivWords); $i++) {
                    $this->createEnRearrangeHint($enDivWords[$i]);
                }
                $this->createEnRearrangeHint('!@#abcdefg');
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
    public function createEnRearrangeHint($str){
        $hint = $this->createEnBasicHint($str);
        
        return $hint;
    }
}