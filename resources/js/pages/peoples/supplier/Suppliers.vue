<template>
    <supplierModal v-if="PeopleRepository.createDialog || PeopleRepository.updateDialog" />

    <!-- <ProductModal v-if="PeopleRepository.createDialog || PeopleRepository.updateDialog" /> -->

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
             v-model="PeopleRepository.supplierSearch"
           ></v-text-field>
         </div>
         <div class="btn">
           <v-btn variant="outlined" color="#112F53">
             Filter
           </v-btn>
           &nbsp;
             <v-btn color="#112F53" @click="PeopleRepository.createDialog=true" variant="flat">
               Create Supplier
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
                   :items="PeopleRepository.suppliers"
                   :loading="PeopleRepository.loading"
                   :search="PeopleRepository.supplierSearch"
                   @update:options="PeopleRepository.fetchSuppliers"
                   :item-key="PeopleRepository.suppliers"
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
                                                           editItem(item)
                                                       "
                                                       >
                                                       <v-icon color="gray"
                                                           >mdi-cash-edit</v-icon
                                                       >
                                                           &nbsp; Edit                                                        </v-list-item-title>
                                                       <v-list-item-title
                                                           class="cursor-pointer d-flex gap-3 justify-left pb-3"
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
 import { watch } from 'vue'
 import { usePeopleRepository } from "../../../store/PeoplesRepository";
 import supplierModal from './SupplierModal.vue';

 
 const PeopleRepository = usePeopleRepository();
 import { ref, computed } from 'vue';
 const selectedItems = ref([]); // Track selected checkboxes
 const selectAll = ref(false); // Track the "select all" checkbox
 
 // Calculate if the select all checkbox should be indeterminate
//  const indeterminate = computed(() => selectedItems.value.length > 0 && selectedItems.value.length < PeopleRepository.suppliers.length);
 
 const indeterminate = computed(() => {
  return selectedItems.value.length > 0 && selectedItems.value.length < PeopleRepository.suppliers.length;
});

// Watch the selectedItems to automatically check/uncheck selectAll
watch(selectedItems, (newVal) => {
  selectAll.value = newVal.length === PeopleRepository.suppliers.length;
});

 // Toggle the selection of all items
 const toggleSelectAll = () => {
   if (selectAll.value) {
       selectedItems.value = [];
   } else {
       selectedItems.value = PeopleRepository.suppliers.slice();
   }
 };
 
 const headers = [
   { title: "", key: "checkbox", align: "start", sortable: false }, // Checkbox column
  
   {
     title: "name",
     align: "left",
     sortable: false,
     key: "name",
     color: "red",
   },
   { title: "Mobile No", key: "phone", align: "left", sortable: false },
   { title: "Address", key: "address", align: "left", sortable: false },
   { title: "Action", key: "action", align: "end", sortable: false },
 ];
 
//  const CreatePaymentDialog = (id) => {
//    PeopleRepository.productId = id;
//    PeopleRepository.createPayment = true;
//  };


 const deleteItem = async (item) => {
   await PeopleRepository.deleteSupplier(item.id);
};

const editItem = (supplier) => {
   PeopleRepository.supplier = supplier;
   PeopleRepository.updateDialog = true;
};




 
//  const ViewPaymentDialog = (item) => {
//    PeopleRepository.ViewEarning = true;
//  };
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
