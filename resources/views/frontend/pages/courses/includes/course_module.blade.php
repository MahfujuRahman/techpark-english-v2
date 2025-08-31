<!-- class module start -->
<div class="class_module">
    <div class="class_module_head">
        <div class="class_module_title">ক্লাস মডিউল</div>
        {{-- <ul class="class_module_content">
            @foreach ($data->course_module_at_a_glance()->get() as $key => $item)
                <li>{{ $item->title }}</li>
                @if ($key < $data->course_module_at_a_glance()->count() - 1)
                    ।
                @endif
            @endforeach
        </ul> --}}
    </div>

    <div class="class_module_details">

        @foreach ($data->modules()->orderBy('module_no', 'asc')->get() as $item)
            <ul class="class_module_features">
                <li class="active">
                    <div class="class_module_title">
                        <div class="class_module_title_and_number">
                            <div class="class_module_number">
                                মডিউল <span class="number"> {{ $item->module_no }} </span>
                            </div>
                            <div class="class_module_title_details">
                                <div class="title">{{ $item->title }}</div>
                                <ul class="details">
                                    <li>
                                        {{ $item->classes()->where('type', 'live')->count() }}
                                        টি লাইভ ক্লাস
                                    </li>

                                    <li>
                                        {{ $item->classes()->where('type', 'recorded')->count() }}
                                        টি রেকর্ডেড ক্লাস
                                    </li>
                                    ।
                                    <li>
                                        {{ $item->quizes()->count() }}
                                        টি কুইজ
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="class_module_acordion_icon">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="class_module_feature_content">
                        <ul>
                            @foreach ($item->classes as $class)
                                {{-- @dd($class)     --}}
                                <li>
                                    <div class="class_module_class_no">ক্লাস {{ $class->class_no }}
                                    </div>
                                    <div class="live_class_and_topic">
                                        <div class="live_class_icon">
                                            <img src="/assets/images/about_page_image/class_module/podcasts.png"
                                                alt="" />
                                        </div>
                                        <div class="class_module_live_class">
                                            @if ($class->type == 'live')
                                                লাইভ ক্লাসঃ
                                            @else
                                                রেকর্ডেড ক্লাসঃ
                                            @endif
                                        </div>
                                        <div class="class_module_topic">{{ $class->title }}</div>
                                    </div>

                                    @if ($class->quizes && $class->quizes->count() > 0)
                                        @foreach ($class->quizes as $quiz)
                                            <div class="quiz_and_mcq">
                                                <div class="quiz_icon">
                                                    <i class="fa-solid fa-file-lines"></i>
                                                </div>
                                                <div class="quiz">কুইজঃ</div>
                                                <div class="mcq">
                                                    {{ $quiz->quiz->title ?? '' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            </ul>
        @endforeach

    </div>
</div>
<script>
    [
        ...document.querySelectorAll(".class_module_acordion_icon"),
    ].forEach((el) => {
        el.onclick = function(e) {
            e.currentTarget.parentNode.parentNode.classList.toggle(
                "active"
            );
            console.log(e.currentTarget);
        };
    });
</script>
<!-- /class module end -->
