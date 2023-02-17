<template>
    <div class="modal-container" @keydown.esc.stop="$emit('modal-close-btn-clicked')">
        <div class="modal-header">
            <v-btn icon @click="$emit('modal-close-btn-clicked')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </div>

        <div class="modal-body">
            <v-form>
                <v-container>
                    <v-text-field v-model="newsHeader" label="Заголовок" required></v-text-field>

                    <v-text-field v-model="newsAnnouncement" label="Анонс" required></v-text-field>

                    <v-textarea v-model="newsBody" label="Текст" required></v-textarea>

                    <v-select multiple v-model="rubricsId" label="Рубрика" :items="arr">
                    </v-select>
                </v-container>
            </v-form>
            <div v-if="isUpdateError">
                <v-alert colored-border type="error" elevation="2">
                    <v-list-item-content>
                        <v-list-item-title>{{ errorMsg }}</v-list-item-title>
                        <li v-for="error in errors" :key="error.id">{{ error }}</li>
                    </v-list-item-content>
                </v-alert>
            </div>
        </div>

        <div class="modal-footer">
            <v-btn text @click="$emit('modal-close-btn-clicked')">Отмена</v-btn>
            <v-btn text @click="updateRecord()">Сохранить</v-btn>
        </div>
    </div>
</template>

<script>

export default {
    name: 'NewsModalEdit',
    components: {},
    props: {
        id: {
            type: Number,
            required: true,
        },
        modalMaskClick: {
            type: Number,
        },
        arr: {
            type: Array,
        }
    },
    watch: {
    },
    data: function () {
        return {
            articleData: {},
            errorMsg: '',
            errors: {},
            isUpdateError: false,
        }
    },
    computed: {
        requestRoute: function () {
            return process.env.VUE_APP_HOST + '/api/news/' + this.id;
        },
        requestRubricsRoute: function () {
            return process.env.VUE_APP_HOST + '/api/news/rubrics';
        },
        newsHeader: {
            get: function () {
                return this.articleData?.news_header ?? '';
            },
            set: function (newVal) {
                this.articleData.news_header = newVal;
            },
        },
        newsAnnouncement: {
            get: function () {
                return this.articleData?.news_announcement ?? '';
            },
            set: function (newVal) {
                this.articleData.news_announcement = newVal;
            },
        },
        newsBody: {
            get: function () {
                return this.articleData?.news_body ?? '';
            },
            set: function (newVal) {
                this.articleData.news_body = newVal;
            },
        },
        rubricsId: {
            get: function () {
                return this.articleData.rubrics ?? '';
            },
            set: function (newVal) {
                this.articleData.rubrics_id = newVal;
            },
        },
    },
    methods: {
        updateData() {
            this.axios.get(
                this.requestRoute,
            ).then(response => {
                if (response.data?.success) {
                    this.articleData = response?.data?.payload;
                    const items = response?.data?.payload?.rubrics;
                    for (let i = 0; i < items.length; i++) {
                        this.articleData.rubrics[i] = { text: items[i].rubric_name, value: items[i].id, }
                    }
                } else {
                    console.log(response?.data);
                }
            }).catch(error => {
                console.log(error);
            });
        },
        updateRecord() {
            const url = this?.requestRoute;
            const data = this?.articleData;
            this.axios.put(url,
                { data },
            ).then(response => {
                if (response.data?.success) {
                    this.$emit('modal-edit-btn-clicked');
                } else {
                    this.isUpdateError = true;
                    this.errorMsg = response?.data.message;
                    this.errors = response?.data.errors;
                    console.log(response?.data);
                }
            }).catch(error => {
                console.log(error);
            }).finally(() => {
                this.isLoading = false;
            });
        },
    },
    created() {
        this.updateData();
    },
}

</script>

<style lang="scss">
.modal-container.news-modal-edit {
    width: 500px;
    max-height: 100vh;

    .modal-body {
        height: unset;
    }
}
</style>
