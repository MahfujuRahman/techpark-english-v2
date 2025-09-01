<!-- course feature start -->
<div class="course_feature_part">
    <ul class="course_features">
        <li class="">
            <div class="feature_title">
                <div class="feature_name">
                    What you will learn in this course?
                </div>
                <div class="feature_acordion_icon">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </div>
            <div class="feature_content">
                <ul style="column-count: unset;width:unset;">
                    @foreach ($data->course_you_will_learn()->where('status', 'active')->get() as $item)
                        <li>
                            <div class="cheak_icon">
                                <i class="fa-regular fa-circle-check"></i>
                            </div>
                            <div class="feature_content_text">
                                {{ $item->title }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>
        <li class="">
            <div class="feature_title">
                <div class="feature_name">Whom is this course for?</div>
                <div class="feature_acordion_icon">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </div>
            <div class="feature_content">
                <ul style="column-count: unset;width:unset;">
                    @foreach ($data->course_for_whom()->where('status', 'active')->get() as $item)
                        <li>
                            <div class="cheak_icon">
                                <i class="fa-regular fa-circle-check"></i>
                            </div>
                            <div class="feature_content_text">
                                {{ $item->title }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>
        <li class="">
            <div class="feature_title">
                <div class="feature_name">
                    Why you learn from us?
                </div>
                <div class="feature_acordion_icon">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </div>
            <div class="feature_content">
                <ul style="column-count: unset;width:unset;">
                    @foreach ($data->course_why_you_learn_from_us()->where('status', 'active')->get() as $item)
                        <li>
                            <div class="cheak_icon">
                                <i class="fa-regular fa-circle-check"></i>
                            </div>
                            <div class="feature_content_text">
                                {{ $item->title }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>
        <li class="">
            <div class="feature_title">
                <div class="feature_name">
                    What you will get in this course?
                </div>
                <div class="feature_acordion_icon">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </div>
            <div class="feature_content">
                <ul style="column-count: unset;width:unset;">
                    @foreach ($data->course_what_you_will_get()->where('status', 'active')->get() as $item)
                        <li class="">
                            <div class="cheak_icon">
                                <i class="fa-regular fa-circle-check"></i>
                            </div>
                            <div class="feature_content_text">
                                {{ $item->title }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>
    </ul>
</div>
<script>
    [...document.querySelectorAll(".feature_acordion_icon")].forEach(
        (el) => {
            el.onclick = function(e) {
                e.currentTarget.parentNode.parentNode.classList.toggle(
                    "active"
                );
                // console.log(e.currentTarget);
            };
        }
    );
</script>
<!-- / course feature end -->
