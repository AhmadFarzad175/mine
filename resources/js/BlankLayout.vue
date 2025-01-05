<template>
    <v-app>
      <v-container 
        class="fill-height d-flex align-center justify-center no-padding"
        fluid
      >
        <slot />
      </v-container>
    </v-app>
  </template>
  
  <script>
  export default {
    name: "BlankLayout",
  };
  </script>
  
  <style scoped>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    width: 100%;
    overflow-x: hidden; /* Prevent horizontal scrolling if something overflows */
  }
  
  .no-padding {
    padding: 0 !important; /* Remove padding */
    margin: 0 !important;  /* Remove margin */
  }
  
  .v-container {
    height: 100% !important; /* Ensure the container fills the available space */
    width: 100% !important;  /* Ensure the container takes the full width */
    padding: 0 !important;   /* Remove any default padding */
  }
  </style>
  