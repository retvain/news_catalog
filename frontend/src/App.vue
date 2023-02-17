<template>
  <v-app>
    <v-app-bar
      app
      flat
    >
      <the-header></the-header>
    </v-app-bar>

    <v-breadcrumbs></v-breadcrumbs>

    <v-main>
      <the-main-content></the-main-content>
    </v-main>

    <v-footer>
      <the-footer></the-footer>
    </v-footer>
  </v-app>
</template>

<script>

import TheHeader from './components/layout/TheHeader'
import TheFooter from './components/layout/TheFooter'
import TheMainContent from './components/layout/TheMainContent'

export default {
  name: 'App',
  components: { TheMainContent, TheFooter, TheHeader },
};
</script>

<style lang='scss'>
@import 'App.scss';
</style>
