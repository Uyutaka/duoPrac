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

var answer = '';
function displayAnswer(answer){
    //var answer = 'test';
    alert(answer);
}