 <div class="course_info">
     @php
         $batch_info = $data->course_batch()->first();
     @endphp
     <div class="course_info_div">
         {{-- <div onclick="showCourseVideo(`{{ $data->intro_video }}`)" --}}
         <div class="course_info_thubnail_and_icon my-0">
             <div class="course_info_thubnail">
                 <img class="img-fluid course_main_img" src="{{ asset($data->image) }}" alt="">
             </div>
             <div class="course_info_icon">
                 <img src="{{ asset('frontend/') }}/assets/images/course_details_image/course_info_icon.png"
                     alt="">
             </div>
         </div>
         <div class="course_info_time">
             <div class="time_have">
                 <div class="time_have_title">সময় বাকী আছে</div>
                 <ul class="timer">
                     @php
                         use Carbon\Carbon;

                         $admissionEndDate = optional($batch_details)->admission_end_date;
                         $formattedDate = $admissionEndDate
                             ? Carbon::parse($admissionEndDate)->format('Y-m-d H:i:s')
                             : null;
                     @endphp

                     <li class="d-none">
                         <div class="amount" data-years></div>
                         <div class="title">Years</div>
                     </li>
                     <li>
                         <div class="amount" data-days></div>
                         <div class="title">দিন</div>
                     </li>|
                     <li>
                         <div class="amount" data-hours></div>
                         <div class="title">ঘণ্টা</div>
                     </li>|
                     <li>
                         <div class="amount" data-minutes></div>
                         <div class="title">মিনিট</div>
                     </li>|
                     <li>
                         <div class="amount" data-seconds></div>
                         <div class="title">সেকেন্ড</div>
                     </li>
                 </ul>
             </div>
             <div class="course_booked">
                 <div>
                     {{ App\Helpers\ConvertHelper::convertToBanglaNumbers($batch_info->booked_percent ?? '০') }}%
                 </div>
                 <div>বুকড</div>
             </div>
         </div>
         <div class="course_fee">

             @if ($batch_info)
                 <del class="twenty_thousand">৳
                     {{ App\Helpers\ConvertHelper::convertToBanglaNumbers(number_format($batch_info->course_price ?? '০', 0, '.', ',')) }}
                 </del>
                 <div class="ten_thousand">৳
                     {{ App\Helpers\ConvertHelper::convertToBanglaNumbers(number_format($batch_info->after_discount_price ?? '০', 0, '.', ',')) }}
                 </div>
             @else
                 <div class="ten_thousand">৳
                     {{ App\Helpers\ConvertHelper::convertToBanglaNumbers(number_format($batch_info->course_price ?? '০', 0, '.', ',')) }}
                 </div>
             @endif
         </div>
         <div class="admit_course">
             @if ($check_enrolled)
                 <a href="{{ url('my-course', $data->slug) }}" class="admit_course_title_and_icon">
                     <div class="admit_course_title">কোর্স দেখুন</div>
                     <div class="admit_course_icon"><i class="fa-solid fa-angle-right"></i></div>
                 </a>
             @else
                 <a href="{{ route('course_enroll', $data->slug) }}" class="admit_course_title_and_icon">
                     <div class="admit_course_title">কোর্সে ভর্তি হোন</div>
                     <div class="admit_course_icon"><i class="fa-solid fa-angle-right"></i></div>
                 </a>
             @endif
             <div class="admit_course_batch">
                 <div class="admit_course_batch_title">ব্যাচ <span>{{ $batch_info->batch_name }}</span>
                 </div>
                 <div class="admit_course_start_and_deadline">
                     <div class="admit_course_start">
                         <div class="admit_course_start_title"><span><i
                                     class="fa-regular fa-calendar-days"></i></span><span>ভর্তী শুরুঃ</span>
                         </div>
                         <div class="admit_course_start_date">
                             {{ \Carbon\Carbon::parse($batch_info->admission_start_date)->format('d M Y') }}
                         </div>
                     </div>
                     <div class="admit_course_line"></div>
                     <div class="admit_course_deadline">
                         <div class="admit_course_deadline_title"><span><i
                                     class="fa-regular fa-calendar-xmark"></i></span><span>ভর্তী শেষঃ</span>
                         </div>
                         <div class="admit_course_deadline_date">
                             {{ \Carbon\Carbon::parse($batch_info->admission_end_date)->format('d M Y') }}
                         </div>
                     </div>
                 </div>
             </div>
             <div class="admit_course_batch_details">
                 <div class="admit_course_orientation">
                     <span>
                         <i class="fa-regular fa-calendar-days"></i>
                     </span>
                     <span>
                         ওরিয়েন্টেশন ও প্রথম ক্লাসঃ
                         {{ \Carbon\Carbon::parse($batch_info->admission_end_date)->format('d M l') }}
                     </span>
                 </div>
                 <div class="admit_course_class_date">
                     <span><i class="fa-regular fa-calendar-days"></i></span>
                     <span>ক্লাসের দিনঃ {{ $batch_info->class_days }}</span>
                 </div>
                 <div class="admit_course_class_time"><span>
                         <i class="fa-regular fa-calendar-days"></i></span>
                     <span>ক্লাসের সময়ঃ
                         {{ \Carbon\Carbon::parse($batch_info->class_start_time)->format('g:i A') }} -
                         {{ \Carbon\Carbon::parse($batch_info->class_end_time)->format('g:i A') }}</span>
                 </div>
             </div>
         </div>
     </div>
     <div class="course_needed">
         <div class="course_needed_title">কোর্সটি করার জন্য যা যা লাগবে</div>
         @if ($data->course_essentials)
             @foreach ($data->course_essentials as $course_essential)
                 <div class="course_needed_internet">
                     <i class="fa-regular fa-circle-dot"></i>
                     {{ $course_essential->title }}
                 </div>
             @endforeach
         @endif
         <div class="course_hotline_and_schedule">
             <div class="course_hotline" style="display: unset; padding: 20px;">
                 <div class="course_hotline_title">
                     যেকোনো প্রয়োজনে কল করুনঃ
                 </div>
                 {{-- @foreach (setting('phone_numbers', true) as $item)
                                    <div class="d-flex mt-2 gap-2 align-items-center justify-content-center">
                                        <i class="fa-solid fa-phone"></i>
                                        <div class="course_hotline_number"> {{ $item->value }} </div>
                                    </div>
                                @endforeach --}}

             </div>
             <div class="course_schedule">(সকাল ১০ টা থেকে রাত ৮ টা)</div>
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

                         if (daysElement) daysElement.innerText = convertToBangla(event.days);
                         if (hoursElement) hoursElement.innerText = convertToBangla(event.hours);
                         if (minutesElement) minutesElement.innerText = convertToBangla(event.minutes);
                         if (secondsElement) secondsElement.innerText = convertToBangla(event.seconds);
                     }
                 },
             });
         @endif

     });
 </script>
