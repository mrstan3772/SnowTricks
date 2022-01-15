$(window).on('load', function () {
    $(".show-more").on("click", function () {
        const $this = $(this);
        const content = $(document).find('.hidden-container');
        let buttonStatus = $this.attr('data-status');
        let buttonText;

        if (buttonStatus === "more") {
            buttonStatus = "less";
            buttonText = '<i class="fas fa-chevron-up"></i>';
            content.removeClass("hide-content").addClass("show-content");
        } else {
            buttonStatus = "more";
            buttonText = '<i class="fas fa-chevron-down"></i>';
            content.removeClass("show-content").addClass("hide-content");
        }

        $this.attr('data-status', buttonStatus);
        $this.children('i').replaceWith(buttonText);
    });
});

$(window).on('load', function () {
    let i = 2;
    $("body#trick_details .load-more-btn").on("click", function () {
        $('.load-more-btn .spinner-grow').css('display', 'inline-block');

        $('.load-more-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "GET",
            url: "/tricks/comment/" + trick_slug + "/thread/" + i,
            success: function (content) {
                $('.trick-comment').parent().append(content);

                $('.load-more-btn').removeAttr('disabled');

                $('.load-more-btn .spinner-grow').css('display', 'none');

                if (content.includes("Fin des commentaires !")) {
                    $('.load-more-btn').attr('disabled', 'disabled');
                }
            }
        });

        i++;
    });
})