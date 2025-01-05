<template>
    <usersModal
        v-if="PeopleRepository.createDialog || PeopleRepository.updateDialog"
    />

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
                        v-model="PeopleRepository.userSearch"
                    ></v-text-field>
                </div>
                <div class="btn">
                    <v-btn variant="outlined" color="#112F53"> Filter </v-btn>
                    &nbsp;
                    <v-btn
                        color="#112F53"
                        @click="PeopleRepository.createDialog = true"
                        variant="flat"
                    >
                        Create User
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
                                        PeopleRepository.itemsPerPage
                                    "
                                    :headers="headers"
                                    :items-length="PeopleRepository.totalItems"
                                    :items="PeopleRepository.users"
                                    :loading="PeopleRepository.loading"
                                    :search="PeopleRepository.userSearch"
                                    @update:options="
                                        PeopleRepository.fetchUsers
                                    "
                                    :item-key="PeopleRepository.users"
                                    hover
                                    class="mx-auto mt-4 no-padding-table"
                                >
                                    <!-- ========================== -->
                                    <template v-slot:item.name="{ item }">
                                        <div
                                            style="
                                                display: flex;
                                                align-items: center;
                                            "
                                        >
                                            <!-- Container for the image -->

                                            <div
                                                style="
                                                    width: 42px;
                                                    height: 42px;
                                                    overflow: hidden;
                                                    margin-right: 10px;
                                                "
                                            >
                                                <!-- Use inline styles to make the avatar square -->
                                                <v-avatar
                                                    size="42"
                                                    style="
                                                        width: 100%;
                                                        height: 100%;
                                                        border-radius: 0;
                                                    "
                                                >
                                                    <img
                                                        :src="item.image"
                                                        alt="Profile Photo"
                                                        style="
                                                            width: 100%;
                                                            height: auto;
                                                            object-fit: cover;
                                                        "
                                                    />
                                                </v-avatar>
                                            </div>
                                            <!-- Display the name with some space -->
                                            <span style="margin-right: 10px">{{
                                                item.name
                                            }}</span>
                                        </div>
                                    </template>

                                    <!-- ================================= -->
                                    <template v-slot:item.state="{ item }">
                                        <div>
                                            <v-switch
                                                v-model="item.status"
                                                color="primary"
                                                :label="
                                                    item.status
                                                        ? 'Active'
                                                        : 'Inactive'
                                                "
                                              
                                                @change="updateState(item)"
                                                class="switchStyle" ></v-switch
                                            >
                                        </div>
                                    </template>

                                    <!-- ==================================== -->

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
import { usePeopleRepository } from "../../../store/PeoplesRepository";
// import OwnerModal from "./OwnerModal.vue";
// import OwnerPickupModal from "./OwnerPickup/OwnerPickupModal.vue";

const PeopleRepository = usePeopleRepository();
import usersModal from "./usersModal.vue";
import { ref, computed } from "vue";
const selectedItems = ref([]); // Track selected checkboxes
const selectAll = ref(false); // Track the "select all" checkbox

// Calculate if the select all checkbox should be indeterminate
//  const indeterminate = computed(() => selectedItems.value.length > 0 && selectedItems.value.length < PeopleRepository.users.length);

const indeterminate = computed(() => {
    return (
        selectedItems.value.length > 0 &&
        selectedItems.value.length < PeopleRepository.users.length
    );
});

// Watch the selectedItems to automatically check/uncheck selectAll
watch(selectedItems, (newVal) => {
    selectAll.value = newVal.length === PeopleRepository.users.length;
});

// Toggle the selection of all items
const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedItems.value = [];
    } else {
        selectedItems.value = PeopleRepository.users.slice();
    }
};

const updateState = async (item) => {
    const formData = {
        status: item.status,
    };
    await PeopleRepository.CreateForSwitch(formData, item.id);
};

const headers = [
    // { title: "", key: "checkbox", align: "start", sortable: false }, // Checkbox column

    {
        title: "Profile",
        align: "left",
        sortable: false,
        key: "name",
        color: "red",
    },
    { title: "Mobile No", key: "phone", align: "left", sortable: false },
    { title: "Email", key: "email", align: "left", sortable: false },
    { title: "Role", key: "role.name", align: "left", sortable: false },
    { title: "Status", key: "state", align: "left", sortable: false },
    { title: "Action", key: "action", align: "end", sortable: false },
];

//  const CreatePaymentDialog = (id) => {
//    PeopleRepository.productId = id;
//    PeopleRepository.createPayment = true;
//  };

const deleteItem = async (item) => {
    await PeopleRepository.DeleteUser(item.id);
};

const editItem = (user) => {
    PeopleRepository.user = user;
    PeopleRepository.updateDialog = true;
};
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
