<template>
    <div class="tree-data-group">
        <tree-data-item v-for="(item, index) in treeData" :key="index" :updateKey="updateKey" class="tree-data-item"
            :item="item" :last-clicked-item-id="lastClickedItemId" @item-clicked="itemClickHandler"
            @item-toggle="$emit('item-toggle')"></tree-data-item>
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
        updateKey: {
            type: Number,
        }
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
    },
}
</script>

<style lang="scss">
.tree-data-item {
    display: flex;
    flex-direction: column;
}
</style>
