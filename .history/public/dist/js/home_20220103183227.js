$(document).ready(function(){
    let i = 2;
    $("body#home .load-more-btn").click(function(){
        $("body#home .trick-grid").load("/tricks/page/" + i);
        i++;
    });
});