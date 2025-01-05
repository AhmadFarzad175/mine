<template>
    <v-app>
      <v-navigation-drawer v-model="drawer" app>
        <SideBar />
      </v-navigation-drawer>
      <div class="main-container">
        <v-main
          style="
            --v-layout-left: 0px;
            --v-layout-right: 0px;
            --v-layout-top: 0px;
            --v-layout-bottom: 0px;"
        >
          <ToolBar />
          <v-divider></v-divider>
          <v-container>
            <slot />
          </v-container>
        </v-main>
      </div>
    </v-app>
  </template>
  
  <script>
  import SideBar from "@/components/SideBar.vue";
  import ToolBar from "@/components/ToolBar.vue";
  
  export default {
    components: {
      SideBar,
      ToolBar,
    },
    data() {
      return {
        drawer: true,
      };
    },
  };
  </script>
  
  <style scoped>
  .main-container {
    background-color: #f8f8f8 !important;
  }
  </style>
  