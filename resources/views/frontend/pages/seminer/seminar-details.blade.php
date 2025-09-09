@php
    $meta = [
        'seo' => [
            'title' => 'seminar-details',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)
@section('contents')
    <section class="science_and_technology_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="science_and_technology_content">
                        <!-- title_area start -->
                        <div class="title_area">
                            <div class="content_text">
                                <p class="text">{{ Str::title($seminar->title) ?? '' }}</p>
                            </div>
                        </div>
                        <!-- title_area end -->

                        <!-- title_image start -->
                        <div class="title_image">
                            <img src="/{{ $seminar->poster }}" alt="tech park it">
                        </div>
                        <div class="timer seminar-time" style="margin-top: 20px;">
                            <h3>
                                <strong>
                                    <li class="d-none">
                                        <div class="amount" data-years></div>
                                        <div class="title">Years</div>
                                    </li>
                                    <li>
                                        <div class="amount" data-days></div>
                                        <div class="title">days</div>
                                    </li>
                                    <li>
                                        <div class="amount" data-hours></div>
                                        <div class="title">hrs</div>
                                    </li>
                                    <li>
                                        <div class="amount" data-minutes></div>
                                        <div class="title">mins</div>
                                    </li>
                                    <li>
                                        <div class="amount" data-seconds></div>
                                        <div class="title">secs</div>
                                    </li>
                                </strong>
                            </h3>


                        </div>

                        <div class="" style="margin-top: 20px;">
                            {!! $seminar->description !!}

                        </div>


                        <!--advantages_of_technology section_area end -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <h3>Upcoming Seminars</h3>
                    @foreach ($seminars as $item)
                        <div class="date_line_area date_line_area_copy">

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="join_button d-flex gap-3 my-2">
                                        <div class="promo-iframe">
                                            <img src="/{{ $item->poster }}" alt="">
                                        </div>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    @php
                                        $date1 = \Carbon\Carbon::now();
                                        $date2 = \Carbon\Carbon::parse($item->date_time);
                                        $diff = $date1->diffInDays($date2);
                                    @endphp

                                    <div>
                                        <h4>{{ $item->title }}</h4>
                                    </div>

                                    <div class="date date_copy">
                                        <span class="date_number date_number_copy">{{ $diff }}</span>
                                        <span class="date_text">দিন বাকী</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach

                    <!-- Subscribe & Review widgets -->
                    <div class="mt-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Subscribe to this seminar</h5>
                                <form method="POST" action="{{ route('seminar.subscribe', $seminar->id) }}">
                                    @csrf
                                    <input type="hidden" name="seminar_id" value="{{ $seminar->id }}">
                                    <div class="mb-2">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Your email address" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Subscribe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title mb-3">Review this seminar</h5>

                    {{-- New review form --}}
                    <form method="POST" action="{{ route('seminar.review', $seminar->id) }}" id="new-review-form">
                        @csrf
                        <input type="hidden" name="seminar_id" value="{{ $seminar->id }}">

                        @guest
                            <div class="row gx-2 mb-2">
                                <div class="col-6">
                                    <input name="name" class="form-control" placeholder="Your name" required>
                                </div>
                                <div class="col-6">
                                    <input name="email" type="email" class="form-control" placeholder="Email" required>
                                </div>
                            </div>
                        @endguest

                        <div class="mb-2">
                            <label class="form-label small">Rating</label>
                            <select name="rating" class="form-select" required>
                                <option value="">Select rating</option>
                                <option value="5">5 - Excellent</option>
                                <option value="4">4 - Very good</option>
                                <option value="3">3 - Good</option>
                                <option value="2">2 - Fair</option>
                                <option value="1">1 - Poor</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small">Your review</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Share your thoughts"></textarea>
                        </div>

                        <button type="submit" class="btn btn-outline-primary w-100">Submit review</button>
                    </form>

                    <hr>
                    {{-- Show existing reviews --}}
                    <div id="reviews-list">
                        @foreach ($seminar->reviews ?? collect() as $review)
                            @include('frontend.pages.seminer.partials._review', ['review' => $review])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="/js/plugins/countdown.js"></script>
    <script>
        setTimeout(function() {
            timezz(document.querySelector('.timer'), {
                date: `{{ Carbon\Carbon::parse($seminar->date_time)->format('M d, Y H:i:s') }}`,
                pause: false,
                stopOnZero: true,
                beforeCreate() {},
                beforeUpdate() {},
                update(event) {},
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
        }, 300)
    </script>
@endsection

<style>
    .logo_area{
        height: unset!important;
    }
    .science_and_technology_content{
        width: unset!important;
    }
    .title_image{
        width: unset!important;
    }
</style>

<script>
    // Reply form toggle and wiring
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('reply-toggle')) {
            const id = e.target.getAttribute('data-id');
            const parent = document.getElementById('review-' + id);
            if (!parent) return;

            // find or create reply form container
            let replyForm = parent.querySelector('.reply-form');
            if (!replyForm) {
                replyForm = document.createElement('div');
                replyForm.className = 'reply-form mt-2';
                replyForm.innerHTML = `
                        <form method="POST" action="{{ route('seminar.review.reply', $seminar->id) }}" class="reply-subform">
                            @csrf
                            <input type="hidden" name="seminar_id" value="{{ $seminar->id }}">
                            <input type="hidden" name="parent_id" value="${id}">
                            @guest
                                <div class="mb-2"><input name="name" class="form-control" placeholder="Your name" required></div>
                                <div class="mb-2"><input name="email" type="email" class="form-control" placeholder="Email" required></div>
                            @endguest
                            <div class="mb-2"><textarea name="comment" class="form-control" rows="2" placeholder="Write a reply..." required></textarea></div>
                            <div><button class="btn btn-sm btn-primary" type="submit">Reply</button> <button type="button" class="btn btn-sm btn-link cancel-reply">Cancel</button></div>
                        </form>`;

                parent.appendChild(replyForm);
                // focus textarea
                const ta = replyForm.querySelector('textarea');
                if (ta) ta.focus();
            } else {
                // toggle visibility
                replyForm.remove();
            }
        }

        if (e.target && e.target.classList.contains('cancel-reply')) {
            const form = e.target.closest('.reply-form');
            if (form) form.remove();
        }
    });
</script>
