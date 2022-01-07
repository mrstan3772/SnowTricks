$(document).ready(function () {
    let i = 2;
    $("body#home .load-more-btn").click(function () {
        $.ajax({
            type: "GET",
            url: "/tricks/page/" + i,
            success: function (content) {
                $('body#home .trick-grid').append(content);

                if (content.includes = "Aucune autre figure trouvé") {
                    $('body#home .load-more-btn').attr('disabled', 'disabled');
                }
            }
        });
        i++;
    });
});