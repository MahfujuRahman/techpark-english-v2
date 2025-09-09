@php
    $meta = [
        'seo' => [
            'title' => 'seminar',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)
@section('contents')
    <!-- free_seminar_area_start -->
    <section class="free_seminar_area free_seminar_area_copy">
        <div class="container">
            <div class="free_seminar_area_content free_seminar_area_content_copy">
                <!-- <div class="row"> -->
                <!-- left_area start -->
                <!-- <div class="col-"> -->
                <div class="left_area left_area_copy">
                    <!--free_seminar_area title start -->
                    <div class="free_seminar_area_title">
                        <h2 class="area_title">
                            ফ্রি সেমিনারের সময়সূচি
                        </h2>
                    </div>
                    <!-- free_seminar_area title end -->

                    <!-- free_seminar_area sub_title start -->
                    <div class="free_seminar_area_sub_title">
                        <span class="sub_title">
                            আপনার ক্যারিয়ার কোন সেক্টরে গড়ে তুলবেন, সিদ্ধান্ত নিতে পারছেন না? আমাদের ফ্রি সেমিনারে জয়েন
                            করুন। বিষয়ভিত্তিক এই সেমিনারগুলোতে প্রতিটি কোর্সের সম্ভাবনা সম্পর্কে জানতে পারবেন। তাছাড়া
                            সেমিনারে উপস্থিত এক্সপার্ট কাউন্সেলরের সাথে কথা বলে আপনি সহজেই উপযুক্ত কোর্স বেছে নেওয়ার
                            সিদ্ধান্ত নিতে পারেন।
                        </span>
                    </div>
                    <!-- free_seminar_area sub_title end -->

                    <!-- date_line_area start -->
                    @foreach ($seminars as $item)
                    <div class="date_line_area date_line_area_copy">

                        @php
                        $date1 = \Carbon\Carbon::now('Asia/Dhaka'); 
                        $date2 = \Carbon\Carbon::parse($item->date_time);

                        $diff = $date1->diffInDays($date2);
               
                       @endphp

                        <div class="date date_copy">
                            <span class="date_number date_number_copy">{{$diff}}</span>
                            <span class="date_text">দিন বাকী</span>
                        </div>

                        <div class="data_science data_science_copy">
                            <span class="data_science_text_title data_science_text_title_copy">{{$item->title}}</span>
                            <div class="data_science_text_sub_title data_science_text_sub_title_copy">
                                <!-- অনলাইন | ১৯ ডিসেম্বর ২৩ সোমবার, 09:00 pm -->
                                <span> অনলাইন</span>
                                <span class="space_space"> |</span>
                                <span>{{\Carbon\Carbon::parse($item->date_time)->format('d F Y h:m A')}}</span>

                            </div>
                        </div>

                
                        <div class="join_button d-flex gap-3">
                            <button type="button" class="button_all p-0 success_video_area" onclick="showVideo(`{{$item->promo_video}}`)">
                                <div class="promo-iframe">                                                                                    
                                    <img src="/{{ $item->poster }}" alt="">
                                </div>
                            </button>
                            <a href="{{ route('seminar.details', $item->id) }}" class="button_all">
                                <span class="btn_text"> বিস্তারিত</span>
                            </a>
                            <button class="button_all" onclick="showSeminarModel({{ $item }})">
                                <span class="btn_text"> জয়েন</span>
                            </button>
                        </div>


                    </div>
                    @endforeach 
                    
                    <!--  date_line_area end -->             

                </div>
                <!-- </div> -->
                <!-- left_area end -->

                <!-- </div> -->
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

        <div id="story_modal" class="modal fade modal-lg" tabindex="-1">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            <b>Seminar Details</b>
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                </div>

            </div>
        </div>
</section>
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
    <!-- free_seminar_area_end -->

@endsection
