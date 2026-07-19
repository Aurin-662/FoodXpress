<!-- core  -->
<script src="{{ asset('assets/vendors/jquery/jquery-3.4.1.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap/bootstrap.bundle.js') }}"></script>

<!-- bootstrap affix -->
<script src="{{ asset('assets/vendors/bootstrap/bootstrap.affix.js') }}"></script>

<!-- wow.js -->
<script src="{{ asset('assets/vendors/wow/wow.js') }}"></script>

<!-- google maps -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>

<!-- FoodHut js -->
<script src="{{ asset('assets/js/foodhut.js') }}"></script>
<script src="{{ asset('js/ajax-cart.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const scrollToHash = function (hash) {
        if (!hash) return;
        const target = document.querySelector(hash);
        if (!target) return;
        const offset = 90;
        const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
    };

    document.querySelectorAll('.navbar .nav-link').forEach(function (link) {
        link.addEventListener('click', function (event) {
            const href = this.getAttribute('href') || '';
            if (!href.includes('#')) return;

            const url = new URL(href, window.location.href);
            if (url.pathname !== window.location.pathname) {
                return;
            }

            event.preventDefault();
            scrollToHash(url.hash);
            history.replaceState(null, '', url.hash || window.location.pathname);
        });
    });

    if (window.location.hash) {
        scrollToHash(window.location.hash);
    }
});
</script>