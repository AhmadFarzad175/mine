<template>
  <v-card app flat>
    <v-layout class="full-height overflow-hidden">
      <v-navigation-drawer
        v-model="drawer"
        :rail="rail"
        permanent
        @click="rail = false"
        class="full-height"
      >
        <div class="logo-container">
          <img
            src="./../assets/navLogo/logo black (1) 3.png"
            alt="Logo"
            class="logo rounded-full"
            max-width="24"
            max-height="24"
          />
        </div>

        <v-list density="default" nav>
          <v-list-item
            prepend-icon="mdi-view-dashboard"
            title="Sales"
            color="#112F53"
            active-class="bg-primary text-white"
            class="menu-title"
            @click="toggleSale"
          ></v-list-item>

          <v-list v-if="isSaleVisible">
            <router-link
              v-for="item in menuSale"
              :key="item.id"
              :to="item.route"
              class="no-underline menu-title"
              :class="{ 'active-item': selectedSale === item.id }"
              @click.native="setSelectedSale(item.id)"
            >
              <transition name="slide-fade">
                <v-list-item
                  :title="item.title"
                  :prepend-icon="item.icon"
                  color="#112F53"
                  :class="{ 'active-sub-item': selectedSale === item.id }"
                  class="child submenu-item"
                />
              </transition>
            </router-link>
          </v-list>

          <v-list-item
            title="Expense"
            prepend-icon="mdi-gauge"
            color="#112F53"
            active-class="bg-primary text-white"
            @click="toggleExpense"
          ></v-list-item>
          <!-- :class="{ 'active-item  ': selectedExpense === item.id }"
        active-class="bg-primary text-white" -->

          <v-list v-if="isExpenseVisible">
            <router-link
              v-for="item in menuExpense"
              :key="item.id"
              :to="item.route"
              class="no-underline menu-title"

              @click.native="setSelectedExpense(item.id)"
            >
              <transition name="slide-fade">
                <v-list-item
                  :title="item.title"
                  :prepend-icon="item.icon"
                  color="#112F53"
                  :class="{ 'active-sub-item text-h2 font-weight-bold': selectedExpense === item.id }"
                  class="child submenu-item"
                />
              </transition>
            </router-link>
          </v-list>
        </v-list>
      </v-navigation-drawer>

      <v-divider></v-divider>
      <v-main class="main-content"></v-main>
    </v-layout>
  </v-card>
</template>

<script setup>
import { ref } from 'vue';

const drawer = ref(true);
const rail = ref(false);
const isSaleVisible = ref(false);
const isExpenseVisible = ref(false);
const selectedSale = ref(null);
const selectedExpense = ref(null);

const toggleSale = () => {
  isSaleVisible.value = !isSaleVisible.value;
  if (isSaleVisible.value) {
    // Reset selected item when toggling the menu
    selectedSale.value = null;
  }
};

const toggleExpense = () => {
  isExpenseVisible.value = !isExpenseVisible.value;
  if (isExpenseVisible.value) {
    // Reset selected item when toggling the menu
    selectedExpense.value = null;
  }
};

const setSelectedSale = (id) => {
  selectedSale.value = id;
};

const setSelectedExpense = (id) => {
  selectedExpense.value = id;
};

const menuSale = ref([
  { id: 1, title: 'Create Sale', icon: 'mdi-circle-small', route: '/create-sale' },
  { id: 2, title: 'All Sales', icon: 'mdi-circle-small', route: '/all-sales' },
  { id: 3, title: 'Products', icon: 'mdi-circle-small', route: '/products' },
]);

const menuExpense = ref([
  { id: 1, title: 'Create Expense', icon: 'mdi-circle-small', route: '/create-expense' },
  { id: 2, title: 'All Expenses', icon: 'mdi-circle-small', route: '/all-expenses' },
  { id: 3, title: 'Category', icon: 'mdi-circle-small', route: '/category' },
]);
</script>

<style scoped>
.full-height {
  height: 100vh;
}

.logo-container {
  display: flex;
  justify-content: center;
  padding: 16px;
}

.logo {
  object-fit: contain;
}

.no-underline {
  text-decoration: none;
}

.main-content {
  min-height: 100vh;
  background-color: #F8F8F8;
}

.child {
  text-decoration: none;
 
}

.active-item {
  font-weight: bold;

}

.active-sub-item{
  background-color: #e0f7fa; /* Example color for active state */
  font-weight: bold;
  border-radius: 4px;

  font-size: 20px !important;
}

.menu-title > *{
  font-size: 25px;
}
.submenu-item>*{
  font-size: 10px;
}
.v-list-item{
  color:black
}


</style>
