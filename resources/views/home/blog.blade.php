<div id="blog" class="container-fluid bg-dark text-light py-5 text-center wow fadeIn">
<h2 class="section-title py-5">OUR MENU</h2>    
<h6 class="lead mb-5">Order Your Favorite Foods</h6>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="foods" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="row">

                <div class="col-12 mb-4">
                    <form method="GET" action="{{ url('/#blog') }}" class="d-flex flex-wrap gap-2 justify-content-center mb-3">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <input type="text" name="search" placeholder="Search food..." value="{{ request('search') }}" class="form-control" style="max-width:220px;">
                        <input type="number" name="min_price" placeholder="Min price" value="{{ request('min_price') }}" class="form-control" style="max-width:120px;">
                        <input type="number" name="max_price" placeholder="Max price" value="{{ request('max_price') }}" class="form-control" style="max-width:120px;">
                        <button class="btn btn-info" type="submit">Filter</button>
                    </form>

                    @php $currentCategory = request('category'); @endphp
                    <div class="d-flex justify-content-center">
                        <div class="category-pills d-flex flex-wrap gap-2">
                            <a href="{{ url('/') }}#blog" class="btn {{ !$currentCategory ? 'btn-primary' : 'btn-outline-light' }} btn-sm">All</a>
                            @foreach($topCategories as $top)
                                <a href="{{ url('/?category='.$top->id) }}#blog" class="btn {{ $currentCategory && $currentCategory == $top->id ? 'btn-primary' : 'btn-outline-light' }} btn-sm">{{ $top->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @foreach($data as $food)

                <div class="col-md-4 mb-4">
                    <div class="card h-100 bg-transparent border-0 text-light menu-card">
                        <img src="{{ asset('food_img/'.$food->image) }}" alt="{{ $food->title }}" class="card-img-top rounded-0">
                        <div class="card-body d-flex flex-column align-items-start text-start">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2 w-100">
                                <h5 class="card-title mb-0">{{ $food->title }}</h5>
                                <span class="badge bg-primary">${{ $food->price }}</span>
                            </div>

                            <div class="menu-card-meta mb-3 w-100">
                                <p class="card-text text-white-50 small mb-1">{{ $food->detail }}</p>
                                <p class="review-block text-white small mb-0">
                                    @php $avg = round($food->reviews->avg('rating'), 1); @endphp
                                    ⭐ {{ $avg ?: 'No ratings yet' }} ({{ $food->reviews->count() }} reviews)
                                </p>
                            </div>

                            @auth
                            <form action="{{ url('add_review', $food->id) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <select name="rating" required class="form-control" style="max-width:100px;">
                                        <option value="">Rate</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }} ⭐</option>
                                        @endfor
                                    </select>
                                    <input type="text" name="comment" placeholder="Write a review (optional)" class="form-control" style="min-width:180px;">
                                    <button class="btn btn-sm btn-outline-info" type="submit">Submit</button>
                                </div>
                            </form>
                            @endauth

                            <div class="mt-auto">
                                <form class="ajax-cart-form d-flex align-items-center gap-2" action="{{ url('add_cart', $food->id) }}" method="POST">
                                    @csrf
                                    <input value="1" type="number" min="1" name="qty" required class="form-control qty-input" style="width:80px;">
                                    <button class="btn btn-warning" type="submit">Add To Cart</button>
                                </form>
                                <div class="ajax-cart-msg text-success small mt-2" style="font-weight:600;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>
</div>
<style>
    .category-pills .btn{ padding:6px 10px; }
    .category-pills a{ color:#fff; }
    .menu-card{
        border:1px solid rgba(255,255,255,0.08);
        background: rgba(255,255,255,0.06);
        box-shadow: 0 14px 35px rgba(0,0,0,0.18);
        border-radius: 18px;
        overflow: hidden;
        transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
        min-height: 100%;
    }
    .menu-card:hover{
        transform: translateY(-6px);
        box-shadow: 0 22px 42px rgba(0,0,0,0.25);
        background: rgba(255,255,255,0.1);
    }
    .menu-card .card-img-top{
        border-radius: 18px 18px 0 0;
        height:220px;
        width:100%;
        object-fit:cover;
        background:#2b2b2b;
    }
    .menu-card .card-body{
        align-items:flex-start;
        text-align:left;
        padding: 1.1rem 1rem 1rem;
    }
    .menu-card .card-title{
        font-size: 1.35rem;
        letter-spacing: 0.02em;
        text-transform: capitalize;
    }
    .menu-card-meta{ min-height:100px; display:block; width:100%; }
    .menu-card-meta p{ margin-bottom:0.35rem; }
    .review-block{
        line-height:1.4;
        display:block;
        color: #ffd966;
    }
    .ajax-cart-form{ width:100%; justify-content:flex-start; gap:0.75rem; }
    .ajax-cart-form .btn-warning{
        background: #ff6b6b;
        border-color: #ff6b6b;
        color:#fff;
    }
    .qty-input{
        max-width:90px;
        border-radius: 12px;
    }
    @media (max-width:767px){
        .category-pills{ justify-content:center; }
        .menu-card .card-body{ padding:1rem; }
        .menu-card .card-img-top{ height:180px; }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Intercept category clicks to load filtered items via AJAX and avoid full page reload
    document.querySelectorAll('.category-pills a').forEach(function(el){
        el.addEventListener('click', function(ev){
            ev.preventDefault();
            var url = el.getAttribute('href');
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(res){ return res.text(); })
                .then(function(html){
                    try{
                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');
                        var newBlog = doc.getElementById('blog');
                        if(newBlog){
                            var current = document.getElementById('blog');
                            current.innerHTML = newBlog.innerHTML;
                            window.history.pushState({}, '', url);
                            current.scrollIntoView({ behavior: 'smooth' });
                        } else {
                            // fallback: full navigation
                            window.location = url;
                        }
                    } catch(e){
                        window.location = url;
                    }
                }).catch(function(){ window.location = url; });
        });
    });

    // Handle back/forward to restore state
    window.addEventListener('popstate', function(){
        location.reload();
    });
});
</script>