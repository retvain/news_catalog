import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'

// Транспорт для взаимодействия с бэкендом.
Vue.use(VueAxios, axios);

Vue.config.productionTip = false

new Vue(
  {
  router,
  vuetify,
  render: h => h(App)
  }
).$mount('#app')
