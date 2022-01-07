$(document).ready(function(){
    let i = 2;
    $("body#home .load-more-btn").click(function(){
        $("").load("/tricks/page/" + i);
        i++;
    });
});