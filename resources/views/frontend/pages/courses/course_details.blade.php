@php
    $meta = [
        // "meta" => [],
        'seo' => [
            'title' => $data->title,
            'image' => asset($data->image),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)
@section('contents')
    <section class="course_details_area" id="course_details">
        <div class="container">
            <div class="course_details_part">
                <div class="course_details">

                    @include('frontend.pages.courses.includes.index')

                    @include('frontend.pages.courses.includes.course_how_is_structured')

                    @include('frontend.pages.courses.includes.features')

                    @include('frontend.pages.courses.includes.course_module')

                    @include('frontend.pages.courses.includes.teachers')
                </div>

                @include('frontend.pages.courses.includes.course_details_card')


            </div>
        </div>
    </section>

    @include('frontend.pages.courses.includes.course_faq')


    
@endsection
