<template>
    <div class="course-batch-all">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Course Batch List</h6>
                    </div>
                    <div>
                        <router-link :to="{ name: 'CourseBatchCreate', params: { id: $route.params.id } }"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Create New Batch
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Admission Start Date</th>
                                <th>Admission End Date</th>
                                <th>Price</th>
                                <th>Sale Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody v-if="batches?.length">
                            <tr v-for="(batch, index) in batches" :key="batch.id">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <div class="batch-name">
                                        <strong>{{ batch.batch_name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div v-if="batch.admission_start_date">
                                        {{ formatDateTime(batch.admission_start_date) }}
                                    </div>
                                    <span v-else class="text-muted">-</span>
                                </td>
                                <td>
                                    <div v-if="batch.admission_end_date">
                                        {{ formatDateTime(batch.admission_end_date) }}
                                    </div>
                                    <span v-else class="text-muted">-</span>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <div class="original-price">{{ formatCurrency(batch.course_price) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ formatCurrency(batch.after_discount_price) }}</strong>
                                </td>
                                <td>
                                    <span v-if="batch.status === 'active'" class="badge badge-success">Active</span>
                                    <span v-else class="badge badge-danger">Inactive</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <router-link
                                            :to="{ name: 'CourseBatchDetails', params: { id: $route.params.id, batch_id: batch.slug } }"
                                            class="btn btn-sm btn-outline-primary mr-2" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </router-link>
                                        <router-link
                                            :to="{ name: 'CourseBatchEdit', params: { id: $route.params.id, batch_id: batch.slug } }"
                                            class="btn btn-sm btn-outline-warning mr-2" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button @click="deleteBatch(batch)" class="btn btn-sm btn-outline-danger"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="no-data-container">
                                        <div class="no-data-icon">
                                            <i class="fas fa-calendar-alt fa-3x"></i>
                                        </div>
                                        <div class="no-data-text">
                                            <h6>No batches found</h6>
                                            <p>Create a new batch for this course.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
    name: 'CourseBatchAll',

    data() {
        return {
            batches: [],
        };
    },

    computed: {
        ...mapState(useCourseDetailsStore, ['currentCourse', 'loading']),
    },

    methods: {
        ...mapActions(useCourseDetailsStore, ['clearMessages']),

        async getCourseBatches() {
            const courseSlug = this.$route.params.id;
            if (!courseSlug) return;

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

                console.log('Fetching batches for course ID:', courseId);
                const response = await axios.get(`course-batches?course_id=${courseId}`);

                console.log('API Response:', response.data);

                // Check if response has the expected structure
                if (response.data && response.data.status === 'success') {
                    this.batches = response.data.data?.data || [];
                    console.log('Loaded batches:', this.batches);
                } else {
                    console.error('Unexpected response structure:', response.data);
                    this.batches = [];
                }
            } catch (error) {
                console.error('Error fetching course batches:', error);
                this.batches = [];
            }
        },

        async deleteBatch(batch) {
            const confirmed = await window.s_confirm('Are you sure you want to delete this batch?', 'Yes, delete it!');
            if (confirmed) {
                try {
                    const batchSlug = batch.slug; // Use slug from the batch data
                    console.log('Deleting batch with slug:', batchSlug);
                    await axios.post(`course-batches/destroy/${batchSlug}`);
                    window.s_alert('Batch deleted successfully!');
                    await this.getCourseBatches();
                } catch (error) {
                    console.error('Error deleting batch:', error);
                    window.s_error('Failed to delete batch!');
                }
            }
        },

        formatDate(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },

        formatDateTime(datetime) {
            if (!datetime) return 'N/A';
            const date = new Date(datetime);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        formatTime(time) {
            if (!time) return 'N/A';
            // Assuming time is in HH:MM format
            const [hours, minutes] = time.split(':');
            const date = new Date();
            date.setHours(parseInt(hours), parseInt(minutes));

            return date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        },

        formatCurrency(amount) {
            if (!amount) return '$0';
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'BDT', // Use your desired currency symbol
                currencyDisplay: 'narrowSymbol',
                minimumFractionDigits: 0
            }).format(amount);
        },

        getProgressBarClass(percentage) {
            if (percentage >= 80) return 'bg-danger';
            if (percentage >= 60) return 'bg-warning';
            return 'bg-success';
        }
    },

    async mounted() {
        await this.getCourseBatches();
    }
};
</script>

<style scoped>
.course-batch-all {
    position: relative;
}

.no-data-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-data-icon {
    margin-bottom: 15px;
    opacity: 0.5;
}

.no-data-text h6 {
    margin-bottom: 8px;
    font-weight: 600;
}

.no-data-text p {
    margin: 0;
    font-size: 0.9rem;
}

.batch-name {
    min-width: 120px;
}

.batch-name strong {
    color: #ffffff;
}

.price-info {
    text-align: center;
}

.original-price {
    font-weight: 500;
}

.discount-badge {
    background-color: #dc3545;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 10px;
    margin-top: 2px;
}

.booking-info {
    min-width: 100px;
}

.booking-numbers {
    font-weight: 500;
    text-align: center;
}

.booking-percentage {
    text-align: center;
    margin-top: 2px;
}

.time-info {
    text-align: center;
    font-size: 0.85rem;
}

.btn-group .btn {
    margin-right: 2px;
    padding: 4px 8px;
}

.btn-group .btn:last-child {
    margin-right: 0;
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

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-top: none;
    font-size: 0.85rem;
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
}

.table td {
    font-size: 0.85rem;
    vertical-align: middle;
    padding: 12px 8px;
}

.badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.badge-success {
    background-color: #28a745;
}

.badge-danger {
    background-color: #dc3545;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    transition: width 0.3s ease;
}

/* Responsive */
@media (max-width: 1200px) {
    .table-responsive {
        font-size: 0.8rem;
    }

    .table th,
    .table td {
        padding: 8px 6px;
    }
}

@media (max-width: 992px) {
    .btn-group {
        flex-direction: column;
    }

    .btn-group .btn {
        margin-bottom: 2px;
        margin-right: 0;
        font-size: 0.75rem;
    }

    .time-info,
    .booking-info,
    .price-info {
        font-size: 0.75rem;
    }
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.7rem;
    }

    .table th,
    .table td {
        padding: 6px 4px;
    }

    .batch-name {
        min-width: 80px;
    }

    .booking-info {
        min-width: 70px;
    }

    .btn-group .btn {
        padding: 2px 4px;
    }

    .btn-group .btn i {
        font-size: 0.7rem;
    }
}

/* Table scrolling for mobile */
@media (max-width: 576px) {
    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }
}
</style>
