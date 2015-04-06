/**
 * Created by Yutaka on 15/03/26.
 */

jQuery(function ($) {

    $(".main").css("text-align", "center");
    $("#next").css("float", "right");
    $("#previous").css("float", "left");
    $("#judge").css("color", "red");
    $("h2").css("color", "blue");
    $("#QuestionEnBasicForm").css("margin", "0px auto");

});

$(document).ready(function(){
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
});