$(document).ready(function () {

    setTimeout(function () {
        $(".alert").alert('close').fadeOut();
    }, 6000);

    $('#clipboard').on('click', function (e) {
        let link = $('#link').text();
        copyContent(link);
        swal('', 'Link (' + link + ') is copied to cliepboard.', 'success');
    });
});

async function copyContent(text) {
    try {
        await navigator.clipboard.writeText(text);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}
