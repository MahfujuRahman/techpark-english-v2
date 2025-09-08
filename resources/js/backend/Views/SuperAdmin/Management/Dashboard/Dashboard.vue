<template>
  <div class="container-fluid">
    <!--Start Dashboard Content-->
    <div class="card mt-3">
      <div class="card-content">
        <div class="row row-group m-0">
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.total_students }}
                <!-- <span class="float-right"><i class="fa fa-users"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                Total Students
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.total_courses }}
                <!-- <span class="float-right"><i class="fa fa-book"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                Total Courses
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.total_instructors }}
                <!-- <span class="float-right"><i class="fa fa-eye"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                Total Instructors
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.total_quizzes }}
                <!-- <span class="float-right"><i class="fa fa-envira"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                Total Quizzes
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-content">
        <div class="row row-group m-0">
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.total_blogs }}
                <!-- <span class="float-right"><i class="fa fa-users"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                Total Blogs
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.total_enrollments }}
                <!-- <span class="float-right"><i class="fa fa-book"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                Total Enrollments
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.total_seminars }}
                <!-- <span class="float-right"><i class="fa fa-eye"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                Total Seminars
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
              <h5 class="text-white mb-0">
                {{ data.total_subscribers }}
                <!-- <span class="float-right"><i class="fa fa-envira"></i></span> -->
              </h5>
              <div class="progress my-3" style="height: 3px">
                <!-- <div class="progress-bar" style="width: 55%"></div> -->
              </div>
              <p class="mb-0 text-white small-font">
                Total Subscribers
                <span class="float-right">
                  <!-- <i class="zmdi zmdi-long-arrow-up"></i> -->
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--End Row-->

    <!-- Google Analytics Section -->
    <div class="row">
      <div class="col-12">
        <div class="card mt-3">
          <div class="card-header">
            <h4 class="card-title">Google Analytics Dashboard</h4>
          </div>
          <div class="card-body">
            <!-- Analytics Summary Cards -->
            <div class="row mb-4">
              <div class="col-12 col-lg-6 col-xl-3">
                <div class="card text-white bg-primary">
                  <div class="card-body">
                    <h5 class="text-white mb-0">
                      {{ realtimeActiveUsers }}
                      <span class="float-right"><i class="fa fa-users"></i></span>
                    </h5>
                    <p class="mb-0 text-white small-font mt-2">
                      Total Active Users (7 days)
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-6 col-xl-3">
                <div class="card text-white bg-success">
                  <div class="card-body">
                    <h5 class="text-white mb-0">
                      {{ totalSessions }}
                      <span class="float-right"><i class="fa fa-eye"></i></span>
                    </h5>
                    <p class="mb-0 text-white small-font mt-2">
                      Total Sessions (7 days)
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-6 col-xl-3">
                <div class="card text-white bg-info">
                  <div class="card-body">
                    <h5 class="text-white mb-0">
                      {{ realtimePageviews }}
                      <span class="float-right"><i class="fa fa-file-text-o"></i></span>
                    </h5>
                    <p class="mb-0 text-white small-font mt-2">
                      Total Pageviews (7 days)
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-6 col-xl-3">
                <div class="card text-white bg-warning">
                  <div class="card-body">
                    <h5 class="text-white mb-0">
                      {{ topCountry }}
                      <span class="float-right"><i class="fa fa-globe"></i></span>
                    </h5>
                    <p class="mb-0 text-white small-font mt-2">
                      Top Country
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
      <div class="col-12">
        <div class="card mt-3">
          <div class="card-header">
            <h4 class="card-title">Active Users & Sessions Over Time</h4>
          </div>
          <div class="card-body">
            <!-- Limit max height so chart doesn't grow too large on wide screens -->
            <div class="chart-container" style="max-height:400px; height:400px;">
              <canvas id="metricsChart" style="width:100%; height:100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="card mt-3">
          <div class="card-header">
            <h4 class="card-title">Top Pages</h4>
          </div>
          <div class="card-body d-flex align-items-center justify-content-center">
            <div class="chart-container" style="max-height:350px; height:350px; width:100%;">
              <canvas id="topPagesChart" style="width:100%; height:100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card mt-3">
          <div class="card-header">
            <h4 class="card-title">Users by Country</h4>
          </div>
          <div class="card-body d-flex align-items-center justify-content-center">
            <div class="chart-container" style="max-height:350px; height:350px;">
              <canvas id="countryChart" style="width:100%; height:100%;"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--End Dashboard Content-->
    <!--start overlay-->
    <div class="overlay"></div>
    <!--end overlay-->
  </div>
  <!-- End container-fluid-->
</template>

<script>
import { Chart, registerables } from 'chart.js';
import { nextTick } from 'vue';
import axios from 'axios';
import { color } from 'chart.js/helpers';

Chart.register(...registerables);

export default {
  data() {
    return {
      data: {},
      googleAnalytics: {
        realtime: {
          active_users: 0,
          pageviews: 0,
          top_pages: []
        },
        historical: {
          metrics_over_time: [],
          top_pages: [],
          users_by_country: []
        }
      },
      charts: {
        metricsChart: null,
        topPagesChart: null,
        countryChart: null
      },
      chartsRendered: false
    };
  },
  computed: {
    totalActiveUsers() {
      return this.googleAnalytics.historical.metrics_over_time.reduce((sum, day) => sum + day.active_users, 0);
    },
    totalSessions() {
      return this.googleAnalytics.historical.metrics_over_time.reduce((sum, day) => sum + day.sessions, 0);
    },
    totalPageviews() {
      return this.googleAnalytics.historical.metrics_over_time.reduce((sum, day) => sum + day.pageviews, 0);
    },
    topCountry() {
      return this.googleAnalytics.historical.users_by_country.length > 0
        ? this.googleAnalytics.historical.users_by_country[0].country
        : 'N/A';
    },
    realtimeActiveUsers() {
      return this.googleAnalytics.realtime.active_users || 0;
    },
    realtimePageviews() {
      return this.googleAnalytics.realtime.pageviews || 0;
    },
    realtimeTopPages() {
      return this.googleAnalytics.realtime.top_pages || [];
    }
  },
  async created() {
    await this.getAllDashboardData();
    await nextTick(); // Wait for DOM updates
    this.renderCharts();
  },
  methods: {
    async getAllDashboardData() {
      try {
        const response = await axios.get("/get-all-dashboard-data");
        if (response.status === 200) {
          this.data = response.data.data;
          console.log('Dashboard data received:', this.data);

          // Set default empty arrays if Google Analytics data is missing
          this.googleAnalytics = {
            realtime: this.data.google_analytics?.realtime || {
              active_users: 0,
              pageviews: 0,
              top_pages: []
            },
            historical: this.data.google_analytics?.historical || {
              metrics_over_time: [],
              top_pages: [],
              users_by_country: []
            }
          };

          console.log('Google Analytics data:', this.googleAnalytics);

          // Update charts if they already exist, otherwise they will be rendered in created()
          if (this.chartsRendered) {
            await nextTick();
            this.updateCharts();
          }
        }
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    },
    renderCharts() {
      // Prevent multiple chart renderings
      if (this.chartsRendered) {
        return;
      }

      // Destroy existing charts to prevent memory leaks
      Object.values(this.charts).forEach(chart => {
        if (chart) {
          chart.destroy();
        }
      });

      // Set global Chart.js defaults for white text
      Chart.defaults.color = '#fff';
      Chart.defaults.font.family = 'Arial, sans-serif';
      Chart.defaults.font.size = 12;

      // Metrics over time chart
      const metricsCtx = document.getElementById("metricsChart");
      if (metricsCtx) {
        const metricsContext = metricsCtx.getContext("2d");

        if (this.googleAnalytics.historical.metrics_over_time.length === 0) {
          // Render empty chart with message
          this.charts.metricsChart = new Chart(metricsContext, {
            type: "line",
            data: {
              labels: [],
              datasets: []
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'No metrics data available',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                }
              }
            }
          });
        } else {
          this.charts.metricsChart = new Chart(metricsContext, {
            type: "line",
            data: {
              labels: this.googleAnalytics.historical.metrics_over_time.map(d => d.date),
              datasets: [
                {
                  label: "Active Users",
                  data: this.googleAnalytics.historical.metrics_over_time.map(d => d.active_users),
                  borderColor: "#007bff",
                  backgroundColor: "rgba(0, 123, 255, 0.1)",
                  tension: 0.4,
                  fill: true
                },
                {
                  label: "Sessions",
                  data: this.googleAnalytics.historical.metrics_over_time.map(d => d.sessions),
                  borderColor: "#28a745",
                  backgroundColor: "rgba(40, 167, 69, 0.1)",
                  tension: 0.4,
                  fill: false
                },
                {
                  label: "Pageviews",
                  data: this.googleAnalytics.historical.metrics_over_time.map(d => d.pageviews),
                  borderColor: "#fd7e14",
                  backgroundColor: "rgba(253, 126, 20, 0.1)",
                  tension: 0.4,
                  fill: false
                }
              ]
            },
            options: {
              responsive: true,
              interaction: {
                mode: 'index',
                intersect: false,
              },
              plugins: {
                title: {
                  display: true,
                  text: 'Analytics Metrics Over Time',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                },
                legend: {
                  position: 'top',
                  labels: {
                    color: '#fff',
                    font: {
                      size: 14
                    }
                  }
                }
              },
              scales: {
                x: {
                  display: true,
                  title: {
                    display: true,
                    text: 'Date',
                    color: '#fff',
                    font: {
                      size: 14,
                      weight: 'bold'
                    }
                  },
                  ticks: {
                    color: '#fff'
                  },
                  grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                  }
                },
                y: {
                  display: true,
                  title: {
                    display: true,
                    text: 'Count',
                    color: '#fff',
                    font: {
                      size: 14,
                      weight: 'bold'
                    }
                  },
                  beginAtZero: true,
                  ticks: {
                    color: '#fff'
                  },
                  grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                  }
                }
              }
            }
          });
        }
      }

      // Top Pages chart
      const topPagesCtx = document.getElementById("topPagesChart");
      if (topPagesCtx) {
        const topPagesContext = topPagesCtx.getContext("2d");

        if (this.googleAnalytics.historical.top_pages.length === 0) {
          this.charts.topPagesChart = new Chart(topPagesContext, {
            type: "bar",
            data: {
              labels: [],
              datasets: []
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'No top pages data available',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                }
              }
            }
          });
        } else {
          this.charts.topPagesChart = new Chart(topPagesContext, {
            type: "bar",
            data: {
              labels: this.googleAnalytics.historical.top_pages.map(d => d.page),
              datasets: [{
                label: "Pageviews",
                data: this.googleAnalytics.historical.top_pages.map(d => d.pageviews),
                backgroundColor: [
                  '#6f42c1',
                  '#e83e8c',
                  '#fd7e14',
                  '#20c997',
                  '#6610f2'
                ],
                borderColor: [
                  '#6f42c1',
                  '#e83e8c',
                  '#fd7e14',
                  '#20c997',
                  '#6610f2'
                ],
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'Top Pages by Pageviews',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                },
                legend: {
                  display: false
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: true,
                    text: 'Pageviews',
                    color: '#fff',
                    font: {
                      size: 14,
                      weight: 'bold'
                    }
                  },
                  ticks: {
                    color: '#fff'
                  },
                  grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                  }
                },
                x: {
                  title: {
                    display: true,
                    text: 'Pages',
                    color: '#fff',
                    font: {
                      size: 14,
                      weight: 'bold'
                    }
                  },
                  ticks: {
                    color: '#fff'
                  },
                  grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                  }
                }
              }
            }
          });
        }
      }

      // Users by Country chart
      const countryCtx = document.getElementById("countryChart");
      if (countryCtx) {
        const countryContext = countryCtx.getContext("2d");

        if (this.googleAnalytics.historical.users_by_country.length === 0) {
          this.charts.countryChart = new Chart(countryContext, {
            type: "doughnut",
            data: {
              labels: [],
              datasets: []
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'No country data available',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                }
              }
            }
          });
        } else {
          this.charts.countryChart = new Chart(countryContext, {
            type: "doughnut",
            data: {
              labels: this.googleAnalytics.historical.users_by_country.map(d => d.country),
              datasets: [{
                label: "Active Users",
                data: this.googleAnalytics.historical.users_by_country.map(d => d.active_users),
                backgroundColor: [
                  '#dc3545',
                  '#007bff',
                  '#28a745',
                  '#fd7e14',
                  '#6f42c1',
                  '#e83e8c',
                  '#20c997'
                ],
                borderWidth: 2,
                borderColor: '#fff'
              }]
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'Active Users by Country',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                },
                legend: {
                  position: 'right',
                  labels: {
                    color: '#fff',
                    font: {
                      size: 14
                    }
                  }
                }
              }
            }
          });
        }
      }

      this.chartsRendered = true;
    },
    renderCharts() {
      // Prevent multiple chart renderings
      if (this.chartsRendered) {
        return;
      }

      // Destroy existing charts to prevent memory leaks
      Object.values(this.charts).forEach(chart => {
        if (chart) {
          chart.destroy();
        }
      });

      // Set global Chart.js defaults for white text
      Chart.defaults.color = '#fff';
      Chart.defaults.font.family = 'Arial, sans-serif';
      Chart.defaults.font.size = 12;

      // Metrics over time chart
      const metricsCtx = document.getElementById("metricsChart");
      if (metricsCtx) {
        const metricsContext = metricsCtx.getContext("2d");

        if (this.googleAnalytics.historical.metrics_over_time.length === 0) {
          // Render empty chart with message
          this.charts.metricsChart = new Chart(metricsContext, {
            type: "line",
            data: {
              labels: [],
              datasets: []
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'No metrics data available',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                }
              }
            }
          });
        } else {
          this.charts.metricsChart = new Chart(metricsContext, {
            type: "line",
            data: {
              labels: this.googleAnalytics.historical.metrics_over_time.map(d => d.date),
              datasets: [
                {
                  label: "Active Users",
                  data: this.googleAnalytics.historical.metrics_over_time.map(d => d.active_users),
                  borderColor: "#007bff",
                  backgroundColor: "rgba(0, 123, 255, 0.1)",
                  tension: 0.4,
                  fill: true
                },
                {
                  label: "Sessions",
                  data: this.googleAnalytics.historical.metrics_over_time.map(d => d.sessions),
                  borderColor: "#28a745",
                  backgroundColor: "rgba(40, 167, 69, 0.1)",
                  tension: 0.4,
                  fill: false
                },
                {
                  label: "Pageviews",
                  data: this.googleAnalytics.historical.metrics_over_time.map(d => d.pageviews),
                  borderColor: "#fd7e14",
                  backgroundColor: "rgba(253, 126, 20, 0.1)",
                  tension: 0.4,
                  fill: false
                }
              ]
            },
            options: {
              responsive: true,
              interaction: {
                mode: 'index',
                intersect: false,
              },
              plugins: {
                title: {
                  display: true,
                  text: 'Analytics Metrics Over Time',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                },
                legend: {
                  position: 'top',
                  labels: {
                    color: '#fff',
                    font: {
                      size: 14
                    }
                  }
                }
              },
              scales: {
                x: {
                  display: true,
                  title: {
                    display: true,
                    text: 'Date',
                    color: '#fff',
                    font: {
                      size: 14,
                      weight: 'bold'
                    }
                  },
                  ticks: {
                    color: '#fff'
                  },
                  grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                  }
                },
                y: {
                  display: true,
                  title: {
                    display: true,
                    text: 'Count',
                    color: '#fff',
                    font: {
                      size: 14,
                      weight: 'bold'
                    }
                  },
                  beginAtZero: true,
                  ticks: {
                    color: '#fff'
                  },
                  grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                  }
                }
              }
            }
          });
        }
      }

      // Top Pages chart
      const topPagesCtx = document.getElementById("topPagesChart");
      if (topPagesCtx) {
        const topPagesContext = topPagesCtx.getContext("2d");

        if (this.googleAnalytics.historical.top_pages.length === 0) {
          this.charts.topPagesChart = new Chart(topPagesContext, {
            type: "bar",
            data: {
              labels: [],
              datasets: []
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'No top pages data available',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                }
              }
            }
          });
        } else {
          this.charts.topPagesChart = new Chart(topPagesContext, {
            type: "bar",
            data: {
              labels: this.googleAnalytics.historical.top_pages.map(d => d.page),
              datasets: [{
                label: "Pageviews",
                data: this.googleAnalytics.historical.top_pages.map(d => d.pageviews),
                backgroundColor: [
                  '#6f42c1',
                  '#e83e8c',
                  '#fd7e14',
                  '#20c997',
                  '#6610f2'
                ],
                borderColor: [
                  '#6f42c1',
                  '#e83e8c',
                  '#fd7e14',
                  '#20c997',
                  '#6610f2'
                ],
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'Top Pages by Pageviews',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                },
                legend: {
                  display: false
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: {
                    display: true,
                    text: 'Pageviews',
                    color: '#fff',
                    font: {
                      size: 14,
                      weight: 'bold'
                    }
                  },
                  ticks: {
                    color: '#fff'
                  },
                  grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                  }
                },
                x: {
                  title: {
                    display: true,
                    text: 'Pages',
                    color: '#fff',
                    font: {
                      size: 14,
                      weight: 'bold'
                    }
                  },
                  ticks: {
                    color: '#fff'
                  },
                  grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                  }
                }
              }
            }
          });
        }
      }

      // Users by Country chart
      const countryCtx = document.getElementById("countryChart");
      if (countryCtx) {
        const countryContext = countryCtx.getContext("2d");

        if (this.googleAnalytics.historical.users_by_country.length === 0) {
          this.charts.countryChart = new Chart(countryContext, {
            type: "doughnut",
            data: {
              labels: [],
              datasets: []
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'No country data available',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                }
              }
            }
          });
        } else {
          this.charts.countryChart = new Chart(countryContext, {
            type: "doughnut",
            data: {
              labels: this.googleAnalytics.historical.users_by_country.map(d => d.country),
              datasets: [{
                label: "Active Users",
                data: this.googleAnalytics.historical.users_by_country.map(d => d.active_users),
                backgroundColor: [
                  '#dc3545',
                  '#007bff',
                  '#28a745',
                  '#fd7e14',
                  '#6f42c1',
                  '#e83e8c',
                  '#20c997'
                ],
                borderWidth: 2,
                borderColor: '#fff'
              }]
            },
            options: {
              responsive: true,
              plugins: {
                title: {
                  display: true,
                  text: 'Active Users by Country',
                  color: '#fff',
                  font: {
                    size: 16,
                    weight: 'bold'
                  }
                },
                legend: {
                  position: 'right',
                  labels: {
                    color: '#fff',
                    font: {
                      size: 14
                    }
                  }
                }
              }
            }
          });
        }
      }

      this.chartsRendered = true;
    },
    updateCharts() {
      // Update existing charts with new data instead of recreating them
      if (this.charts.metricsChart && this.googleAnalytics.historical.metrics_over_time.length > 0) {
        this.charts.metricsChart.data.labels = this.googleAnalytics.historical.metrics_over_time.map(d => d.date);
        this.charts.metricsChart.data.datasets[0].data = this.googleAnalytics.historical.metrics_over_time.map(d => d.active_users);
        this.charts.metricsChart.data.datasets[1].data = this.googleAnalytics.historical.metrics_over_time.map(d => d.sessions);
        this.charts.metricsChart.data.datasets[2].data = this.googleAnalytics.historical.metrics_over_time.map(d => d.pageviews);
        this.charts.metricsChart.update();
      }

      if (this.charts.topPagesChart && this.googleAnalytics.historical.top_pages.length > 0) {
        this.charts.topPagesChart.data.labels = this.googleAnalytics.historical.top_pages.map(d => d.page);
        this.charts.topPagesChart.data.datasets[0].data = this.googleAnalytics.historical.top_pages.map(d => d.pageviews);
        this.charts.topPagesChart.update();
      }

      if (this.charts.countryChart && this.googleAnalytics.historical.users_by_country.length > 0) {
        this.charts.countryChart.data.labels = this.googleAnalytics.historical.users_by_country.map(d => d.country);
        this.charts.countryChart.data.datasets[0].data = this.googleAnalytics.historical.users_by_country.map(d => d.active_users);
        this.charts.countryChart.update();
      }
    }
  },
  beforeUnmount() {
    // Clean up charts to prevent memory leaks
    Object.values(this.charts).forEach(chart => {
      if (chart) {
        chart.destroy();
      }
    });
  }
};

</script>



<style scoped></style>
