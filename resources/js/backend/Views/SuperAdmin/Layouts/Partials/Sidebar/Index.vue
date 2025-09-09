<template>
  <!--Start sidebar-wrapper-->
  <div id="sidebar-wrapper">
    <div class="brand-logo">
      <router-link :to="{ name: `adminDashboard` }" class="d-flex align-items-center">
        <img :src="`${get_setting_value('image') ?? 'avatar.png'} `" class="logo-icon" alt="logo icon" />
        <h5 class="logo-text">Super Admin Panel</h5>
      </router-link>
      <div class="close-btn">
        <i class="zmdi zmdi-close" @click="toggle_menu"></i>
      </div>
    </div>

    <div class="text-center mt-3">
      <img class="rounded-circle p-1" height="70" width="70" :src="`${auth_info.image ?? 'avatar.png'}`" alt="" />
      <p class="mt-2">{{ auth_info.first_name }} {{ auth_info.last_name }}</p>
    </div>
    <hr />
    <ul class="metismenu" id="menu">
      <!-- <li class="menu-label">Management</li> -->
      <!-- <li>
        <router-link :to="{ name: `adminDashboard` }" class="border" href="javascript:void();">
          <div class="parent-icon">
            <i class="zmdi zmdi-view-dashboard"></i>
          </div>
          <div class="menu-title">Dashboard</div>
        </router-link>
      </li> -->
      <side-bar-single-menu :icon="`zmdi zmdi-view-dashboard`" :menu_title="`Dashboard`"
        :route_name="`adminDashboard`" />
      <!-- Management start -->
      <side-bar-drop-down-menus :icon="`fa fa-plus`" :menu_title="`User Management`" :menus="[
        {
          route_name: `AllUser`,
          title: `User`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
      ]" />



      <side-bar-drop-down-menus :icon="`fa fa-plus`" :menu_title="`CourseManagement`" :menus="[
        {
          route_name: `AllCourseCategory`,
          title: `CourseCategory`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllCourses`,
          title: `All Courses`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
        // {
        //   route_name: `AllCourse`,
        //   title: `Course`,
        //   icon: `zmdi zmdi-dot-circle-alt`,
        // },
        {
          route_name: `AllCourseInstructors`,
          title: `CourseInstructors`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
      ]" />
      <side-bar-drop-down-menus :icon="`fa fa-plus`" :menu_title="`QuizManagement`" :menus="[
        {
          route_name: `AllQuizQuestionTopic`,
          title: `Quiz Topic`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllQuizQuestion`,
          title: `Quiz Question`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllQuiz`,
          title: `All Quiz`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllQuizSubmissionResult`,
          title: `Quiz Submission Result`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
      ]" />
      <side-bar-drop-down-menus :icon="`fa fa-plus`" :menu_title="`Website Management`" :menus="[
        {
          route_name: `AllWebsiteBanner`,
          title: `Website Banner`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllSubBanner`,
          title: `Sub Banner`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllSuccssStories`,
          title: `Succss Stories`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllOurSpeciality`,
          title: `Our Speciality`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route: { name: `EditOurTrainer`, params: { id: 'our-trainers' } },
          title: `Our Trainer`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllWebsiteBrand`,
          title: `Website Brand`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route: { name: `EditAboutUs`, params: { id: 'about-us' } },
          title: `About Us`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route: { name: `EditOurMoto`, params: { id: 'our-moto' } },
          title: `Our Moto`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route: { name: `EditOurMission`, params: { id: 'our-mission' } },
          title: `Our Mission`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route: { name: `EditOurVision`, params: { id: 'our-vision' } },
          title: `Our Vision`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
        {
          route: { name: `EditOurTeam`, params: { id: 'our-team' } },
          title: `OurTeam`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
        {
          route_name: `AllFaq`,
          title: `Faqs`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
        {
          route: { name: `EditPrivacyPolicy`, params: { id: 'privacy-policy' } },
          title: `PrivacyPolicy`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route: { name: `EditRefundPolicy`, params: { id: 'refund-policy' } },
          title: `RefundPolicy`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route: { name: `EditCookiePolicy`, params: { id: 'cookie-policy' } },
          title: `CookiePolicy`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route: { name: `EditTermConditionPolicy`, params: { id: 'term-condition-policy' } },
          title: `TermConditionPolicy`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
      ]" />
      <side-bar-drop-down-menus :icon="`fa fa-plus`" :menu_title="`BlogManagement`" :menus="[
        {
          route_name: `AllBlogCategory`,
          title: `BlogCategory`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllBlog`,
          title: `Blog`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllBlogWriter`,
          title: `BlogWriter`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllBlogTag`,
          title: `BlogTag`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
      ]" />
      <side-bar-drop-down-menus :icon="`fa fa-plus`" :menu_title="`GalleryManagement`" :menus="[
        {
          route_name: `AllGalleryCategory`,
          title: `GalleryCategory`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllGallery`,
          title: `Gallery`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
      ]" />
      <side-bar-single-menu :icon="`fa fa-plus`" :menu_title="`EnrollInformation`"
        :route_name="`AllEnrollInformation`" />
      <side-bar-single-menu :icon="`fa fa-plus`" :menu_title="`PaymentGateways`" :route_name="`AllPaymentGateways`" />
      <side-bar-single-menu :icon="`fa fa-plus`" :menu_title="`EmailConfigures`" :route_name="`AllEmailConfigures`" />
      <side-bar-drop-down-menus :icon="`fa fa-plus`" :menu_title="`SeminerManagement`" :menus="[
        {
          route_name: `AllSeminer`,
          title: `Seminer`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllSeminerParticipant`,
          title: `SeminerParticipant`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllSeminerSubscribers`,
          title: `SeminerSubscribers`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllSeminerReviews`,
          title: `SeminerReviews`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
      ]" />
      <side-bar-drop-down-menus :icon="`fa fa-plus`" :menu_title="`CommunicationManagement`" :menus="[
        {
          route_name: `AllContactMessage`,
          title: `ContactMessage`,
          icon: `zmdi zmdi-dot-circle-alt`,
        }, {
          route_name: `AllSubscriber`,
          title: `Subscriber`,
          icon: `zmdi zmdi-dot-circle-alt`,
        },
      ]" />

      <!-- Management end -->
    </ul>
  </div>
</template>

<script>
//auth_store
import { auth_store } from "../../../../../GlobalStore/auth_store";
import { site_settings_store } from "../../../../../GlobalStore/site_settings_store";
import { mapState, mapActions } from "pinia";
//components
import SideBarDropDownMenus from "./SideBarDropDownMenus.vue";
import SideBarSingleMenu from "./SideBarSingleMenu.vue";
export default {
  components: { SideBarDropDownMenus, SideBarSingleMenu },
  methods: {
    ...mapActions(site_settings_store, {
      get_setting_value: "get_setting_value",
    }),

    logout_submit: function () {
      let confirm = window.confirm("logout");
      if (confirm) {
        this.log_out();
      }
    },
    toggle_menu: function () {
      document.getElementById("wrapper").classList.toggle("toggled");
    },
    hide_menu: function () {
      document.getElementById("wrapper").classList.add("toggled");
    },
    onDashboardClick() {
      // Close all dropdown menus when dashboard is clicked
      window.dispatchEvent(
        new CustomEvent("collapse-all-menus", {
          detail: { except: null },
        })
      );
    },
  },
  watch: {
    '$route'(to, from) {
      // Auto-hide sidebar when navigating to task board
      if (to.name === 'TaskBoard') {
        this.hide_menu();
      }
    }
  },
  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
    }),
  },
};
</script>

<style>
/* Dashboard active state styling */
#menu>li.active>a,
#menu>li>a.active {
  background-color: #007bff !important;
  color: white !important;
  border-radius: 4px;
  margin: 2px;
}
</style>
<!-- <side-bar-drop-down-menus :icon="`fa fa-plus`" :icon_image="`https://files.etek.com.bd/images/icon_sales.png`"
    :menu_title="`title Management`" :menus="[
                {
                    route_name: `AllUser`,
                    title: `title`,
                    icon: `zmdi zmdi-dot-circle-alt`,
                },
            ]" /> -->
