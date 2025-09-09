@php
    $meta = [
        'seo' => [
            'title' => 'gallery',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/fancybox/jquery.fancybox.min.css') }}" />
@endpush

@section('contents')
    <!-- my_tema_area start -->
    <section>
        <div class="container">
            <div class="our_course_area pb-0 mt-4">
                <div class="our_course_area_content pb-0">
                    <div class="course_schedule_name">
                        <ul class="flex-wrap pt-4 gallery_nav">
                            <li>
                                <a href="?page=1" class="{{ !request()->has('gallery_category_id') ? ' active' : ' ' }}">
                                    All
                                </a>
                            </li>
                            @php
                                $galleryCategories = App\Modules\Management\GalleryManagement\GalleryCategory\Models\Model::where(
                                    'status',
                                    'active',
                                )->get();
                            @endphp
                            @foreach ($galleryCategories as $gcat)
                                <li>
                                    <a href="?gallery_category_id={{ $gcat->id }}&page=1"
                                        class="{{ request()->has('gallery_category_id') && request()->get('gallery_category_id') == $gcat->id ? ' active' : '' }}
                                            
                                        ">
                                        {{ $gcat->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="my_tema_area all_area mt-4">
        <div class="container">
            <div class="gallery_area">
                <div class="gallery_content">
                    @foreach ($galleryImages as $gmat)
                        <div class="gallery_content_img">
                            <a href="{{ assetHelper(optional($gmat)->image) }}" data-fancybox="gallery"
                                data-caption="{{ $gmat->title }}">
                                <img src="{{ assetHelper(optional($gmat)->image) }}" alt="{{ $gmat->title }}"
                                    loading="lazy">
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
            <div class="mt-4">

                {!! $galleryImages->links() !!}
            </div>
    </section>
    <!-- my_tema_area end-->
@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/fancybox/jquery.fancybox.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('[data-fancybox="gallery"]').fancybox({
                loop: true,
                buttons: [
                    "zoom",
                    "share",
                    "slideShow",
                    "fullScreen",
                    "download",
                    "thumbs",
                    "close"
                ],
                animationEffect: "fade",
                transitionEffect: "fade"
            });
        });
    </script>
@endpush
