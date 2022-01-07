$(document).ready(function(){
    let i = 2;
    $("button").click(function(){
        $("body#home .load-more-btn").load("/tricks/page/" + i);
        i++;
    });
});