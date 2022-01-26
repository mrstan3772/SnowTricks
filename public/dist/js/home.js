$(window).on('load', function () {
    let i = 2;
    $("body#home .load-more-btn").click(function () {
        $('.load-more-btn .spinner-grow').css('display', 'inline-block');

        $('body#home .load-more-btn').attr('disabled', 'disabled');

        $('#toTop').fadeOut();

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
        scrollBottom = $(document).height() - $(window).height() - $(window).scrollTop();
        if (scrollBottom) {
            $('#toTop').fadeOut();
        } else {
            $('#toTop').fadeIn();
        }

        if(!$(this).scrollTop()) {
            $('#toBottom').fadeIn();
        } else {
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

    $("#toBottom").on('click', function () {
        $('#toBottom').attr('disabled', 'disabled');
        const trick_bloc_offset_top = $('.trick-grid').offset().top;
        $("html, body").animate({ scrollTop: trick_bloc_offset_top }, 1000, function () {
            $('#toBottom').removeAttr('disabled');
        });
    });
});

// Handling the modal confirmation message.
$(document).on('submit', 'form[data-confirmation]', function (event) {
    var $form = $(this),
        $confirm = $('#confirmationModal');

    if ($confirm.data('result') !== 'yes') {
        //cancel submit event
        event.preventDefault();

        $confirm
            .off('click', '#btnYes')
            .on('click', '#btnYes', function () {
                $confirm.data('result', 'yes');
                $form.find('input[type="submit"]').attr('disabled', 'disabled');
                $form.submit();
            })
            .modal('show');
    }
});