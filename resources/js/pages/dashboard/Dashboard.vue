<template>
    <v-row>
        <v-col cols="12" md="3">
            <v-card subtitle="Today Sales" hover>
                <v-card-text class="borderBT mb-4 mx-3">
                    <v-icon left>mdi-cash-register</v-icon>
                    {{ DashboardRepository.dashboards?.today_sales || "N/A" }}
                </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12" md="3">
            <v-card subtitle="Today Expenses" hover>
                <v-card-text class="borderNavy mb-4 mx-3">
                    <v-icon left>mdi-credit-card</v-icon>
                    {{
                        DashboardRepository.dashboards?.today_expenses || "N/A"
                    }}
                </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12" md="3">
            <v-card subtitle="Today Profit" hover>
                <v-card-text class="borderNavy mb-4 mx-3">
                    <v-icon left>mdi-cash-multiple</v-icon>
                    {{ DashboardRepository.dashboards?.today_profit || "N/A" }}
                </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12" md="3">
            <v-card subtitle="Today Pickup" hover>
                <v-card-text class="borderGreen mb-4 mx-3">
                    <v-icon left>mdi-cash-multiple</v-icon>
                    {{ DashboardRepository.dashboards?.today_pickup || "N/A" }}
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <div
        style="
            display: flex;
            justify-content: space-between;
            gap: 1em;
            margin-top: 1em;
        "
    >
        <div class="bordered-shadow" style="width: 70%">
            <barChart />
        </div>
        <div class="bordered-shadow" style="width: 30%">
            <TotalPaymentCharts />
        </div>
    </div>

    <div
        style="
            margin-top: 1em;
            display: flex;
            justify-content: space-between;
            gap: 1em;
        "
    >
        <div class="bordered-shadow" style="width: 50%">
            <v-card class="pt-4 bg-white rounded-xl" variant="flat">
                <div>
                    <!-- Header -->
                    <div class="d-flex justify-space-between mb-6">
                        <span>
                            <p class="text-lg font-bold">
                                {{ DashboardRepository.totalExpenses || 0 }}
                            </p>
                            <p class="text-subtitle-2">
                                Total Expense base on Amount and Percentage
                            </p>
                        </span>
                        <span class="flex flex-col gap-1">
                            <!-- Filter buttons for switching between expense views -->
                            <v-btn
                                size="x-small"
                                :style="{
                                    backgroundColor:
                                        activeButton === 'year'
                                            ? '#112F53'
                                            : '',
                                    color:
                                        activeButton === 'year' ? '#fff' : '',
                                }"
                                @click="
                                    updateExpenses(
                                        DashboardRepository.dashboards
                                            .yearExpenses,
                                        'year',
                                        'black'
                                    )
                                "
                            >
                                {{ "This Year" }}
                            </v-btn>
                            <v-btn
                                size="x-small"
                                :style="{
                                    backgroundColor:
                                        activeButton === 'month'
                                            ? '#112F53'
                                            : '',
                                    color:
                                        activeButton === 'month' ? '#fff' : '',
                                }"
                                @click="
                                    updateExpenses(
                                        DashboardRepository.dashboards
                                            .monthExpenses,
                                        'month',
                                        'red'
                                    )
                                "
                            >
                                {{ "This Month" }}
                            </v-btn>
                            <v-btn
                                size="x-small"
                                :style="{
                                    backgroundColor:
                                        activeButton === 'today'
                                            ? '#112F53'
                                            : '',
                                    color:
                                        activeButton === 'today' ? '#fff' : '',
                                }"
                                @click="
                                    updateExpenses(
                                        DashboardRepository.dashboards
                                            .todayExpenses,
                                        'today',
                                        'green'
                                    )
                                "
                            >
                                {{ "Today" }}
                            </v-btn>
                        </span>
                    </div>

                    <!-- List of expenses with percentages and progress bars -->
                    <div
                        v-for="expense in DashboardRepository.expensesList"
                        :key="expense.id"
                        class="mb-4"
                    >
                        <div class="d-flex justify-space-between">
                            <span class="text-sm font-bold text-gray-700">
                                {{ expense.name }} ({{
                                    expense.total || 0
                                }})
                            </span>
                            <span class="text-sm font-bold">
                                {{ expense.percentage?.toFixed(2) }}%
                            </span>
                        </div>
                        <v-progress-linear
                            :model-value="Number(expense.percentage)"
                            height="6"
                            :color="generateRandomColor()"
                            class="rounded-lg"
                        ></v-progress-linear>
                    </div>
                </div>
            </v-card>
        </div>

        <div class="bordered-shadow" style="width: 50%">
            <v-card variant="flat" class="top-customers-card">
                <h2 class="pr-4 py-2">Top Customers</h2>
                <v-table class="payment-table">
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Number of Sales</th>
                                <th class="text-left">Total Amount</th>
                                <th class="text-left">Total Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in DashboardRepository.dashboards
                                    .top_customers"
                                :key="item.id"
                            >
                                <td>{{ item.customer_name }}</td>
                                <td>{{ item.total_sales }}</td>
                                <td>{{ item.total_amount }}</td>
                                <td>{{ item.total_paid }}</td>
                            </tr>
                        </tbody>
                    </template>
                </v-table>
            </v-card>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useDashboardRepository } from "./../../store/DashboardRepository";
import TotalPaymentCharts from "./TotalPaymentCharts.vue";
import barChart from "./barChart.vue";

const DashboardRepository = useDashboardRepository();

onMounted(() => {
    DashboardRepository.fetchDashboardData();
});

const activeButton = ref("today");

const updateExpenses = (expenses, type, color) => {
    activeButton.value = type;
    DashboardRepository.updateExpenses(expenses.expenses, color);
    DashboardRepository.totalExpenses = expenses.total
};

// Generate a random color
const generateRandomColor = () => {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
};
</script>

<style scoped>
.MenuColor {
    background-color: #f8f8f8 !important;
}
.calibri_font {
    font-family: "Calibri", sans-serif;
    font-weight: bold;
    font-style: normal;
}

.bordered-shadow {
    border: 1px solid #e0e0e0; /* Light border */
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    border-radius: 8px; /* Rounded corners (optional) */
    padding: 16px; /* Inner padding (optional) */
    background-color: white; /* Background color */
}

.payment-table thead th {
    background-color: #eceff1;
    color: #424242;
    font-weight: bold;
    padding: 12px;
    border-bottom: 2px solid #cfd8dc;
}

.payment-table tbody tr:nth-child(odd) {
    background-color: #fafafa;
}
.payment-table tbody tr {
    width: 100%;
}
.payment-table tbody tr:hover {
    background-color: #f1f1f1;
}

.payment-table tbody td {
    padding: 10px;
    border-bottom: 1px solid #eceff1;
}
</style>
