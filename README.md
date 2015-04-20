# duoPrac

## Table


CREATE TABLE ex_sentences (id INT NOT NULL PRIMARY KEY, japanese CHAR(255) NOT NULL, english CHAR(255) NOT NULL, section INT NULL);

CREATE TABLE en_infos (id INT NOT NULL PRIMARY KEY, word_count INT NOT NULL);

CREATE TABLE en_results (   id INT NOT NULL PRIMARY KEY AUTO_INCREMENT  , type CHAR(255) NOT NULL, quest_id INT NOT NULL, date DATETIME  NOT NULL, score INT NOT NULL, incorrect_words char(255));

## Screen shot


###TOP(duoPrac/)
![基本問題2](/Screenshot/TOP.png)

###基本問題1(/Questions/enBasic/1)
![基本問題2](/Screenshot/Question1.png)

###例文一覧(/ExSentences)
![例文一覧](/Screenshot/ExSentences.png)

###基本問題の結果ページ(/EnResults/basic)
![基本問題の結果ページ](/Screenshot/EnResultBasic.png)

