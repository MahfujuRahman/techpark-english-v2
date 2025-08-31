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

                </div>
            </div>
        </div>
    </section>




    <script src="/js/plugins/countdown.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const timerElement = document.querySelector('.timer');

            // Function to convert numbers to Bengali numerals
            function convertToBangla(number) {
                const banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                return number.toString().split('').map(digit => banglaDigits[digit] || digit).join('');
            }


        });

        [
            ...document.querySelectorAll(".general_question_acordion_icon"),
        ].forEach((element) => {
            element.onclick = function(e) {
                e.currentTarget.parentNode.parentNode.classList.toggle(
                    "active"
                );
                // console.log(e.currentTarget.parentNode.classList);
            };
        });
    </script>
@endsection
