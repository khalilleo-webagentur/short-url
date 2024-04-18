$(document).ready(function () {

    setTimeout(function () {
        $(".alert").alert('close').fadeOut();
    }, 6000);

    // Delete button on URLS
    let linkDataId = $('.linkDataId');

    if (linkDataId.length) {
        linkDataId.on('click', function (e) {
            e.preventDefault();
            $('.delLinkId').val($(this).attr('data-id'));
        });
    }
});