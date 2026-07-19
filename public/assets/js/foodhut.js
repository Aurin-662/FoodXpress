
// smooth scroll
$(document).ready(function(){
    $(".navbar .nav-link").on('click', function(event) {
        var href = $(this).attr('href') || '';

        if (!href.includes('#')) {
            return;
        }

        var url = new URL(href, window.location.href);

        if (url.pathname !== window.location.pathname) {
            return;
        }

        var target = document.querySelector(url.hash);
        if (!target) {
            return;
        }

        event.preventDefault();

        $('html, body').animate({
            scrollTop: $(target).offset().top - 90
        }, 700, function(){
            window.location.hash = url.hash;
        });
    });
});

new WOW().init();

function initMap() {
    var uluru = {lat: 23.75763227184108, lng: 90.35102632233169};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15,
      center: uluru
    });
    var marker = new google.maps.Marker({
      position: uluru,
      map: map
    });
 }
