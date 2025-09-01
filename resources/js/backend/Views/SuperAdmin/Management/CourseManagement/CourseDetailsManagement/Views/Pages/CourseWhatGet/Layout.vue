<template>
    <div class="course-what-get-layout">
        <div class="setting_management">
            
            <!-- Main Content Area -->
            <div class="setting_body custom_scroll">
                <!-- Content Header -->
                <div class="content-header mb-4">
                    <h4 class="content-title">What You Will Get</h4>
                    <p class="content-description">
                        Add learning outcomes and skills that students will acquire after completing this course.
                    </p>
                </div>
                
                <!-- Router View -->
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CourseWhatGetLayout',
};
</script>

<style scoped>
.course-what-get-layout {
    min-height: 100vh;
}

.setting_management {
    display: flex;
    height: 100%;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.navs {
    width: 130px;
    border-right: 1px solid #dee2e6;
    padding: 0;
}

.navs ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.navs li {
    border-bottom: 1px solid #e9ecef;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    color: #ffff;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.nav-item:hover {
    background: #e9ecef;
    color: #495057;
    text-decoration: none;
}

.nav-item.router-link-active {
    background: #007bff;
    color: #fff;
}

.nav-item i {
    margin-right: 10px;
    width: 16px;
}

.setting_body {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
}

.content-header {
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.content-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 10px;
}

.content-description {
    color: #6c757d;
    margin-bottom: 0;
    line-height: 1.5;
}

.custom_scroll::-webkit-scrollbar {
    width: 6px;
}

.custom_scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.custom_scroll::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.custom_scroll::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
