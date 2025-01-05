<template>
        <!-- Customer Information -->

        <v-row>
          <v-col cols="12" md="3">
          <v-card subtitle="Sales" hover>
            <v-card-text class="borderBT mb-4 mx-3">
              {{ ReportRepository.customerReportDetails?.sales?.length || 'N/A' }} 
            </v-card-text>
          </v-card>
        </v-col>

        
        <v-col cols="12" md="3">
          <v-card subtitle="Total Sales Amount" hover>
            <v-card-text class="borderBT mb-4 mx-3">
              {{ ReportRepository.customerReportDetails?.total_amount || 'N/A' }} 
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="3">
          <v-card subtitle="Total Sales Payment" hover>
            <v-card-text class="borderNavy mb-4 mx-3">
              {{ ReportRepository.customerReportDetails?.total_paid || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="3">
          <v-card subtitle="Total Sales Due" hover>
            <v-card-text class="borderNavy mb-4 mx-3">
              {{ ReportRepository.customerReportDetails?.total_due || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>

      
      </v-row>
  
        <!-- Product Table -->

        <v-card style="margin-top:20px;" >
    <v-tabs v-model="tab" align-tabs="end" color="deep-purple-accent-4">
      <v-tab :value="1">Sales</v-tab>
      <v-tab :value="2">Sale Payments</v-tab>
    </v-tabs>

    <v-card-text>
      <v-tabs-window v-model="tab">
        <v-tabs-window-item :value="1">
         

          <!-- Payment Sent Data Table -->
          <v-data-table-server
    theme="cursor-pointer"
    v-model:items-per-page="ReportRepository.itemsPerPage"
    :headers="sentHeaders"
    :items-length="ReportRepository.totalItems"
    :items="ReportRepository.customerReportDetails.sales"
    :loading="ReportRepository.loading"
    :item-key="'id'"
    hover
    class="mx-auto mt-4 no-padding-table"
    >
  
</v-data-table-server>

        </v-tabs-window-item>

        <!-- @update:options="ReportRepository.fetchReceivedLoans" -->
        <!-- Payment Received Data Table -->
        <v-tabs-window-item :value="2">
         
          <v-data-table-server
            theme="cursor-pointer"
            v-model:items-per-page="ReportRepository.itemsPerPage"
            :headers="receivedHeaders"
            :items-length="ReportRepository.totalItems"
            :items="ReportRepository.customerReportDetails.sales_payments"
            :loading="ReportRepository.loading"
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
  import { useReportRepository } from "../../store/ReportsRepository";
  import { ref, computed, onMounted } from "vue";
  import { useRoute } from "vue-router";
  const ReportRepository = useReportRepository();
  const routeParams = useRoute();
  let tab = ref(1); // Default to 'Payment Sent'
  onMounted(async () => {
    await ReportRepository.fetCustomerDetails(routeParams.params.id);
    // loading.value = false;
  });

  const sentHeaders = [
   {
     title: "Date",
     align: "left",
     sortable: false,
     key: "date",
     color: "red",
   },
   { title: "Reference", key: "reference", align: "left", sortable: false },
   { title: "Amount", key: "totalAmount", align: "left", sortable: false },
   { title: "Paid", key: "totalPaid", align: "left", sortable: false },
   { title: "Due", key: "totalDue", align: "left", sortable: false },
 ];

const receivedHeaders = [
{
     title: "Date",
     align: "left",
     sortable: false,
     key: "date",
     color: "red",
   },
   { title: "Sale Ref", key: "sale_ref", align: "left", sortable: false },
   { title: "Amount", key: "amount", align: "left", sortable: false },
   { title: "Notes", key: "description", align: "left", sortable: false },
];
  </script>
  
  <style scoped>
  .date-picker-container {
    margin: 0.5rem 0; /* Adjust the space here */
  }
  
  .borderBT {
    border-bottom: 4px solid #112f53; /* Dark Navy Blue */
  }
  .borderGreen {
    border-bottom: 4px solid #006400; /* Dark Green */
  }
  .borderBlue {
    border-bottom: 4px solid #00008b; /* Dark Blue */
  }
  .borderGray {
    border-bottom: 4px solid #4f4f4f; /* Classic Gray */
  }
  .borderNavy {
    border-bottom: 4px solid #2f4f4f; /* Slate Navy */
  }
  .borderBrown {
    border-bottom: 4px solid #8b4513; /* Saddle Brown */
  }
  .borderOlive {
    border-bottom: 4px solid #556b2f; /* Olive Drab */
  }
  .card {
    margin: 1.4rem;
    padding: 1.4rem;
  }
  </style>
  