<template>
    <div class="course-what-learn-details">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="page-title mb-0">Item Details</h4>
                    </div>
                    <div class="d-flex gap-2">
                        <router-link 
                            :to="{ name: 'CourseWhatGetEdit', params: { id: $route.params.id, slug: item?.slug } }"
                            class="btn btn-primary btn-sm"
                            v-if="item?.slug"
                        >
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </router-link>
                        <router-link 
                            :to="{ name: 'CourseWhatGet', params: { id: $route.params.id } }"
                            class="btn btn-outline-secondary btn-sm"
                        >
                            <i class="fas fa-arrow-left mr-1"></i>
                            Back to List
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading item details...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="alert alert-danger" role="alert">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Error Loading Details
            </h5>
            <p class="mb-0">{{ error }}</p>
            <hr>
            <div class="mb-0">
                <button @click="loadItemDetails" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-redo mr-1"></i>
                    Try Again
                </button>
            </div>
        </div>

        <!-- Details Content -->
        <div v-else-if="item" class="row">
            <div class="col-lg-12">
                <!-- Main Details Card -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle mr-2 text-primary"></i>
                            Item Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong class="text-muted">Title:</strong>
                            </div>
                            <div class="col-sm-9">
                                <h6 class="mb-0">{{ item.title || 'No title provided' }}</h6>
                            </div>
                        </div>
                       

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong class="text-muted">Slug:</strong>
                            </div>
                            <div class="col-sm-9">
                                <code class="bg-light px-2 py-1 rounded">{{ item.slug }}</code>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong class="text-muted">Status:</strong>
                            </div>
                            <div class="col-sm-9">
                                <span 
                                    :class="['badge', item.status === 'active' ? 'badge-success' : 'badge-secondary']"
                                >
                                    {{ item.status === 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-cogs mr-2 text-primary"></i>
                            Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <router-link 
                                :to="{ name: 'CourseWhatGetEdit', params: { id: $route.params.id, slug: item?.slug } }"
                                class="btn btn-primary"
                                v-if="item?.slug"
                            >
                                <i class="fas fa-edit mr-1"></i>
                                Edit Item
                            </router-link>
                            
                            <button 
                                @click="confirmDelete"
                                class="btn btn-danger"
                            >
                                <i class="fas fa-trash mr-1"></i>
                                Delete Item
                            </button>
                            
                            <router-link 
                                :to="{ name: 'CourseWhatGet', params: { id: $route.params.id } }"
                                class="btn btn-outline-secondary"
                            >
                                <i class="fas fa-list mr-1"></i>
                                View All Items
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- No Item Found -->
        <div v-else class="text-center py-5">
            <div class="alert alert-warning">
                <h5 class="alert-heading">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Item Not Found
                </h5>
                <p class="mb-0">The requested item could not be found.</p>
                <hr>
                <div class="mb-0">
                    <router-link 
                        :to="{ name: 'CourseWhatGet', params: { id: $route.params.id } }"
                        class="btn btn-outline-warning"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to List
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCourseDetailsStore } from '../../../Store/courseDetailsStore.js';

import axios from 'axios';

export default {
    name: 'CourseWhatGetDetails',
    
    setup() {
        const route = useRoute();
        const router = useRouter();
        const courseStore = useCourseDetailsStore();
        
        // Reactive data
        const item = ref(null);
        const loading = ref(false);
        const error = ref('');
        
        // Computed properties
        const itemSlug = computed(() => route.params.slug);
        
        // Methods
        const loadCourseIfNeeded = async () => {
            if (!courseStore.course && route.params.id) {
                try {
                    await courseStore.getCourseDetails(route.params.id);
                } catch (err) {
                    console.error('Error loading course details:', err);
                }
            }
        };
        
        const loadItemDetails = async () => {
            if (!itemSlug.value) {
                error.value = 'No item slug provided';
                return;
            }
            
            loading.value = true;
            error.value = '';
            
            try {
                // Ensure course is loaded first
                await loadCourseIfNeeded();
                
                const response = await axios.get(`course-what-you-will-gets/${itemSlug.value}`);
                
                if (response.data && response.data.status === 'success') {
                    item.value = response.data.data;
                } else {
                    throw new Error(response.data?.message || 'Failed to load item details');
                }
            } catch (err) {
                console.error('Error loading item details:', err);
                
                if (err.response?.status === 404) {
                    error.value = 'Learning item not found';
                } else if (err.response?.status === 403) {
                    error.value = 'You do not have permission to view this item';
                } else {
                    error.value = err.response?.data?.message || err.message || 'Failed to load item details';
                }
            } finally {
                loading.value = false;
            }
        };
        
        const confirmDelete = async () => {
            const confirmed = await window.s_confirm(
                'Are you sure you want to delete this learning item?',
                'Yes!'
            );
            
            if (confirmed) {
                await deleteItem();
            }
        };
        
        const deleteItem = async () => {
            try {
                const response = await axios.post(`course-what-you-will-gets/destroy/${itemSlug.value}`);
                
                if (response.data && response.data.status === 'success') {
                    window.s_alert('Item deleted successfully!', 'success');
                    
                    // Navigate back to list
                    router.push({ name: 'CourseWhatGet', params: { id: route.params.id } });
                } else {
                    throw new Error(response.data?.message || 'Failed to delete item');
                }
            } catch (err) {
                console.error('Error deleting item:', err);
                
                const errorMessage = err.response?.data?.message || err.message || 'Failed to delete item';
                window.s_error(errorMessage);
            }
        };
        
        const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (err) {
                return dateString;
            }
        };
        
        // Lifecycle
        onMounted(() => {
            loadItemDetails();
        });
        
        return {
            // Store
            courseStore,
            
            // Data
            item,
            loading,
            error,
            
            // Methods
            loadItemDetails,
            confirmDelete,
            formatDate,
        };
    },
};
</script>

<style scoped>
.course-what-learn-details {
    padding: 0;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #495057;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin: 0;
    font-size: 0.875rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
}

.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 0.75rem 1.25rem;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
}

.badge-primary {
    background-color: #4e73df;
    color: white;
}

.badge-success {
    background-color: #1cc88a;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.btn {
    border-radius: 0.35rem;
    font-weight: 500;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.alert {
    border-radius: 0.35rem;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.text-justify {
    text-align: justify;
}

.gap-2 {
    gap: 0.5rem;
}

code {
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .d-flex.gap-2 {
        margin-top: 1rem;
    }
    
    .d-flex.flex-wrap.gap-2 {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>