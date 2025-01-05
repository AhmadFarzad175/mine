<template>
  <PaymentSentModal
        v-if="PeopleRepository.createPaymentSentDialog || PeopleRepository.updatePaymentSentDialog"

    />
    <PaymentReceivedModal v-if="PeopleRepository.createPaymentReceivedDialog || PeopleRepository.updatePaymentReceivedDialog" />
  <v-card>
    <v-tabs v-model="tab" align-tabs="end" color="deep-purple-accent-4">
      <v-tab :value="1">Payment Sent</v-tab>
      <v-tab :value="2">Payment Received</v-tab>
    </v-tabs>

    <v-card-text>
      <v-tabs-window v-model="tab">
        <v-tabs-window-item :value="1">
          <div class="btn-search pt-2">
              
                <div class="btn">
                   
                    <v-btn
                        color="#112F53"
                        @click="PeopleRepository.createPaymentSentDialog = true"
                        variant="flat"
                    >
                        Create Payment Sent
                    </v-btn>
                </div>
            </div>

          <!-- Payment Sent Data Table -->
          <v-data-table-server
    theme="cursor-pointer"
    v-model:items-per-page="PeopleRepository.itemsPerPage"
    :headers="sentHeaders"
    :items-length="PeopleRepository.totalItems"
    :items="PeopleRepository.sentLoans"
    :loading="PeopleRepository.loading"
    @update:options="PeopleRepository.fetchSentLoans"
    :item-key="'id'"
    hover
    class="mx-auto mt-4 no-padding-table"
>
    <template v-slot:item.action="{ item }">
        <v-menu>
            <template v-slot:activator="{ props }">
                <v-btn icon v-bind="props" variant="text">
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
            </template>
            <v-list>
                <v-list-item>
                    <v-list-item-title
                        class="cursor-pointer d-flex gap-3 justify-left pb-3"
                        @click="editItemSent(item)"
                    >
                        <v-icon color="gray">mdi-cash-edit</v-icon>
                        &nbsp; Edit
                    </v-list-item-title>
                    <v-list-item-title
                        class="cursor-pointer d-flex gap-3 justify-left"
                        @click="deleteItemSent(item)"
                    >
                        <v-icon color="gray">mdi-delete-outline</v-icon>
                        &nbsp; Delete
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </template>
</v-data-table-server>

        </v-tabs-window-item>

        <v-tabs-window-item :value="2">
          <!-- Payment Received Data Table -->
          <div class="btn-search pt-2">
              
              <div class="btn">
                 
                  <v-btn
                      color="#112F53"
                      @click="PeopleRepository.createPaymentReceivedDialog = true"
                      variant="flat"
                  >
                      Create Payment Received
                  </v-btn>
              </div>
          </div>
          <v-data-table-server
            theme="cursor-pointer"
            v-model:items-per-page="PeopleRepository.itemsPerPage"
            :headers="receivedHeaders"
            :items-length="PeopleRepository.totalItems"
            :items="PeopleRepository.receivedLoans"
            :loading="PeopleRepository.loading"
            @update:options="PeopleRepository.fetchReceivedLoans"
            :item-key="'id'"
            hover
            class="mx-auto mt-4 no-padding-table"
          >

          <template v-slot:item.action="{ item }">
        <v-menu>
            <template v-slot:activator="{ props }">
                <v-btn icon v-bind="props" variant="text">
                    <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
            </template>
            <v-list>
                <v-list-item>
                    <v-list-item-title
                        class="cursor-pointer d-flex gap-3 justify-left pb-3"
                        @click="editItemReceived(item)"
                    >
                        <v-icon color="gray">mdi-cash-edit</v-icon>
                        &nbsp; Edit
                    </v-list-item-title>
                    <v-list-item-title
                        class="cursor-pointer d-flex gap-3 justify-left"
                        @click="deleteItemReceived(item)"
                    >
                        <v-icon color="gray">mdi-delete-outline</v-icon>
                        &nbsp; Delete
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </template>
          </v-data-table-server>

          
        </v-tabs-window-item>
      </v-tabs-window>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { usePeopleRepository } from "../../../../store/PeoplesRepository";
import { ref, onMounted, watch } from "vue";

import PaymentSentModal from "./PaymentSentModal.vue"
import PaymentReceivedModal from "./PaymentReceivedModal.vue";

const PeopleRepository = usePeopleRepository();
let tab = ref(1); // Default to 'Payment Sent'

// Load the initial data for the default tab
onMounted(() => {
  fetchDataForTab(tab.value);
PeopleRepository.fetchCurrencies();

});

// Watch for tab changes and fetch the relevant data
watch(tab, (newVal) => {
  fetchDataForTab(newVal);
});

// Fetch data based on the current tab
const fetchDataForTab = (tabValue) => {
  if (tabValue === 1) {
    PeopleRepository.fetchSentLoans();
  } else if (tabValue === 2) {
    PeopleRepository.fetchReceivedLoans();
  }
};

const editItemSent = (sentLoan) => {
    PeopleRepository.sentLoan = sentLoan;
    PeopleRepository.updatePaymentSentDialog = true;
};

const deleteItemSent = async (item) => {
    await PeopleRepository.deletePaymentSent(item.id);
};

const editItemReceived = (receivedLoan) => {
  console.log(receivedLoan)
    PeopleRepository.receivedLoan = receivedLoan;
    PeopleRepository.updatePaymentReceivedDialog = true;
};

const deleteItemReceived = async (item) => {
    await PeopleRepository.deletePaymentReceived(item.id);
};


// Headers for Payment Sent and Payment Received
// Headers for Payment Sent
const sentHeaders = [
   {
     title: "Date",
     align: "left",
     sortable: false,
     key: "payment_date",
     color: "red",
   },
   { title: "Loan People", key: "loan_people", align: "left", sortable: false },
   { title: "Amount", key: "amount_with_currency", align: "left", sortable: false },
   { title: "Notes", key: "notes", align: "left", sortable: false },
   { title: "Action", key: "action", align: "end", sortable: false }, // Add action header here
 ];

const receivedHeaders = [
{
     title: "Date",
     align: "left",
     sortable: false,
     key: "payment_date",
     color: "red",
   },
   { title: "Loan People", key: "loan_people", align: "left", sortable: false },
   { title: "Amount", key: "amount_with_currency", align: "left", sortable: false },
   { title: "Notes", key: "notes", align: "left", sortable: false },
   { title: "Action", key: "action", align: "end", sortable: false }, // Add action header here
];
</script>

<style scoped>
.v-tabs {
  margin-bottom: 1rem;
}
</style>
