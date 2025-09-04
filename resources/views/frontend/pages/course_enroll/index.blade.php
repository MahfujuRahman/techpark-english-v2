@php
    $meta = [
        'seo' => [
            'title' => "$course->title - Enroll",
            'image' => asset($course->image),
        ],
    ];
@endphp
@extends('frontend.layouts.layout', $meta)
@section('contents')
    <!-- Course Enrollment Section -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row g-4">
                <!-- Left: Course Details & Payment Info -->
                <div class="col-lg-6">

                    <!-- Payment Information Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header text-white p-3"
                            style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-mobile-alt me-2"></i>
                                Payment Information
                            </h5>
                            <small class="opacity-75">There are many payment methods available</small>
                        </div>
                        <div class="card-body p-0">
                            <div style="background-color: #fff;">
                                <div class="d-flex align-items-center justify-content-between p-3 border-bottom"
                                    style="transition: background-color 0.2s;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <span class="fw-bold fs-6">bKash</span>
                                            <small class="d-block text-muted">Personal</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div>
                                            <img src="{{ asset('frontend/assets/images/bkash.png') }}" alt="bKash"
                                                style="height:35px; width:auto;">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-3 border-bottom"
                                    style="transition: background-color 0.2s;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <span class="fw-bold fs-6">Rocket</span>
                                            <small class="d-block text-muted">Personal</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div>
                                            <img src="{{ asset('frontend/assets/images/roket.png') }}" alt="Rocket"
                                                style="height:35px; width:auto;">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-3 border-bottom"
                                    style="transition: background-color 0.2s;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <span class="fw-bold fs-6">Nagad</span>
                                            <small class="d-block text-muted">Personal</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div>
                                            <img src="{{ asset('frontend/assets/images/nogot.png') }}" alt="Nagad"
                                                style="height:35px; width:auto;">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between p-3"
                                    style="transition: background-color 0.2s;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <span class="fw-bold fs-6">CellFin</span>
                                            <small class="d-block text-muted">Personal</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div>
                                            <img src="{{ asset('frontend/assets/images/cellz_fin.png') }}" alt="CellFin"
                                                style="height:35px; width:auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info m-3 mb-0 border-0 rounded-3" style="background-color: #e3f2fd;">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-info-circle text-info mt-1"></i>
                                    <div>
                                        <small class="text-muted">
                                            Click the Pay Now button to send payment.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Payment Information Card End -->
                </div>
                <!-- Right: Payment form -->
                <div class="col-12 col-lg-6">
                    <div class="payment_form_area p-4 rounded-3 shadow-sm bg-white border">
                        <div class="form-header mb-4">
                            <h4 class="fw-bold text-dark mb-1">Complete Your Payment</h4>
                        </div>
                        <div
                            class="d-flex align-items-center mb-4 gap-4 flex-column flex-md-row bg-light rounded-4 shadow-sm p-3 border border-2 border-primary-subtle">

                            <div class="flex-shrink-0 text-center">
                                <img src="{{ asset($course->image) }}" class="img-fluid rounded-2 shadow border "
                                    alt="{{ $course->title }}" style="max-width: 100px; background: #fff;">
                            </div>
                            <div class="flex-grow-1 text-center text-md-start">
                                <h4 class="fw-bold text-dark mb-1">{{ $course->title }}</h4>
                                <div
                                    class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                                    @php
                                        $course_controller = new App\Http\Controllers\Course\CourseController();
                                        $data = $course_controller->course_batch_details($course->id);
                                        $batch_info = $data['batch'] ?? null;
                                    @endphp

                                    @if ($batch_info->after_discount_price != null && $batch_info->after_discount_price > 0)
                                        <span
                                            class="text-decoration-line-through text-muted fs-6">৳{{ number_format($batch_info->course_price) }}</span>
                                        <span
                                            class="fw-bold text-success fs-5">৳{{ number_format($batch_info->after_discount_price) }}</span>
                                        <span class="badge bg-success-subtle text-success ms-2">Discount</span>
                                    @else
                                        <span
                                            class="fw-bold text-primary fs-5">৳{{ number_format($batch_info->course_price) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('course_enroll_submit', $course->slug) }}" method="POST">
                            @csrf
                            <div class="form_area">
                                <div class="pricing-details border rounded p-3 mb-3" style="background-color: #f8f9fa;">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted">Price :</span>
                                                <span class="fw-bold">৳
                                                    {{ number_format($batch_info->course_price) }}</span>
                                            </div>
                                        </div>
                                        @if ($batch_info->after_discount_price != null && $batch_info->after_discount_price > 0)
                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">Discount :</span>
                                                    <span class="text-success fw-bold">- ৳
                                                        {{ number_format($batch_info->course_price - $batch_info->after_discount_price) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <hr class="my-2">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold fs-5">Total :</span>
                                                <span class="fw-bold fs-4 text-primary">৳
                                                    {{ number_format($batch_info->after_discount_price ? $batch_info->after_discount_price : $batch_info->course_price) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                                <i class="fas fa-credit-card me-2"></i>
                                Pay Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END: course_and_payment_area_content row -->
        </div>
        </div>
    </section>
    <!-- course_and_payment_area end-->

    <!-- Bank Logos Section -->
    <section class="py-4" style="background-color: #ffffff;">
        <div class="container">
            <div class="text-center mb-4">
                <h5 class="fw-bold text-dark">You can pay by</h5>
            </div>
            <div class="row justify-content-center">
                <div class="col-auto">
                    <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                        <div class="text-center">
                            <img src="{{ asset('frontend/assets/images/bkash.png') }}" alt="bKash" class="img-fluid"
                                style="height: 45px; filter: grayscale(20%); transition: all 0.3s; cursor: pointer;"
                                onmouseover="this.style.filter='grayscale(0%); transform: scale(1.1)'"
                                onmouseout="this.style.filter='grayscale(20%); transform: scale(1)'">
                        </div>
                        <div class="text-center">
                            <img src="{{ asset('frontend/assets/images/roket.png') }}" alt="Rocket" class="img-fluid"
                                style="height: 45px; filter: grayscale(20%); transition: all 0.3s; cursor: pointer;"
                                onmouseover="this.style.filter='grayscale(0%); transform: scale(1.1)'"
                                onmouseout="this.style.filter='grayscale(20%); transform: scale(1)'">
                        </div>
                        <div class="text-center">
                            <img src="{{ asset('frontend/assets/images/nogot.png') }}" alt="Nagad" class="img-fluid"
                                style="height: 45px; filter: grayscale(20%); transition: all 0.3s; cursor: pointer;"
                                onmouseover="this.style.filter='grayscale(0%); transform: scale(1.1)'"
                                onmouseout="this.style.filter='grayscale(20%); transform: scale(1)'">
                        </div>
                        <div class="text-center">
                            <img src="{{ asset('frontend/assets/images/cellz_fin.png') }}" alt="CellFin"
                                class="img-fluid"
                                style="height: 45px; filter: grayscale(20%); transition: all 0.3s; cursor: pointer;"
                                onmouseover="this.style.filter='grayscale(0%); transform: scale(1.1)'"
                                onmouseout="this.style.filter='grayscale(20%); transform: scale(1)'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Bank Logos Section End -->

    <!-- contact area end -->
@endsection
