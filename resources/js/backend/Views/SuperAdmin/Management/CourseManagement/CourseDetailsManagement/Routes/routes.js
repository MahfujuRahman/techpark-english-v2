// Course Details Management Routes
import AllCourse from '../Views/AllCourse.vue';
import CreateCourse from '../Views/CreateCourse.vue';
import CourseDetailsLayout from '../Views/Layouts/CourseDetailsLayout.vue';
import CourseManagementLayout from '../Views/Layouts/CourseManagementLayout.vue';

// Course Details Pages
import CourseOverview from '../Views/Pages/CourseOverview.vue';
import CourseBanner from '../Views/Pages/CourseBanner.vue';
import CoursePreview from '../Views/Pages/CoursePreview.vue';

// Course Batch
import CourseBatchLayout from '../Views/Pages/CourseBatch/Layout.vue';
import CourseBatchAll from '../Views/Pages/CourseBatch/All.vue';
import CourseBatchForm from '../Views/Pages/CourseBatch/Form.vue';
import CourseBatchDetails from '../Views/Pages/CourseBatch/Details.vue';

// Course What Will Learn
import CourseWhatLearnLayout from '../Views/Pages/CourseWhatLearn/Layout.vue';
import CourseWhatLearnAll from '../Views/Pages/CourseWhatLearn/All.vue';
import CourseWhatLearnForm from '../Views/Pages/CourseWhatLearn/Form.vue';
import CourseWhatLearnDetails from '../Views/Pages/CourseWhatLearn/Details.vue';

// Course How Is Structured
import CourseHowIsStructuredLayout from '../Views/Pages/CourseHowIsStructured/Layout.vue';
import CourseHowIsStructuredAll from '../Views/Pages/CourseHowIsStructured/All.vue';
import CourseHowIsStructuredForm from '../Views/Pages/CourseHowIsStructured/Form.vue';
import CourseHowIsStructuredDetails from '../Views/Pages/CourseHowIsStructured/Details.vue';

// Course For Whom
import CourseForWhomLayout from '../Views/Pages/CourseForWhom/Layout.vue';
import CourseForWhomAll from '../Views/Pages/CourseForWhom/All.vue';
import CourseForWhomForm from '../Views/Pages/CourseForWhom/Form.vue';
import CourseForWhomDetails from '../Views/Pages/CourseForWhom/Details.vue';

// Course Why Learn
import CourseWhyLearnLayout from '../Views/Pages/CourseWhyLearn/Layout.vue';
import CourseWhyLearnAll from '../Views/Pages/CourseWhyLearn/All.vue';
import CourseWhyLearnForm from '../Views/Pages/CourseWhyLearn/Form.vue';
import CourseWhyLearnDetails from '../Views/Pages/CourseWhyLearn/Details.vue';

// Course Trainer
import CourseTrainerLayout from '../Views/Pages/CourseTrainer/Layout.vue';
import CourseTrainerCreate from '../Views/Pages/CourseTrainer/Create.vue';

// Course Milestones
import CourseMilestoneLayout from '../Views/Pages/CourseMilestone/Layout.vue';
import CourseMilestoneAll from '../Views/Pages/CourseMilestone/All.vue';

// Course Module Classes
import CourseClassLayout from '../Views/Pages/CourseClass/Layout.vue';
import CourseClassAll from '../Views/Pages/CourseClass/All.vue';

// Course Modules (Individual Module Management)
import CourseModulesAll from '../Views/Pages/CourseModules/All.vue';

// Course FAQ
import CourseFaqLayout from '../Views/Pages/CourseFaq/Layout.vue';
import CourseFaqAll from '../Views/Pages/CourseFaq/All.vue';
import CourseFaqForm from '../Views/Pages/CourseFaq/Form.vue';
import CourseFaqDetails from '../Views/Pages/CourseFaq/Details.vue';

// Course Module
import CourseModuleLayout from '../Views/Pages/CourseModuleSystem/Layout.vue';
import CourseModuleAll from '../Views/Pages/CourseModuleSystem/All.vue';
import CourseModuleCreate from '../Views/Pages/CourseModuleSystem/Create.vue';
import CourseModuleForm from '../Views/Pages/CourseModuleSystem/Form.vue';
import CourseModuleCSV from '../Views/Pages/CourseModuleSystem/CSV.vue';
import CourseAtGlance from '../Views/Pages/CourseModuleSystem/AtGlance.vue';
import CourseClassQuiz from '../Views/Pages/CourseModuleSystem/CourseClassQuiz.vue';

// Course FAQ
import CourseQuizLayout from '../Views/Pages/CourseQuiz/Layout.vue';
import CourseQuizAll from '../Views/Pages/CourseQuiz/All.vue';
import CourseQuizForm from '../Views/Pages/CourseQuiz/Form.vue';
import CourseQuizDetails from '../Views/Pages/CourseQuiz/Details.vue';

//Course What will you get
import CourseWhatGetLayout from '../Views/Pages/CourseWhatGet/Layout.vue';
import CourseWhatGetAll from '../Views/Pages/CourseWhatGet/All.vue';
import CourseWhatGetForm from '../Views/Pages/CourseWhatGet/Form.vue';
import CourseWhatGetDetails from '../Views/Pages/CourseWhatGet/Details.vue';

//Course Essential Requirements
import CourseEssentialRequirementsLayout from '../Views/Pages/CourseEssentialRequirements/Layout.vue';
import CourseEssentialRequirementsAll from '../Views/Pages/CourseEssentialRequirements/All.vue';
import CourseEssentialRequirementsForm from '../Views/Pages/CourseEssentialRequirements/Form.vue';
import CourseEssentialRequirementsDetails from '../Views/Pages/CourseEssentialRequirements/Details.vue';

// Course Routines
import CourseRoutines from '../Views/Pages/CourseRoutine/CourseRoutine.vue';

export default [
    {
        path: '/course-details-management',
        name: 'CourseDetailsManagement',
        component: CourseManagementLayout,
        meta: {
            title: 'Course Details Management',
            permissions: ["course_details_management"],
        },
        children: [
            {
                path: '',
                redirect: { name: 'AllCourses' }
            },
            {
                path: 'all-courses',
                name: 'AllCourses',
                component: AllCourse,
            },
            {
                path: 'create-course',
                name: 'CreateCourse', 
                component: CreateCourse,
            },
            {
                path: 'edit-course/:id',
                name: 'EditCourse', 
                component: CreateCourse,
                props: true,
            },
            {
                path: 'course/:id',
                component: CourseDetailsLayout,
                children: [
                    {
                        path: '',
                        redirect: 'overview'
                    },
                    {
                        path: 'overview',
                        name: 'CourseDetails',
                        component: CourseOverview,
                    },
                    {
                        path: 'course-overview',
                        name: 'CourseOverview',
                        component: CourseOverview,
                    },
                    {
                        path: 'course-banner',
                        name: 'CourseBanner',
                        component: CourseBanner,
                    },
                    {
                        path: 'course-preview',
                        name: 'CoursePreview',
                        component: CoursePreview,
                    },

                    // Course Batch Routes
                    {
                        path: 'batch',
                        component: CourseBatchLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseBatch',
                                component: CourseBatchAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseBatchAll',
                                component: CourseBatchAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseBatchCreate',
                                component: CourseBatchForm,
                            },
                            {
                                path: 'edit/:batch_id',
                                name: 'CourseBatchEdit',
                                component: CourseBatchForm,
                            },
                            {
                                path: 'details/:batch_id',
                                name: 'CourseBatchDetails',
                                component: CourseBatchDetails,
                            },
                        ]
                    },

                    // Course What Will Learn Routes
                    {
                        path: 'what-learn',
                        component: CourseWhatLearnLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseWhatLearn',
                                component: CourseWhatLearnAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseWhatLearnAll',
                                component: CourseWhatLearnAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseWhatLearnCreate',
                                component: CourseWhatLearnForm,
                            },
                            {
                                path: ':slug/edit',
                                name: 'CourseWhatLearnEdit',
                                component: CourseWhatLearnForm,
                            },
                            {
                                path: ':slug/details',
                                name: 'CourseWhatLearnDetails',
                                component: CourseWhatLearnDetails,
                            },
                        ]
                    },

                    // Course How Is Structured Routes
                    {
                        path: 'how-structured',
                        component: CourseHowIsStructuredLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseHowIsStructured',
                                component: CourseHowIsStructuredAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseHowIsStructuredAll',
                                component: CourseHowIsStructuredAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseHowIsStructuredCreate',
                                component: CourseHowIsStructuredForm,
                            },
                            {
                                path: ':slug/edit',
                                name: 'CourseHowIsStructuredEdit',
                                component: CourseHowIsStructuredForm,
                            },
                            {
                                path: ':slug/details',
                                name: 'CourseHowIsStructuredDetails',
                                component: CourseHowIsStructuredDetails,
                            },
                        ]
                    },

                    // Course For Whom Routes
                    {
                        path: 'for-whom',
                        component: CourseForWhomLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseForWhom',
                                component: CourseForWhomAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseForWhomAll',
                                component: CourseForWhomAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseForWhomCreate',
                                component: CourseForWhomForm,
                            },
                            {
                                path: ':slug/edit',
                                name: 'CourseForWhomEdit',
                                component: CourseForWhomForm,
                            },
                            {
                                path: ':slug/details',
                                name: 'CourseForWhomDetails',
                                component: CourseForWhomDetails,
                            },
                        ]
                    },

                    // Course Why Learn Routes
                    {
                        path: 'why-learn',
                        component: CourseWhyLearnLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseWhyLearn',
                                component: CourseWhyLearnAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseWhyLearnAll',
                                component: CourseWhyLearnAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseWhyLearnCreate',
                                component: CourseWhyLearnForm,
                            },
                            {
                                path: ':slug/edit',
                                name: 'CourseWhyLearnEdit',
                                component: CourseWhyLearnForm,
                            },
                            {
                                path: ':slug/details',
                                name: 'CourseWhyLearnDetails',
                                component: CourseWhyLearnDetails,
                            },
                        ]
                    },
                    // Course What You Will Get Routes
                    {
                        path: 'what-you-will-get',
                        component: CourseWhatGetLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseWhatGet',
                                component: CourseWhatGetAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseWhatGetAll',
                                component: CourseWhatGetAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseWhatGetCreate',
                                component: CourseWhatGetForm,
                            },
                            {
                                path: ':slug/edit',
                                name: 'CourseWhatGetEdit',
                                component: CourseWhatGetForm,
                            },
                            {
                                path: ':slug/details',
                                name: 'CourseWhatGetDetails',
                                component: CourseWhatGetDetails,
                            },
                        ]
                    },
                    // Course Course Essential Requirements
                    {
                        path: 'course-essential-requirements',
                        component: CourseEssentialRequirementsLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseEssentialRequirements',
                                component: CourseEssentialRequirementsAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseEssentialRequirementsAll',
                                component: CourseEssentialRequirementsAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseEssentialRequirementsCreate',
                                component: CourseEssentialRequirementsForm,
                            },
                            {
                                path: ':slug/edit',
                                name: 'CourseEssentialRequirementsEdit',
                                component: CourseEssentialRequirementsForm,
                            },
                            {
                                path: ':slug/details',
                                name: 'CourseEssentialRequirementsDetails',
                                component: CourseEssentialRequirementsDetails,
                            },
                        ]
                    },

                    // Course Trainer Routes
                    {
                        path: 'trainer',
                        component: CourseTrainerLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseTrainer',
                                component: CourseTrainerCreate,
                            },
                            {
                                path: 'create',
                                name: 'CourseTrainerCreate',
                                component: CourseTrainerCreate,
                            },
                        ]
                    },

                    // Course Milestone Routes
                    {
                        path: 'milestone',
                        component: CourseMilestoneLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseMilestone',
                                component: CourseMilestoneAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseMilestoneAll',
                                component: CourseMilestoneAll,
                            },
                        ]
                    },

                    // Course Class Routes
                    {
                        path: 'class',
                        component: CourseClassLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseClass',
                                component: CourseClassAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseClassAll',
                                component: CourseClassAll,
                            },
                        ]
                    },

                    // Course FAQ Routes
                    {
                        path: 'faq',
                        component: CourseFaqLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseFaq',
                                component: CourseFaqAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseFaqAll',
                                component: CourseFaqAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseFaqCreate',
                                component: CourseFaqForm,
                            },
                            {
                                path: ':slug/edit',
                                name: 'CourseFaqEdit',
                                component: CourseFaqForm,
                            },
                            {
                                path: ':slug/details',
                                name: 'CourseFaqDetails',
                                component: CourseFaqDetails,
                            },
                        ]
                    },

                    // Course Module Routes
                    {
                        path: 'module',
                        component: CourseModuleLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseModule',
                                component: CourseModuleAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseModuleAll',
                                component: CourseModuleAll,
                            },
                            {
                                path: 'modules-management',
                                name: 'CourseModulesAll',
                                component: CourseModulesAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseModuleCreate',
                                component: CourseModuleCreate,
                            },
                            {
                                path: 'csv-upload',
                                name: 'CourseModuleCSV',
                                component: CourseModuleCSV,
                            },
                            {
                                path: 'at-glance',
                                name: 'CourseAtGlance',
                                component: CourseAtGlance,
                            },
                        ]
                    },

                    // Course Class Quiz Routes
                    {
                        path: 'class-quiz',
                        component: CourseQuizLayout,
                        children: [
                            {
                                path: '',
                                name: 'CourseQuiz',
                                component: CourseQuizAll,
                            },
                            {
                                path: 'all',
                                name: 'CourseQuizAll',
                                component: CourseQuizAll,
                            },
                            {
                                path: 'create',
                                name: 'CourseQuizCreate',
                                component: CourseQuizForm,
                            },
                            {
                                path: ':slug/edit',
                                name: 'CourseQuizEdit',
                                component: CourseQuizForm,
                            },
                            {
                                path: ':slug/details',
                                name: 'CourseQuizDetails',
                                component: CourseQuizDetails,
                            },
                        ]
                    },

                    // Course Routine Routes
                    {
                        path: 'routine',
                        name: 'CourseRoutines',
                        component: CourseRoutines,
                    },
                ]
            },
        ]
    },
];
