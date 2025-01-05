<template>
    <div class="date-picker-container">
      <date-picker 
        v-model:value="ReportRepository.dateRange" 
        range 
        @change="onDateChange"
        class="mb-5"
      ></date-picker>
      <v-row>
        <v-col cols="12" md="4">
          <v-card subtitle="Sales" hover>
            <v-card-text class="borderBT mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.totalSales || 'N/A' }} 
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card subtitle="Total Sales Payment" hover>
            <v-card-text class="borderNavy mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.totalSalePayments || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card subtitle="Total Sales Due" hover>
            <v-card-text class="borderNavy mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.totalSalesDue || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card subtitle="Billable Expenses" hover>
            <v-card-text class="borderGreen mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.totalBillExpenses || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card subtitle="Bill Expense Payment" hover>
            <v-card-text class="borderGray mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.totalExpensePayments || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card subtitle="Bill Expense Due" hover>
            <v-card-text class="borderGray mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.totalBillExpensesDue || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>

        <!--  -->

        <v-col cols="12" md="4">
          <v-card subtitle="None Billable Expenses" hover>
            <v-card-text class="borderNavy mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.noneBillExpenses || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card subtitle="All Expenses" hover>
            <v-card-text class="borderNavy mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.allExpense || 'N/A' }}
            </v-card-text>
            <!-- <v-card-actions> -->
              <!-- <div class="text-gray" >

                Non-Billable Expense: {{ ReportRepository.profitAndLoss?.noneBillExpenses || 'N/A' }} + 
                Billable Expense: {{ ReportRepository.profitAndLoss?.totalBillExpenses || 'N/A' }}
              </div> -->
          <!-- </v-card-actions> -->
          </v-card>
        </v-col>
        <v-col cols="12" md="4">
          <v-card subtitle="Owner Pickup" hover>
            <v-card-text class="borderBlue mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.totalPickups || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>
  
        
  
      
  
        <!-- <v-col cols="12" md="4">
          <v-card subtitle="Payable Amount" hover>
            <v-card-text class="borderBrown mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.TotalLoanPaymentsReceived || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col>
  
        <v-col cols="12" md="4">
          <v-card subtitle="Receivable Amount" hover>
            <v-card-text class="borderOlive mb-4 mx-3">
              {{ ReportRepository.profitAndLoss?.TotalLoanPaymentsSent || 'N/A' }}
            </v-card-text>
          </v-card>
        </v-col> -->
      </v-row>
    </div>
  </template>
  
  <script setup>
  import { useReportRepository } from "../../store/ReportsRepository";
  const ReportRepository = useReportRepository();
  import { onMounted } from 'vue';
  import DatePicker from 'vue-datepicker-next';
  import { ref } from "vue";
  import 'vue-datepicker-next/index.css';
  
  
  const dateRange = ref([new Date(), new Date()]);
  // Call this function when the date range changes
  const onDateChange = () => {
    const [startDate, endDate] = ReportRepository.dateRange;
    if (startDate && endDate) {
      ReportRepository.fetchProfitAndloss(startDate, endDate);
    }
  };
  


  // Fetch profit and loss data on mount
  onMounted(() => {
    ReportRepository.dateRange = dateRange.value;
    
    ReportRepository.fetchProfitAndloss(dateRange.value[0], dateRange.value[1]);
  });
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
  