$(window).on('load', function () {
    let i = 2;
    $("body#home .load-more-btn").click(function () {
        $('.load-more-btn .spinner-grow').css('display', 'inline-block');

        $('body#home .load-more-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "GET",
            url: "/tricks/page/" + i,
            success: function (content) {
                $('body#home .trick-grid').append(content);

                $('body#home .load-more-btn').removeAttr('disabled');

                $('.load-more-btn .spinner-grow').css('display', 'none');

                if (content.includes("Aucune autre figure trouvé")) {
                    $('body#home .load-more-btn').attr('disabled', 'disabled');
                }
            }
        });

        i++;
    });

    $(window).on('scroll', function () {
        if ($(this).scrollTop()) {
            $('#toTop').fadeIn();
            $('#toBottom').fadeIn();
        } else {
            $('#toTop').fadeOut();
            $('#toBottom').fadeOut();
        }
    });

    $("#toTop").on('click', function () {
        $('#toTop').attr('disabled', 'disabled');
        const trick_bloc_offset_top = $('.trick-grid').offset().top;
        $("html, body").animate({ scrollTop: trick_bloc_offset_top }, 1000, function () {
            $('#toTop').removeAttr('disabled');
        });
    });
});