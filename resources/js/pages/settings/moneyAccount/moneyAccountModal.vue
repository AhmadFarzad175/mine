<template>
    <div>
        <v-dialog
            transition="dialog-top-transition"
            width="50rem"
            v-model="isDialogActive"
        >
            <template v-slot:default="{ isActive }">
                <v-card>
                    <v-card-title class="pt-4 d-flex justify-space-between">
                        <p class="font-weight-bold">
                            &nbsp;
                            {{
                                SettingRepository.updateDialog
                                    ? "Edit Account"
                                    : "Create Account"
                            }}&nbsp;
                        </p>
                        <v-btn class="px-2" variant="text" @click="closeDialog">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-divider class="mx-6"></v-divider>
                    <v-spacer></v-spacer>

                    <v-card-text>
                        <v-form ref="formRef">
                            <div class="pt-6">
                                <v-text-field
                                    v-model="formData.name"
                                    variant="outlined"
                                    label=" * Account name"
                                    style="width: 100%"
                                    class="pb-4"
                                    :return-object="false"
                                    :rules="[rules.required, rules.name]"
                                    density="compact"
                                ></v-text-field>

                                <div class="d-flex justify-space-between mb-6">
                                    <v-text-field
                                        v-model="formData.amount"
                                        variant="outlined"
                                        label="* Amount"
                                        style="
                                            width: 45%;
                                            height: 50px;
                                            position: relative;
                                        "
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
                            </div>
                        </v-form>
                    </v-card-text>
                    <div class="d-flex mb-6 mx-6">
                        <v-btn color="#112F53" @click="submitForm">
                            &nbsp; &nbsp;
                            {{
                                SettingRepository.updateDialog
                                    ? "Update"
                                    : "Create"
                            }}
                            &nbsp; &nbsp;
                        </v-btn>
                    </div>
                </v-card>
            </template>
        </v-dialog>
    </div>
</template>

<script setup>
import { reactive, ref, computed, watch, onMounted } from "vue";
import { useSettingRepository } from "../../../store/SettingsRepository";
import { useExpenseRepository } from "../../../store/ExpensesRepository";
const SettingRepository = useSettingRepository();

const formData = reactive({
    name: "",
    amount: "",
    currencyIndex: 0,
    currency: "", // Default to the first currency
});

const formRef = ref(null);

const rules = {
    required: (value) => !!value || "This field is required",
    // name: (value) => /^[a-zA-Z\u0600-\u06FF\s]*$/.test(value) || "Name is not correct",
};

// Computed property to handle the dialog visibility
const isDialogActive = computed({
    get() {
        return SettingRepository.createDialog || SettingRepository.updateDialog;
    },
    set(value) {
        SettingRepository.createDialog = value;
        SettingRepository.updateDialog = value;
    },
});

// Function to swap currencies
const swapeCurrency = () => {
    formData.currencyIndex =
        (formData.currencyIndex + 1) % SettingRepository.currencies.length;
    formData.currency = SettingRepository.currencies[formData.currencyIndex];
    console.log(formData.currency)
};

// Watcher to fill form data when editing
// watch(() => SettingRepository.updateDialog, (isEditing) => {
//     if (isEditing) {
//         formData.name = SettingRepository.saleProduct?.product_name || '';
//         formData.details = SettingRepository.saleProduct?.details || '';
//     } else {
//         // Reset form when creating a new product
//         formData.name = "";
//         formData.details = "";
//     }
// });

onMounted(() => {
    if (SettingRepository.updateDialog) {
        formData.id = SettingRepository.moneyAccount?.id || "";
        formData.name = SettingRepository.moneyAccount?.name || "";
        formData.amount = SettingRepository.moneyAccount?.amount || "";

        // Find the index of the existing currency
        let foundCurrencyIndex = SettingRepository.currencies.findIndex(
            (currency) =>
                currency.id === SettingRepository.moneyAccount?.currency_id
        );
        // Default to 0 if no match is found (i.e., if `foundCurrencyIndex` is -1)
        formData.currencyIndex =
            foundCurrencyIndex !== -1 ? foundCurrencyIndex : 0;

        // Find the currency object
        formData.currency =
            SettingRepository.currencies.find(
                (currency) =>
                    currency.id === SettingRepository.moneyAccount?.currency_id
            ) || SettingRepository.currencies[0]; // Default to the first currency if not found
    } else {
        formData.id = "";
        formData.name = "";
        formData.amount = "";
        formData.currencyIndex = 0; // Default to the first currency
        formData.currency = SettingRepository.currencies[0]; // Default to the first currency
    }
});

// Function to close the dialog
const closeDialog = () => {
    SettingRepository.createDialog = false;
    SettingRepository.updateDialog = false;
};

// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (SettingRepository.updateDialog) {
            // Call the update method
            SettingRepository.UpdateMoneyAccount(formData.id, formData);
        } else {
            // Call the create method
            SettingRepository.CreateMoneyAccount(formData);
        }
        closeDialog();
    }
};

</script>

<style scoped></style>
