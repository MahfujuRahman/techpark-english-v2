<!-- job possition and frelancing part start -->
<div class="job_possition_and_freelancing" id="course_feature">

    <div class="job_possition">
        <h2 class="job_possition_title">
            {{-- আপনি যেসব পজিশনে জব করতে পারবেন --}}
            কোর্সটি যেভাবে সাজানো হয়েছে
        </h2>
        <div class="job_categories">
            @foreach ($data->course_how_is_structured()->get() as $item)
                <div class="job_categorie">
                    <div class="job_categorie_icon">
                        <i class="fa-regular fa-circle-check"></i>
                    </div>
                    <div class="job_categorie_title">
                        {{ $item->title }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- / job possintion and freelanching part end -->
