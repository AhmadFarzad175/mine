<template>
    <div>
        <v-dialog
            transition="dialog-top-transition"
            width="50rem"
            v-model="isDialogActive"
        >
            <template v-slot:default="{ isActive }">
                <v-card class="px-3">
                    <v-card-title class="pt-4 d-flex justify-space-between">
                        <p class="font-weight-bold">
                            &nbsp;{{
                                PeopleRepository.updateAccountDialog
                                    ? "Edit Account"
                                    : "Create Account"
                            }}&nbsp;
                        </p>
                        <v-btn class="px-2" variant="text" @click="closeDialog">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-divider class="mx-6"></v-divider>
                    <v-card-text>
                        <v-form ref="formRef">
                            <div class="input_field_flex mb-2">
                                <v-text-field
                                    v-model="formData.name"
                                    variant="outlined"
                                    label="Account Name *"
                                    style="width: 45%"
                                    density="compact"
                                    :return-object="false"
                                    :rules="[rules.required]"
                                ></v-text-field>

                                <v-autocomplete
                                    v-model="formData.currencyId"
                                    :items="PeopleRepository.currencies"
                                    variant="outlined"
                                    label="Currencies *"
                                    style="width: 45%"
                                    item-value="id"
                                    item-title="name"
                                    clearable
                                    density="compact"
                                    :rules="[rules.required]"
                                    :disabled="
                                        PeopleRepository.updateAccountDialog
                                    "
                                ></v-autocomplete>
                            </div>

                            <div class="input_field_flex">
                                <div class="input-width">
                                    <v-text-field
                                        v-model="formData.amount"
                                        variant="outlined"
                                        type="number"
                                        label="Amount *"
                                        :return-object="false"
                                        density="compact"
                                        :rules="[rules.required, rules.number]"
                                        :disabled="
                                            PeopleRepository.updateAccountDialog
                                        "
                                    >
                                    </v-text-field>
                                </div>

                                <v-autocomplete
                                    v-if="formData.amount > 0"
                                    v-model="formData.payOrReceive"
                                    :items="typeArray"
                                    style="width: 45%"
                                    item-value="value"
                                    item-title="label"
                                    label="I want to *"
                                    variant="outlined"
                                    density="compact"
                                    :rules="[rules.required]"
                                    :disabled="
                                        PeopleRepository.updateAccountDialog
                                    "
                                    :color="formData.payOrReceive == 'payMoney' ? 'green' : 'red'"
                                ></v-autocomplete>
                            </div>
                        </v-form>
                    </v-card-text>
                    <div class="d-flex mb-6 mx-6">
                        <v-btn color="#112F53" @click="submitForm">
                            &nbsp; &nbsp; Save &nbsp; &nbsp;
                        </v-btn>
                    </div>
                </v-card>
            </template>
        </v-dialog>
    </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from "vue";

import { useRoute } from "vue-router";
const routeParams = useRoute();

import { usePeopleRepository } from "../../../store/PeoplesRepository";
const PeopleRepository = usePeopleRepository();

const props = defineProps({
    stakeId: {
        type: String,
        required: true,
    },
});

const formData = reactive({
    name: "",
    currencyId: "",
    payOrReceive: "",
    amount: "",
    stakeholderId: props.stakeId, // Initialize with the prop
});

const formRef = ref(null);

const rules = {
    required: (value) => !!value || "This Field is required",
};

// Computed property for dialog visibility
const isDialogActive = computed({
    get() {
        return (
            PeopleRepository.createAccountDialog ||
            PeopleRepository.updateAccountDialog
        );
    },
    set(value) {
        PeopleRepository.createAccountDialog = value;
        PeopleRepository.updateAccountDialog = value;
    },
});

// Function to close the dialog
const closeDialog = () => {
    PeopleRepository.createAccountDialog = false;
    PeopleRepository.updateAccountDialog = false;
};

const typeArray = [
    { label: "Pay Money", value: "payMoney" },
    { label: "Receive Money", value: "receiveMoney" },
];

// Initialize form data

onMounted(() => {
    if (PeopleRepository.updateAccountDialog) {
        console.log(PeopleRepository.stakeholderAccount);
        // If we are editing an existing Stakeholder, fill the form with the existing data
        formData.id = PeopleRepository.stakeholderAccount?.id || "";
        formData.name = PeopleRepository.stakeholderAccount?.name || "";
        formData.currencyId =
            PeopleRepository.stakeholderAccount?.currency.id || "";
        formData.amount = PeopleRepository.stakeholderAccount?.amount || "";
        formData.payOrReceive =
            PeopleRepository.stakeholderAccount?.payOrReceive || "";
    } else {
        // If we are creating a new stakeholder, reset the form

        formData.name = "";
        formData.currencyId = "";
        formData.amount = "" || "0";
        formData.payOrReceive = "";
    }
});

// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (PeopleRepository.updateAccountDialog) {
            PeopleRepository.UpdateStakeholderAccount(formData.id, formData);
        } else {
            PeopleRepository.CreateStakeholderAccount(formData);
        }
        closeDialog();
    }
};
</script>

<style scoped>
.input_field_flex {
    display: flex;
    gap: 1.5em;
}

.input-width {
    width: 350px !important;
}


span {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
}

.switch {
    display: flex;
    justify-content: flex-end;
    align-items: flex-end;
}
.image-upload-container {
    position: relative;
    display: inline-block;
    right: 20px;
    height: 7.29rem;
    width: 8rem;
    margin-right: 1.25px !important;
    border-radius: 0.5rem;
    overflow: hidden;
    border: 1px solid gray;
}

.image-preview {
    height: 100%;
    width: 100%;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    top: 0;
    height: 100%;
    width: 100%;
    border-radius: 0.5rem;
    background-color: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
}

.overlay-button {
    border: none;
    background-color: transparent;
    cursor: pointer;
}

.close-button {
    position: absolute;
    bottom: -0.25rem;
    right: -0.25rem;
    border: none;
    background-color: transparent;
    /* color: #060505; */
    cursor: pointer;
}

.edit-button {
    position: absolute;
    top: -0.25rem;
    right: -0.25rem;
    border: none;
    background-color: transparent;
    color: #777777;
    cursor: pointer;
}
</style>
