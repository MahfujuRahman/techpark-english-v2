<template>
    <div class="course-batches-form">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i :class="isEditMode ? 'fas fa-edit mr-2' : 'fas fa-plus mr-2'"></i>
                    {{ isEditMode ? 'Edit Course Batch' : 'Create New Course Batch' }}
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="submitForm">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h6 class="section-title">Basic Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_name" class="form-label">Batch Name *</label>
                                    <input type="text" id="batch_name" v-model="formData.batch_name"
                                        class="form-control" :class="{ 'is-invalid': errors.batch_name }"
                                        placeholder="Batch 1" required>
                                    <div v-if="errors.batch_name" class="invalid-feedback">
                                        {{ errors.batch_name }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_student_limit" class="form-label">Student Limit *</label>
                                    <input type="number" id="batch_student_limit" v-model="formData.batch_student_limit"
                                        class="form-control" :class="{ 'is-invalid': errors.batch_student_limit }"
                                        placeholder="50" min="1" required>
                                    <div v-if="errors.batch_student_limit" class="invalid-feedback">
                                        {{ errors.batch_student_limit }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="booked_percent" class="form-label">Seat Booked (%)</label>
                                    <input type="number" id="booked_percent" v-model="formData.booked_percent"
                                        class="form-control" :class="{ 'is-invalid': errors.booked_percent }"
                                        placeholder="50" min="1" required>
                                    <div v-if="errors.booked_percent" class="invalid-feedback">
                                        {{ errors.booked_percent }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admission Dates -->
                    <div class="form-section">
                        <h6 class="section-title">Admission Period</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admission_start_date" class="form-label">Admission Start Date</label>
                                    <input type="date" id="admission_start_date"
                                        v-model="formData.admission_start_date" class="form-control"
                                        :class="{ 'is-invalid': errors.admission_start_date }">
                                    <div v-if="errors.admission_start_date" class="invalid-feedback">
                                        {{ errors.admission_start_date }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admission_end_date" class="form-label">Admission End Date</label>
                                    <input type="date" id="admission_end_date"
                                        v-model="formData.admission_end_date" class="form-control"
                                        :class="{ 'is-invalid': errors.admission_end_date }">
                                    <div v-if="errors.admission_end_date" class="invalid-feedback">
                                        {{ errors.admission_end_date }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="form-section">
                        <h6 class="section-title">Pricing Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="course_price" class="form-label">Course Price *</label>
                                    <input type="number" id="course_price" v-model="formData.course_price"
                                        class="form-control" :class="{ 'is-invalid': errors.course_price }"
                                        placeholder="5000" min="0" step="0.01" required @input="calculateDiscountPrice">
                                    <div v-if="errors.course_price" class="invalid-feedback">
                                        {{ errors.course_price }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="course_discount" class="form-label">Discount (%)</label>
                                    <input type="number" id="course_discount" v-model="formData.course_discount"
                                        class="form-control" :class="{ 'is-invalid': errors.course_discount }"
                                        placeholder="10" min="0" max="100" step="0.01" @input="calculateDiscountPrice">
                                    <div v-if="errors.course_discount" class="invalid-feedback">
                                        {{ errors.course_discount }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="after_discount_price" class="form-label">Final Price</label>
                                    <input type="number" id="after_discount_price"
                                        v-model="formData.after_discount_price" class="form-control"
                                        :class="{ 'is-invalid': errors.after_discount_price }" step="0.01" readonly>
                                    <div v-if="errors.after_discount_price" class="invalid-feedback">
                                        {{ errors.after_discount_price }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Class Schedule -->
                    <div class="form-section">
                        <h6 class="section-title">Class Schedule</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_class_date" class="form-label">First Class Date</label>
                                    <input type="date" id="first_class_date" v-model="formData.first_class_date"
                                        class="form-control" :class="{ 'is-invalid': errors.first_class_date }">
                                    <div v-if="errors.first_class_date" class="invalid-feedback">
                                        {{ errors.first_class_date }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_days" class="form-label">Class Days</label>
                                    <input type="text" id="class_days" v-model="formData.class_days"
                                        class="form-control" :class="{ 'is-invalid': errors.class_days }"
                                        placeholder="Sunday, Tuesday, Thursday">
                                    <div v-if="errors.class_days" class="invalid-feedback">
                                        {{ errors.class_days }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_start_time" class="form-label">Class Start Time</label>
                                    <input type="time" id="class_start_time" v-model="formData.class_start_time"
                                        class="form-control" :class="{ 'is-invalid': errors.class_start_time }">
                                    <div v-if="errors.class_start_time" class="invalid-feedback">
                                        {{ errors.class_start_time }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_end_time" class="form-label">Class End Time</label>
                                    <input type="time" id="class_end_time" v-model="formData.class_end_time"
                                        class="form-control" :class="{ 'is-invalid': errors.class_end_time }">
                                    <div v-if="errors.class_end_time" class="invalid-feedback">
                                        {{ errors.class_end_time }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Settings -->
                    <div class="form-section">
                        <h6 class="section-title">Additional Settings</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="show_percentage_on_cards" class="form-label">Show Booking
                                        Percentage</label>
                                    <select id="show_percentage_on_cards" v-model="formData.show_percentage_on_cards"
                                        class="form-control" :class="{ 'is-invalid': errors.show_percentage_on_cards }">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                    <div v-if="errors.show_percentage_on_cards" class="invalid-feedback">
                                        {{ errors.show_percentage_on_cards }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" v-model="formData.status" class="form-control"
                                        :class="{ 'is-invalid': errors.status }">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <div v-if="errors.status" class="invalid-feedback">
                                        {{ errors.status }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between">
                            <button type="button" @click="goBack" class="btn btn-secondary" :disabled="submitting">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back
                            </button>

                            <button type="submit" class="btn btn-primary" :disabled="submitting">
                                <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else :class="isEditMode ? 'fas fa-save mr-1' : 'fas fa-plus mr-1'"></i>
                                {{ submitting ? 'Processing...' : (isEditMode ? 'Update Batch' : 'Create Batch') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCourseDetailsStore } from '@/Views/SuperAdmin/Management/CourseManagement/CourseDetailsManagement/Store/courseDetailsStore.js';

export default {
    name: 'CourseBatchForm',

    data() {
        return {
            submitting: false,
            loading: false,
            errors: {},
            batchData: null,
            formData: {
                batch_name: '',
                admission_start_date: '',
                admission_end_date: '',
                batch_student_limit: 50,
                booked_percent: 0,
                course_price: '',
                course_discount: 0,
                after_discount_price: '',
                first_class_date: '',
                class_days: '',
                class_start_time: '',
                class_end_time: '',
                show_percentage_on_cards: 'yes',
                status: 'active',
            },
        };
    },

    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),

        isEditMode() {
            return this.$route.name === 'CourseBatchEdit' && this.$route.params.batch_id;
        },

        courseId() {
            return this.currentCourse?.id;
        },

        batchSlug() {
            return this.$route.currentCourse?.slug; // This is actually the slug from the route
        },
        batchId() {
            return this.$route.params.batch_id;
        }
    },

    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),

        calculateDiscountPrice() {
            const price = parseFloat(this.formData.course_price) || 0;
            const discount = parseFloat(this.formData.course_discount) || 0;

            if (price > 0 && discount > 0) {
                const discountAmount = (price * discount) / 100;
                this.formData.after_discount_price = (price - discountAmount).toFixed(2);
            } else {
                this.formData.after_discount_price = price.toFixed(2);
            }
        },

        async loadBatchData() {
            if (!this.isEditMode || !this.batchId) return;

            this.loading = true;
            try {
                const batchSlug = this.batchId; // Now this contains the slug from route params
                console.log('Loading batch data for slug:', batchSlug);
                // According to your routes: Route::get('{slug}', [Controller::class,'show']);
                const response = await axios.get(`course-batches/${batchSlug}`);

                console.log('Form - API Response:', response.data);

                if (response.data && response.data.status === 'success') {
                    this.batchData = response.data.data;
                    this.populateForm();
                } else {
                    window.s_error('Failed to load batch data');
                }
            } catch (error) {
                console.error('Error loading batch data:', error);
                window.s_error('Failed to load batch data');
            } finally {
                this.loading = false;
            }
        },

        populateForm() {
            if (!this.batchData) return;

            // Populate form with existing data
            Object.keys(this.formData).forEach(key => {
                if (this.batchData[key] !== undefined && this.batchData[key] !== null) {
                    this.formData[key] = this.batchData[key];
                }
            });

            // Format datetime fields for inputs
            if (this.batchData.admission_start_date) {
                this.formData.admission_start_date = this.formatDateTimeForInput(this.batchData.admission_start_date);
            }
            if (this.batchData.admission_end_date) {
                this.formData.admission_end_date = this.formatDateTimeForInput(this.batchData.admission_end_date);
            }
            if (this.batchData.first_class_date) {
                this.formData.first_class_date = this.formatDateTimeForInput(this.batchData.first_class_date);
            }
        },

        formatDateTimeForInput(datetime) {
            if (!datetime) return '';
            // Convert to format required by datetime-local input (YYYY-MM-DDTHH:MM)
            // const date = new Date(datetime);
            // const year = date.getFullYear();
            // const month = String(date.getMonth() + 1).padStart(2, '0');
            // const day = String(date.getDate()).padStart(2, '0');
            // const hours = String(date.getHours()).padStart(2, '0');
            // const minutes = String(date.getMinutes()).padStart(2, '0');

            // return `${year}-${month}-${day}T${hours}:${minutes}`;

            const d = new Date(this.batchData.first_class_date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            
            return `${year}-${month}-${day}`;
        },

        validateForm() {
            this.errors = {};

            if (!this.formData.batch_name?.trim()) {
                this.errors.batch_name = 'Batch name is required';
            }

            if (!this.formData.batch_student_limit || this.formData.batch_student_limit < 1) {
                this.errors.batch_student_limit = 'Student limit must be 1 or greater';
            }

            if (!this.formData.course_price || this.formData.course_price <= 0) {
                this.errors.course_price = 'Course price is required and must be greater than 0';
            }

            // Validate admission dates
            if (this.formData.admission_start_date && this.formData.admission_end_date) {
                const startDate = new Date(this.formData.admission_start_date);
                const endDate = new Date(this.formData.admission_end_date);

                if (startDate >= endDate) {
                    this.errors.admission_end_date = 'Admission end date must be after start date';
                }
            }

            // Validate class times
            if (this.formData.class_start_time && this.formData.class_end_time) {
                if (this.formData.class_start_time >= this.formData.class_end_time) {
                    this.errors.class_end_time = 'Class end time must be after start time';
                }
            }

            return Object.keys(this.errors).length === 0;
        },

        async submitForm() {
            if (!this.validateForm()) {
                window.s_warning('Please fill all required fields correctly.');
                return;
            }

            if (!this.courseId) {
                window.s_error('Course ID not found.');
                return;
            }

            this.submitting = true;

            try {
                const batchData = {
                    ...this.formData,
                    course_id: this.courseId,
                };

                // For create mode, add default values
                if (!this.isEditMode) {
                    batchData.seat_booked = 0;
                    batchData.booked_percent = 0;
                }

                // Remove empty values
                Object.keys(batchData).forEach(key => {
                    if (batchData[key] === '' || batchData[key] === null) {
                        delete batchData[key];
                    }
                });

                console.log('Submitting batch data:', batchData);

                let response;
                if (this.isEditMode) {
                    const batchSlug = this.batchId; // This is actually the slug from route params
                    response = await axios.post(`course-batches/update/${batchSlug}`, batchData);
                } else {
                    response = await axios.post('course-batches/store', batchData);
                }

                console.log('Submit - API Response:', response.data);

                if (response.data && response.data.status === 'success') {
                    window.s_alert(this.isEditMode ? 'Batch updated successfully!' : 'Batch created successfully!');
                    this.goBack();
                } else {
                    window.s_error(response.data.message || 'Failed to save batch');
                }

            } catch (error) {
                console.error('Error saving batch:', error);

                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                    window.s_warning('Form has errors. Please correct them.');
                } else {
                    window.s_error('Failed to save batch');
                }
            } finally {
                this.submitting = false;
            }
        },

        goBack() {
            this.$router.push({
                name: 'CourseBatchAll',
                params: { id: this.$route.params.id }
            });
        },
    },

    async mounted() {
        // Ensure we have course details
        const courseSlug = this.$route.params.id;
        if (courseSlug && !this.currentCourse) {
            await this.getCourseDetails(courseSlug);
        }

        // Load batch data if in edit mode
        if (this.isEditMode) {
            await this.loadBatchData();
        }
    }
};
</script>

<style scoped>
.course-batches-form {
    position: relative;
    max-width: 100%;
}

.form-section {
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}

.section-title::before {
    content: '';
    width: 20px;
    height: 2px;
    background-color: #007bff;
    margin-right: 10px;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 10px 12px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 5px;
    font-size: 0.875rem;
    color: #dc3545;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    font-weight: 500;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-section {
        padding: 15px;
        margin-bottom: 20px;
    }

    .form-actions {
        text-align: center;
    }

    .form-actions .btn {
        width: 100%;
        margin: 5px 0;
    }

    .section-title {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .form-section {
        padding: 10px;
    }

    .section-title::before {
        width: 15px;
        margin-right: 8px;
    }
}
</style>
