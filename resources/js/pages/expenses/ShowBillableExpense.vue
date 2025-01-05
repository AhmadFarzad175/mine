<template>
    <div class="all-expense rounded-xl m-4">
        <div class="card rounded-xl bg-white">
            <!-- Customer Information -->
            <v-skeleton-loader
                :loading="loading"
                type="list-item"
                class="mb-4"
                v-if="loading"
            ></v-skeleton-loader>

            <template v-else>
                <div class="customer-info-container">
                    <div class="customer-info-detail">
                        <span class="info-label">Supplier:</span>
                        <span class="info-value">{{
                            ExpensesRepository.expense.supplier?.name
                        }}</span>
                    </div>

                    <div class="customer-info-detail">
                        <span class="info-label">Phone No:</span>
                        <span class="info-value">{{
                            ExpensesRepository.expense.supplier?.phone
                        }}</span>
                    </div>

                    <div class="customer-info-detail">
                        <span class="info-label">Address:</span>
                        <span class="info-value">
                            {{ ExpensesRepository.expense.supplier?.address }}
                        </span>
                    </div>

                    <div class="customer-info-detail">
                        <span class="info-label">Date:</span>
                        <span class="info-value">{{
                            ExpensesRepository.expense.date
                        }}</span>
                    </div>
                </div>
            </template>

            <!-- Product Table -->
            <div class="totals-container" >
                <div
                    class="customer-info-detail"
                    style=" display:flex; justify-content: space-between;"
                >
                    <span class="info-label" style="text-align: left"
                        >Bill Number: &ThinSpace;
                    </span>
                    <span class="info-value">
                        {{ ExpensesRepository.expense.bill_number }}</span
                    >
                </div>
            </div>
            <v-skeleton-loader
                :loading="loading"
                type="table"
                class="mt-12"
                v-if="loading"
            ></v-skeleton-loader>

            <div v-else class="overflow-x-auto mt-12 pb-6 table w-full">
                <v-table
                    class="text-sm text-left"
                    density="compact"
                    style="width: 150rem"
                >
                    <thead class="text-xs uppercase thead">
                        <tr>
                            <th scope="col" class="py-3 text-start">#</th>
                            <th scope="col" class="py-3 text-center">
                                Product
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-end">
                                SubTotal
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(pro, index) in ExpensesRepository.expense
                                .ExpenseDetails"
                            :key="index"
                        >
                            <td class="text-start">{{ index + 1 }}</td>
                            <td class="text-center">{{ pro.product_name }}</td>
                            <td class="text-center">
                                {{ pro.quantity }} {{ pro.unit }}
                            </td>
                            <td class="text-center">
                                {{ pro.unit_price }}
                                {{ ExpensesRepository.expense.currency?.code }}
                            </td>
                            <td class="text-end">
                                {{ pro.total }}
                                {{ ExpensesRepository.expense.currency?.code }}
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </div>

            <!-- Totals -->
            <div class="totals-container" v-if="!loading">
                <div class="totals-detail">
                    <div class="total-label">Grand Total</div>
                    <div class="total-value">
                        {{ ExpensesRepository.expense.amount }}
                        {{ ExpensesRepository.expense.currency?.code }}
                    </div>
                </div>

                <div class="totals-detail">
                    <div class="total-label">Paid</div>
                    <div class="total-value">
                        {{ ExpensesRepository.expense.paid }}
                        {{ ExpensesRepository.expense.currency?.code }}
                    </div>
                </div>

                <div class="totals-detail grand-total">
                    <div class="total-label">Due</div>
                    <div class="total-value">
                        {{ due }}
                        {{ ExpensesRepository.expense.currency?.code }}
                    </div>
                </div>
            </div>

            <div class="totals-container description">
                <div
                    class="customer-info-detail"
                >
                    <span class="info-label" style="text-align: left"
                        >Note: &ThinSpace;
                    </span>
                    <span class="info-value">
                        {{ ExpensesRepository.expense.description }}</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useExpenseRepository } from "./../../store/ExpensesRepository";
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
const ExpensesRepository = useExpenseRepository();

const loading = ref(true);
const routeParams = useRoute();

const due = computed(() => {
    const amount = parseFloat(ExpensesRepository.expense.amount || 0);
    const paid = parseFloat(ExpensesRepository.expense.paid || 0);
    return amount - paid;
});

onMounted(async () => {
    await ExpensesRepository.fetchBillabeExpense(routeParams.params.id);
    loading.value = false;
});
</script>

<style scoped>
.all-expense {
    width: 100%;
}

.detail_table {
    width: 250px;
    display: flex;
    justify-content: space-between;
    padding: 0.7em;
    gap: 1em;
}

.table tbody tr:hover {
    background-color: #f1f1f1; /* light gray background */
}

.table thead tr {
    background: #ecf1f4 !important;
}

/* Customer Info Styling */
.customer-info-container {
    display: flex;
    flex-direction: column;
    /* margin-top: 1rem;
    margin-right: 1rem; */
    /* background-color: #f8f9fa; */
    /* padding: 1rem; */
    border-radius: 8px;
    width: 400px;
}

.customer-info-detail {
    display: flex;
    justify-content: space-between;
    text-align: right;
    padding: 0.7em;
    border-bottom: 1px dotted #ddd; /* Dotted underline between label and value */
    /* margin-bottom: 0.8rem; */
    /* font-size: 1rem; */
}

.info-label {
    font-weight: bold;
    color: #333;
}

.info-value {
    color: #555;
}

/* Totals Styling */
.totals-container {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    margin-top: 2rem;
    margin-right: 1rem;
}

.totals-detail {
    display: flex;
    justify-content: space-between;
    width: 300px;
    padding: 0.5rem 0;
    font-size: 1rem;
    border-bottom: 1px dotted #ddd;
}

.totals-detail:last-child {
    border-bottom: none;
}

.total-label {
    font-weight: bold;
    color: #333;
}

.total-value {
    color: #555;
}

.grand-total .total-value {
    font-size: 1.2rem;
    font-weight: bold;
    color: #000;
}

.grand-total .total-label {
    font-size: 1.2rem;
    font-weight: bold;
}

.table {
    border-collapse: collapse; /* Ensures borders are combined */
    width: 100%; /* Ensures the table takes the full width */
    border-radius: 8px; /* Rounds the corners */
    overflow: hidden; /* Ensures rounded corners work */
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1); /* Adds a subtle shadow */
}

.table tbody tr {
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.table tbody tr:hover {
    background-color: #f1f1f1; /* Light gray background on hover */
}

.table thead tr {
    background: #ecf1f4 !important; /* Keeps the existing header background */
}

.description{
    align-items: flex-start;
}
</style>
