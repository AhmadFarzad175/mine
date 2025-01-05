<template>
  <PaymentModal v-if="EarningRepository.createPaymentDialog || EarningRepository.updatePaymentDialog" />
  <ViewPayment v-if="EarningRepository.viewPaymentDialog" />
    <div class="all-expense rounded-xl m-4">
      <div class="card rounded-xl bg-white" rtl>
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
              v-model="EarningRepository.earningSearch"
            ></v-text-field>
          </div>
          <div class="btn">
            <v-btn variant="outlined" color="#112F53">
              Filter
            </v-btn>
            &nbsp;
            <router-link to="/createSales">
              <v-btn color="#112F53" variant="flat">
                Create Sale
              </v-btn>
            </router-link>
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
                    v-model:items-per-page="EarningRepository.itemsPerPage"
                    :headers="headers"
                    :items-length="EarningRepository.totalItems"
                    :items="EarningRepository.earnings"
                    :loading="EarningRepository.loading"
                    :search="EarningRepository.earningSearch"
                    @update:options="EarningRepository.fetchEarnings"
                    :item-key="EarningRepository.earnings"
                    hover
                    class="mx-auto mt-4 no-padding-table"
                  >
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
  
                    <template v-slot:item.due="{ item }">
                      <span class="text-red">{{ item.due }}</span>
                    </template>
  
                    <template
                                        v-slot:item.action="{ item }"
                                      
                                    >
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
                                                        @click="
                                                            CreatePaymentDialog(
                                                                item
                                                            )
                                                        "
                                                    >
                                                        <v-icon color="gray"
                                                            >mdi-cash-edit</v-icon
                                                        >
                                                        &nbsp; Create Payment                                                    </v-list-item-title>

                                                    <v-list-item-title
                                                        class="cursor-pointer d-flex gap-3 justify-left pb-3"
                                                        @click="
                                                            ViewPaymentDialog(
                                                                item
                                                            )
                                                        "
                                                    >
                                                        <v-icon color="gray"
                                                            >mdi-cash-sync</v-icon
                                                        >
                                                        &nbsp; Show Paymanet
                                                    </v-list-item-title>

                                                  <router-link
                                                        :to="
                                                            '/updateSale/' +
                                                            item.id
                                                        "
                                                    >
                                                        <v-list-item-title
                                                          
                                                            class="cursor-pointer d-flex gap-3 justify-left pb-3"
                                                        >
                                                            <v-icon color="gray"
                                                                >mdi-square-edit-outline</v-icon
                                                            >
                                                            &nbsp; Edit
                                                        </v-list-item-title>
                                                    </router-link>

                                                   
                                                    <router-link
                                                        :to="
                                                            '/showSales/' +
                                                            item.id
                                                        "
                                                    >
                                                        <v-list-item-title
                                                            class="cursor-pointer d-flex gap-3 justify-left pb-3"
                                                        >
                                                            <v-icon color="gray"
                                                                >mdi-eye-outline</v-icon
                                                            >
                                                            &nbsp; View                                                        </v-list-item-title>
                                                    </router-link>


                                                    <!-- <router-link
                                                        
                                                    > -->
                                                        <v-list-item-title
                                                            class="cursor-pointer d-flex gap-3 justify-left"
                                                            @click="
                                                            deleteItem(item)
                                                        "
                                                        >
                                                        <v-icon color="gray"
                                                            >mdi-delete-outline</v-icon
                                                        >
                                                            &nbsp; Delete                                                        </v-list-item-title>
                                                    <!-- </router-link> -->
                                                

                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </template>
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
  import { useEarningRepository } from "./../../store/EarningRepository";
  import { ref, computed } from 'vue';
  import PaymentModal from './PaymentModal.vue'
  import ViewPayment from './ViewPayment.vue'
  
  const EarningRepository = useEarningRepository();
  const selectedItems = ref([]); // Track selected checkboxes
  const selectAll = ref(false); // Track the "select all" checkbox
  
  // Calculate if the select all checkbox should be indeterminate
  const indeterminate = computed(() => selectedItems.value.length > 0 && selectedItems.value.length < EarningRepository.earnings.length);
  
  // Toggle the selection of all items
  const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedItems.value = [];
    } else {
        selectedItems.value = EarningRepository.earnings.slice();
    }
  };
  
  const headers = [
    { title: "", key: "checkbox", align: "start", sortable: false }, // Checkbox column
    { title: "Date", key: "date", align: "start", sortable: false },
    { title: "Reference", key: "ref#", align: "center", sortable: false },
    {
      title: "Added By",
      key: "name",
      align: "center",
      sortable: false,
    },
    { title: "Customer", key: "customer_name", align: "center", sortable: false },
    {
      title: "Paid",
      key: "paid_with_code",
      align: "center",
      sortable: false,
    },
    {
      title: "Due",
      align: "center",
      sortable: false,
      key: "due_with_code",
      color: "red",
    },
    { title: "Grand Total", key: "total_amount_with_code", align: "center", sortable: false },
    { title: "Action", key: "action", align: "end", sortable: false },
  ];
  
  const CreatePaymentDialog = (item) => {
    // EarningRepository.productId = id;

    EarningRepository.sales_id= item.id;
    
    EarningRepository.earning=item;

    EarningRepository.createPaymentDialog = true;
  };

  const ViewPaymentDialog = (item) => {
      // console.log(item.id);
      // const earningId = item.id;
      // EarningRepository.updatePaymentId = item.id;
  EarningRepository.sales_id = item.id; 
      EarningRepository.salePayments =[];
      if (EarningRepository.salePayments.length === 0) {
          EarningRepository.FetchEarningPayments(item.id)
              .then(() => {
                  EarningRepository.viewPaymentDialog = true;
              })
              .catch((error) => {
                  console.error("Error fetching data:", error);
              });
      }
  };

  const deleteItem = async (item) => {
    await EarningRepository.DeleteEarning(item.id);
};

  
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
