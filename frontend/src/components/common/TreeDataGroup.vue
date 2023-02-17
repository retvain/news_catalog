<template>
    <div class="tree-data-group">
        <tree-data-item
            v-for="(item, index) in treeData"
            :key="index"
            class="tree-data-item"
            :item="item"
            :last-clicked-item-id="lastClickedItemId"
            @change-event="transmit"
            @item-clicked="itemClickHandler"
            @item-toggle="$emit('item-toggle')"
            @dropdown-btn-clicked="$emit('dropdown-btn-clicked', $event)"
        ></tree-data-item>
    </div>
</template>

<script>

import TreeDataItem from '../common/TreeDataItem.vue';

export default {
    name: 'TreeDataGroup',
    components: { TreeDataItem },
    props: {
        treeData: {
            type: Array,
            required: true,
        },
    },
    data: function () {
        return {
            lastClickedItemId: 0,
        }
    },
    methods: {
        itemClickHandler(item) {
            this.lastClickedItemId = item.id;
            this.$emit('item-clicked', item)
        },
        transmit(event, item) {
            this.$emit(event, item)
        },
    },
}
</script>

<style lang="scss">
.tree-data-item {
    display: flex;
    flex-direction: column;
}
.tree-data-item.selected {
    background-color: #ebe6f2;
    border-radius: 12px;
}
</style>
