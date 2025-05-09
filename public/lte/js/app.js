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

window.addEventListener('load', function() {
    const image = document.querySelector('img');
    if (image) {
        image.style.filter = 'blur(0)';
    }
});