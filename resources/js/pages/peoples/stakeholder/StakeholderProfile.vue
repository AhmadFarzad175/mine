<template>
    <ProfileModal
        :stake-id="stakeId"
        v-if="
            PeopleRepository.createAccountDialog ||
            PeopleRepository.updateAccountDialog
        "
    />

    <StatementModal
        :stake-id="stakeId"
        :pay_or_receive="pay_or_receive"
        v-if="
            PeopleRepository.createStatementDialog ||
            PeopleRepository.updateStatementDialog
        "
    />

    <v-row>
        <v-col cols="12" md="4">
            <v-card subtitle="Expenses" hover>
                <v-card-text class="borderBT mb-4 mx-3"> N/A </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12" md="4">
            <v-card subtitle="Total Expenses Amount" hover>
                <v-card-text class="borderBT mb-4 mx-3"> N/A </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12" md="4">
            <v-card subtitle="Total Expenses Payment" hover>
                <v-card-text class="borderNavy mb-4 mx-3"> N/A </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <!-- Product Table -->

    <v-card style="margin-top: 20px">
        <v-tabs v-model="tab" align-tabs="start">
            <v-tab :value="1">Sales</v-tab>
            <v-tab :value="2">Statements</v-tab>
            <v-tab :value="3">Accounts</v-tab>
        </v-tabs>

        <v-card-text>
            <v-tabs-window v-model="tab">
                <v-tabs-window-item :value="1">
                    <!-- Payment Sent Data Table -->
                    <v-data-table-server
                        theme="cursor-pointer"
                        v-model:items-per-page="PeopleRepository.itemsPerPage"
                        :headers="sale"
                        :items-length="PeopleRepository.totalItems"
                        :loading="PeopleRepository.loading"
                        :item-key="'id'"
                        hover
                        class="mx-auto mt-4 no-padding-table"
                    >
                    </v-data-table-server>
                </v-tabs-window-item>

                <!-- @update:options="PeopleRepository.fetchReceivedLoans" -->
                <!-- Payment Received Data Table -->
                <v-tabs-window-item :value="2">
                    <div class="d-flex justify-end gap-2" >
                        <v-btn
                            color="#112F53"
                            variant="flat"
                            @click="createPayment('PayMoney')"
                        >
                            I Pay Money
                        </v-btn>
                        <v-btn
                            color="#112F53"
                            variant="flat"
                            @click="createPayment('ReceiveMoney')"
                        >
                            I Receive Money
                        </v-btn>
                    </div>

                    <v-data-table-server
                        theme="cursor-pointer"
                        v-model:items-per-page="PeopleRepository.itemsPerPage"
                        :headers="statement"
                        :items="PeopleRepository.stakeholderStatements"
                        :items-length="PeopleRepository.totalItems"
                        :loading="PeopleRepository.loading"
                        :item-key="'id'"
                        @update:options="
                            (options) =>
                                PeopleRepository.fetchStakeholderStatements({
                                    ...options,
                                    id: stakeId,
                                })
                        "
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
                                            <v-icon color="gray"
                                                >mdi-cash-edit</v-icon
                                            >
                                            &nbsp; Edit
                                        </v-list-item-title>
                                        <v-list-item-title
                                            class="cursor-pointer d-flex gap-3 justify-left"
                                            @click="deleteItemReceived(item)"
                                        >
                                            <v-icon color="gray"
                                                >mdi-delete-outline</v-icon
                                            >
                                            &nbsp; Delete
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                    </v-data-table-server>
                </v-tabs-window-item>

                <v-tabs-window-item :value="3">
                    <div class="d-flex justify-end mb-3">
                        <v-btn
                            color="#112F53"
                            variant="flat"
                            @click="PeopleRepository.createAccountDialog=true"
                        >
                            Create
                        </v-btn>
                    </div>
                    <v-data-table-server
                        theme="cursor-pointer"
                        v-model:items-per-page="PeopleRepository.itemsPerPage"
                        :headers="Account"
                        :items-length="PeopleRepository.totalItems"
                        :items="PeopleRepository.stakeholderAccounts"
                        :loading="PeopleRepository.loading"
                        @update:options="
                            (options) =>
                                PeopleRepository.fetchStakeholderAccounts({
                                    ...options,
                                    id: stakeId,
                                })
                        "
                        :item-key="PeopleRepository.stakeholderAccounts"
                        hover
                        class="mx-auto mt-4 no-padding-table"
                    >
                        <template v-slot:item.action="{ item }">
                            <v-menu>
                                <template v-slot:activator="{ props }">
                                    <v-list-item-title
                                        class="cursor-pointer d-flex"
                                        style="justify-content: flex-end"
                                        @click="editItem(item)"
                                    >
                                        <v-icon color="gray"
                                            >mdi-square-edit-outline</v-icon
                                        >
                                    </v-list-item-title>
                                </template>
                            </v-menu>
                        </template>
                    </v-data-table-server>
                </v-tabs-window-item>
            </v-tabs-window>
        </v-card-text>
    </v-card>
</template>

<script setup>
import { usePeopleRepository } from "../../../store/PeoplesRepository";
import ProfileModal from "./ProfileModal.vue";
import StatementModal from "./StatementModal.vue";

import { ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute(); // Access the current route
const stakeId = route.params.stakeId; // Get the stakeId from the route parameter
let pay_or_receive = ''

const PeopleRepository = usePeopleRepository();
let tab = ref(1); // Default to ''../store/PeoplesRepository

const editItem = (account) => {
    PeopleRepository.stakeholderAccount = account;
    console.log(account);
    PeopleRepository.updateAccountDialog = true;
};

const createPayment = (payType) => {
    PeopleRepository.createStatementDialog=true
    pay_or_receive = payType;
}

const sale = [
    { title: "Reference", key: "ref", align: "left", sortable: false },
    { title: "Warehouse", key: "warehouse", align: "left", sortable: false },
    { title: "Grand Total", key: "grandTotal", align: "left", sortable: false },
    { title: "Status", key: "status", align: "left", sortable: false },
    {
        title: "Shipping Status",
        key: "shipStatus",
        align: "left",
        sortable: false,
    },
];

const statement = [
    {
        title: "Date",
        align: "left",
        sortable: false,
        key: "date",
        color: "red",
    },
    { title: "Reference", key: "ref", align: "left", sortable: false },

    {
        title: "Type",
        key: "type",
        align: "left",
        sortable: false,
    },
    {
        title: "Debit or Credit",
        key: "payOrReceive",
        align: "left",
        sortable: false,
    },
    { title: "Amount", key: "amountWithCurrency", align: "left", sortable: false },
    { title: "Balance", key: "balance", align: "left", sortable: false },
    {
        title: "description",
        key: "description",
        align: "left",
        sortable: false,
    },
];

const Account = [
    { title: "Name", key: "name", align: "left", sortable: false },
    { title: "Currency", key: "currency.code", align: "left", sortable: false },
    { title: "Amount", key: "amount", align: "left", sortable: false },
    { title: "Action", key: "action", align: "end", sortable: false },
];
</script>

<style scoped>
.gap-2 {
    gap: 10px;
}
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
