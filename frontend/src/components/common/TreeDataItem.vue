<template>
    <li :class="['tree-data-item', isSelectedItem(item) && 'selected']">
        <div class="item-name" :class="{ bold: haveChild }" @click="itemClickEvent(item)">
            <i class="mdi mdi-folder-outline" v-if="haveChild"></i>
            <i class="mdi mdi-circle-small" v-else></i>
            <div class="item-name">{{ item[this.itemsObject.parentKey] }}</div>

            <div class="mdi mdi-chevron-right" v-if="haveChild" :class="{ 'item-open': isOpen }" @click="toggle(item)">
            </div>
        </div>
        <ul class="tree-data-main" v-show="isOpen" v-if="haveChild">
            <tree-data-item v-for="(item, index) in this.itemsObject.dropdownContent" :item="item" :key="index"
                :last-clicked-item-id="lastClickedItemId" :items-object="itemsObject" @item-clicked="itemClickHandler"
                ></tree-data-item>
        </ul>
    </li>
</template>

<script>

import TreeDataItem from '../common/TreeDataItem.vue'
export default {
    name: 'TreeDataItem',
    components: { TreeDataItem },
    props: {
        idFieldName: {
            type: String,
            default: 'id',
        },
        item: {

        },
        lastClickedItemId: {
            type: Number,
        },
    },
    data() {
        return {
            isOpen: false,
            isDropDownOpen: false,
            itemsObject: {
                itemHaveChild: 'have_child',
                parentKey: 'rubric_name',
                recursionKey: 'id',
                dropdownContent: [],
            },
            urlItemId: 0,
        };
    },
    computed: {
        haveChild: function () {
            return this.item[this.itemsObject.itemHaveChild];
        },
        requestChildrenRoute: function () {
            return process.env.VUE_APP_HOST + '/api/news/rubrics/' + this.urlItemId + '/children';
        },
    },
    methods: {
        updateChildrenData() {
            this.axios.get(
                this.requestChildrenRoute,
            ).then(response => {
                if (response.data?.success) {
                    this.itemsObject.dropdownContent = response?.data?.items;
                } else {
                    console.log(response?.data);
                }
            }).catch(error => {
                console.log(error);
            });
        },
        transmit(event, item) {
            this.$emit('change-event', event, item);
        },
        itemClickEvent(item) {
            this.$emit('item-clicked', item);
        },
        itemClickHandler(item) {
            this.$emit('item-clicked', item);
        },
        toggle(item) {
            this.$emit('item-clicked', item);
            this.urlItemId = item.id;
            this.isOpen = !this.isOpen
            this.$emit('item-toggle');
            this.updateChildrenData();
        },
        isSelectedItem(item) {
            return item[this.idFieldName] === this.lastClickedItemId;
        },
    },
}

</script>

<style scoped lang="scss">
.item-name {
    display: flex;
    align-items: center;
    padding: 6px 12px;
    cursor: pointer;
}

.item-name.bold {
    font-weight: bold;
}

.item-name:hover {
    background-color: #f6f2fc;
    border-radius: 12px;
}

.item-open {
    transform-origin: center center;
    transform: rotate(90deg);
    transition: all 0.5s cubic-bezier(0.25, 1.7, 0.35, 0.8);
    top: -3px;
}

.mdi {
    width: 24px;
    height: 24px;
    font-size: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tree-data-main {
    width: 200px;
    height: 200px;
    background-color: aqua;
}

.mdi-chevron-right:hover {
    background-color: #ae9dc6;
    border-radius: 12px;
}
</style>
