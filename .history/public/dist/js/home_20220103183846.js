$(document).ready(function () {
    let i = 2;
    $("body#home .load-more-btn").click(function () {
        $.ajax({
            type: "GET",
            url: "/tricks/page/" + i,
            success: function (text) {
                $('body#home .trick-grid').apped('<div>' + text + '</div>');
            }
        });
        i++;
    });
});