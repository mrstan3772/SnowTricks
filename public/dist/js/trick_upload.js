Dropzone.autoDiscover = false;

$(window).on('load', function () {
    var referenceList = new ReferenceList($('.js-reference-list'));

    initializeDropzone(referenceList);

    $('.dz-button').text('Déposez vos fichiers ici !')
})

class ReferenceList {
    constructor($element) {
        this.$element = $element;
        this.references = [];
        this.render();

        this.$element.on('click', '.js-reference-delete', (event) => {
            this.handleReferenceDelete(event);
        });

        this.$element.on('blur', '.js-edit-filename', (event) => {
            this.handleReferenceEditFilename(event);
        });

        $.ajax({
            url: this.$element.data('url')
        }).then(data => {
            this.references = data;
            this.render();
        })
    }

    truncateString(str, max = 10) {
        if (str.length > max) {
            return str.slice(0, max) + "...";
        } else {
            return str;
        }
    }

    addReference(reference) {
        this.references.push(reference);
        this.render();
    }

    handleReferenceDelete(event) {
        const $li = $(event.currentTarget).closest('.list-group-item');
        const id = $li.data('id');
        $li.addClass('disabled');

        $.ajax({
            url: '/admin/trick/references/' + id,
            method: 'DELETE'
        }).then(() => {
            this.references = this.references.filter(reference => {
                return reference.id !== id;
            });
            this.render();
        });
    }

    handleReferenceEditFilename(event) {
        const $li = $(event.currentTarget).closest('.list-group-item');
        const id = $li.data('id');
        const reference = this.references.find(reference => {
            return reference.id === id;
        });
        reference.ta_original_filename = $(event.currentTarget).val();
        $.ajax({
            url: '/admin/trick/references/'+id,
            method: 'PUT',
            data: JSON.stringify(reference)
        });
    }

    render() {
        const itemsHtml = this.references.map(reference => {
            const ta_original_filename = this.truncateString(reference.ta_original_filename, 12)
            let figure

            if (reference.ta_type === 'image') {
                figure = `<img src="${path}/${reference.filePath}" alt="trick image">`
            } else {
                figure = `
                            <video playsinline controls>
                                <source src="${path}/${reference.filePath}" type="${reference.ta_mime_type}" />
                            </video>
                    `
            }

            return `
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-column" data-id="${reference.id}">
                        <input type="text" value="${reference.ta_original_filename}" class="js-edit-filename mb-2" style="width: auto; max-width: 150px">
                        ${figure}
                        <h4> ${ta_original_filename} </h4>
                        <span>
                            <a href="/admin/trick/references/${reference.id}/download"><span class="fa fa-download"></span></a>
                             <button class="js-reference-delete btn btn-link"><span class="fa fa-trash"></span></button>
                        </span>
                    </li>
                `
        });
        this.$element.html(itemsHtml.join(''));
    }
}

function initializeDropzone(referenceList) {
    var formElement = document.querySelector('.js-reference-dropzone');
    if (!formElement) {
        return;
    }
    var dropzone = new Dropzone(formElement, {
        paramName: 'reference',
        init: function () {
            this.on('error', function (file, data) {
                if (data.detail) {
                    this.emit('error', file, data.detail);
                }
            });

            this.on('success', function (file, data) {
                referenceList.addReference(data);
            });
        }
    });
}