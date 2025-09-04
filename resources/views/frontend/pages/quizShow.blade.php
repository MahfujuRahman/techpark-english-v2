@extends('frontend.layouts.layout')

@section('contents')
    <div class="container py-4">
        <div class="mb-3">
            <div class="card">
                <div class="card-body d-flex flex-column flex-sm-row justify-content-between align-items-baseline">
                    <h1 class="h3 mb-3 mb-sm-0">Quiz: {{ $quiz->title }}</h1>
                    <div>
                        <a href="{{ session('return_to_module', url()->previous()) }}"
                            onclick="event.preventDefault(); clearReturnToModule();" class="btn btn-primary">
                            Return to Course
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if (session('user_answers'))
                    <!-- Results Section -->
                    <div class="alert alert-info mb-4">
                        <h3 class="h5">Your Results:</h3>
                        <p class="mb-0">Score:
                            <strong>{{ session('score') }}</strong>/<span>{{ session('total_questions') }}</span>
                        </p>
                    </div>

                    <div class="row g-4">
                        @foreach ($topic as $index => $question)
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <p class="h5 mb-3">{{ $index + 1 }}. {{ $question->quizQuestion->title }}</p>
                                        <div>
                                            @if ($question->quizQuestion->is_multiple)
                                                <!-- Multiple Choice -->
                                                @foreach ($question->quizQuestion->options as $optionKey => $option)
                                                    <div class="form-check mb-2">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="answers[{{ $index }}][selected_options][]"
                                                            value="{{ $option->id }}"
                                                            id="q{{ $question->id }}o{{ $optionKey }}" disabled
                                                            {{ in_array($option->id, session('user_answers')[$index]['selected_options'] ?? []) ? 'checked' : '' }}>
                                                        <label
                                                            class="form-check-label {{ $option->is_correct ? 'text-success fw-bold' : '' }}"
                                                            for="q{{ $question->id }}o{{ $optionKey }}">
                                                            {{ $option->title }}
                                                        </label>
                                                        @if (in_array($option->id, session('user_answers')[$index]['selected_options'] ?? []) && !$option->is_correct)
                                                            <span class="text-danger ms-2">(Your Answer)</span>
                                                        @endif
                                                        @if ($option->is_correct)
                                                            <span class="text-success ms-2">(Correct Answer)</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @else
                                                <!-- Single Choice -->
                                                @foreach ($question->quizQuestion->options as $optionKey => $option)
                                                    <div class="form-check mb-2">
                                                        <input type="radio" class="form-check-input"
                                                            name="answers[{{ $index }}][selected_option]"
                                                            value="{{ $option->id }}"
                                                            id="q{{ $question->id }}o{{ $optionKey }}" disabled
                                                            {{ session('user_answers')[$index]['selected_option'] == $option->id ? 'checked' : '' }}>
                                                        <label
                                                            class="form-check-label {{ $option->is_correct ? 'text-success fw-bold' : '' }}"
                                                            for="q{{ $question->id }}o{{ $optionKey }}">
                                                            {{ $option->title }}
                                                        </label>
                                                        @if (session('user_answers')[$index]['selected_option'] == $option->id && !$option->is_correct)
                                                            <span class="text-danger ms-2">(Your Answer)</span>
                                                        @endif
                                                        @if ($option->is_correct)
                                                            <span class="text-success ms-2">(Correct Answer)</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Quiz Form -->
                    <form action="{{ route('quiz.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        <input type="hidden" name="course_id" value="{{ $courseId }}">
                        <input type="hidden" name="class_id" value="{{ $classId }}">
                        <div class="row g-4">
                            @foreach ($topic as $index => $question)
                                <div class="col-lg-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <p class="h5 mb-3">
                                                {{ $index + 1 }}. {{ $question->quizQuestion->title }}
                                                <small class="text-success">
                                                    {{ $question->quizQuestion->is_multiple ? '(Multiple Select)' : '(Single Select)' }}
                                                </small>
                                            </p>
                                            <div>
                                                @if ($question->quizQuestion->is_multiple)
                                                    <!-- Multiple Choice -->
                                                    @foreach ($question->quizQuestion->options as $optionKey => $option)
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="answers[{{ $index }}][selected_options][]"
                                                                value="{{ $option->id }}"
                                                                id="q{{ $question->id }}o{{ $optionKey }}">
                                                            <label class="form-check-label"
                                                                for="q{{ $question->id }}o{{ $optionKey }}">
                                                                {{ $option->title }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <!-- Single Choice -->
                                                    @foreach ($question->quizQuestion->options as $optionKey => $option)
                                                        <div class="form-check mb-2">
                                                            <input type="radio" class="form-check-input"
                                                                name="answers[{{ $index }}][selected_option]"
                                                                value="{{ $option->id }}"
                                                                id="q{{ $question->id }}o{{ $optionKey }}" required>
                                                            <label class="form-check-label"
                                                                for="q{{ $question->id }}o{{ $optionKey }}">
                                                                {{ $option->title }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <input type="hidden" name="answers[{{ $index }}][question_id]"
                                                value="{{ $question->id }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                Submit Quiz
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

<script>
    function clearReturnToModule() {
        fetch("{{ route('clear.return.to.module') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = "{{ session('return_to_module', url()->previous()) }}";
                }
            })
            .catch(error => console.error("Error clearing return_to_module session:", error));
    }
</script>
