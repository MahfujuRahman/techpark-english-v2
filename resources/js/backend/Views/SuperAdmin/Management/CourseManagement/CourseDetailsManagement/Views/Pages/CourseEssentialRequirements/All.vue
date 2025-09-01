<template>
    <div class="course-what-learn-all">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list mr-2"></i>
                        Essential Requirements - All Items
                    </h5>
                    <div class="header-actions">
                        <router-link 
                            :to="{ name: 'CourseEssentialRequirementsCreate', params: { id: $route.params.id } }"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            Add New Item
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-if="loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading learning items...</p>
                </div>

                <div v-else>
                    <!-- Learning Items List -->
                    <div v-if="learningItems.length > 0" class="learning-items">
                        <div v-for="(item, index) in learningItems" :key="item.id" class="learning-item">
                            <div class="item-content">
                                <div class="item-number">
                                    {{ index + 1 }}
                                </div>
                                <div class="item-icon">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                                <div class="item-details">
                                    <h6 class="item-title">{{ item.title }}</h6>
                                    <p class="item-description">{{ item.description }}</p>
                                    <div class="item-meta">
                                        <span :class="getStatusClass(item.status)">{{ getStatusLabel(item.status) }}</span>
                                        <small class=" ml-2">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ formatDate(item.created_at) }}
                                        </small>
                                    </div>
                                </div>
                                <div class="item-actions">
                                    <router-link 
                                        :to="{ name: 'CourseEssentialRequirementsDetails', params: { id: $route.params.id, slug: item.slug } }"
                                        class="btn btn-sm btn-outline-info"
                                        title="View Details"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </router-link>
                                    <router-link 
                                        :to="{ name: 'CourseEssentialRequirementsEdit', params: { id: $route.params.id, slug: item.slug } }"
                                        class="btn btn-sm btn-outline-primary ml-1"
                                        title="Edit"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                    <button 
                                        @click="deleteItem(item)"
                                        class="btn btn-sm btn-outline-danger ml-1"
                                        title="Delete"
                                        :disabled="deleting"
                                    >
                                        <i v-if="deleting && deletingId === item.id" class="fas fa-spinner fa-spin"></i>
                                        <i v-else class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="empty-state text-center py-5">
                        <div class="empty-icon mb-3">
                            <i class="fas fa-graduation-cap fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted">No Essential Requirement Items Found</h5>
                        <p class="text-muted mb-4">
                            Start by adding the essential requirements for this course.
                        </p>
                        <router-link 
                            :to="{ name: 'CourseEssentialRequirementsCreate', params: { id: $route.params.id } }"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            Add First Essential Requirement
                        </router-link>
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
    name: 'CourseEssentialRequirementsAll',
    
    data() {
        return {
            learningItems: [],
            loading: false,
            deleting: false,
            deletingId: null,
        };
    },
    
    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse']),
    },
    
    methods: {
        ...mapActions(useCourseDetailsStore, ['getCourseDetails']),
        
        async getLearningItems() {
            const courseSlug = this.$route.params.id;
            if (!courseSlug) return;

            this.loading = true;
            try {
                // First ensure we have the current course loaded
                const store = useCourseDetailsStore();
                if (!store.currentCourse) {
                    await store.getCourseDetails(courseSlug);
                }

                const courseId = store.currentCourse?.id;
                if (!courseId) {
                    console.error('Course ID not found');
                    return;
                }

                console.log('Fetching items for course ID:', courseId);
                const response = await axios.get(`course-essential-requirements?course_id=${courseId}`);

                console.log('API Response:', response.data);

                // Check if response has the expected structure
                if (response.data && response.data.status === 'success') {
                    this.learningItems = response.data.data?.data || [];
                    console.log('Loaded items:', this.learningItems);
                } else {
                    console.error('Unexpected response structure:', response.data);
                    this.learningItems = [];
                }
            } catch (error) {
                console.error('Error fetching items:', error);
                window.s_error('Failed to load items!');
                this.learningItems = [];
            } finally {
                this.loading = false;
            }
        },
        
        async deleteItem(item) {
            const confirmed = await window.s_confirm(
                'Are you sure you want to delete this learning item?',
                'Yes, delete it!'
            );
            
            if (confirmed) {
                this.deleting = true;
                this.deletingId = item.id;
                
                try {
                    const itemSlug = item.slug;
                    console.log('Deleting item with slug:', itemSlug);
                    
                    await axios.post(`course-essential-requirements/destroy/${itemSlug}`);

                    window.s_alert('Item deleted successfully!');
                    await this.getLearningItems(); // Refresh the list
                } catch (error) {
                    console.error('Error deleting item:', error);
                    window.s_error('Failed to delete item!');
                } finally {
                    this.deleting = false;
                    this.deletingId = null;
                }
            }
        },
        
        getStatusLabel(status) {
            const labels = {
                'active': 'Active',
                'inactive': 'Inactive',
            };
            return labels[status] || 'Unknown';
        },
        
        getStatusClass(status) {
            const classes = {
                'active': 'badge badge-success',
                'inactive': 'badge badge-danger',
            };
            return classes[status] || 'badge badge-light';
        },
        
        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },
    },
    
    async mounted() {
        await this.getLearningItems();
    }
};
</script>

<style scoped>
.course-what-learn-all {
    max-width: 100%;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.header-actions .btn {
    border-radius: 20px;
    font-size: 0.9rem;
}

.learning-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.learning-item {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s ease;
}

.learning-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-color: #007bff;
}

.item-content {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.item-number {
    background-color: #007bff;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.item-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.item-details {
    flex: 1;
}

.item-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 8px;
}

.item-description {
    color: #6c757d;
    margin-bottom: 10px;
    line-height: 1.5;
}

.item-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.item-actions {
    display: flex;
    flex-direction: row;
    gap: 5px;
    flex-shrink: 0;
}

.item-actions .btn {
    min-width: 40px;
    padding: 5px 8px;
}

.empty-state {
    padding: 60px 20px;
}

.empty-icon {
    opacity: 0.5;
}

.badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .item-content {
        flex-direction: column;
        gap: 10px;
    }
    
    .item-number {
        align-self: flex-start;
    }
    
    .item-actions {
        flex-direction: row;
        justify-content: flex-start;
    }
    
    .item-actions .btn {
        min-width: auto;
    }
    
    .header-actions {
        margin-top: 15px;
    }
}

@media (max-width: 576px) {
    .learning-item {
        padding: 15px;
    }
    
    .item-actions .btn {
        font-size: 0.8rem;
        padding: 4px 6px;
    }
}
</style>


