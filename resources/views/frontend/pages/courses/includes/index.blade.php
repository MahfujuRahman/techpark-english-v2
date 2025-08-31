<div class="course_title">
    <h2 class="course_title_text">
        {{ $data->title }}
    </h2>
</div>

<!-- what is course start -->
<div class="what_is_course">
    <h2 class="what_is_course_title">{{ $data->title }} কী?</h2>
    <p class="what_is_course_details">
        {!! $data->what_is_this_course !!}
    </p>
</div>
<!-- /what is course end -->

<!-- why learn this course start -->
<div class="why_learn_this_course">
    <h2 class="why_learn_this_course_title">
        এই Course টি আপনাকে কিভাবে সাহায্য করবে
        {{-- এই কোর্স কেনো করবেন? --}}
    </h2>
    <div class="why_learn_this_course_details">
        <style>
            .why_learn_this_course_details>ul {
                padding-left: 30px;

                li {
                    list-style-type: disc;
                }
            }
        </style>
        {!! $data->why_is_this_course !!}
    </div>
</div>
<!-- /why learn this course end -->
