<template lang="">
  <div class="col-md-6">
    <label> {{ setup.module_name }} </label>
    <!-- {{ value }} -->
    <!-- {{ all.data }} -->
    <div class="custom_drop_down mb-2">
      <div class="selected_list  c-pointer position-relative" @click="show_list = !show_list">
        <template v-if="!selected.length">
          <div class="">Select {{ setup.module_name }}</div>
          <i :class="show_list ? 'fa fa-angle-up' : 'fa fa-angle-down'"  style="position: absolute; right: 10px;"></i>
        </template>

<template v-else>
          <div v-for="item in selected" :key="item.id" :id="item.id" class="selected_item">
            <div class="label">
              {{ item.name || item.title  }}
            </div>
            <div @click.prevent="remove_item(item)" class="remove">
              <i class="fa fa-close"></i>
            </div>
          </div>
        </template>
</div>
<div class="drop_down_items" v-if="show_list">
  <div class="drop_down_data_search">
    <input @keyup="search_item($event)" class="form-control" placeholder="search.." id="table_search_box"
      type="search" />

    <button type="button" @click.prevent="show_list = false" class="btn btn-danger">
      <i class="fa fa-close"></i>
    </button>
  </div>
  <ul class="option_list custom_scroll">
    <li class="option_item" v-for="item in all.data" :key="item.id">
      <label :for="`drop_item_${item.id}`">
        <div class="check_box">
          <input @change="set_selected(item, $event)" :checked="is_selected(item)" type="checkbox"
            :id="`drop_item_${item.id}`" class="form-check-input ml-0" />
        </div>
        <div class="label">{{ item.name || item.title }}</div>
      </label>
    </li>
  </ul>
  <div class="drop_down_footer data_list">
    <pagination :data="all" :get_data="get_all" :set_paginate="set_paginate" :set_page="set_page" />
  </div>
</div>
</div>
<input type="hidden" :id="name" :name="name" :value="multiple ? `[${selected_ids}]` : `${selected_ids}`" />
</div>
</template>
<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../../store";
import debounce from "../../helpers/debounce";
import setup from "../../setup";
export default {
  props: {
    multiple: {
      type: Boolean,
      default: false,
    },
    name: {
      type: String,
      default: "users_" + parseInt(Math.random() * 1000),
    },
    value: {
      type: [Array, Object, String, Number],
      default: [],
    },
  },
  created: function () {
    if (!this.all?.data?.length) {
      this.get_all();
    }
    this.$watch("value", function (v) {
      // If value is an array of objects, set selected directly
      if (Array.isArray(v) && v.length && typeof v[0] === 'object') {
        this.selected = v;
      } else if (Array.isArray(v) && this.all && Array.isArray(this.all.data)) {
        // fallback for array of ids, only if all.data is available
        this.selected = this.all.data.filter(item => v.includes(item.id));
      } else if (typeof v === 'object' && v !== null && v.id) {
        // Handle single object (like from relationship)
        this.selected = [v];
      } else if ((typeof v === 'string' || typeof v === 'number') && this.all && Array.isArray(this.all.data)) {
        // Handle single ID (string or number)
        const item = this.all.data.find(item => item.id == v);
        this.selected = item ? [item] : [];
      } else {
        this.selected = [];
      }
    }, { immediate: true });
  },
  data: () => ({
    selected: [],
    show_list: false,
    setup,
  }),
  methods: {
    ...mapActions(store, ["get_all", "set_paginate", "set_page"]),
    search_item: debounce(async function (event) {
      let value = event.target.value;
      this.search_key = value;
      this.only_latest_data = true;
      await this.get_all();
      this.only_latest_data = false;
    }, 500),
    set_selected: function (item, event) {
      if (!this.multiple) {
  this.selected = [item];
  // notify parent about change (single select)
  try { this.$emit('selected-changed', item.id || item); } catch(e){}
  return;
      }
      // Defensive: event may be undefined when called from watcher
      const checked = event && event.target ? event.target.checked : true;
      if (checked) {
        if (!this.selected.find((i) => i.id === item.id)) {
          this.selected.push(item);
        }
      } else {
        this.selected = this.selected.filter((i) => i.id != item.id);
      }
    },
    is_selected: function (item) {
      return this.selected.find((i) => i.id == item.id);
    },
    remove_item: function (item) {
      this.selected = this.selected.filter((i) => i.id != item.id);
  try { this.$emit('selected-changed', null); } catch(e){}
    },
  },
  computed: {
    ...mapState(store, ["all"]),
    ...mapWritableState(store, ["search_key"]),
    selected_ids: function () {
      return this.selected.map((i) => i.id).join(",");
    },
  },
};
</script>


<style scoped>
.selected_list {
  overflow: hidden;
}
</style>