Dropzone.autoDiscover = false;

$(window).on('load', function () {
    $(function () {
        //Dropzone class
        $('#trickForm').dropzone({
            // The configuration
            autoProcessQueue: false,
            uploadMultiple: true,
            // clickable: '#dropzone-previews',
            maxFilesize: 250, // MB
            parallelUploads: 10,
            maxFiles: 10,
            acceptedFiles: "image/png,image/webp,image/gif,image/jpeg,video/webm',video/3gpp,video/3gpp2,video/mpeg,video/ogg,video/mp4",
            addRemoveLinks: true,
            paramName: 'reference',
            // previewsContainer: '#dropzone-previews',

            // The setting up of the dropzone
            init: function () {
                var myDropZone = this;

                // First change the button to actually tell Dropzone to process the queue.
                this.element.querySelector("button[type=submit]").addEventListener("click", function (e) {
                    const trick_name = document.querySelector('#trick_trick_name').value
                    const trick_description = document.querySelector('#trick_trick_description').value
                    const trick_group_id = document.querySelector('#trick_trick_group_id').value
                    const trick_creation_date = document.querySelector('#trick_trick_creation_date').value

                    const form_errors = []

                    if (!validator.isLength(trick_name, { min: 3, max: 255 })) form_errors.push('Le nom de la figure doit correspondre à une  taille compris entre 3 et 255 caratcères !')
                    if (!validator.isLength(trick_description, { min: 25, max: 10000 })) form_errors.push('Le description de la figure doit correspondre à une  taille compris entre 25 et 10000 caratcères !')
                    if (!['41', '42', '43', '44', '45'].includes(trick_group_id)) form_errors.push('Groupe invalide !')

                    
                    if (form_errors.length !== 0) {
                        e.preventDefault();
                        e.stopPropagation();

                        $('#trickForm').css({
                            "border-color": "red",
                        });

                        $('.errors-trick-form > li').replaceWith('');

                        $('.errors-container').removeClass('d-none').addClass('d-block');

                        $(form_errors).each(function (i, error) {
                            $('.errors-trick-form').append('<li>' + error + '</li>');
                        })
                    } else {
                        e.preventDefault();
                        e.stopPropagation();
                        myDropZone.processQueue();
                    }
                });

                this.on("addedfile", function (file) {
                    if (this.files.length <= 10) {
                        $('#trickForm').css({
                            "border-color": "olive",
                        });
                    }
                });

                this.on("successmultiple", function (file, response) {
                    console.log(response)

                    $("#trickForm").slideUp(1000, function() {
                        $(this).remove();

                        $('.success-container').removeClass('d-none').addClass('d-block');

                        $('.success-container').append(`La figure ${response} a été crée avec succès !`);

                        setTimeout(function() {
                            window.location.href = '/admin/trick/'
                        }, 5000)
                    });

                });

                this.on('sendingmultiple', function (file, xhr, formData) {

                });

                this.on("errormultiple", function (files, response) {
                    console.log(response)
                });
            },

            dictDefaultMessage: "Déposer l'image ici pour la transférer",
            dictRemoveFile: "Supprimer le fichier",
            dictCancelUpload: "Annuler",
            dictFileTooBig: "Le fichier est trop volomineux",
            dictInvalidFileType: "Ce type de fichier n'est pas autorisé",
            dictMaxFilesExceeded: "La limite du nombre maximum de fichiers à uploader a été atteint"
        })
    })
})