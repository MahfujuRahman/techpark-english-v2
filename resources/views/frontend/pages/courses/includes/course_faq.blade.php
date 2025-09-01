<!-- general question area start-->
@if (count($data->course_faqs) > 0)
    <div class="general_question_part">
        <div class="container">
            <div class="general_questions">
                <div class="general_question_details">
                    <div class="general_question_head">
                        <div class="general_question_heading_title">General Questions</div>
                        <div class="general_question_heading_brief">
                            You can search here if you have any questions.
                        </div>
                    </div>
                    <ul class="general_question_all" style="width: 600px;">
                        @foreach ($data->course_faqs as $faq)
                            <div class="general_question">
                                <li class="">
                                    <div class="general_question_title_and_icon">
                                        <div class="general_question_title">
                                            {{ $faq->title }}
                                        </div>
                                        <div class="general_question_acordion_icon">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    <div class="general_question_content">
                                        {{ $faq->description }}
                                    </div>
                                </li>
                            </div>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif


<script>
    [...document.querySelectorAll(".general_question_acordion_icon"), ].forEach((element) => {
        element.onclick = function(e) {
            e.currentTarget.parentNode.parentNode.classList.toggle(
                "active"
            );
            // console.log(e.currentTarget.parentNode.classList);
        };
    });
</script>
