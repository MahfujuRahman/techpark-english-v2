<template>
    <div class="course-routine-all">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Course Routines
                </h5>
                <button @click="createNewRoutine" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i>
                    Add Routine
                </button>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="filters mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <select v-model="filters.module" @change="applyFilters" class="form-control">
                                <option value="">All Modules</option>
                                <option v-for="module in modules" :key="module.id" :value="module.id">
                                    {{ module.title }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select v-model="filters.class" @change="applyFilters" class="form-control">
                                <option value="">All Classes</option>
                                <option v-for="classItem in filteredClasses" :key="classItem.id" :value="classItem.id">
                                    {{ classItem.title }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input v-model="filters.date" @change="applyFilters" type="date" class="form-control"
                                placeholder="Filter by date">
                        </div>
                        <div class="col-md-3">
                            <select v-model="filters.status" @change="applyFilters" class="form-control">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <!-- Routines List -->
                <div v-else-if="filteredRoutines.length > 0" class="routines-list">
                    <div v-for="(routine, index) in filteredRoutines" :key="routine.id || index" class="routine-item">
                        <div class="routine-header">
                            <div class="routine-info">
                                <div class="routine-date">
                                    <div class="date-box">
                                        <div class="day">{{ formatDay(routine.date) }}</div>
                                        <div class="month">{{ formatMonth(routine.date) }}</div>
                                    </div>
                                </div>
                                <div class="routine-details">
                                    <h6 class="routine-topic">{{ routine.topic }}</h6>
                                    <p class="routine-meta">
                                        <span class="routine-time">
                                            <i class="fas fa-clock"></i>
                                            {{ formatTime(routine.time) }}
                                        </span>
                                        <span class="routine-class" v-if="routine.class">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                            {{ routine.class.title }}
                                        </span>
                                    </p>
                                    <p class="routine-module" v-if="routine.module">
                                        <small class="text-muted">
                                            Module: {{ routine.module.title }}
                                        </small>
                                    </p>
                                </div>
                            </div>
                            <div class="routine-actions">
                                <span :class="'badge badge-' + (routine.status === 'active' ? 'success' : 'danger')">
                                    {{ routine.status }}
                                </span>
                                <button @click="editRoutine(routine)" class="btn btn-outline-primary btn-sm ml-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteRoutine(routine.id, index)"
                                    class="btn btn-outline-danger btn-sm ml-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="empty-state text-center py-5">
                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No routines found</h5>
                    <p class="text-muted">Create your first routine to get started.</p>
                    <button @click="createNewRoutine" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        Create First Routine
                    </button>
                </div>
            </div>
        </div>

        <!-- Routine Form Modal -->
        <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ isEditing ? 'Edit Routine' : 'Create New Routine' }}
                        </h5>
                        <button @click="closeModal" type="button" class="close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveRoutine">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="module_id">Module *</label>
                                        <select id="module_id" v-model="currentRoutine.module_id"
                                            @change="filterClassesByModule" class="form-control" required>
                                            <option value="">Select Module</option>
                                            <option v-for="module in modules" :key="module.id" :value="module.id">
                                                {{ module.title }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_id">Class *</label>
                                        <select id="class_id" v-model="currentRoutine.class_id" class="form-control"
                                            required :disabled="!currentRoutine.module_id">
                                            <option value="">Select Class</option>
                                            <option v-for="classItem in modalFilteredClasses" :key="classItem.id"
                                                :value="classItem.id">
                                                {{ classItem.title }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="topic">Topic *</label>
                                <input type="text" id="topic" v-model="currentRoutine.topic" class="form-control"
                                    placeholder="Enter class topic" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Date *</label>
                                        <input type="date" id="date" v-model="currentRoutine.date" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time">Time *</label>
                                        <input type="time" id="time" v-model="currentRoutine.time" class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" v-model="currentRoutine.status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button @click="closeModal" type="button" class="btn btn-secondary">Cancel</button>
                        <button @click="saveRoutine" type="button" class="btn btn-primary" :disabled="submitting">
                            <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                            {{ submitting ? 'Saving...' : (isEditing ? 'Update' : 'Create') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';

export default {
    name: 'CourseRoutineAll',

    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),

        filteredClasses() {
            if (!this.filters.module) {
                return this.classes;
            }
            return this.classes.filter(classItem => classItem.course_modules_id == this.filters.module);
        },

        modalFilteredClasses() {
            if (!this.currentRoutine.module_id) {
                return [];
            }
            return this.classes.filter(classItem => classItem.course_modules_id == this.currentRoutine.module_id);
        },

        filteredRoutines() {
            let filtered = [...this.routines];

            if (this.filters.module) {
                filtered = filtered.filter(routine => routine.module_id == this.filters.module);
            }

            if (this.filters.class) {
                filtered = filtered.filter(routine => routine.class_id == this.filters.class);
            }

            if (this.filters.date) {
                filtered = filtered.filter(routine => routine.date === this.filters.date);
            }

            if (this.filters.status) {
                filtered = filtered.filter(routine => routine.status === this.filters.status);
            }

            return filtered.sort((a, b) => {
                const dateA = new Date(a.date + ' ' + a.time);
                const dateB = new Date(b.date + ' ' + b.time);
                return dateA - dateB;
            });
        }
    },

    data() {
        return {
            loading: false,
            routines: [],
            modules: [],
            classes: [],
            showModal: false,
            isEditing: false,
            submitting: false,
            filters: {
                module: '',
                class: '',
                date: '',
                status: ''
            },
            currentRoutine: {
                course_id: '',
                module_id: '',
                class_id: '',
                topic: '',
                date: '',
                time: '',
                status: 'active'
            }
        };
    },

    async created() {
        await this.loadData();
    },

    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),

        async loadData() {
            await Promise.all([
                this.loadRoutines(),
                this.loadModules(),
                this.loadClasses()
            ]);
        },

        async loadRoutines() {
            this.loading = true;
            try {
                const courseSlug = this.$route.params.id;
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                const response = await axios.get(`course-module-class-routines?course_id=${courseId}&get_all=1`);
                if (response.data && response.data.status === 'success') {
                    this.routines = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading routines:', error);
                window.s_error('Failed to load routines');
            } finally {
                this.loading = false;
            }
        },

        async loadModules() {
            try {
                const courseSlug = this.$route.params.id;
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                const response = await axios.get(`course-modules?course_id=${courseId}&get_all=1`);
                if (response.data && response.data.status === 'success') {
                    this.modules = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading modules:', error);
            }
        },

        async loadClasses() {
            try {
                const courseSlug = this.$route.params.id;
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                const response = await axios.get(`course-module-classes?course_id=${courseId}&get_all=1`);
                if (response.data && response.data.status === 'success') {
                    this.classes = response.data.data || [];
                }
            } catch (error) {
                console.error('Error loading classes:', error);
            }
        },

        filterClassesByModule() {
            // Reset class selection when module changes
            this.currentRoutine.class_id = '';
        },

        createNewRoutine() {
            this.isEditing = false;
            const store = useCourseDetailsStore();
            this.currentRoutine = {
                course_id: store.currentCourse?.id || '',
                module_id: '',
                class_id: '',
                topic: '',
                date: '',
                time: '',
                status: 'active'
            };
            this.showModal = true;
        },

        editRoutine(routine) {
            this.isEditing = true;
            this.currentRoutine = { ...routine };
            this.showModal = true;
        },

        async saveRoutine() {
            if (!this.currentRoutine.topic.trim()) {
                window.s_warning('Please enter a topic');
                return;
            }

            if (!this.currentRoutine.module_id) {
                window.s_warning('Please select a module');
                return;
            }

            if (!this.currentRoutine.class_id) {
                window.s_warning('Please select a class');
                return;
            }

            if (!this.currentRoutine.date) {
                window.s_warning('Please select a date');
                return;
            }

            if (!this.currentRoutine.time) {
                window.s_warning('Please select a time');
                return;
            }

            this.submitting = true;
            try {
                const store = useCourseDetailsStore();
                const routineData = {
                    ...this.currentRoutine,
                    course_id: store.currentCourse?.id
                };

                let response;
                if (this.isEditing) {
                    response = await axios.post(`course-module-class-routines/update/${this.currentRoutine.slug}`, routineData);
                } else {
                    response = await axios.post('course-module-class-routines/store', routineData);
                }

                if (response.data && response.data.status === 'success') {
                    window.s_alert(this.isEditing ? 'Routine updated successfully!' : 'Routine created successfully!');
                    this.closeModal();
                    await this.loadRoutines();
                } else {
                    window.s_error('Failed to save routine');
                }
            } catch (error) {
                console.error('Error saving routine:', error);
                window.s_error('Failed to save routine');
            } finally {
                this.submitting = false;
            }
        },

        async deleteRoutine(id, index) {
           
            const result = await window.s_confirm(
                'Are you sure you want to delete this item? This action cannot be undone.',
                'Confirm',
                'warning'
            );

            if (result && (result.isConfirmed || result === true)) {
                try {
                    if (id) {
                        const routine = this.routines[index];
                        await axios.post(`course-module-class-routines/destroy/${routine.slug}`);
                    }
                    this.routines.splice(index, 1);
                    window.s_alert('Routine deleted successfully!');
                } catch (error) {
                    console.error('Error deleting routine:', error);
                    window.s_error('Failed to delete routine');
                }
            }
        },

        closeModal() {
            this.showModal = false;
            this.isEditing = false;
            this.currentRoutine = {
                course_id: '',
                module_id: '',
                class_id: '',
                topic: '',
                date: '',
                time: '',
                status: 'active'
            };
        },

        applyFilters() {
            // Filters are automatically applied through computed property
        },

        formatDay(date) {
            if (!date) return '';
            const d = new Date(date);
            console.log(d.getDate().toString().padStart(2, '0'));
            return d.getDate().toString().padStart(2, '0');
        },

        formatMonth(date) {
            if (!date) return '';
            const d = new Date(date);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return months[d.getMonth()];
        },

        formatTime(time) {
            if (!time) return '';
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            return `${displayHour}:${minutes} ${ampm}`;
        }
    }
};
</script>

<style scoped>
.course-routine-all {
    max-width: 100%;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.filters {
    padding: 1rem;
    border-radius: 0.5rem;
}

.routines-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.routine-item {
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1.5rem;
    transition: box-shadow 0.15s ease-in-out;
}

.routine-item:hover {
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.routine-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.routine-info {
    display: flex;
    gap: 1rem;
    flex: 1;
}

.routine-date {
    flex-shrink: 0;
}

.date-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem;
    padding: 0.75rem;
    text-align: center;
    min-width: 60px;
}

.day {
    font-size: 1.5rem;
    font-weight: bold;
    line-height: 1;
}

.month {
    font-size: 0.75rem;
    text-transform: uppercase;
    margin-top: 0.25rem;
}

.routine-details {
    flex: 1;
}

.routine-topic {
    margin: 0 0 0.5rem 0;
    color: #495057;
    font-weight: 600;
}

.routine-meta {
    margin: 0.5rem 0;
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.routine-time,
.routine-class {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
    font-size: 0.875rem;
}

.routine-time i {
    color: #28a745;
}

.routine-class i {
    color: #007bff;
}

.routine-module {
    margin: 0.5rem 0 0 0;
}

.routine-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.empty-state {
    padding: 3rem 1rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.badge-success {
    background-color: #28a745;
}

.badge-secondary {
    background-color: #6c757d;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.modal {
    z-index: 1050;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.form-control {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.form-control:disabled {
    background-color: #f8f9fa;
    opacity: 0.6;
}

/* Calendar-like styling for date inputs */
input[type="date"].form-control,
input[type="time"].form-control {
    background-color: white;
    cursor: pointer;
}

/* Responsive Design */
@media (max-width: 768px) {
    .routine-header {
        flex-direction: column;
        gap: 1rem;
    }

    .routine-info {
        width: 100%;
    }

    .routine-actions {
        width: 100%;
        justify-content: space-between;
    }

    .routine-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .filters .row {
        margin: 0;
    }

    .filters .col-md-3 {
        padding: 0.25rem;
    }
}

/* Status indicators */
.routine-item.status-inactive {
    opacity: 0.7;
}

.routine-item.status-inactive .routine-topic {
    text-decoration: line-through;
}

/* Loading animation */
.spinner-border {
    width: 2rem;
    height: 2rem;
}

/* Enhanced form styling */
.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.modal-dialog {
    margin: 1.75rem auto;
}

/* Filter styling */
.filters .form-control {
    background-color: white;
    border: 1px solid #ced4da;
}

.filters .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    border-color: #007bff;
}

/* Button hover effects */
.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Card header styling */
.card-header {
    border-bottom: 1px solid #dee2e6;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

/* Animation for routine items */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.routine-item {
    animation: fadeInUp 0.3s ease-out;
}
</style>