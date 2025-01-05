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
                            {{ PeopleRepository.ownerUpdateDialog ? 'Edit Expense' : 'Create Expense' }}
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

                                <!-- <v-autocomplete
                                    v-model="formData.owner_id"
                                    :items="PeopleRepository.owners"
                                    variant="outlined"
                                    label=" * ownerPickup"
                                    style="width: 45%"
                                    item-value="id"
                                    item-title="name"
                                    clearable
                                    density="compact"
                                    :rules="[rules.required]"
                                ></v-autocomplete> -->
                            </div>

                            <div class="d-flex justify-space-between mb-6">
                                <v-text-field
                                    v-model="formData.amount"
                                    variant="outlined"
                                    label="* Amount"
                                    style="width: 45%; height: 50px; position: relative;"
                                    :return-object="false"
                                    density="compact"
                                    :rules="[rules.required, rules.number]"
                                >
                                    <!-- Currency swap span inside the input -->
                                    <template v-slot:append>
                                        <v-btn 
                                            class="currency-btn"
                                            text
                                            @click="swapeCurrency"
                                        >
                                            {{ formData.currency?.code }}
                                        </v-btn>
                                    </template>
                                </v-text-field>
                            </div>

                            <v-textarea
                                v-model="formData.description"
                                variant="outlined"
                                label="Details"
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
import { reactive, ref, computed , onMounted  } from "vue";

import { useRoute } from "vue-router";
const routeParams = useRoute();

import { usePeopleRepository } from "../../../../store/PeoplesRepository";

 
 const PeopleRepository = usePeopleRepository();

const formData = reactive({
    date: "",
    amount: "",
    owner_id: "",
    description: "",
    currency_id: "",
    currencyIndex: 0,
    currency:"", // Default to the first currency
});

const formRef = ref(null);

const rules = {
    required: (value) => !!value || "This Field is required",
};

// Computed property for dialog visibility
const isDialogActive = computed({
    get() {
        return PeopleRepository.ownerCreateDialog || PeopleRepository.ownerUpdateDialog;
    },
    set(value) {
        PeopleRepository.ownerCreateDialog = value;
        PeopleRepository.ownerUpdateDialog = value;
    },
});

// Function to swap currencies
const swapeCurrency = () => {
    formData.currencyIndex = (formData.currencyIndex + 1) % PeopleRepository.currencies.length;
    formData.currency = PeopleRepository.currencies[formData.currencyIndex];
};

// Function to close the dialog
const closeDialog = () => {
    PeopleRepository.ownerCreateDialog = false;
    PeopleRepository.ownerUpdateDialog = false;
};

// Initialize form data
formData.date = PeopleRepository.getTodaysDate();

onMounted(() => {


    if (PeopleRepository.ownerUpdateDialog) {
        // If we are editing an existing expense, fill the form with the existing data
        formData.id = PeopleRepository.ownerPickup?.id || '';
        formData.date = PeopleRepository.ownerPickup?.date || '';
        formData.amount = PeopleRepository.ownerPickup?.amount || '';
        // formData.owner_id = PeopleRepository.ownerPickup?.category.id || '';
        formData.description = PeopleRepository.ownerPickup?.description || '';
        formData.owner_id = routeParams.params.id ;

        // Find the index of the existing currency
        let foundCurrencyIndex = PeopleRepository.currencies.findIndex(
            (currency) => currency.id === PeopleRepository.ownerPickup?.currency_id
        );

        // Default to 0 if no match is found (i.e., if `foundCurrencyIndex` is -1)
        formData.currencyIndex = foundCurrencyIndex !== -1 ? foundCurrencyIndex : 0;

        // Find the currency object
        formData.currency = PeopleRepository.currencies.find(
            (currency) => currency.id === PeopleRepository.ownerPickup?.currency_id
        ) || PeopleRepository.currencies[0]; // Default to the first currency if not found

    } else {
        // If we are creating a new expense, reset the form
        formData.id = "";
        formData.date = PeopleRepository.getTodaysDate();
        formData.amount = "";
        formData.owner_id = PeopleRepository.owner.id;
        formData.description = "";
        formData.currencyIndex = 0; // Default to the first currency
        formData.currency = PeopleRepository.currencies[0]; // Default to the first currency
    }
});


// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (PeopleRepository.ownerUpdateDialog) {
            formData.currency_id = formData.currency.id;
            PeopleRepository.UpdateOwnersPickup(formData.id, formData);
        } else {

            formData.currency_id = formData.currency.id;
            PeopleRepository.CreateOwnerPickup(formData);
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

.currency-btn {
    height: 100%;
    color: #112F53;
    background-color: #ecf1f4;
    cursor: pointer;
    padding: 0 12px;
    border-left: 1px solid #dcdcdc;
    margin: 0; /* Remove any space */
}

.v-text-field {
    padding-right: 0 !important; /* Ensure no extra padding */
}

</style>
