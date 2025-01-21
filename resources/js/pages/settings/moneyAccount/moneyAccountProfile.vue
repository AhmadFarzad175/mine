<template>
    <div class="all-expense rounded-xl m-4">
        <div class="card rounded-xl bg-white">
            <!-- v-table server -->
            <div>
                <v-app>
                    <v-main>
                        <v-row>
                            <v-col>
                                <v-data-table-server
                                    theme="cursor-pointer"
                                    v-model:items-per-page="
                                        SettingRepository.itemsPerPage
                                    "
                                    :headers="headers"
                                    :items-length="SettingRepository.totalItems"
                                    :items="SettingRepository.accountStatements"
                                    :loading="SettingRepository.loading"
                                    :search="
                                        SettingRepository.moneyAccountSearch
                                    "
                                    @update:options="
                                        () =>
                                            SettingRepository.fetchAccountStatements(accountId)
                                    "
                                    :item-key="
                                        SettingRepository.accountStatements
                                    "
                                    hover
                                    class="mx-auto mt-4 no-padding-table"
                                >
                                    <template v-slot:item.action="{ item }">
                                        <v-menu>
                                            <template
                                                v-slot:activator="{ props }"
                                            >
                                                <v-btn
                                                    icon
                                                    v-bind="props"
                                                    variant="text"
                                                >
                                                    <v-icon
                                                        >mdi-dots-vertical</v-icon
                                                    >
                                                </v-btn>
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
import { useSettingRepository } from "../../../store/SettingsRepository";
import { useRoute } from "vue-router";
const SettingRepository = useSettingRepository();

const route = useRoute(); // Access the current route
const accountId = route.params.accountId; // Get the stakeId from the route parameter

const headers = [
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
    {
        title: "Amount",
        key: "amountWithCurrency",
        align: "left",
        sortable: false,
    },
    { title: "Balance", key: "balance", align: "left", sortable: false },
    {
        title: "description",
        key: "description",
        align: "left",
        sortable: false,
    },
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

.v-table {
    width: 100% !important;
}
.v-main {
    margin-left: 0px !important;
    margin-right: 0px !important;
}

.btn-search {
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

.check-all-checkbox > * {
    margin-left: -5px !important;
}
</style>
