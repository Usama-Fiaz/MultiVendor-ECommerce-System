(function ($) {
    "use strict";

    $('.profile-aside-btn').on('click', function () {
        $('#shop-sidebar, .profile-aside-overlay').toggleClass('active');
    });
    $('.profile-aside-close-btn, .profile-aside-overlay').on('click', function () {
        $('#shop-sidebar, .profile-aside-overlay').removeClass('active');
    })

    $('.stopPropagation').on('click', function (e) {
        e.stopPropagation();
    })
})(jQuery);