<template>

    <!-- <ProductModal v-if="ReportRepository.createDialog || ReportRepository.updateDialog" /> -->

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
             v-model="ReportRepository.supplierSearch"
           ></v-text-field>
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
                   v-model:items-per-page="ReportRepository.itemsPerPage"
                   :headers="headers"
                   :items-length="ReportRepository.totalItems"
                   :items="ReportRepository.suppliersReport"
                   :loading="ReportRepository.loading"
                   :search="ReportRepository.supplierSearch"
                   @update:options="ReportRepository.FetchSupplierReports"
                   :item-key="ReportRepository.suppliersReport"
                   hover
                   class="mx-auto mt-4 no-padding-table"
                 >

                   <template v-slot:item.due="{ item }">
                     <span class="text-red">{{ item.due }}</span>
                   </template>
 
                   <template
                                       v-slot:item.action="{ item }"
                                     
                                   >
                                       <v-menu
                                       
                                       >
                                           <template
                                               v-slot:activator="{ props }"
                                               >
                                               
                                               
                                               <router-link
                                                   :to="
                                                       '/detailsSupplier/' +
                                                       item.id
                                                   "
                                               >
                                                   <v-list-item-title
                                                       class="cursor-pointer d-flex"
                                                       style="justify-content: flex-end;"

                                                   >
                                                       <v-icon color="gray"
                                                           >mdi-eye-outline</v-icon
                                                       >
                                                                                                           </v-list-item-title>
                                               </router-link>
                                               
                                           </template>
                                          
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
 import { watch,ref, computed } from 'vue';
 import { useReportRepository } from "../../store/ReportsRepository";
  const ReportRepository = useReportRepository();
  import { onMounted } from 'vue';
 const selectedItems = ref([]); // Track selected checkboxes
 const selectAll = ref(false); // Track the "select all" checkbox
 


// Watch the selectedItems to automatically check/uncheck selectAll
watch(selectedItems, (newVal) => {
  selectAll.value = newVal.length === ReportRepository.suppliersReport.length;
});


 const headers = [
  
   {
     title: "Supplier Name",
     align: "left",
     sortable: false,
     key: "supplier_name",
     color: "red",
    },
    { title: "Total Expenses", key: "total_expenses", align: "left", sortable: false },
    { title: "Total Amount", key: "total_amount", align: "left", sortable: false },
    { title: "Total Paid", key: "total_paid", align: "left", sortable: false },
    { title: "Total Due", key: "total_due", align: "left", sortable: false },
   { title: "Mobile Number", key: "phone", align: "left", sortable: false },
   { title: "Address", key: "address", align: "left", sortable: false },
   { title: "Action", key: "action", align: "end", sortable: false },
 ];
 
 

 </script>
 

<style scoped>


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
