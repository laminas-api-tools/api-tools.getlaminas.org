(function ($) {
    $('.manual-toc>ul').addClass('collapse');
    $('.manual-toc li.current').closest('ul.collapse').removeClass('collapse');
    $('.manual-toc h2').on('click', function (e) {
        $(e.target).next('ul').toggleClass('collapse');
    });
})($);
