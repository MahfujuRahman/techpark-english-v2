//app layout
import Layout from "../Layouts/Layout.vue";
//Dashboard
import Dashboard from "../Management/Dashboard/Dashboard.vue";
//SettingsRoutes
import SettingsRoutes from "../Management/Settings/setup/routes.js";
//UserRoutes
import UserRoutes from "../Management/UserManagement/User/setup/routes.js";
//routes
import TermConditionPolicyRoutes from '../../../GlobalManagement/WebsiteManagement/TermConditionPolicy/setup/routes.js';
import CookiePolicyRoutes from '../../../GlobalManagement/WebsiteManagement/CookiePolicy/setup/routes.js';
import RefundPolicyRoutes from '../../../GlobalManagement/WebsiteManagement/RefundPolicy/setup/routes.js';
import PrivacyPolicyRoutes from '../../../GlobalManagement/WebsiteManagement/PrivacyPolicy/setup/routes.js';
import SubscriberRoutes from '../../../GlobalManagement/CommunicationManagement/Subscriber/setup/routes.js';
import FaqRoutes from '../../../GlobalManagement/WebsiteManagement/Faq/setup/routes.js';
import OurTeamRoutes from '../../../GlobalManagement/WebsiteManagement/OurTeam/setup/routes.js';
import CourseInstructorsRoutes from '../../../GlobalManagement/CourseManagement/CourseInstructors/setup/routes.js';
import CourseCategoryRoutes from '../../../GlobalManagement/CourseManagement/CourseCategory/setup/routes.js';
import WebsiteBannerRoutes from '../../../GlobalManagement/WebsiteManagement/WebsiteBanner/setup/routes.js';
import ContactMessageRoutes from '../../../GlobalManagement/CommunicationManagement/ContactMessage/setup/routes.js';
import SeminerReviewsRoutes from '../../../GlobalManagement/SeminerManagement/SeminerReviews/setup/routes.js';
import SeminerSubscribersRoutes from '../../../GlobalManagement/SeminerManagement/SeminerSubscribers/setup/routes.js';
import SeminerParticipantRoutes from '../../../GlobalManagement/SeminerManagement/SeminerParticipant/setup/routes.js';
import SeminerRoutes from '../../../GlobalManagement/SeminerManagement/Seminer/setup/routes.js';
import EmailConfiguresRoutes from '../../../GlobalManagement/EmailConfigures/setup/routes.js';
import PaymentGatewaysRoutes from '../../../GlobalManagement/PaymentGateways/setup/routes.js';
import EnrollInformationRoutes from '../../../GlobalManagement/EnrollInformation/setup/routes.js';
import GalleryRoutes from '../../../GlobalManagement/GalleryManagement/Gallery/setup/routes.js';
import GalleryCategoryRoutes from '../../../GlobalManagement/GalleryManagement/GalleryCategory/setup/routes.js';
import BlogTagRoutes from '../../../GlobalManagement/BlogManagement/BlogTag/setup/routes.js';
import BlogWriterRoutes from '../../../GlobalManagement/BlogManagement/BlogWriter/setup/routes.js';
import BlogRoutes from '../../../GlobalManagement/BlogManagement/Blog/setup/routes.js';
import BlogCategoryRoutes from '../../../GlobalManagement/BlogManagement/BlogCategory/setup/routes.js';
import OurVisionRoutes from '../../../GlobalManagement/WebsiteManagement/OurVision/setup/routes.js';
import OurMissionRoutes from '../../../GlobalManagement/WebsiteManagement/OurMission/setup/routes.js';
import OurMotoRoutes from '../../../GlobalManagement/WebsiteManagement/OurMoto/setup/routes.js';
import WebsiteBrandRoutes from '../../../GlobalManagement/WebsiteManagement/WebsiteBrand/setup/routes.js';
import AboutUsRoutes from '../../../GlobalManagement/WebsiteManagement/AboutUs/setup/routes.js';
import OurTrainerRoutes from '../../../GlobalManagement/WebsiteManagement/OurTrainer/setup/routes.js';
import OurSpecialityRoutes from '../../../GlobalManagement/WebsiteManagement/OurSpeciality/setup/routes.js';
import SuccssStoriesRoutes from '../../../GlobalManagement/WebsiteManagement/SuccssStories/setup/routes.js';
import SubBannerRoutes from '../../../GlobalManagement/WebsiteManagement/SubBanner/setup/routes.js';
import QuizSubmissionResultRoutes from '../../../GlobalManagement/QuizManagement/QuizSubmissionResult/setup/routes.js';
import QuizRoutes from '../../../GlobalManagement/QuizManagement/Quiz/setup/routes.js';
import QuizQuestionRoutes from '../../../GlobalManagement/QuizManagement/QuizQuestion/setup/routes.js';
import QuizQuestionTopicRoutes from '../../../GlobalManagement/QuizManagement/QuizQuestionTopic/setup/routes.js';
import CourseRoutes from '../../../GlobalManagement/CourseManagement/Course/setup/routes.js';
import CourseDetailsManagementRoutes from '../Management/CourseManagement/CourseDetailsManagement/Routes/routes.js';
import CourseBatchRoutes from '../../../GlobalManagement/CourseManagement/CourseBatch/setup/routes.js';

const routes = {
  path: "",
  component: Layout,
  children: [
    {
      path: "dashboard",
      component: Dashboard,
      name: "adminDashboard",
    },
    //management routes
        TermConditionPolicyRoutes,
        CookiePolicyRoutes,
        RefundPolicyRoutes,
        PrivacyPolicyRoutes,
        SubscriberRoutes,
        FaqRoutes,
        OurTeamRoutes,
        CourseInstructorsRoutes,
        CourseCategoryRoutes,
        WebsiteBannerRoutes,
        ContactMessageRoutes,
        SeminerReviewsRoutes,
        SeminerSubscribersRoutes,
        SeminerParticipantRoutes,
        SeminerRoutes,
        EmailConfiguresRoutes,
        PaymentGatewaysRoutes,
        EnrollInformationRoutes,
        GalleryRoutes,
        GalleryCategoryRoutes,
        BlogTagRoutes,
        BlogWriterRoutes,
        BlogRoutes,
        BlogCategoryRoutes,
        OurVisionRoutes,
        OurMissionRoutes,
        OurMotoRoutes,
        WebsiteBrandRoutes,
        AboutUsRoutes,
        OurTrainerRoutes,
        OurSpecialityRoutes,
        SuccssStoriesRoutes,
        SubBannerRoutes,
        QuizSubmissionResultRoutes,
        QuizRoutes,
        QuizQuestionRoutes,
        QuizQuestionTopicRoutes,
        CourseBatchRoutes,
        CourseRoutes,
       
        // Course Details Management (New Pinia-based system)
        ...CourseDetailsManagementRoutes,

      

    //user routes
    UserRoutes,
    //settings
    SettingsRoutes,
  ],
};

export default routes;
