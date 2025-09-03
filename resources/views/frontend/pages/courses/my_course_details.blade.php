@php
    $meta = [
        // "meta" => [],
        'seo' => [
            'title' => 'My Course',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)
@section('contents')
    <div class="course_details_quiz_area">
        <div class="container">
            <div class="course_progress_mearn">
                <div class="course_progress_mearn_title">
                    {{ $course->title }}
                </div>
                <div class="course_progress_area">
                    <div class="course_progress">
                        <div class="course_progress_title">কোর্স প্রোগ্রেস {{ $progress }} %</div>
                        <div class="course_progress_bar">
                            <div class="course_progress_bar_complete" style="width: {{ $progress }}%;"></div>
                        </div>
                        {{-- <div class="course_progress_info">
                            কোর্স সার্টিফিকেট পেতে কোর্সটি সম্পুর্ণ করুন
                        </div> --}}
                    </div>
                    {{-- <div class="course_certificate">
                        <div class="course_certificate_image">
                            <img class="img-fluid" src="/frontend/assets/images/course_details_image/course_certificate.png"
                                alt="" />
                        </div>
                        <div class="course_certificate_lock">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="class_routine">
                <div class="class_routine_title_and_details">
                    <div class="class_routine_title">ক্লাস রুটিন</div>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#routine_modal">
                        <div class="class_routine_details">
                            বিস্তারিত দেখুন<i class="fa-solid fa-arrow-right-long"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- /course details quiz area end -->
    <!-- course quize and module part start -->
    <div class="course_quize_and_module_part" id="course_section">
        <div class="container">
            <div class="mearn_course_detail_module_and_bootstrap_quiz">
                <h3 class="mb-2">Course Outlines: </h3>
                <div class="mearn_course_detail_module">

                    <div>
                        @foreach ($course->milestones as $course_mile_stone)
                            @if ($course_mile_stone->modules->count())
                                <h5 class="mb-3"><b>{{ $course_mile_stone->milestone_no }}</b>:
                                    {{ $course_mile_stone->title }}</h5>
                                <div class="class_module_details">
                                    @foreach ($course_mile_stone->modules as $key => $item)
                                        <ul class="class_module_features">
                                            <li>
                                                <div class="class_module_title">
                                                    <div class="class_module_title_and_number">
                                                        <div class="class_module_title_details">
                                                            <div class="title">মডিউল <span class="number">
                                                                    {{ $item->module_no }} - </span> {{ $item->title }}
                                                            </div>
                                                            <ul class="details">
                                                                <li>
                                                                    {{ $item->classes->where('type', 'live')->count() }}
                                                                    টি লাইভ ক্লাস
                                                                </li>

                                                                <li>
                                                                    {{ $item->classes->where('type', 'recorded')->count() }}
                                                                    টি রেকর্ডেড ক্লাস
                                                                </li>
                                                                ।
                                                                <li>
                                                                    {{ $item->quizes->count() }}
                                                                    টি কুইজ
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="class_module_acordion_icon">
                                                        <i class="fa-solid fa-chevron-down"></i>
                                                    </div>
                                                </div>
                                                <div class="class_module_feature_content">
                                                    <ul>
                                                        @foreach ($item->classes as $key => $class)
                                                            <li>
                                                                <div class="class_module_class_no">ক্লাস
                                                                    {{ $class->class_no }}</div>

                                                                <div data-key="{{ $key }}"
                                                                    data-link="`{{ $class->link }}`"
                                                                    class="live_class_and_topic" style="cursor: pointer"
                                                                    onclick="getClassVideolink(`{{ $class->class_video_link }}`)"
                                                                    courseCompletion('{{ $course->id }}',
                                                                    {{ $course_mile_stone->id }}, {{ $item->id }},
                                                                    {{ $class->id }})">
                                                                    @if ($class->is_complete)
                                                                        <div class="live_class_icon">
                                                                            <i
                                                                                class="fa-solid fa-circle-check text-success"></i>
                                                                        </div>
                                                                    @else
                                                                        <div class="live_class_icon">
                                                                            <img src="/frontend/assets/images/about_page_image/class_module/podcasts.png"
                                                                                alt="" />
                                                                        </div>
                                                                    @endif
                                                                    <div class="class_module_live_class">
                                                                        @if ($class->type == 'live')
                                                                            লাইভ ক্লাসঃ
                                                                        @else
                                                                            রেকর্ডেড ক্লাসঃ
                                                                        @endif
                                                                    </div>
                                                                    <div class="class_module_topic cursor_pointer">
                                                                        {{ $class->title }}</div>
                                                                </div>

                                                                @if ($class->class_quiz && $class->class_quiz->quiz)
                                                                    <div class="quiz_and_mcq">
                                                                        @if ($class->class_quiz->is_complete)
                                                                            <div class="quiz_icon">
                                                                                <i
                                                                                    class="fa-solid fa-circle-check text-success"></i>
                                                                            </div>
                                                                        @else
                                                                            <div class="quiz_icon">
                                                                                <i class="fa-solid fa-list-check"></i>
                                                                            </div>
                                                                        @endif

                                                                        <div class="quiz">কুইজঃ </div>
                                                                        <div class="mcq">
                                                                            {{ $class->class_quiz->quiz->title ?? '' }}
                                                                            {{-- MCQ Question about basics of Bootstrap --}}
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @if ($class->class_exam && $class->class_exam->exam)
                                                                    <div class="quiz_and_mcq">
                                                                        @if ($class->class_exam->exam->is_complete)
                                                                            <div class="quiz_icon">
                                                                                <i
                                                                                    class="fa-solid fa-circle-check text-success"></i>
                                                                            </div>
                                                                        @else
                                                                            <div class="quiz_icon">
                                                                                <i class="fa-solid fa-file-lines"></i>
                                                                            </div>
                                                                        @endif
                                                                        <div class="quiz">এক্সামঃ </div>
                                                                        <div class="mcq">
                                                                            {{ $class->class_exam->exam->title ?? '' }}
                                                                            {{-- MCQ Question about basics of Bootstrap --}}
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="course_lession_video_btn">

                        <div class="course_lession_video">
                            <div class="course_lession_video_thum">

                                <iframe id="class_video_link" width="100%" height="450"
                                    src=""
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture;"
                                    allowfullscreen>
                                </iframe>
                            </div>
                          

                        </div>

                        <div class="course_lession_video_next_btn">
                            <div class="next_btn" onclick="nextVideo()">
                                নেক্সট লেসন <i class="fa-solid fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade modal-xl" id="routine_modal" tabindex="-1" aria-labelledby="routine_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="course_details_quiz_area">
                        <div class="class_routine">
                            <div class="class_routine_title_and_details justify-content-center">
                                <div class="class_routine_title">ক্লাস রুটিন</div>
                            </div>
                            <div class="class_routine_table">
                                @foreach ($course->routines as $month => $routine)
                                    <div class="class_routine_month">{{ $month }}</div>
                                    <div class="table_div">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <td>ক্লাস নং</td>
                                                    <td>তারিখ</td>
                                                    <td>সময়</td>
                                                    <td>ক্লাস টপিক</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($routine as $class_routine)
                                                    <tr>
                                                        <td>ক্লাস
                                                            {{ $class_routine->class ? $class_routine->class->class_no : '' }}
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($class_routine->date)->format('d F') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($class_routine->date)->format('l') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($class_routine->date)->format('Y') }}
                                                        </td>
                                                        <td>রাত
                                                            {{ \Carbon\Carbon::parse($class_routine->time)->format('g:i A') }}
                                                        </td>
                                                        <td>
                                                            <div>
                                                                @if ($class_routine->class && $class_routine->class->type == 'recorded')
                                                                    রেকর্ডেড ক্লাসঃ {{ $class_routine->topic }}
                                                                @else
                                                                    লাইভ ক্লাসঃ {{ $class_routine->topic }}
                                                                @endif
                                                            </div>
                                                            {{-- <div>কুইজঃMCQ Question about basics of Bootstrap</div> --}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let classVideos = [];
        let currentIndex = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Populate classVideos array
            @php
                $allClasses = [];
                foreach ($course->milestones as $milestone) {
                    if ($milestone->modules->count()) {
                        foreach ($milestone->modules as $module) {
                            if ($module->classes->count()) {
                                foreach ($module->classes as $class) {
                                    $allClasses[] = $class;
                                }
                            }
                        }
                    }
                }
            @endphp
            @foreach($allClasses as $class)
                classVideos.push('{{ $class->class_video_link }}');
            @endforeach

            // Get the first class video link
            @php
                $firstClass = null;
                foreach ($course->milestones as $milestone) {
                    if ($milestone->modules->count()) {
                        foreach ($milestone->modules as $module) {
                            if ($module->classes->count()) {
                                $firstClass = $module->classes->first();
                                break 2;
                            }
                        }
                    }
                }
                $firstVideoLink = $firstClass ? $firstClass->class_video_link : '';
            @endphp
            if ('{{ $firstVideoLink }}') {
                getClassVideolink('{{ $firstVideoLink }}');
            }
        });

        [...document.querySelectorAll(".class_module_acordion_icon")].forEach(
            (el) => {
                el.onclick = function(e) {
                    e.currentTarget.parentNode.parentNode.classList.toggle("active");
                    console.log(e.currentTarget);
                };
            }
        );

        function nextVideo() {
            if (currentIndex < classVideos.length - 1) {
            currentIndex++;
            getClassVideolink(classVideos[currentIndex]);
            } else {
            alert('No more videos in this course.');
            }
        }

        function getClassVideolink(link) {
            console.log(link);
            // Set current index based on the original link before conversion
            currentIndex = classVideos.indexOf(link);
            // Convert YouTube watch URL to embed URL if needed
            let embedLink = link;
            if (link.includes('youtube.com/watch')) {
            const videoId = link.split('v=')[1].split('&')[0];
            embedLink = `https://www.youtube.com/embed/${videoId}`;
            }
            document.getElementById('class_video_link').src = embedLink;
            document.getElementById("course_section").scrollIntoView();
        }

        function courseCompletion(courseId, milestoneId, moduleId, classId) {
            // Placeholder: Implement logic to mark the class as complete
            console.log('Marking class as complete:', courseId, milestoneId, moduleId, classId);
            // Add AJAX call or other logic here to update completion status
        }

    </script>
@endsection
