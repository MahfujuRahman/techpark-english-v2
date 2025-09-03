<!-- class module start -->
<div class="class_module">
    <div class="class_module_details">
        @if (isset($data) && is_object($data) && isset($data->milestones) && count($data->milestones) > 0)
            @foreach ($data->milestones()->orderBy('milestone_no', 'asc')->get() as $milestone)
                <ul class="class_module_features">
                    <li class="milestone-item my-3">
                        <div class="class_module_title">
                            <div class="class_module_title_and_number">
                                <div class="class_module_number">
                                    Milestone <span class="number"> {{ $milestone->milestone_no ?? $loop->iteration }} </span>
                                </div>
                                <div class="class_module_title_details">
                                    <div class="title">{{ $milestone->title }}</div>
                
                                    <ul class="details">
                                        <li>
                                            {{ isset($milestone->modules) ? $milestone->modules()->where('status', 'active')->count() : 0 }}
                                            Modules
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="class_milestone_acordion_icon">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="milestone-content">
                            @foreach ($milestone->modules as $module)
                                <ul class="class_module_features mx-3">
                                    <li class="module-item">
                                        <div class="class_module_title">
                                            <div class="class_module_title_and_number">
                                                <div class="class_module_number">
                                                    Module <span class="number"> {{ $module->module_no }} </span>
                                                </div>
                                                <div class="class_module_title_details">
                                                    <div class="title">{{ $module->title }}</div>
                                                    <ul class="details">
                                                        <li>
                                                            {{ $module->classes()->where('type', 'live')->count() }}
                                                            Live Classes
                                                        </li>
                                                        ।
                                                        <li>
                                                            {{ $module->classes()->where('type', 'recorded')->count() }}
                                                            Recorded Classes
                                                        </li>
                                                        ।
                                                        <li>
                                                            {{ $module->quizes()->count() }}
                                                            Quizzes
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
                                                @foreach ($module->classes as $class)
                                                    <li>
                                                        <div class="class_module_class_no">Class {{ $class->class_no }}
                                                        </div>
                                                        <div class="live_class_and_topic">
                                                            <div class="live_class_icon">
                                                                @if ($class->type == 'live')
                                                                    <i class="fa-solid fa-video-camera"></i>
                                                                @else
                                                                    <i class="fa-solid fa-play-circle"></i>
                                                                @endif
                                                            </div>
                                                            <div class="class_module_live_class">
                                                                @if ($class->type == 'live')
                                                                    Live Class:
                                                                @else
                                                                    Recorded Class:
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
                                                                    <div class="quiz">Quiz:</div>
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
                    </li>
                </ul>
            @endforeach
        @endif



    </div>
</div>
<script>
    // Handle milestone accordion
    document.querySelectorAll(".class_milestone_acordion_icon").forEach((el) => {
        el.onclick = function(e) {
            e.preventDefault();
            const milestoneItem = e.currentTarget.closest('.milestone-item');
            if (!milestoneItem) return;

            const isExpanded = milestoneItem.classList.toggle('active');
            if (isExpanded) {
                // When opening a milestone, auto-open all child modules
                milestoneItem.querySelectorAll('.module-item').forEach((mod) => {
                    mod.classList.add('active');
                    const modContent = mod.querySelector('.class_module_feature_content');
                    if (modContent) modContent.style.display = 'block';
                    const modIcon = mod.querySelector('.class_module_acordion_icon i');
                    if (modIcon) modIcon.style.transform = 'rotate(180deg)';
                });
            } else {
                // If collapsing the milestone, ensure all descendant modules are collapsed and reset
                milestoneItem.querySelectorAll('.module-item.active').forEach((mod) => {
                    mod.classList.remove('active');
                    const modContent = mod.querySelector('.class_module_feature_content');
                    if (modContent) modContent.style.display = 'none';
                    const modIcon = mod.querySelector('.class_module_acordion_icon i');
                    if (modIcon) modIcon.style.transform = '';
                });
            }
        };
    });

    // Handle module accordion
    document.querySelectorAll(".class_module_acordion_icon").forEach((el) => {
        el.onclick = function(e) {
            e.preventDefault();
            const moduleItem = e.currentTarget.closest('.module-item');
            if (!moduleItem) return;

            const content = moduleItem.querySelector('.class_module_feature_content');
            const icon = e.currentTarget.querySelector('i');
            const isExpanded = moduleItem.classList.toggle('active');

            // Explicitly toggle display to avoid CSS specificity conflicts
            if (content) {
                content.style.display = isExpanded ? 'block' : 'none';
            }

            // Rotate icon for visual feedback
            if (icon) {
                icon.style.transform = isExpanded ? 'rotate(180deg)' : '';
            }
        };
    });
</script>
<!-- /class module end -->
