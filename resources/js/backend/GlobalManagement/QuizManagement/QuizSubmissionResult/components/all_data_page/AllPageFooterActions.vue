<template lang="">
    <div class="d-flex footer-actions">
         <!-- <create /> -->
        <export-all />
        <export-selected />
        <import />
        <active />
        <inactive />
        <trash />
        <bulk-actions />
    </div>
</template>
<script>
import Create from "./action_buttons/Create.vue"
import ExportAll from "./action_buttons/ExportAll.vue"
import ExportSelected from "./action_buttons/ExportSelected.vue"
import Import from "./action_buttons/Import.vue"
import Active from "./action_buttons/Active.vue"
import Inactive from "./action_buttons/InActive.vue"
import Trash from "./action_buttons/Trash.vue"
import BulkActions from "./action_buttons/BulkActions.vue"
export default {
    components: {
        Create,
        ExportAll,
        ExportSelected,
        Import,
        Active,
        Inactive,
        Trash,
        BulkActions,
    }
}
</script>
<style scoped>
.footer-actions > * {
    margin-right: 8px;
}

.footer-actions > *:last-child {
    margin-right: 0;
}
</style>
