<template>
  <div class="all-expense rounded-xl m-4">
    <div class="card bg-white">
      <div class="btn-search pt-2">
        <date-picker 
          v-model:value="ReportRepository.expenseDateRange" 
          @change="onDateChange"
          range 
        ></date-picker>

        <div class="text-field">
          <v-text-field
            :loading="loading"
            color="#D3E2F8"
            density="compact"
            variant="outlined"
            label="Search"
            append-inner-icon="mdi-magnify"
            style="width: 300px;"
            single-line
            hide-details
            v-model="ReportRepository.categoryExpenseReportSearch"
          
          ></v-text-field>
        </div>
      </div>

      <!-- Data Table -->
      <v-data-table-server
        theme="cursor-pointer"
        v-model:items-per-page="ReportRepository.itemsPerPage"
        :headers="headers"
        :items-length="ReportRepository.totalItems"
        :items="ReportRepository.categoryExpenseReport"
        :loading="ReportRepository.loading"
        :search="ReportRepository.categoryExpenseReportSearch"
        :item-key="'id'"
        hover
        class="mx-auto mt-4 no-padding-table"
      >
        <template v-slot:item.due="{ item }">
          <span class="text-red">{{ item.due }}</span>
        </template>

        <template v-slot:item.action="{ item }">
          <v-menu>
            <template v-slot:activator="{ props }">
              <v-btn icon="mdi-dots-vertical" v-bind="props" variant="text"></v-btn>
            </template>
          </v-menu>
        </template>
      </v-data-table-server>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import { useReportRepository } from "../../store/ReportsRepository";

const ReportRepository = useReportRepository();
const expenseDateRange = ref([new Date(), new Date()]);


const onDateChange = () => {
  const [startDate, endDate] = ReportRepository.expenseDateRange;
  if (startDate && endDate) {

    ReportRepository.fetchExpenseCategoryReports(startDate, endDate);
  }
};

// Watch for changes in the search field to automatically trigger API call
watch(
  () => ReportRepository.categoryExpenseReportSearch,
  (newSearchTerm) => {
    const [startDate, endDate] = ReportRepository.expenseDateRange;
    if (startDate && endDate) {
      ReportRepository.fetchExpenseCategoryReports(startDate, endDate);
    }
  }
);

onMounted(() => {
  ReportRepository.expenseDateRange = expenseDateRange.value;
  ReportRepository.fetchExpenseCategoryReports(expenseDateRange.value[0], expenseDateRange.value[1]);
});

const headers = [
  { title: "Category", align: "left", sortable: false, key: "category_name", color: "red" },
  { title: "Amount", key: "amount", align: "left", sortable: false },
];
</script>

<style scoped>
.v-table {
  width: 100% !important;
}
.v-main {
  margin-left: 0px !important;
  margin-right: 0px !important;
}
.btn-search {
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
.check-all-checkbox > * {
  margin-left: -5px !important;
}
</style>
