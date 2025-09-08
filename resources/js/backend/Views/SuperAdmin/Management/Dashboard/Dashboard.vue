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
    <div class="row">
      <div class="col-12 col-lg-12">
        <div class="card">
          <div class="card-header">Latest Purchase Orders</div>
          <table
            class="table table-hover text-center table-bordered table_responsive"
          >
            <thead>
              <tr>
                <th>#</th>
                <th>Order Id</th>
                <th>reference</th>
                <th>suppliyer</th>
                <th>total</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in data?.getLatesOrders" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ item.Order_id ?? "N/A" }}</td>
                <td>{{ item.reference ?? "N/A" }}</td>
                <td>{{ item.suppliyer?.name ?? "N/A" }}</td>
                <td>{{ item.total ?? "N/A" }}</td>
                <td>{{ item.date ?? "N/A" }}</td>
                <td>
                  <router-link
                    :to="{
                      name: 'EditPurchaseOrder',
                      params: { id: item.slug },
                    }"
                  >
                    <button class="btn btn-info">View</button>
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-lg-12">
        <div class="card">
          <div class="card-header">Latest Sales Orders</div>
          <table
            class="table table-hover text-center table-bordered table_responsive"
          >
            <thead>
              <tr>
                <th>#</th>
                <th>Order Id</th>
                <th>reference</th>
                <th>costomer</th>
                <th>total</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in data?.getLatesSalesOrders"
                :key="index"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ item.order_id ?? "N/A" }}</td>
                <td>{{ item.reference ?? "N/A" }}</td>
                <td>{{ item.customer?.name.substring(0, 10) ?? "N/A" }}</td>
                <td>{{ item.total ?? "N/A" }}</td>
                <td>{{ item.date ?? "N/A" }}</td>
                <td>
                  <router-link
                    :to="{ name: 'EditSalesOrder', params: { id: item.slug } }"
                  >
                    <button class="btn btn-info">View</button>
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
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
export default {
  data: () => ({
    data: {},
  }),
  created: async function () {
    await this.get_all_dashboard_data();
  },
  methods: {
    get_all_dashboard_data: async function () {
      let response = await axios.get("get-all-dashboard-data");
      if (response.status == 200) {
        this.data = response.data.data;
      }
    },
  },
};
</script>

<style></style>
