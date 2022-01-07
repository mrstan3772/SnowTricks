$(document).ready(function () {
    let i = 2;
    $("body#home .load-more-btn").click(function () {
        $.ajax({
            type: "GET",
            url: "/tricks/page/" + i,
            success: function (content) {
                $('body#home .trick-grid').append(content);

                if(content.includes = "Plus de figure") {

                }
            }
        });
        i++;
    });
});