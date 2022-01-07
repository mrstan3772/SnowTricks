$(document).ready(function () {
    let i = 2;
    $("body#home .load-more-btn").click(function () {
        $('.load-more-btn .spinner-grow').css('display', 'inline-block');

        $('body#home .load-more-btn').attr('disabled', 'disabled');

        $.ajax({
            type: "GET",
            url: "/tricks/page/" + i,
            success: function (content) {
                $('body#home .trick-grid').append(content);
                
            

                if (content.includes("Aucune autre figure trouvé")) {
                    $('body#home .load-more-btn').attr('disabled', 'disabled');
                }
            }
        });

        setTimeout(() => {
            console.log('Chargement des figures en cours...');

            $('.load-more-btn .spinner-grow').css('display', 'none');
        }, 3000);

        i++;
    });
});