<template>
    <PaymentModal v-if="EarningRepository.createPaymentDialog || EarningRepository.updatePaymentDialog" />

    <div>
        <v-dialog
            transition="dialog-top-transition"
            width="50rem"
            v-model="EarningRepository.viewPaymentDialog"
        >
            <template v-slot:default="{ isActive }">
                <v-card class="px-3 m-12 payment-card">
                    <v-card-title
                        class="px-2 pt-4 d-flex justify-space-between"
                    >
                        <p class="font-weight-bold title-text">
                            Payments
                        </p>

                        <v-btn class="px-2" variant="text" @click="isActive.value = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    
                    <v-divider></v-divider>
                    

                    <div class="d-flex flex-column body-2 px-2 py-4">
                        <v-table class="payment-table">
                            <thead>
                                <tr>
                                    <th class="text-left">Date</th>
                                    <th class="text-left">Paid Amount</th>
                                    <th class="text-center">Note</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    class="text-left"
                                    v-for="(item, index) in EarningRepository.salePayments"
                                    :key="index"
                                >
                                    <td>{{ item.date }}</td>
                                    <td>{{ item.amount }}</td>
                                    <td class="text-center">{{ item.description }}</td>
                                    <td class="text-right">
                                        <v-btn
                                            class="text-red action-btn"
                                            icon
                                            flat
                                            @click="deleteItem(item)"
                                        >
                                            <v-icon>mdi mdi-trash-can-outline</v-icon>
                                        </v-btn>
                                        <v-btn
                                            class="action-btn"
                                            icon
                                            flat
                                            @click="editItem(item)"
                                        >
                                            <v-icon>mdi-pencil-outline</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                </v-card>
            </template>
        </v-dialog>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useEarningRepository } from "@/store/EarningRepository";

const EarningRepository = useEarningRepository();
import paymentModal from "./PaymentModal.vue";

const deleteItem = async (item) => {
    await EarningRepository.DeleteEarningPayment(item.id);
};

const editItem = async (item) => {
    console.log(item)
    // EarningRepository.productId = item.id;
    EarningRepository.earning = item
    EarningRepository.FetchEarningPayment(item.id).then(() => {
        EarningRepository.updatePaymentDialog = true;
    });
};
</script>

<style scoped>
.payment-card {
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.title-text {
    font-size: 1.5rem;
    color: #333;
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

.payment-table tbody tr:hover {
    background-color: #f1f1f1;
}

.payment-table tbody td {
    padding: 10px;
    border-bottom: 1px solid #eceff1;
}

.action-btn {
    color: #424242;
    transition: color 0.2s;
}

.action-btn:hover {
    color: #1976d2;
}

.v-btn .v-icon {
    font-size: 18px;
}

/* .v-card-title {
    background-color: #f5f5f5;
    padding: 16px;
    border-radius: 10px 10px 0 0;
} */
</style>
