# duoPrac

## Table


CREATE TABLE ex_sentences (id INT NOT NULL PRIMARY KEY, japanese CHAR(255) NOT NULL, english CHAR(255) NOT NULL, section INT NULL);

CREATE TABLE en_infos (id INT NOT NULL PRIMARY KEY, word_count INT NOT NULL);

CREATE TABLE en_results (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, type CHAR(255) NOT NULL, quest_id INT NOT NULL, date DATETIME NOT NULL, flag INT NOT NULL);

## Screen shot

![基本問題2](/ScreenShot/:Questions:enBasic:2.png)
