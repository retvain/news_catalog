<template>
    <div class="modal-container"
         @keydown.esc.stop="$emit('modal-close-btn-clicked')"
    >
        <div class="modal-header">
            <v-btn
                icon
                @click="$emit('modal-close-btn-clicked')"
            >
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </div>

        <div class="modal-body">
            <h3>{{ this.newsHeader }}</h3>
            <p>{{ this.newsBody }}</p>
        </div>

        <div class="modal-footer">
            <v-chip-group
                active-class="primary--text"
                column
            >
                <v-chip
                    v-for="tag in this.rubricsData"
                    :key="tag.id"
                    color="#ebe6f2"
                >
                    {{ tag }}
                </v-chip>
            </v-chip-group>
        </div>
    </div>
</template>

<script>

export default {
    name: 'NewsModalView',
    components: {  },
    props: {
        id: {
            type: Number,
            required: true,
        },
        modalMaskClick: {
            type: Number,
        },
    },
    watch: {
        modalMaskClick: function (newVal) {
            if (newVal) {
                this.$emit('modal-close-btn-clicked')
            }
        },
    },
    data: function () {
        return {
            articleData: {},
            rubricsData: [],
        }
    },
    computed: {
        requestRoute: function () {
            return process.env.VUE_APP_HOST + '/api/news/' + this.id;
        },
        newsHeader: function () {
            return this?.articleData?.news_header || 'нет данных';
        },
        newsBody: function () {
            return this?.articleData?.news_body || 'нет данных';
        },
    },
    methods: {
        updateData() {
            this.axios.get(
                this.requestRoute,
            ).then(response => {
                if (response.data?.success) {
                    this.articleData = response?.data?.payload;
                    this.rubricsData = response?.data?.payload.rubrics.map(item => item.rubric_name);
                } else {
                    console.log(response?.data);
                }
            }).catch(error => {
                console.log(error);
            });
        },
    },
    created() {
        this.updateData();
    },
}

</script>

<style lang="scss">

</style>
