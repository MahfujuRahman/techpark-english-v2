<div class="course_info">
    @php
        $batch_info = $batch_details;
    @endphp
    <div class="course_info_div">
        <div class="course_info_thubnail_and_icon my-0">
            <div class="course_info_thubnail">
                <img class="img-fluid course_main_img" src="{{ assetHelper(optional($data)->image) }}"
                    alt="{{ optional($data)->title }}" style="width: 100%;" loading="lazy">
            </div>
            <div class="course_info_icon" style="cursor:pointer;"
                onclick="showVideo(`{{ optional($data)->intro_video }}`)">
                <img src="{{ asset('frontend/') }}/assets/images/course_info_icon.png" alt="">
            </div>
        </div>
        <div class="course_info_time">
            <div class="time_have">
                <div class="time_have_title">Time left</div>
                <ul class="timer">
                    <li class="d-none">
                        <div class="amount" data-years></div>
                        <div class="title">Years</div>
                    </li>
                    <li>
                        <div class="amount" data-days></div>
                        <div class="title">Days</div>
                    </li>|
                    <li>
                        <div class="amount" data-hours></div>
                        <div class="title">Hours</div>
                    </li>|
                    <li>
                        <div class="amount" data-minutes></div>
                        <div class="title">Minutes</div>
                    </li>|
                    <li>
                        <div class="amount" data-seconds></div>
                        <div class="title">Seconds</div>
                    </li>
                </ul>
            </div>

            @if ($batch_info?->show_percentage_on_cards == 'yes')
                <div class="course_booked">
                    <div>
                        {{ $batch_info->booked_percent ?? 0 }}%
                    </div>
                    <div>Booked</div>
                </div>
            @endif
        </div>

        <div class="course_fee">

            @if ($batch_info)
                <del class="twenty_thousand">৳
                    {{ number_format($batch_info->course_price ?? 0, 0, '.', ',') }}
                </del>
                <div class="ten_thousand">৳
                    {{ number_format($batch_info->after_discount_price ?? 0, 0, '.', ',') }}
                </div>
            @else
                <div class="ten_thousand">৳
                    {{ number_format($batch_info->course_price ?? 0, 0, '.', ',') }}
                </div>
            @endif
        </div>
        <div class="admit_course">
            @if ($check_enrolled)
                <a href="{{ url('my-course', $data->slug) }}" class="admit_course_title_and_icon">
                    <div class="admit_course_title">View Course</div>
                    <div class="admit_course_icon"><i class="fa-solid fa-angle-right"></i></div>
                </a>
            @else
                <a href="{{ route('course_enroll', $data->slug) }}" class="admit_course_title_and_icon">
                    <div class="admit_course_title">Enroll in Course</div>
                    <div class="admit_course_icon"><i class="fa-solid fa-angle-right"></i></div>
                </a>
            @endif

            <div class="admit_course_batch">
                <div class="admit_course_batch_title">Batch <span>{{ $batch_info->batch_name }}</span>
                </div>
                <div class="admit_course_start_and_deadline">
                    <div class="admit_course_start">
                        <div class="admit_course_start_title"><span><i
                                    class="fa-regular fa-calendar-days"></i></span><span>Admission Starts:</span>
                        </div>
                        <div class="admit_course_start_date">
                            {{ \Carbon\Carbon::parse($batch_info->admission_start_date)->format('d M Y ') }}
                        </div>
                    </div>
                    <div class="admit_course_line"></div>
                    <div class="admit_course_deadline">
                        <div class="admit_course_deadline_title"><span><i
                                    class="fa-regular fa-calendar-xmark"></i></span><span>Admission Ends:</span>
                        </div>
                        <div class="admit_course_deadline_date">
                            {{ \Carbon\Carbon::parse($batch_info->admission_end_date)->format('d M Y g:i A') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="admit_course_batch_details">
                <div class="admit_course_orientation">
                    <span>
                        <i class="fa-regular fa-calendar-days"></i>
                    </span>
                    Orientation & First Class:
                    {{ \Carbon\Carbon::parse($batch_info->first_class_date)->format('d M l Y') }}
                    </span>
                </div>
                <div class="admit_course_class_date">
                    <span><i class="fa-regular fa-calendar-days"></i></span>
                    <span>Class Days: {{ $batch_info->class_days }}</span>
                </div>
                <div class="admit_course_class_time"><span>
                        <i class="fa-regular fa-calendar-days"></i></span>
                    <span>Class Time:
                        {{ \Carbon\Carbon::parse($batch_info->class_start_time)->format('g:i A') }} -
                        {{ \Carbon\Carbon::parse($batch_info->class_end_time)->format('g:i A') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="course_needed">
        <div class="course_needed_title">Requirements to take this course</div>
        @if ($data->course_essential_requirements)
            @foreach ($data->course_essential_requirements as $course_essential)
                <div class="course_needed_internet">
                    <i class="fa-regular fa-circle-dot"></i>
                    {{ $course_essential->title }}
                </div>
            @endforeach
        @endif
        <div class="course_hotline_and_schedule">
            <div class="course_hotline" style="display: unset; padding: 20px;">
                <div class="course_hotline_title">
                    Call us for any assistance:
                </div>
                @php
                    $phone_numbers = setting('phone_numbers', true);
                    // If $phone_numbers is an array with 'setting_values', extract that
                    if (is_array($phone_numbers) && isset($phone_numbers[0]['setting_values'])) {
                        $phone_numbers = $phone_numbers[0]['setting_values'];
                    }
                @endphp
                @foreach ($phone_numbers as $item)
                    @php
                        $phone_number = is_array($item) ? $item['value'] ?? '' : $item;
                    @endphp
                    <div class="d-flex mt-2 gap-2 align-items-center justify-content-center">
                        <i class="fa-solid fa-phone"></i>
                        <a class="course_hotline_number" href="tel:{{ $phone_number }}"> {{ $phone_number }} </a>
                    </div>
                @endforeach
            </div>
            <div class="course_schedule">(10 AM to 8 PM)</div>
        </div>
    </div>
</div>

<script src="/js/plugins/countdown.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const timerElement = document.querySelector('.timer');

        // Function to convert numbers to Bengali numerals
        function convertToBangla(number) {
            const banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            return number.toString().split('').map(digit => banglaDigits[digit] || digit).join('');
        }
        @if ($formattedDate)
            timezz(timerElement, {
                date: "{{ $formattedDate }}",
                pause: false,
                stopOnZero: true,
                update(event) {
                    if (event.total <= 0) {
                        document.querySelector("[data-days]").innerText = "০";
                        document.querySelector("[data-hours]").innerText = "০";
                        document.querySelector("[data-minutes]").innerText = "০";
                        document.querySelector("[data-seconds]").innerText = "০";
                    } else {
                        const daysElement = document.querySelector("[data-days]");
                        const hoursElement = document.querySelector("[data-hours]");
                        const minutesElement = document.querySelector("[data-minutes]");
                        const secondsElement = document.querySelector("[data-seconds]");

                        //  if (daysElement) daysElement.innerText = convertToBangla(event.days);
                        //  if (hoursElement) hoursElement.innerText = convertToBangla(event.hours);
                        //  if (minutesElement) minutesElement.innerText = convertToBangla(event.minutes);
                        //  if (secondsElement) secondsElement.innerText = convertToBangla(event.seconds);
                    }
                },
            });
        @endif

    });
</script>

{{-- Include frontend.js for showVideo function --}}
<script src="{{ asset('js/frontend.js') }}"></script>

{{-- Video Modal --}}
<div class="modal fade" id="story_modal" tabindex="-1" aria-labelledby="storyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Video content will be inserted here -->
            </div>
        </div>
    </div>
</div>
