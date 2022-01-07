$(document).ready(function () {
    let i = 2;
    $("body#home .load-more-btn").click(function () {
        $('.load-more-btn .spinner-grow').show();

        $.ajax({
            type: "GET",
            url: "/tricks/page/" + i,
            success: function (content) {
                $('body#home .load-more-btn').attr('disabled', 'disabled');

                $('body#home .trick-grid').append(content);
                
                $('body#home .load-more-btn').removeAttr(attributeName);;

                if (content.includes("Aucune autre figure trouvé")) {
                    $('body#home .load-more-btn').attr('disabled', 'disabled');
                }

                $('.load-more-btn .spinner-grow').hide();
            }
        });
        i++;
    });
});