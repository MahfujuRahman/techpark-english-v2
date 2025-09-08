@extends('frontend.layouts.layout')


@section('contents')
    <div class="container py-5 wishlist-page">
        <h2 class="mb-4">My Wishlist</h2>

        @if ($wishlist->count())
            <div class="row">
                @foreach ($wishlist as $item)
                    @php $course = $item->course; @endphp
                    @if ($course)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <a href="{{ route('course_details', $course->slug) }}">
                                    <img src="{{ assetHelper(optional($course)->image) }}" class="card-img-top"
                                        alt="{{ $course->title }}" loading="lazy">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <a href="{{ route('course_details', $course->slug) }}">{{ $course->title }}</a>
                                    </h5>

                                    <p class="card-text text-muted mb-3">
                                        {!! \Illuminate\Support\Str::limit(strip_tags($course->what_is_this_course ?? ''), 120) !!}
                                    </p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <a href="{{ route('course_details', $course->slug) }}"
                                            class="btn btn-primary btn-sm">
                                            View Course
                                        </a>

                                        <form action="{{ route('wishlist.remove', $course->id) }}" method="POST"
                                            class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $wishlist->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <h4 class="mb-3">Your wishlist is empty</h4>
                <a href="{{ route('courses') }}" class="btn btn-primary">Browse Courses</a>
            </div>
        @endif
    </div>
@endsection
