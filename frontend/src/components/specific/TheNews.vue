<template>
    <v-card>
        <v-toolbar flat>
            <v-btn icon @click="searchData()">
                <v-icon>mdi-magnify</v-icon>
            </v-btn>
            <v-text-field hide-details @keydown.enter="searchData()" single-line v-model="searchStringData.search">
            </v-text-field>
        </v-toolbar>

        <div class="pa-4">
            <div class="news-card">
                <v-card v-for="(item, itemIndex) in this.newsData" :key="itemIndex">
                    <v-toolbar flat>
                        <v-toolbar-title>{{ item.news_header }}</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <v-btn icon @click="setEditAlrticle(item.id)">
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>

                        <v-btn icon @click="setDeleteAlrticle(item.id)">
                            <v-icon>mdi-trash-can-outline</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <div>{{ item.news_announcement }}</div>
                    </v-card-text>
                    <v-card-actions>
                        <div>
                            <v-chip-group active-class="primary--text" column>
                                <v-chip v-for="tag in item.rubrics" :key="tag.id" color="#ebe6f2">
                                    {{ tag.rubric_name }}
                                </v-chip>
                            </v-chip-group>
                        </div>

                        <div>
                            <v-btn color="green" small text @click="setViewAlrticle(item.id)">
                                Просмотр
                            </v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </div>
            <div class="add-news-form">
                <div class="btn-group">
                    <v-btn small text @click="openFormSync" :class="{ 'btn-active': isFormOpen && isSync }">
                        Добавить новость синхронно
                    </v-btn>
                    <v-btn small text @click="openFormAsync" :class="{ 'btn-active': isFormOpen && !isSync }">
                        Добавить новость асинхронно
                    </v-btn>
                </div>
                <div v-if="this.isFormOpen">
                    <v-form>
                        <v-container>
                            <v-text-field v-model="newsHeader" label="Заголовок" required></v-text-field>

                            <v-text-field v-model="newsAnnouncement" label="Анонс" required></v-text-field>

                            <v-text-field v-model="newsBody" label="Текст" required></v-text-field>

                            <v-select multiple v-model="rubricsId" label="Рубрика" :items="arr">
                            </v-select>
                        </v-container>
                    </v-form>

                    <v-toolbar flat v-if="this.isSync">
                        <v-spacer></v-spacer>
                        <v-btn text small @click="cancelForm">
                            Отмена
                        </v-btn>
                        <v-btn text small @click="clearForm">
                            Очистить
                        </v-btn>
                        <v-btn text small @click="createRecord">
                            Сохранить
                        </v-btn>
                    </v-toolbar>

                    <v-toolbar flat v-if="!this.isSync">
                        <v-spacer></v-spacer>
                        <v-btn text small @click="cancelForm">
                            Отмена
                        </v-btn>
                        <v-btn text small @click="clearForm">
                            Очистить
                        </v-btn>
                        <v-btn text small @click="createRecordAsync">
                            Сохранить
                        </v-btn>
                    </v-toolbar>

                    <div v-if="isUpdateError">
                        <v-alert colored-border type="error" elevation="2">
                            <v-list-item-content>
                                <v-list-item-title>{{ errorMsg }}</v-list-item-title>
                                <li v-for="error in errors" :key="error.id">{{ error }}</li>
                            </v-list-item-content>
                        </v-alert>
                    </div>

                    <div v-if="isAsyncSuccess">
                        <v-alert colored-border type="success" elevation="2">
                            <p>Запрос выполнен успешно</p>
                            <v-btn text small @click="updateData()">
                                Обновить страницу
                            </v-btn>
                        </v-alert>
                    </div>

                </div>
            </div>
        </div>
        <news-modal-set @modal-close-btn-clicked="closeModal" @data-changes="updateData"
            :is-modal-visible="isModalVisible" :mode.sync="modalMode" :id="lastClickedArticleId"
            :arr="arr"></news-modal-set>
    </v-card>
</template>

<script>

import NewsModalSet from './News/NewsModalSet.vue'

export default {
    name: 'TheNews',
    components: { NewsModalSet },
    data: function () {
        return {
            arr: [],
            newsData: [],
            searchStr: '',
            searchStringData: {
                search: '',
            },
            isFormOpen: false,
            articleData: {
                news_header: '',
                news_announcement: '',
                news_body: '',
                rubrics_id: [],
            },
            isUpdateError: false,
            lastClickedArticleId: 0,
            isModalVisible: false,
            modalMode: 'view',
            errorMsg: '',
            errors: {},
            isSync: false,
            postId: null,
            isAsyncSuccess: false,
        }
    },
    computed: {
        requestRoute: function () {
            return process.env.VUE_APP_HOST + '/api/news/';
        },
        requestRubricsRoute: function () {
            return process.env.VUE_APP_HOST + '/api/news/rubrics';
        },
        searchRoute: function () {
            return process.env.VUE_APP_HOST + '/api/news/search';
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
                return this.articleData?.rubrics_id ?? '';
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
                    this.newsData = response?.data?.items;
                } else {
                    console.log(response?.data);
                }
            }).catch(error => {
                console.log(error);
            });

            this.axios.get(
                this.requestRubricsRoute,
            ).then(response => {
                if (response.data?.success) {
                    const items = response?.data?.items;
                    for (let i = 0; i < items.length; i++) {
                        this.arr[i] = { text: items[i].rubric_name, value: items[i].id, }
                    }
                } else {
                    console.log(response?.data);
                }
            }).catch(error => {
                console.log(error);
            });
        },
        searchData() {
            const url = this.searchRoute;
            const data = this.searchStringData;

            this.axios.post(url,
                { data },
            ).then(response => {
                if (response.data?.success) {
                    this.newsData = response?.data?.items;
                } else {
                    console.log(response?.data);
                    this.updateData();
                }
            }).catch(error => {
                console.log(error);
            });
        },
        openFormSync() {
            this.isFormOpen = true;
            this.isSync = true;
            this.isUpdateError = false;
            this.isAsyncSuccess = false;
        },
        openFormAsync() {
            this.isFormOpen = true;
            this.isSync = false;
            this.isUpdateError = false;
            this.isAsyncSuccess = false;
        },
        clearForm() {
            this.isUpdateError = false;
            this.isAsyncSuccess = false;
            this.newsHeader = '';
            this.newsAnnouncement = '';
            this.newsBody = '';
            this.rubricsId = [];
        },
        cancelForm() {
            this.isFormOpen = false;
            this.isUpdateError = false;
            this.isAsyncSuccess = false;
            this.newsHeader = '';
            this.newsAnnouncement = '';
            this.newsBody = '';
            this.rubricsId = [];
        },
        createRecord() {
            this.isUpdateError = false;
            this.isAsyncSuccess = false;
            const url = this.requestRoute;
            const data = this.articleData;

            this.axios.post(url,
                { data },
            ).then(response => {
                if (response.data?.success) {
                    this.isFormOpen = false;
                    this.updateData();
                } else {
                    this.isUpdateError = true;
                    this.errorMsg = response?.data.message;
                    this.errors = response?.data.errors;
                    console.log(response?.data);
                }
            }).catch(error => {
                console.log(error);
            });
        },
        async createRecordAsync() {
            this.isUpdateError = false;
            this.isAsyncSuccess = false;
            const url = this.requestRoute;
            const data = this.articleData;
            const response = await this.axios.post(url, { data });
            if (response?.data?.success) {
                this.isAsyncSuccess = true;
            } else {
                this.isUpdateError = true;
                this.errorMsg = response?.data.message;
                this.errors = response?.data.errors;
            }
            console.log(response?.data);
        },
        setViewAlrticle(id) {
            this.lastClickedArticleId = id;
            this.modalMode = 'view';
            this.isModalVisible = true;
        },
        setDeleteAlrticle(id) {
            this.lastClickedArticleId = id;
            this.modalMode = 'delete';
            this.isModalVisible = true;
        },
        setEditAlrticle(id) {
            this.lastClickedArticleId = id;
            this.modalMode = 'edit';
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
        },
    },
    created() {
        this.updateData();
    },
}

</script>

<style lang="scss">
.pa-4 {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
}
.btn-group {
    display: flex;
    flex-direction: column;

    .btn-active {
        background-color: #ebe6f2;
    }
}
</style>
