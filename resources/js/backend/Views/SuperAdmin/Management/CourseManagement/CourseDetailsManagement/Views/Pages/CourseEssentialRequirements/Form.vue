<template>
    <div class="course-what-learn-form">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i :class="isEditMode ? 'fas fa-edit mr-2' : 'fas fa-plus mr-2'"></i>
                    {{ isEditMode ? 'Edit Essential Requirement' : 'Create New Essential Requirement' }}
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="submitForm">
                    <!-- Title -->
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input 
                            type="text" 
                            id="title"
                            v-model="formData.title"
                            class="form-control"
                            :class="{ 'is-invalid': errors.title }"
                            placeholder="Enter learning outcome title"
                            required
                        >
                        <div v-if="errors.title" class="invalid-feedback">
                            {{ errors.title }}
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select 
                            id="status"
                            v-model="formData.status"
                            class="form-control"
                            :class="{ 'is-invalid': errors.status }"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div v-if="errors.status" class="invalid-feedback">
                            {{ errors.status }}
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between">
                            <button 
                                type="button" 
                                @click="goBack" 
                                class="btn btn-secondary"
                                :disabled="submitting"
                            >
                                <i class="fas fa-arrow-left mr-1"></i>
                                Back
                            </button>
                            
                            <button 
                                type="submit" 
                                class="btn btn-primary"
                                :disabled="submitting"
                            >
                                <i v-if="submitting" class="fas fa-spinner fa-spin mr-1"></i>
                                <i v-else :class="isEditMode ? 'fas fa-save mr-1' : 'fas fa-plus mr-1'"></i>
                                {{ submitting ? 'Processing...' : (isEditMode ? 'Update Item' : 'Create Item') }}
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
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';

export default {
    name: 'CourseEssentialRequirementsForm',
    
    data() {
        return {
            submitting: false,
            loading: false,
            errors: {},
            itemData: null,
            formData: {
                title: '',
                status: 'active',
            },
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),
        
        isEditMode() {
            return this.$route.name === 'CourseEssentialRequirementsEdit' && this.$route.params.slug;
        },
        
        courseId() {
            return this.currentCourse?.id;
        },
        
        itemSlug() {
            return this.$route.params.slug;
        }
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),
        
        async loadItemData() {
            if (!this.isEditMode || !this.itemSlug) return;
            
            this.loading = true;
            try {
                const itemSlug = this.itemSlug; // This contains the slug from route params
                console.log('Loading will get item data for slug:', itemSlug);

                const response = await axios.get(`course-essential-requirements/${itemSlug}`);

                console.log('Form - API Response:', response.data);
                
                if (response.data && response.data.status === 'success') {
                    this.itemData = response.data.data;
                    this.populateForm();
                } else {
                    window.s_error('Failed to load learning item data');
                }
            } catch (error) {
                console.error('Error loading learning item data:', error);
                window.s_error('Failed to load learning item data');
            } finally {
                this.loading = false;
            }
        },
        
        populateForm() {
            if (!this.itemData) return;
            
            // Populate form with existing data
            Object.keys(this.formData).forEach(key => {
                if (this.itemData[key] !== undefined && this.itemData[key] !== null) {
                    this.formData[key] = this.itemData[key];
                }
            });
        },
        
        validateForm() {
            this.errors = {};
            
            if (!this.formData.title?.trim()) {
                this.errors.title = 'Title is required';
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
                const itemData = {
                    ...this.formData,
                    course_id: this.courseId,
                };
                
                // Remove empty values
                Object.keys(itemData).forEach(key => {
                    if (itemData[key] === '' || itemData[key] === null) {
                        delete itemData[key];
                    }
                });
                
                console.log('Submitting learning item data:', itemData);
                
                let response;
                if (this.isEditMode) {
                    const itemSlug = this.itemSlug; // This contains the slug from route params
                    response = await axios.post(`course-essential-requirements/update/${itemSlug}`, itemData);
                } else {
                    response = await axios.post('course-essential-requirements/store', itemData);
                }
                
                console.log('Submit - API Response:', response.data);
                
                if (response.data && response.data.status === 'success') {
                    window.s_alert(this.isEditMode ? 'Item updated successfully!' : 'Item created successfully!');
                    this.goBack();
                } else {
                    window.s_error(response.data.message || 'Failed to save Item');
                }
                
            } catch (error) {
                console.error('Error saving Item:', error);
                
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                    window.s_warning('Form has errors. Please correct them.');
                } else {
                    window.s_error('Failed to save Item');
                }
            } finally {
                this.submitting = false;
            }
        },
        
        goBack() {
            this.$router.push({ 
                name: 'CourseEssentialRequirementsAll', 
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
        
        // Load item data if in edit mode
        if (this.isEditMode) {
            await this.loadItemData();
        }
    }
};
</script>

<style scoped>
.course-what-learn-form {
    position: relative;
    max-width: 800px;
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

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
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
    .form-actions {
        text-align: center;
    }
    
    .form-actions .btn {
        width: 100%;
        margin: 5px 0;
    }
}
</style>
