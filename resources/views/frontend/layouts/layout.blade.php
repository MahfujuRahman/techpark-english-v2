<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0PM08PK3E8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-0PM08PK3E8');
    </script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- @include('frontend.layouts.includes.pwa') --}}

    @if (!isset($seo))
        @include('frontend.layouts.includes.meta', [
            'seo' => (object) [
                'title' => 'website',
            ],
        ])
    @else
        @include('frontend.layouts.includes.meta', ['seo' => (object) $seo])
    @endif

    @php
        // $website_about = \App\Models\WebsiteCoreInformation::where('status', 1)->first();
        $website_about = 'gf';
    @endphp

    {{-- <link rel="shortcut icon" type="image/x-icon" href="{{ $meta->fabicon ?? asset(setting(key: 'fabicon')) }}"> --}}
    {{-- <link rel="shortcut icon" type="image/x-icon" href="{{ $meta->fabicon ?? $website_about->fabicon }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/icon/fontawesome-free-6.2.0-web/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/styles/style.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/styles/custom.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- js plugins --}}
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/owl.carousel.min.css">
    <script src="{{ asset('frontend') }}/assets/js/owl.carousel.min.js"></script>

    <script src="{{ asset('js/plugins/localforage.min.js') }}"></script>
    {{-- <script src="{{ asset('js/plugins/turbolinks.min.js') }}"></script> --}}
    <script src="{{ asset('js/plugins/sweetalert.js') }}"></script>
    <script src="{{ asset('js/plugins/axios.js') }}"></script>
    {{-- <script src="{{ asset('js/plugins/vue.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- frontend vue scripts --}}
    {{-- <script src="{{ asset('js/frontend_vue.js') }}"></script> --}}

    {{-- turbo link setup --}}
    {{-- <script src="{{ asset('js/frontend.js') }}" defer></script> --}}

    {{-- pwa setup --}}
    {{-- <script src="{{ asset('main.js') }}" defer></script> --}}

    @stack('styles')
</head>

<body id="top">

    @if (session()->has('success'))
        <script>
            window.toaster("{{ session()->get('success') }}");
        </script>
    @endif
    @if (session()->has('warning'))
        <script>
            window.toaster("{{ session()->get('warning') }}", 'warning');
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            window.toaster("{{ session()->get('error') }}", 'error');
        </script>
    @endif

    @include('frontend.layouts.header')

    <main>
        @yield('contents')
    </main>

    @include('frontend.layouts.footer')

    <!-- chating_and_go_to_top_area start -->
    <section class="chating_and_go_to_top_area">
        <!-- chating_area start -->
        <div class="chating_area">
            <div class="logo_and_text">
                <div class="logo">
                    <i class="fa-brands fa-facebook-messenger"></i>
                </div>
                <a href="{{ setting(key: 'messenger') }}" target="_blank" class="text">chat</a>
            </div>
        </div>
        <!-- chating_area end -->
        <!-- go_to_top_area start -->
        <div class="go_to_top_area">
            <a href="#top" class="logo">
                <i class="fa-solid fa-angle-up"></i>
            </a>
        </div>
        <!-- go_to_top_area end -->
    </section>
    <!-- chating_and_go_to_top_area end -->

    <script>
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 1
                },
            }
        })
    </script>

    @stack('scripts')

</body>

</html>
