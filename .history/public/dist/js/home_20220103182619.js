$(document).ready(function(){
    const i = 1;
    $("button").click(function(){
        $("body#home .load-more-btn").load("/tricks/page/" + i);
    });
});