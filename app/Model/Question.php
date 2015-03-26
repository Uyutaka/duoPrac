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

    public function val_wordContents($postAnswer){
        $postWords = explode(" ", $postAnswer['answer']);
        for($i = 0; $i = count($postWords); $i++){
//            if($postWords[$i])
        }
    }




    //正誤判定
    public function enBasic_checkWord($postAnswer){
//        var_dump($postAnswer);
        $id = $this->getUrlParam(3);
        $correct = $this->getEnglish($id);
        if($postAnswer == $correct){
            return true;
        }else{
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

    public function getEnHint($id){
        //TODO ex: "□□! □□."　のようなヒントを表示
        $enDivWords = explode(" ", $this->getEnglish($id));
        var_dump($enDivWords);


        for($i = 0; $i < count($enDivWords); $i++){
            $this->checkMark($enDivWords[$i], $i);
        }

    }
    public function checkMark($str, $num){
        //TODO 分割した単語から! ? '. "がどこにあるかを吐く
        $endMarks = array('?', '!', ".");
        $otherMarks = array('"'); //TODO 「'」もいずれ入れる。
        
        for($i = 0; $i < count($endMarks); $i++) {
            if(strpos($str, $endMarks[$i]) == true){
                var_dump($num);
                var_dump($endMarks[$i]);

            }
        }

    }
}