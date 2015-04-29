/**
 * Created by Yutaka on 15/04/28.
 */

jQuery(function ($) {

$(".main").css("text-align", "center");
    $(".main .select").css("color", "green");
    $(".main .select").css("border", "solid 1px #0000ff");
});


$(document).ready(function(){

    $(".hintContent").hide();
    var flg = "close";
    $(".showHintBtn").click(function(){
        $(".hintContent").slideToggle();
        if(flg == "close"){
            $(this).text("HINT閉じる");
            flg = "open";
        }else{
            $(this).text("HINT表示");
            flg = "close";
        }
    });



    $(".answerContent").hide();
    var flg = "close";
    $(".showAnswerBtn").click(function(){
        $(".answerContent").slideToggle();
        if(flg == "close"){
            $(this).text("答えを閉じる");
            flg = "open";
        }else{
            $(this).text("答えを表示");
            flg = "close";
        }
    });

    $("#listenBtn").click(function(){
        var url = 'http://tts-api.com/tts.mp3?q=';
        url += encodeURI($(".answerContent").text());
        console.log(url);
        new Audio(url).play();

    });

});