<template>
  <component :is="layout">
    <router-view />
  </component>
</template>

<script>
import { onBeforeMount } from "vue";
import { useAuthRepository } from "@/store/AuthRepository";
import DefaultLayout from "./DefaultLayout.vue";
import BlankLayout from "./BlankLayout.vue";

export default {
  computed: {
    layout() {
      // Use the route's meta to determine which layout to use
      const layout = this.$route.meta.layout || "DefaultLayout";
      return layout === "DefaultLayout" ? DefaultLayout : BlankLayout;
    },
  },
  setup() {
    const AuthRepository = useAuthRepository();

    onBeforeMount(() => {
      // Ensure the AuthRepository is initialized when the app starts
      AuthRepository.initialize();
    });

    return {
      AuthRepository
    };
  },
};
</script>
