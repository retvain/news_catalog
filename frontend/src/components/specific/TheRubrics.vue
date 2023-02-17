<template>
  <div>
    <v-toolbar flat>
      <v-btn icon @click="searchData()">
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
      <v-text-field hide-details @keydown.enter="searchData()" single-line v-model="searchStringData.search">
      </v-text-field>
    </v-toolbar>
    <v-card>
      <div class="pa-4">
        <v-col cols="5">
          <div>
            <tree-data-group @item-clicked="transmit" :tree-data="treeData" :updateKey="updateKey"></tree-data-group>
          </div>
          <v-btn text :class="{ 'active': isActive }" @click="openFormAddParentRubric" color="info">
            Добавить новую рубрику
          </v-btn>
        </v-col>
        <v-col class="main-form">
          <h5>Редактирование рубрики</h5>
          <v-toolbar flat>
            <h5 v-if="this.currentObject.rubric_name && isActive">{{ this.currentObject.rubric_name }}</h5>
            <v-spacer></v-spacer>
            <v-btn icon :class="{ 'active': isActive }" :disabled="!this.isActive" @click="openFormEditChildRubric">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>

            <v-btn icon :class="{ 'active': isActive }" @click="openFormDeleteChildRubric" :disabled="!this.isActive">
              <v-icon>mdi-trash-can-outline</v-icon>
            </v-btn>

            <v-btn icon :class="{ 'active': isActive }" @click="openFormAddChildRubric" :disabled="!this.isActive">
              <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-toolbar>

          <div v-if="this.isAddFormOpen" class="rubrics-form">
            <v-form>
              <v-container>
                <p>Добавление рубрики:</p>
                <v-text-field v-model="rubricName" label="Рубрика" required></v-text-field>
              </v-container>
            </v-form>

            <v-toolbar flat>
              <v-spacer></v-spacer>
              <v-btn text small @click="cancelForm">
                Отмена
              </v-btn>
              <v-btn text small @click="clearForm">
                Очистить
              </v-btn>
              <v-btn text small @click="addParentRubric">
                Сохранить
              </v-btn>
            </v-toolbar>
          </div>

          <div v-if="this.isAddChildFormOpen" class="rubrics-form">
            <v-form>
              <v-container>
                <v-text-field v-model="rubricChildName" :label="this.currentObject.rubric_name + '/ Рубрика'"
                  required></v-text-field>
              </v-container>
            </v-form>

            <v-toolbar flat>
              <v-spacer></v-spacer>
              <v-btn text small @click="cancelForm">
                Отмена
              </v-btn>
              <v-btn text small @click="clearForm">
                Очистить
              </v-btn>
              <v-btn text small @click="addChildRubric">
                Сохранить
              </v-btn>
            </v-toolbar>
          </div>

          <div v-if="this.isDeleteChildFormOpen" class="rubrics-form">
            <v-toolbar flat>
              <v-spacer></v-spacer>
              <v-btn text small @click="cancelDelete">
                Отмена
              </v-btn>
              <v-btn text small @click="DeleteChildRubric">
                Удалить
              </v-btn>
            </v-toolbar>
          </div>

          <div v-if="this.isEditChildFormOpen" class="rubrics-form">
            <v-form>
              <v-container>
                <v-text-field v-model="rubricEditName" label="Рубрика" required></v-text-field>
              </v-container>
            </v-form>

            <v-toolbar flat>
              <v-spacer></v-spacer>
              <v-btn text small @click="cancelEdit">
                Отмена
              </v-btn>
              <v-btn text small @click="clearForm">
                Очистить
              </v-btn>
              <v-btn text small @click="editChildRubric">
                Сохранить
              </v-btn>
            </v-toolbar>
          </div>

          <div v-if="isUpdateError" class="api-error">
            <v-alert colored-border type="error" elevation="2">
              <v-list-item-content>
                <v-list-item-title>{{ errorMsg }}</v-list-item-title>
                <li v-for="error in errors" :key="error.id">{{ error }}</li>
              </v-list-item-content>
            </v-alert>
          </div>

        </v-col>
      </div>
    </v-card>
  </div>
</template>

<script>

import TreeDataGroup from '../common/TreeDataGroup.vue';

export default {
  name: 'TheRubrics',
  components: { TreeDataGroup },
  data: function () {
    return {
      updateKey: 0,
      searchStr: '',
      rubricsArr: [],
      currentObject: {},
      isActive: false,
      isModalVisible: false,
      modalMode: 'create',
      isAddFormOpen: false,
      rubricData: {
        rubric_name: '',
      },
      rubricEditData: {
        rubric_name: '',
      },
      rubricChildData: {
        rubric_name: '',
        parent_id: '',
      },
      errorMsg: '',
      errors: {},
      isUpdateError: false,
      isAddChildFormOpen: false,
      isDeleteChildFormOpen: false,
      isEditChildFormOpen: false,
      treeData: [],
      searchStringData: {
        search: '',
      },
    }
  },
  computed: {
    requestRoute: function () {
      return process.env.VUE_APP_HOST + '/api/news/rubrics';
    },
    requestParentRoute: function () {
      return process.env.VUE_APP_HOST + '/api/news/rubrics/parents';
    },
    requestUpdateRoute: function () {
      return process.env.VUE_APP_HOST + '/api/news/rubrics/' + this.currentObject.id;
    },
    searchRoute: function () {
      return process.env.VUE_APP_HOST + '/api/news/rubrics/search';
    },
    rubricName: {
      get: function () {
        return this.rubricData?.rubric_name ?? '';
      },
      set: function (newVal) {
        this.rubricData.rubric_name = newVal;
      },
    },
    rubricEditName: {
      get: function () {
        return this.rubricEditData?.rubric_name ?? '';
      },
      set: function (newVal) {
        this.rubricEditData.rubric_name = newVal;
      },
    },
    rubricChildName: {
      get: function () {
        return this.rubricChildData?.rubric_name ?? '';
      },
      set: function (newVal) {
        this.rubricChildData.rubric_name = newVal;
      },
    },
  },
  methods: {
    updateData() {
      this.axios.get(
        this.requestParentRoute,
      ).then(response => {
        if (response.data?.success) {
          this.treeData = response?.data?.items;
        } else {
          console.log(response?.data);
        }
      }).catch(error => {
        console.log(error);
      });
    },
    transmit(data) {
      this.currentObject = data;
      this.rubricChildData.parent_id = data.id;
      this.rubricEditData.rubric_name = data.rubric_name;
      this.$emit('item-clicked', data);
      this.isActive = true;
      this.isAddFormOpen = false;
    },
    openFormAddParentRubric() {
      this.isAddFormOpen = true;
      this.isActive = false;
      this.isDeleteChildFormOpen = false;
      this.isEditChildFormOpen = false;
      this.isAddChildFormOpen = false;
    },
    openFormAddChildRubric() {
      this.isAddChildFormOpen = true;
      this.isDeleteChildFormOpen = false;
      this.isEditChildFormOpen = false;
      this.isAddFormOpen = false;
    },
    openFormDeleteChildRubric() {
      this.isDeleteChildFormOpen = true;
      this.isEditChildFormOpen = false;
      this.isAddFormOpen = false;
      this.isAddChildFormOpen = false;
    },
    openFormEditChildRubric() {
      this.isEditChildFormOpen = true;
      this.isDeleteChildFormOpen = false;
      this.isAddFormOpen = false;
      this.isAddChildFormOpen = false;
    },
    addParentRubric() {
      this.isUpdateError = false;
      const url = this.requestRoute;
      const data = this.rubricData;

      this.axios.post(url,
        { data },
      ).then(response => {
        if (response.data?.success) {
          this.isAddFormOpen = false;
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
    addChildRubric() {
      this.isUpdateError = false;
      const url = this.requestRoute;
      const data = this.rubricChildData;

      this.axios.post(url,
        { data },
      ).then(response => {
        if (response.data?.success) {
          this.isAddChildFormOpen = false;
          this.updateData();
          this.updateKey++;
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
    DeleteChildRubric() {
      this.axios.delete(
        this.requestUpdateRoute,
      ).then(response => {
        if (response.data?.success) {
          this.isDeleteChildFormOpen = false;
          this.updateData();
          this.updateKey++;
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
    editChildRubric() {
      this.isUpdateError = false;
      const url = this.requestUpdateRoute;
      const data = this.rubricEditData;

      this.axios.put(url,
        { data },
      ).then(response => {
        if (response.data?.success) {
          this.isEditChildFormOpen = false;
          this.updateData();
          this.updateKey++;
          this.isActive = false;
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
    clearForm() {
      this.isUpdateError = false;
      this.rubricName = '';
      this.rubricEditName = '';
    },
    cancelForm() {
      this.isUpdateError = false;
      this.rubricName = '';
      this.isAddFormOpen = false;
    },
    cancelEdit() {
      this.isUpdateError = false;
      this.rubricEditName = this.currentObject.rubric_name;
      this.isEditChildFormOpen = false;
    },
    cancelDelete() {
      this.isUpdateError = false;
      this.isDeleteChildFormOpen = false;
    },
    searchData() {
      const url = this.searchRoute;
      const data = this.searchStringData;

      this.axios.post(url,
        { data },
      ).then(response => {
        if (response.data?.success) {
          this.treeData = response?.data?.items;
        } else {
          console.log(response?.data);
          this.updateData();
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

<style  lang="scss" scoped>
.v-btn.active {
  color: blue;
}

h5 {
  margin: 0;
  margin-right: 12px;
  display: flex;
  justify-content: end;
}

.main-form {
  display: flex;
  align-items: flex-end;
  flex-direction: column;
}

.rubrics-form,
.api-error,
.v-toolbar__content {
  width: 400px;
}
</style>
