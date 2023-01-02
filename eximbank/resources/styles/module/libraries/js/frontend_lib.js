$(document).ready(function () {
    $('#content-main').on('click', '.click-view-ebook', function () {
        var id = $(this).data('id');

        $.ajax({
            url: base_url +'/libraries/update',
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            window.location = '';
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });

    });

    $('#content-main').on('click', '.click-view-doc', function () {
        var id = $(this).data('id');

        $.ajax({
            url: base_url +'/libraries/update',
            type: 'post',
            data: {
                id: id,
            }
        }).done(function(data) {
            window.location = '';
            return false;
        }).fail(function(data) {
            show_message('Lỗi hệ thống', 'error');
            return false;
        });

    });

    $('.slider-top').bxSlider({
        auto: true,
        pager: true,
    });

    var slider1 = $('.slider1').bxSlider({
        auto: true,
        pager: false,
    });

    $('a.pager-prev-1').click(function () {
        var current = slider1.getCurrentSlide();
        slider1.goToPrevSlide(current) - 1;
    });

    $('a.pager-next-1').click(function () {
        var current = slider1.getCurrentSlide();
        slider1.goToNextSlide(current) + 1;
    });

    var slider = $('.slider2').bxSlider({
        auto: false,
        pager: false,
    });

    $('a.pager-prev').click(function () {
        var current = slider.getCurrentSlide();
        slider.goToPrevSlide(current) - 1;
    });

    $('a.pager-next').click(function () {
        var current = slider.getCurrentSlide();
        slider.goToNextSlide(current) + 1;
    });

    // $("#slide-cm").lightSlider({
    //     loop:true,
    //     keyPress:true,
    //     item: 1,
    // });

    $(".button-slide a.pager-prev").hover(function() {
        $(".button-slide a.pager-prev img").attr('src', "/styles/images/hover_4.png");
    }, function() {
        $(".button-slide a.pager-prev img").attr('src', "/styles/images/slide-left.png");
    });

    $(".button-slide a.pager-next").hover(function() {
        $(".button-slide a.pager-next img").attr('src', "/styles/images/hover-3.png");
    }, function() {
        $(".button-slide a.pager-next img").attr('src', "/styles/images/slide-right.png");
    });

    $(".button-slide a.pager-prev-1").hover(function() {
        $(".button-slide a.pager-prev-1 img").attr('src', "/styles/images/hover_4.png");
    }, function() {
        $(".button-slide a.pager-prev-1 img").attr('src', "/styles/images/slide-left.png");
    });

    $(".button-slide a.pager-next-1").hover(function() {
        $(".button-slide a.pager-next-1 img").attr('src', "/styles/images/hover-3.png");
    }, function() {
        $(".button-slide a.pager-next-1 img").attr('src', "/styles/images/slide-right.png");
    });

    if(window.navigator.userAgent.toLowerCase().indexOf("chrome") > 0) {
        $("body").on("mousedown", ".bx-viewport a", function() {
            if($(this).attr("href") && $(this).attr("href") != "#") {
                window.location=$(this).attr("href");
            }
        });
    }
});