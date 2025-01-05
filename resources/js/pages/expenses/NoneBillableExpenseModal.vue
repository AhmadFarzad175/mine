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
                            {{ ExpensesRepository.updateDialog ? 'Edit Expense' : 'Create Expense' }}
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
                                    v-model="formData.expense_category_id"
                                    :items="ExpensesRepository.categories"
                                    variant="outlined"
                                    label=" * Category"
                                    style="width: 45%"
                                    item-value="id"
                                    item-title="name"
                                    clearable
                                    density="compact"
                                    :rules="[rules.required]"
                                ></v-autocomplete>
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
                                v-model="formData.note"
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
import { useExpenseRepository } from "./../../store/ExpensesRepository";

const ExpensesRepository = useExpenseRepository();

const formData = reactive({
    date: "",
    amount: "",
    expense_category_id: "",
    note: "",
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
        return ExpensesRepository.createDialog || ExpensesRepository.updateDialog;
    },
    set(value) {
        ExpensesRepository.createDialog = value;
        ExpensesRepository.updateDialog = value;
    },
});

// Function to swap currencies
const swapeCurrency = () => {
    formData.currencyIndex = (formData.currencyIndex + 1) % ExpensesRepository.currencies.length;
    formData.currency = ExpensesRepository.currencies[formData.currencyIndex];
};

// Function to close the dialog
const closeDialog = () => {
    ExpensesRepository.createDialog = false;
    ExpensesRepository.updateDialog = false;
};

// Initialize form data
formData.date = ExpensesRepository.getTodaysDate();

onMounted(() => {
    if (ExpensesRepository.updateDialog) {
        console.log(ExpensesRepository.currencies, ExpensesRepository.singleExpense)
        // If we are editing an existing expense, fill the form with the existing data
        formData.id = ExpensesRepository.singleExpense?.id || '';
        formData.date = ExpensesRepository.singleExpense?.date || '';
        formData.amount = ExpensesRepository.singleExpense?.amount || '';
        formData.expense_category_id = ExpensesRepository.singleExpense?.category.id || '';
        formData.note = ExpensesRepository.singleExpense?.note || '';

        // Find the index of the existing currency
        let foundCurrencyIndex = ExpensesRepository.currencies.findIndex(
            (currency) => currency.id === ExpensesRepository.singleExpense?.currency_id
        );

        // Default to 0 if no match is found (i.e., if `foundCurrencyIndex` is -1)
        formData.currencyIndex = foundCurrencyIndex !== -1 ? foundCurrencyIndex : 0;

        // Find the currency object
        formData.currency = ExpensesRepository.currencies.find(
            (currency) => currency.id === ExpensesRepository.singleExpense?.currency_id
        ) || ExpensesRepository.currencies[0]; // Default to the first currency if not found

    } else {
        // If we are creating a new expense, reset the form
        formData.id = "";
        formData.date = ExpensesRepository.getTodaysDate();
        formData.amount = "";
        formData.expense_category_id = "";
        formData.note = "";
        formData.currencyIndex = 0; // Default to the first currency
        formData.currency = ExpensesRepository.currencies[0]; // Default to the first currency
    }
});


// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (ExpensesRepository.updateDialog) {
            ExpensesRepository.UpdateNoneBillableExpense(formData.id, formData);
        } else {
            ExpensesRepository.CreateNoneBillableExpense(formData);
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
