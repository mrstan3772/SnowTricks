$(document).ready(function () {
    let i = 2;
    $("body#home .load-more-btn").click(function () {
        $("body#home .trick-grid").load("/tricks/page/" + i);
        $.ajax({
            type: "GET",
            url: "/tricks/page/" + i,
            success: function (text) {
                $('body#home .trick-grid').append('<div>' + text + '</div>');
            }
        });
        i++;
    });
});