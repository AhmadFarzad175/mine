<template>
    <CurrencyModal
        v-if="SettingRepository.createDialog || SettingRepository.updateDialog"
    />

    <!-- <ProductModal v-if="SettingRepository.createDialog || SettingRepository.updateDialog" /> -->

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
                        style="width: 300px"
                        single-line
                        hide-details
                        v-model="SettingRepository.currencySearch"
                    ></v-text-field>
                </div>
                <div class="btn">
                    <v-btn variant="outlined" color="#112F53"> Filter </v-btn>
                    &nbsp;
                    <v-btn
                        color="#112F53"
                        @click="SettingRepository.createDialog = true"
                        variant="flat"
                    >
                        Create Currency
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
                                    v-model:items-per-page="
                                        SettingRepository.itemsPerPage
                                    "
                                    :headers="headers"
                                    :items-length="SettingRepository.totalItems"
                                    :items="SettingRepository.currencies"
                                    :loading="SettingRepository.loading"
                                    :search="SettingRepository.currencySearch"
                                    @update:options="
                                        SettingRepository.fetchCurrencies
                                    "
                                    :item-key="SettingRepository.currencies"
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
                                        <span class="text-red">{{
                                            item.due
                                        }}</span>
                                    </template>

                                    <template v-slot:item.action="{ item }">
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
                                                        @click="editItem(item)"
                                                    >
                                                        <v-icon color="gray"
                                                            >mdi-cash-edit</v-icon
                                                        >
                                                        &nbsp; Edit
                                                    </v-list-item-title>
                                                    <v-list-item-title
                                                        class="cursor-pointer d-flex gap-3 justify-left pb-3"
                                                        @click="
                                                            deleteItem(item)
                                                        "
                                                    >
                                                        <v-icon color="gray"
                                                            >mdi-delete-outline</v-icon
                                                        >
                                                        &nbsp; Delete
                                                    </v-list-item-title>
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
import { watch } from "vue";
import { useSettingRepository } from "../../../store/SettingsRepository";
const SettingRepository = useSettingRepository();

//  import ProductModalCategory from "./productModalCategory.vue"
import { ref, computed } from "vue";
import CurrencyModal from "./currencyModal.vue";
const selectedItems = ref([]); // Track selected checkboxes
const selectAll = ref(false); // Track the "select all" checkbox

// Calculate if the select all checkbox should be indeterminate
//  const indeterminate = computed(() => selectedItems.value.length > 0 && selectedItems.value.length < SettingRepository.currencies.length);

const indeterminate = computed(() => {
    return (
        selectedItems.value.length > 0 &&
        selectedItems.value.length < SettingRepository.currencies.length
    );
});

// Watch the selectedItems to automatically check/uncheck selectAll
watch(selectedItems, (newVal) => {
    selectAll.value = newVal.length === SettingRepository.currencies.length;
});

// Toggle the selection of all items
const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedItems.value = [];
    } else {
        selectedItems.value = SettingRepository.currencies.slice();
    }
};

const headers = [
    { title: "", key: "checkbox", align: "start", sortable: false }, // Checkbox column
    {
        title: "Currency Name",
        align: "left",
        sortable: false,
        key: "name",
        color: "red",
    },
    {
        title: "Currency Code",
        align: "left",
        sortable: false,
        key: "code",
        color: "red",
    },
    { title: "Currency Symbol", key: "symbol", align: "left", sortable: false },
    { title: "Currency Rate", key: "rate", align: "left", sortable: false },
    { title: "Action", key: "action", align: "end", sortable: false },
];

//  const CreatePaymentDialog = (id) => {
//    SettingRepository.productId = id;
//    SettingRepository.createPayment = true;
//  };

const deleteItem = async (item) => {
    await SettingRepository.DeleteCurrency(item.id);
};

const editItem = (Currency) => {
    SettingRepository.Currency = Currency;
    SettingRepository.updateDialog = true;
};
// const editItem = (category) => {
//     SettingRepository.category = category; // Set the category for editing
//     SettingRepository.updateDialog = true; // Open the edit dialog
// };

//  const ViewPaymentDialog = (item) => {
//    SettingRepository.ViewEarning = true;
//  };
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
