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
                                PeopleRepository.updateStatementDialog
                                    ? props.pay_or_receive == "PayMoney" ? "Edit Pay Money" : "Edit Receive Money"
                                    : props.pay_or_receive == "PayMoney" ? "create Pay Money" : "create Receive Money"
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
                                    type="Date"
                                    v-model="formData.date"
                                    variant="outlined"
                                    label=" * Date"
                                    style="width: 45%"
                                    density="compact"
                                    :return-object="false"
                                ></v-text-field>

                                <v-autocomplete
                                    v-model="formData.money_account_id"
                                    :items="PeopleRepository.moneyAccounts"
                                    variant="outlined"
                                    label="Money Account *"
                                    style="width: 45%"
                                    item-value="id"
                                    item-title="name"
                                    clearable
                                    density="compact"
                                    :rules="[rules.required]"
                                    :disabled="
                                        PeopleRepository.updateStatementDialog
                                    "
                                ></v-autocomplete>
                            </div>

                            <div class="input_field_flex mb-6">
                                <v-autocomplete
                                    v-model="formData.stakeholder_account_id"
                                    :items="
                                        PeopleRepository.stakeholderAccounts
                                    "
                                    variant="outlined"
                                    label="Stakeholder Account *"
                                    style="width: 45%"
                                    item-value="id"
                                    item-title="name"
                                    clearable
                                    density="compact"
                                    :rules="[rules.required]"
                                    :disabled="
                                        PeopleRepository.updateStatementDialog
                                    "
                                ></v-autocomplete>
                                <v-text-field
                                    v-model="formData.amount"
                                    variant="outlined"
                                    type="number"
                                    label="Amount *"
                                    style="width: 45%"
                                    :return-object="false"
                                    density="compact"
                                    :rules="[rules.required, rules.number]"
                                    :disabled="
                                        PeopleRepository.updateStatementDialog
                                    "
                                >
                                    <span class="span" >
                                        {{ selectedCurrencyCode }}
                                    </span>
                                </v-text-field>
                            </div>
                            <v-textarea
                                v-model="formData.description"
                                variant="outlined"
                                label="note"
                                rows="2"
                            ></v-textarea>
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
    pay_or_receive: {
        type: String,
        required: true,
    }
});

const formData = reactive({
    date: "",
    currency_id: "",
    amount: "",
    stakeholder_id: props.stakeId, // Initialize with the prop
    stakeholder_account_id: "",
    money_account_id: "",
    description: "",
    pay_or_receive: props.pay_or_receive,
    type: props.pay_or_receive == "PayMoney" ? "PaymentSent" : "PaymentReceived"
});

const formRef = ref(null);

const rules = {
    required: (value) => !!value || "This Field is required",
};

// Computed property for the currency code
const selectedCurrencyCode = computed(() => {
    const selectedAccount = PeopleRepository.moneyAccounts.find(
        (account) => account.id === formData.money_account_id
    );
    
    // Update formData.currency_id whenever the selected account changes
    formData.currency_id = selectedAccount?.currency_id || ""; 

    console.log(selectedAccount);
    return selectedAccount?.code || ""; // Fallback to an empty string
});


// Computed property for dialog visibility
const isDialogActive = computed({
    get() {
        return (
            PeopleRepository.createStatementDialog ||
            PeopleRepository.updateStatementDialog
        );
    },
    set(value) {
        PeopleRepository.createStatementDialog = value;
        PeopleRepository.updateStatementDialog = value;
    },
});

// Function to close the dialog
const closeDialog = () => {
    PeopleRepository.createStatementDialog = false;
    PeopleRepository.updateStatementDialog = false;
};

// Initialize form data

onMounted(() => {
    if (PeopleRepository.updateStatementDialog) {
        // If we are editing an existing Stakeholder, fill the form with the existing data
        formData.id = PeopleRepository.stakeholderStatement?.id || "";
        formData.date = PeopleRepository.stakeholderStatement?.date || "";
        formData.currency_id =
            PeopleRepository.stakeholderStatement?.currency.id || "";
        formData.amount = PeopleRepository.stakeholderStatement?.amount || "";
        formData.money_account_id =
            PeopleRepository.stakeholderStatement?.money_account_id || "";
        formData.stakeholder_account_id =
            PeopleRepository.stakeholderStatement?.stakeholder_account_id || "";
        formData.description =
            PeopleRepository.stakeholderStatement?.description || "";
    } else {
        // If we are creating a new stakeholder, reset the form

        formData.date = PeopleRepository.getTodaysDate();
        formData.currency_id = "";
        formData.amount = "";
        formData.money_account_id = "";
        formData.stakeholder_account_id = "";
        formData.description = "";
    }
});

// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (PeopleRepository.updateStatementDialog) {
            PeopleRepository.UpdateStakeholderAccount(formData.id, formData);
        } else {
            PeopleRepository.CreateStakeholderStatement(formData);
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

.span {
    background-color: #fef1fe;
    position: absolute;
    right: 1px; /* Adjust this value to change the distance from the left edge */
    top: 50%;
    transform: translateY(-50%);
    padding: 0.5rem 0.6rem;
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
