<template>

    <!-- <ProductModal v-if="ReportRepository.createDialog || ReportRepository.updateDialog" /> -->

   <div class="all-expense rounded-xl m-4">
     <div class="card rounded-xl bg-white">
       <div class="btn-search pt-2 ">

        <date-picker 
        v-model:value="ReportRepository.ownerDateRange" 
        @change="onDateChange"
        range 
      ></date-picker>
        
           
       </div>
 
       <!-- v-table server -->
       <div>
         <v-app>
           <v-main>
             <v-row>
               <v-col>
                 <!-- @update:options="ReportRepository.fetchExpensesCategory" -->
                 <v-data-table-server
                   theme="cursor-pointer"
                   v-model:items-per-page="ReportRepository.itemsPerPage"
                   :headers="headers"
                   :items-length="ReportRepository.totalItems"
                   :items="ReportRepository.ownersReports"
                   :loading="ReportRepository.loading"
                   :item-key="ReportRepository.ownersReports"
                   hover
                   class="mx-auto mt-4 no-padding-table"
                 >
                   <!-- Select all checkbox in the header -->
                
 
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
 import { ref, computed, onMounted } from 'vue';
 import DatePicker from 'vue-datepicker-next';
  import 'vue-datepicker-next/index.css';
 import { useReportRepository } from "../../store/ReportsRepository";

const ReportRepository = useReportRepository();
 const ownerDateRange = ref([new Date(), new Date()]);


const onDateChange = () => {
  const [startDate, endDate] = ReportRepository.ownerDateRange;
  if (startDate && endDate) {

    ReportRepository.fetchOwnersReports(startDate, endDate);
  }
};

onMounted(() => {
  ReportRepository.ownerDateRange = ownerDateRange.value;
  ReportRepository.fetchOwnersReports(ownerDateRange.value[0], ownerDateRange.value[1]);
});
 
 const headers = [
  
   {
     title: "Owner Name",
     align: "left",
     sortable: false,
     key: "owner_name",
     color: "red",
   },
   { title: "Amount", key: "amount", align: "left", sortable: false },
 ];
 

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
