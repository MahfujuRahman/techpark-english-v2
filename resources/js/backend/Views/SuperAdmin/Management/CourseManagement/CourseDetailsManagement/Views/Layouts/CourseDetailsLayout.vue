<template>
    <div class="course-details-layout">
        <div class="setting_management">
            <!-- Sidebar Navigation -->
            <div class="navs">
                <ul>
                    <li>
                        <router-link :to="{ name: 'AllCourses' }" class="nav-item">
                            <i class="far fa-circle mr-1"></i>
                            <div>All Course</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseDetails', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course Overview</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseBatch', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course Information</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseHowIsStructured', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>How Is Structured</div>
                        </router-link>
                    </li>

                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseWhatLearn', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>What You Will Learn</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseForWhom' }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course For Whom</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseWhyLearn', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Why you Learn From Us</div>
                        </router-link>
                    </li>

                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseWhatGet', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>What You Will Get</div>
                        </router-link>
                    </li>

                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseModule', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course Modules Class</div>
                        </router-link>
                    </li>
                    
                    <!-- <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseBanner', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্স ব্যানার</div>
                        </router-link>
                    </li> -->
                    
                    <!-- <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CoursePreview', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>কোর্স প্রিভিউ</div>
                        </router-link>
                    </li> -->
                    
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseQuiz', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course Class Quiz</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseTrainer', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course Trainers</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseRoutines', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Course routine</div>
                        </router-link>
                    </li>
                    
                    <li v-if="currentCourse">
                        <router-link 
                            :to="{ name: 'CourseFaq', params: { id: currentCourse.slug } }" 
                            class="nav-item"
                        >
                            <i class="far fa-circle mr-1"></i>
                            <div>Faq Questions</div>
                        </router-link>
                    </li>
                </ul>
            </div>
            
            <!-- Main Content Area -->
            <div class="setting_body custom_scroll">
                <!-- Course Header -->
                <div v-if="currentCourse" class="course-header mb-4">
                    <div class="course-info">
                        <div class="course-image">
                            <img 
                                v-if="currentCourse.image && !imageError" 
                                :src="`/${currentCourse.image}`" 
                                :alt="currentCourse.title"
                                class="img-fluid rounded"
                                @error="handleImageError"
                            >
                            <div v-else class="no-image">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                        </div>
                        <div class="course-details">
                            <h4 class="course-title">{{ currentCourse.title }}</h4>
                            <p class="course-type">{{ currentCourse.type }}</p>
                            <span 
                                :class="['badge', currentCourse.status === 'active' ? 'badge-success' : 'badge-danger']"
                            >
                                {{ currentCourse.status === 'active' ? 'active' : 'inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'AllCourses' }">All Courses</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ currentCourse.title }}
                            </li>
                        </ol>
                    </nav>
                </div>
                
                <!-- Loading State -->
                <div v-if="loading" class="loading-container">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">loading...</span>
                    </div>
                </div>
                
                <!-- Error Message -->
                <!-- <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ errorMessage }}
                    <button type="button" class="close" @click="clearMessages" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> -->
                
                <!-- Success Message -->
                <!-- <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ successMessage }}
                    <button type="button" class="close" @click="clearMessages" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> -->
                
                <!-- Router View for Course Content -->
                <router-view />
            </div>
        </div>
    </div>
</template>
<script>
import { ref } from 'vue';
import { useCourseDetailsStore } from '../../Store/courseDetailsStore.js';

export default {
    name: 'CourseDetailsLayout',
    
    setup() {
        const store = useCourseDetailsStore();
        const imageError = ref(false);
        
        return { 
            store,
            imageError
        };
    },
    
    computed: {
        currentCourse() {
            return this.store.currentCourse;
        },
        loading() {
            return this.store.loading;
        },
        errorMessage() {
            return this.store.errorMessage;
        },
        successMessage() {
            return this.store.successMessage;
        }
    },
    
    async mounted() {
        const courseSlug = this.$route.params.id; // This is actually the slug now
        
        if (courseSlug) {
            try {
                // First check if course is in localStorage
                const storedCourse = localStorage.getItem('current_course');
                if (storedCourse) {
                    const course = JSON.parse(storedCourse);
                    if (course.slug === courseSlug) {
                        this.store.setCurrentCourse(course);
                    }
                }
                
                // Always fetch fresh data using slug
                await this.store.getCourseDetails(courseSlug);
            } catch (error) {
                console.error('Error loading course details:', error);
                // Redirect to all courses if course not found
                if (error.response?.status === 404) {
                    this.$router.push({ name: 'AllCourses' });
                }
            }
        }
    },
    
    watch: {
        '$route.params.id': {
            async handler(newSlug, oldSlug) {
                if (newSlug && newSlug !== oldSlug) {
                    try {
                        await this.store.getCourseDetails(newSlug);
                    } catch (error) {
                        console.error('Error loading course details:', error);
                        if (error.response?.status === 404) {
                            this.$router.push({ name: 'AllCourses' });
                        }
                    }
                }
            },
            immediate: false
        },
        
        currentCourse() {
            // Reset image error when course changes
            this.imageError = false;
        }
    },
    
    methods: {
        clearMessages() {
            this.store.clearMessages();
        },
        
        handleImageError() {
            this.imageError = true;
            console.log('Course image failed to load');
        }
    },
    
    beforeUnmount() {
        // Clear messages when leaving the component
        this.store.clearMessages();
    }
};
</script>

<style scoped>
.course-details-layout {
    height: 100%;
    background: transparent !important;
    color: white !important;
}

.setting_management {
    display: flex;
    height: 100vh;
    background: transparent !important;
}

.navs {
    width: 300px;
    background: transparent !important;
    border-right: 1px solid rgba(255, 255, 255, 0.2);
    overflow-y: auto;
}

.navs ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.navs li {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    color: white !important;
    background: transparent !important;
}

.nav-item:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #007bff !important;
    text-decoration: none;
}

.nav-item.router-link-active,
.nav-item.router-link-exact-active {
    background-color: #007bff !important;
    color: white !important;
}

.nav-item i {
    margin-right: 8px;
    font-size: 0.9rem;
    color: inherit;
}

.nav-item div {
    font-size: 0.9rem;
    line-height: 1.3;
    color: inherit;
}

.setting_body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: transparent !important;
    color: white !important;
}

.custom_scroll {
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
}

.custom_scroll::-webkit-scrollbar {
    width: 6px;
}

.custom_scroll::-webkit-scrollbar-track {
    background: transparent;
}

.custom_scroll::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.course-header {
    background: transparent !important;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white !important;
}

.course-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.course-image {
    width: 80px;
    height: 80px;
    margin-right: 20px;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(255, 255, 255, 0.1);
}

.course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    color: rgba(255, 255, 255, 0.7);
}

.course-details {
    flex: 1;
}

.course-title {
    margin: 0 0 5px 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: white !important;
}

.course-type {
    margin: 0 0 10px 0;
    color: rgba(255, 255, 255, 0.8) !important;
    font-size: 0.9rem;
}

.breadcrumb {
    background-color: transparent !important;
    padding: 0;
    margin: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: rgba(255, 255, 255, 0.7);
}

.breadcrumb-item a {
    color: #ffffff !important;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: rgba(255, 255, 255, 0.8) !important;
}

.loading-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 200px;
}

.alert {
    margin-bottom: 20px;
    background: transparent !important;
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white !important;
}

.alert-danger {
    border-color: rgba(220, 53, 69, 0.5) !important;
    background: rgba(220, 53, 69, 0.1) !important;
}

.alert-success {
    border-color: rgba(40, 167, 69, 0.5) !important;
    background: rgba(40, 167, 69, 0.1) !important;
}

.badge {
    font-size: 0.8rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .setting_management {
        flex-direction: column;
        height: auto;
    }
    
    .navs {
        width: 100%;
        max-height: 200px;
    }
    
    .course-info {
        flex-direction: column;
        text-align: center;
    }
    
    .course-image {
        margin-right: 0;
        margin-bottom: 15px;
    }
}

/* Global styles for all course management components */
/* These styles will affect all child components */
:deep(.card) {
    background: transparent !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

.card-header{
    background: transparent !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    display: flex;
    justify-content: space-between;
}

:deep(.card-body) {
    background: transparent !important;
    color: white !important;
}

:deep(.card-footer) {
    background: transparent !important;
    border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

:deep(.table) {
    background: transparent !important;
    color: white !important;
}

:deep(.table th) {
    background: transparent !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

:deep(.table td) {
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

:deep(.table-hover tbody tr:hover) {
    background: rgba(255, 255, 255, 0.1) !important;
    color: white !important;
}

:deep(.form-control) {
    background: transparent !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    color: white !important;
}

:deep(.form-control:focus) {
    background: transparent !important;
    border-color: #007bff !important;
    color: white !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}

:deep(.form-control::placeholder) {
    color: rgba(255, 255, 255, 0.6) !important;
}

:deep(.btn) {
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
}

:deep(.btn-primary) {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
}

:deep(.btn-outline-primary) {
    background: transparent !important;
    border-color: #007bff !important;
    color: #007bff !important;
}

:deep(.btn-outline-warning) {
    background: transparent !important;
    border-color: #ffc107 !important;
    color: #ffc107 !important;
}

:deep(.btn-outline-danger) {
    background: transparent !important;
    border-color: #dc3545 !important;
    color: #dc3545 !important;
}

:deep(.pagination .page-link) {
    background: transparent !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    color: white !important;
}

:deep(.pagination .page-item.active .page-link) {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
}

:deep(.pagination .page-item.disabled .page-link) {
    background: transparent !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: rgba(255, 255, 255, 0.5) !important;
}

:deep(label) {
    color: white !important;
}

:deep(.text-muted) {
    color: rgba(255, 255, 255, 0.6) !important;
}

:deep(.text-dark) {
    color: white !important;
}

:deep(h1), :deep(h2), :deep(h3), :deep(h4), :deep(h5), :deep(h6) {
    color: white !important;
}

:deep(p) {
    color: white !important;
}

:deep(.no-data-container) {
    color: rgba(255, 255, 255, 0.8) !important;
}

:deep(.no-data-text h5) {
    color: white !important;
}

:deep(.no-data-text p) {
    color: rgba(255, 255, 255, 0.7) !important;
}

:deep(.pagination-info) {
    color: rgba(255, 255, 255, 0.8) !important;
}

:deep(.per-page-selector label) {
    color: white !important;
}

:deep(select.form-control) {
    background: transparent !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
}

:deep(select.form-control option) {
    background: #343a40 !important;
    color: white !important;
}

:deep(.modal-content) {
    background: rgba(33, 37, 41, 0.95) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

:deep(.modal-header) {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
}

:deep(.modal-footer) {
    border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
}

:deep(.close) {
    color: white !important;
    opacity: 0.8;
}

:deep(.close:hover) {
    color: white !important;
    opacity: 1;
}
</style>
