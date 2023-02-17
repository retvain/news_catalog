<template>
  <v-toolbar flat light>
    <v-toolbar-title>
      <router-link to="/home">Новостной каталог</router-link>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    <v-toolbar-items>
      <v-btn text v-for="menuItem in menuItems" :key="menuItem.name">
        <router-link :to=menuItem.url>{{ menuItem.title }}</router-link>
      </v-btn>
    </v-toolbar-items>
  </v-toolbar>
</template>

<script>
export default {
  name: 'HeaderComponent',
  data: function () {
    return {
      menuItems: [
        {
          name: 'news',
          title: 'Новости',
          url: '/news',
        },
        {
          name: 'rubrics',
          title: 'Рубрики',
          url: '/rubrics',
        },
      ],
    }
  },
}
</script>
