$(document).ready(function () {

    // setTimeout(function () {
    //     $(".alert").alert('close').fadeOut();
    // }, 6000);

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

});

function isLocalStorageAvailable() { return typeof (Storage) !== "undefined" }

async function copyContent(text) {
    try {
        if (isLocalStorageAvailable) {
            await navigator.clipboard.writeText(text);
        }
    } catch (err) {
        swal('', 'clipboard is not avialble on your Browser.', 'warning');
    }
}
