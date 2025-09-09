<div class="free_seminar_area">
    <div class="container">
        <div class="free_seminar_area_content">
            <div class="row">
                <!-- left_area start -->
                <div class="col-xs-12 col-md-12 col-lg-6">
                    <div class="left_area">
                        <!--free_seminar_area title start -->
                        <div class="free_seminar_area_title">
                            <h2 class="area_title">
                                Join Our Free Seminar
                            </h2>
                        </div>
                        <!-- free_seminar_area title end -->

                        <!-- free_seminar_area sub_title start -->
                        <div class="free_seminar_area_sub_title">
                            <span class="sub_title">
                                Not sure which sector to build your career in? Join our free seminar! In these
                                topic-based seminars, you will learn about the opportunities in each course. You can
                                also talk to expert counselors at the seminar to easily decide which course is right for
                                you.
                            </span>
                        </div>
                        <!-- free_seminar_area sub_title end -->

                        <!-- date_line_area start -->
                        @foreach ($seminars as $item)
                            @php
                                $date1 = \Carbon\Carbon::now();
                                $date2 = \Carbon\Carbon::parse($item->date_time);

                                $difference = $date1->diffInDays($date2);
                            @endphp
                            <div class="date_line_area">

                                <div class="date">
                                    <span class="date_number">{{ $difference }}</span>
                                    <span class="date_text">দিন বাকী</span>
                                </div>

                                <div class="data_science">
                                    <span class="data_science_text_title">
                                        {{ $item->title }}
                                    </span>
                                    <div class="data_science_text_sub_title">
                                        <!-- অনলাইন | ১৯ ডিসেম্বর ২৩ সোমবার, 09:00 pm -->
                                        <span> অনলাইন</span>
                                        <span class="space_space"> |</span>
                                        <span>
                                            {{ Carbon\Carbon::parse($item->date_time)->format('d F l, h:i a') }}
                                        </span>

                                    </div>
                                </div>

                                <div class="join_button">
                                    <button class="button_all" onclick="showSeminarModel({{ $item }})">
                                        <span class="btn_text"> জয়েন</span>
                                    </button>
                                </div>

                            </div>
                        @endforeach
                        <!-- date_line_area end -->
                        <!-- date_line_area start -->

                        <!--  date_line_area end -->

                        <!-- free_seminar_area_button start-->
                        <div class="free_seminar_button_area">
                            <a href="/seminar" class="button_all">
                                <span class="btn_icon"><i class="fa-solid fa-calendar-days"></i></span>
                                <span class="btn_text">All Seminar Schedules</span>
                            </a>
                        </div>
                        <!-- free_seminar_area_button end-->

                    </div>
                </div>
                <!-- left_area end -->

                <div class="col-xs-12 col-md-12 col-lg-6">
                    <div class="right_area">
                        <div class="free_seminar_image">
                            <img src="{{ asset('frontend') }}/assets/images/seminar.png"
                                alt="seminar_image tech park it">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="seminar_modal" class="modal fade modal-lg" tabindex="-1">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"><b>সেমিনারের জন্য রেজিস্ট্রেশন করুন</b></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-8 mx-auto">
                            <form class="mb-3" id="seminar_form" onsubmit="registerSeminar(event)">
                                <div class="mb-3">
                                    <label for="name" class="col-form-label">নামঃ </label>
                                    <input type="text" name="full_name" class="form-control" id="name">
                                </div>
                                <div class="mb-3">
                                    <label for="phone_number" class="col-form-label">মোবাইল নাম্বারঃ</label>
                                    <input type="number" name="phone_number" class="form-control"
                                        id="phone_number"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="col-form-label">ই-মেইলঃ</label>
                                    <input type="email" name="email" class="form-control" id="email"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="col-form-label">ঠিকানাঃ </label>
                                    <textarea name="address" class="form-control" id="address"></textarea>
                                </div>

                                <button type="submit" class="button_all me-2">সাবমিট</button>
                                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <script>
            var seminar_modal = new bootstrap.Modal(document.getElementById('seminar_modal'));


            function showSeminarModel(seminar) {
                window.seminar_id = seminar.id;
                document.getElementById('seminar_form').reset();
                seminar_modal.toggle();
                // console.log(seminar);
            }

            function registerSeminar(event) {
                event.preventDefault();
                let formData = new FormData(event.target);
                formData.append("seminar_id", window.seminar_id);
                // console.log('form submitting!');
                fetch("/seminar-registration", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: formData
                }).then(async res => {
                    let response = {}
                    response.status = res.status
                    response.data = await res.json();
                    return response;
                }).then(res => {
                    if (res.status === 422) {
                        res.data.err_message = 'Validation errors occurred. Please check the form.';
                        error_response(res.data)
                    }
                    if (res.status === 200) {
                        window.toaster("Registration for the seminar submitted!");
                        seminar_modal.toggle();
                        document.getElementById('seminar_form').reset();
                        // location.href = "/order-complete";
                    }
                })
            }
        </script>
    </div>
</div>
