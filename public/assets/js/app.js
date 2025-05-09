$(document).ready(function () {

    setTimeout(function () {
        $(".alert").alert('close').fadeOut();
    }, 10000);

    if ($('#clipboard').length) {
        $('#clipboard').on('click', function (e) {
            let link = $('#link').text();
            copyContent(link);
            swal('', 'Link (' + link + ') is copied to clipboard.', 'success');
        });
    }

    if ($('.three-dots').length) {
        $('.three-dots').on('click', function () {
            $('.dataId').val($(this).attr('data-id'));
        });
    }

    if ($('.cUrlCode').length) {
        $('.cUrlCode').on('click', function () {
            let code = $(this).attr('data-description');
            copyContent(code);
            swal('', 'URL (' + code + ') is copied to clipboard.', 'success');
        });
    }

    let modal = $('.modal');
    modal.on('shown.bs.modal', function () {
        $(this).find('[autofocus]').focus();
    });

    if($('.rmModal').length) {
        $('.rmModal').on('click', function () {
            $('.modalToRemoveAfterClick').modal('hide');
    
            setTimeout(function () {
                $('input').val('');
                $('input[type=radio]').val('');
            }, 2000);
        });
    }

    if($('.checkboxGroup').length) {
        $('input[type="checkbox"]').on('change', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
         });
    }
});

function isLocalStorageAvailable() { return typeof (Storage) !== "undefined" }

async function copyContent(text) {
    try {
        if (isLocalStorageAvailable) {
            await navigator.clipboard.writeText(text);
        }
    } catch (err) {
        swal('', 'clipboard is not available on your Browser.', 'warning');
    }
}

window.addEventListener('load', function() {
    const image = document.querySelector('img');
    if (image) {
        image.style.filter = 'blur(0)';
    }
});
