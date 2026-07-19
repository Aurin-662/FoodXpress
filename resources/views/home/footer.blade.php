<!-- CONTACT Section  -->
    <div id="contact" class="container-fluid bg-dark text-light border-top wow fadeIn">
        <div class="row">
            <div class="col-md-6 px-0">
                <div id="map" style="width: 100%; height: 100%; min-height: 400px"></div>
            </div>
            <div class="col-md-6 px-5 has-height-lg middle-items">
                <h3>FIND US</h3>
                <p>Visit FoodXpress for a warm dining experience or order online for fast doorstep delivery. We serve fresh pizzas, juicy burgers, and crispy fried chicken made daily with quality ingredients. Whether you're dining in or ordering for delivery, our team is dedicated to serving you great food with a smile.</p>
                <div class="text-muted">
                    <p><span class="ti-location-pin pr-3"></span> House 12, Road 5, Dhanmondi, Dhaka-1209</p>
                    <p><span class="ti-support pr-3"></span> +880 1XXX-XXXXXX</p>
                    <p><span class="ti-email pr-3"></span>contact@foodxpress.com</p>
                </div>
            </div>
        </div>
    </div>

    <!-- page footer  -->
    <div class="container-fluid bg-dark text-light has-height-md middle-items border-top text-center wow fadeIn">
        <div class="row">
            <div class="col-sm-4">
                <h3>EMAIL US</h3>
                <P class="text-muted">info@website.com</P>
            </div>
            <div class="col-sm-4">
                <h3>CALL US</h3>
                <P class="text-muted">(123) 456-7890</P>
            </div>
            <div class="col-sm-4">
                <h3>FIND US</h3>
                <P class="text-muted">12345 Fake ST NoWhere AB Country</P>
            </div>
        </div>
    </div>
    <div class="bg-dark text-light text-center border-top wow fadeIn">
<p class="mb-0 py-3 text-muted small">&copy; Copyright <script>document.write(new Date().getFullYear())</script> FoodXpress. All rights reserved.</p>    </div>
    <!-- end of page footer -->

	<!-- core  -->
    <script src="{{ asset('assets/vendors/jquery/jquery-3.4.1.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/bootstrap.bundle.js') }}"></script>

    <!-- bootstrap affix -->
    <script src="{{ asset('assets/vendors/bootstrap/bootstrap.affix.js') }}"></script>

    <!-- wow.js -->
    <script src="{{ asset('assets/vendors/wow/wow.js') }}"></script>
    
    <!-- google maps -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
    <!-- FoodXpress js -->
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