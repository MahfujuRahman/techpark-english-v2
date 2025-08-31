<!-- course trainer start-->
@if ($data->course_instructors != null && count($data->course_instructors) > 0)
    <section class="course_trainers_area">
        <div class="container">
            <div class="trainers_description">
                <div class="trainers_title">
                    <h2 class="trainers_title_bangla text-center">Course Instructors</h2>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($data->course_instructors as $teacher)
                        <div class="trainers_details mb-4">
                            <div class="trainer_details">
                                <div class="trainer_images">
                                    <div class="image">
                                        <img src="{{ asset($teacher->image) }}" alt="" />
                                    </div>
                                    <div class="trainer_info">
                                        <div class="trainer_name">{{ $teacher->full_name }}</div>
                                        <p>{{ $teacher->designation }}</p>

                                        <div class="trainer_link d-flex gap-2">
                                            @if ($teacher->twitter)
                                                <a target="_blank" href="{{ $teacher->twitter }}"><i
                                                        class="fa-brands tw fa-square-twitter"></i></a>
                                            @endif
                                            @if ($teacher->facebook)
                                                <a target="_blank" href="{{ $teacher->facebook }}"><i
                                                        class="fa-brands fb fa-square-facebook"></i></a>
                                            @endif
                                            @if ($teacher->linkedin)
                                                <a target="_blank" href="{{ $teacher->linkedin }}"><i
                                                        class="fa-brands ld fa-linkedin"></i></a>
                                            @endif
                                            @if ($teacher->instagram)
                                                <a target="_blank" href="{{ $teacher->instagram }}"><i
                                                        class="fa-brands in fa-square-instagram"></i></a>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                                <div class="trainer_descrip">
                                    <p>
                                        {!! $teacher->short_description !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>
@endif
<!-- /course trainer end -->
