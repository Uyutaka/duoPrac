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
        ),
        'rearrangeAnswer' => array(
            'wordCountRule' => array(
                'rule' => array('val_rearrange'),
                'message' => '重複なく全ての数字を使って答えてください。'
            )
        )
    );


    //独自のval関数
    //引数はpostされた文字列
    public function val_postWord($post){
        $postWords = explode(" ", $post['answer']);

        $id = $this->getUrlParam(3); //urlの三番目の値　改良したい。。

        if (count($postWords) <= $this->getWordCount($id)) {
            return true;
        } else {
            return false;
        }
    }

    public function val_rearrange($post){
        $id = $this->getUrlParam(3); //urlの三番目の値　改良したい。。

        $postArr = explode(' ', $post['rearrangeAnswer']);

        $isPostCount = false;
        //文字数の判定
        if(count($postArr) == count($this->getEnWordsArr($id))){
            $isPostCount = true;
        }else{
            return false;
        }

        //postされた文字の数が選択肢と一致した場合
        if($isPostCount){
            $postStr = implode('', $postArr);
            //連結した文字列が数字かどうか
            if(preg_match('/^[0-9]+$/', $postStr)){
                return true;
            }
            return false;
        }
    }




    //正誤判定(validate後)
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

    public function enRearrange_checkWord($postAnswer, $shuffledArr, $id){

        $postedWordArr = $this->getPostedWord_enRearrange($postAnswer, $shuffledArr, $id);


        $isCorrect = false;

        if($postedWordArr){
            $correctWordsArr = $this->getEnWordsArr($id);

            $correctCount = 0;
            for($i = 0; $i < count($correctWordsArr); $i++){
                if($postedWordArr[$i] == $correctWordsArr[$i]){
                    $correctCount += 1;
                }
            }
            if($correctCount == count($correctWordsArr)){
                $isCorrect = true;
            }else{
                $isCorrect = false;
            }
        }else{
            $isCorrect = false;
        }
        return $isCorrect;
    }


    //postされた選択肢を単語に変換する。
    //選択肢以外の数字がポストされた場合はfalse
    public function getPostedWord_enRearrange($postAnswer, $shuffledArr, $id){
        $postArr = explode(' ', $postAnswer);

        //それぞれの選択肢を単語に置き換え
        $postedWordArr = array();
        $isPostedWords = true; //選択肢以外の数字を代入した場合false
        for($i = 0; $i < count($shuffledArr); $i++){
            if($shuffledArr[$postArr[$i]]){
                $postedWordArr[$i] = $shuffledArr[$postArr[$i]];
            }else{
                $isPostedWords = false;
            }
        }

        if($isPostedWords){
            return $postedWordArr;
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

    public function getRearrangeScore($postAnswer, $shuffledArr, $id){

        $postedWordArr = $this->getPostedWord_enRearrange($postAnswer, $shuffledArr, $id);

        $correctCount = 0;

        $correctWordsArr = $this->getEnWordsArr($id);
        $totalCount = count($correctWordsArr);

        if($postedWordArr){

            for($i = 0; $i < count($correctWordsArr); $i++){
                if($postedWordArr[$i] == $correctWordsArr[$i]){
                    $correctCount += 1;
                }
            }

        }
        $score = ($correctCount / $totalCount) * 100;

        return $score;
    }

    public function getRearrangeIncorrectWords($postAnswer, $shuffledArr, $id){
        $postedWordArr = $this->getPostedWord_enRearrange($postAnswer, $shuffledArr, $id);
        $correctWordsArr = $this->getEnWordsArr($id);

        $incorrectWordArr = array();
        if($postedWordArr){

            for($i = 0; $i < count($correctWordsArr); $i++){
                if($postedWordArr[$i] == $correctWordsArr[$i]){
                }else{
                    $incorrectWordArr[$i] = $correctWordsArr[$i];
                }
            }
        }

        return implode(",", $incorrectWordArr);


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
            case 1: //enRearrange

                for($i = 0; $i < count($enDivWords); $i++) {
                    $enHintArr[$i] = $this->createEnRearrangeHint($enDivWords[$i]);
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
                $hint .= '*';
            }else{
                $hint .= $strArr[$i];
            }
        }
        return $hint;
    }

    //78 　　8☓["Natto"]
    public function createEnRearrangeHint($str){
        $hint = $this->createEnBasicHint($str);
        $hintArr = str_split($hint);

        if(count(array_unique($hintArr)) == 1){
            //記号入っていない
            $hint = '*';
        }
        elseif(strpos($hint , "*'*") == true){
            //省略形を*に変換
            $hint = str_replace("*'*", '*', $hint);
        }else{
            //重複している*を一つにまとめる(例：　'**? => '*?)
            $doubleArr = array();
            for($i = 0; $i < count($hintArr); $i++){
                if($hintArr[$i] == '*'){
                    array_push($doubleArr, $i);
                }
            }
            for($i = 1; $i < count($doubleArr); $i++) {
                unset($hintArr[$doubleArr[$i]]);
            }
            $hint = implode('', $hintArr);
        }
        return $hint;
    }
}