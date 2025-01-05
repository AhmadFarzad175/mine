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
                            {{ PeopleRepository.updatePaymentSentDialog ? 'Edit Payment' : 'Create Payment' }}
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
                                    v-model="formData.payment_date"
                                    variant="outlined"
                                    label=" * Date"
                                    style="width: 45%"
                                    density="compact"
                                    :return-object="false"
                                ></v-text-field>

                                <!-- <v-autocomplete
                                    v-model="formData.expenseCategoryId"
                                    :items="PeopleRepository.categories"
                                    variant="outlined"
                                    label=" * Category"
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
                                    <template v-slot:append >

                                        <v-btn 
                                            class="currency-btn"
                                            @click="swapeCurrency"
                                            text
                                        >
                                            {{ formData.currency?.code }}
                                        </v-btn>
                                    </template>
                                </v-text-field>
                            </div>

                            <v-textarea
                                v-model="formData.notes"
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

import { usePeopleRepository } from "../../../../store/PeoplesRepository";
const PeopleRepository = usePeopleRepository();
import { useRoute } from "vue-router";
const routeParams = useRoute();

const formData = reactive({
    payment_date: "",
    amount: "",
    notes: "",
    currencyIndex: 0,
    currency: {}, // Default to the first currency
    loan_people_id: routeParams.params.id,
    //  PeopleRepository.sales_id,
});
// PeopleRepository.earning?.currency,
const formRef = ref(null);

const rules = {
    required: (value) => !!value || "This Field is required",
};

// Computed property for dialog visibility
const isDialogActive = computed({
    get() {
        return PeopleRepository.createPaymentSentDialog || PeopleRepository.updatePaymentSentDialog;
    },
    set(value) {
        PeopleRepository.createPaymentSentDialog = value;
        PeopleRepository.updatePaymentSentDialog = value;
    },
});

// Function to swap currencies
const swapeCurrency = () => {
    formData.currencyIndex = (formData.currencyIndex + 1) % PeopleRepository.currencies.length;
    formData.currency = PeopleRepository.currencies[formData.currencyIndex];
};

// Function to close the dialog
const closeDialog = () => {
    PeopleRepository.createPaymentSentDialog = false;
    PeopleRepository.updatePaymentSentDialog = false;
};

// Initialize form data
formData.date = PeopleRepository.getTodaysDate();

onMounted(() => {
    if (PeopleRepository.updatePaymentSentDialog) {
        // If we are editing an existing expense, fill the form with the existing data
        formData.id = PeopleRepository.sentLoan?.id || '';
        formData.payment_date = PeopleRepository.sentLoan?.payment_date || '';
        formData.amount = PeopleRepository.sentLoan?.amount || '';
        formData.notes = PeopleRepository.sentLoan?.notes || '';
        formData.currency = PeopleRepository.sentLoan?.currency || {}; // Default to the first currency
        formData.loan_people_id= PeopleRepository.sentLoan.loan_people_id;


   
    } else {

        

        // If we are creating a new expense, reset the form
        formData.id = "";
        formData.payment_date = PeopleRepository.getTodaysDate();
        formData.amount = "";
        formData.notes = "";
        formData.currencyIndex = 0; // Default to the first currency
        formData.currency = PeopleRepository.currencies[0];
        formData.loan_people_id= routeParams.params.id;
        // PeopleRepository.earning?.currency; // Default to the first currency
    }
});


// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (PeopleRepository.updatePaymentSentDialog) {
            PeopleRepository.updatePaymentsent(formData.id, formData);
        } else {
            PeopleRepository.createPaymentSent(formData);
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
