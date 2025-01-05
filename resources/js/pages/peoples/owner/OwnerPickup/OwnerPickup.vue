<template>

<OwnerPickupModal
        v-if="
            PeopleRepository.ownerCreateDialog ||
            PeopleRepository.ownerUpdateDialog
        "
    />
  <div class="all-expense rounded-xl m-4">
    <div class="card rounded-xl bg-white">
      <div class="btn-search pt-2">
        <div class="text-field">
          <v-text-field
            :loading="loading"
            color="#D3E2F8"
            density="compact"
            variant="outlined"
            label="Search "
            append-inner-icon="mdi-magnify"
            style="width: 300px;"
            single-line
            hide-details
            v-model="PeopleRepository.ownerpickupSearch"
            @input="fetchData"
          ></v-text-field>
        </div>
        <div class="btn">
          <v-btn variant="outlined" color="#112F53">
            Filter
          </v-btn>
        </div>
      </div>

      <!-- v-table server -->
      <div>
        <v-app>
          <v-main>
            <v-row>
              <v-col>
                <v-data-table-server
                  theme="cursor-pointer"
                  v-model:items-per-page="PeopleRepository.itemsPerPage"
                  :headers="headers"
                  :items-length="PeopleRepository.totalItems"
                  :items="PeopleRepository.ownerPickups"
                  :loading="PeopleRepository.loading"
                  :search="PeopleRepository.ownerpickupSearch"
                  @update:page="onPageChange"
                  @update:items-per-page="onItemsPerPageChange"
                  hover
                  class="mx-auto mt-4 no-padding-table"
                >
                  <!-- Your table slots -->

                  <!-- Select all checkbox in the header -->
                  <template v-slot:header.checkbox>
                    <v-checkbox
                      v-model="selectAll"
                      :indeterminate="indeterminate"
                      hide-details
                      @click="toggleSelectAll"
                      class="check-all-checkbox"
                    ></v-checkbox>
                  </template>

                  <!-- Checkbox column for each row -->
                  <template v-slot:item.checkbox="{ item }">
                    <v-checkbox
                      v-model="selectedItems"
                      :value="item"
                      hide-details
                      density="compact"
                    ></v-checkbox>
                  </template>

                  <template v-slot:item.action="{ item }">
                                        <v-menu>
                                            <template
                                                v-slot:activator="{ props }"
                                            >
                                                <v-btn
                                                    icon="mdi-dots-vertical"
                                                    v-bind="props"
                                                    variant="text"
                                                ></v-btn>
                                            </template>
                                            <v-list>
                                                <v-list-item>
                                                   
                                                    <v-list-item-title
                                                        class="cursor-pointer d-flex gap-3 justify-left pb-3"
                                                        @click="editItem(item)"
                                                    >
                                                        <v-icon color="gray"
                                                            >mdi-cash-edit</v-icon
                                                        >
                                                        &nbsp; Edit
                                                    </v-list-item-title>
                                                    <v-list-item-title
                                                        class="cursor-pointer d-flex gap-3 justify-left pb-3"
                                                        @click="
                                                            deleteItem(item)
                                                        "
                                                    >
                                                        <v-icon color="gray"
                                                            >mdi-delete-outline</v-icon
                                                        >
                                                        &nbsp; Delete
                                                    </v-list-item-title>
                                                    <!-- </router-link> -->
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </template>
                  <!-- Other template slots for actions -->
                </v-data-table-server>
              </v-col>
            </v-row>
          </v-main>
        </v-app>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import necessary modules and state

import { ref, computed, watch } from 'vue'
import { useRoute } from "vue-router";
import { usePeopleRepository } from "../../../../store/PeoplesRepository.js";
import OwnerPickupModal from "./OwnerPickupModal.vue";

const routeParams = useRoute();
const PeopleRepository = usePeopleRepository();
const selectedItems = ref([]);
const selectAll = ref(false);

// Handle checkbox selection
const indeterminate = computed(() => {
 return selectedItems.value.length > 0 && selectedItems.value.length < PeopleRepository.ownerPickups.length;
});

watch(selectedItems, (newVal) => {
 selectAll.value = newVal.length === PeopleRepository.ownerPickups.length;
});

const toggleSelectAll = () => {
  if (selectAll.value) {
      selectedItems.value = [];
  } else {
      selectedItems.value = PeopleRepository.ownerPickups.slice();
  }
};

// Headers for the table
const headers = [
  { title: "", key: "checkbox", align: "start", sortable: false }, 
  { title: "Date", key: "date", align: "left", sortable: false },
  { title: "Owner", key: "owner", align: "left", sortable: false },
  { title: "Amount", key: "amount_with_code", align: "left", sortable: false },
  { title: "Note", key: "description", align: "left", sortable: false },
  { title: "Action", key: "action", align: "end", sortable: false },
];


PeopleRepository.current_owner_id = routeParams.params.id
// Handle pagination and data fetching
const fetchData = async () => {
 await PeopleRepository.fetchOwnerPickups({
   page: PeopleRepository.currentPage,
   itemsPerPage: PeopleRepository.itemsPerPage,
   owner_id: routeParams.params.id
 });
};

const onPageChange = (page) => {
 PeopleRepository.currentPage = page;
 fetchData();
};

const onItemsPerPageChange = (itemsPerPage) => {
 PeopleRepository.itemsPerPage = itemsPerPage;
 fetchData();
};

PeopleRepository.fetchCurrencies();


const editItem = (ownerPickup) => {
    PeopleRepository.ownerPickup = ownerPickup;
    PeopleRepository.ownerUpdateDialog = true;
};

const deleteItem = async (item) => {
    await PeopleRepository.deleteOwnerPickup(item.id);
};


fetchData();
</script>

<style scoped>
/* .card {
   margin: 1.4rem;
   padding: 1.4rem;
} */
/* background-color: green; */
/* .all-expense {
 width: 83.2%;
} */
/* .v-table > :nth-child(2) {
 justify-content: space-evenly;
}
.v-table > :nth-child(2) > :nth-child(1) {
 direction: ltr;
}
.v-table > :nth-child(2) > :nth-child(2) {
 width: 47rem;
 align-self: center;
} */


 .v-table{
   width: 100% !important;
 }
 .v-main{
   margin-left: 0px !important;
   margin-right: 0px !important;
 }

 .btn-search{
   /* width: fit-content; */
   display: flex !important;
   justify-content: space-between;
 }
 .no-padding-table .v-data-table-server tbody tr td {
 padding: 0 !important;
 margin: 0 !important;
}

.pa-0 {
 padding: 0 !important;
}

.ma-0 {
 margin: 0 !important;
}

.check-all-checkbox > *{
   margin-left: -5px !important;
}



</style>
