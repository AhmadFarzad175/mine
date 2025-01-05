<template>
    <div>
        <v-dialog
            transition="dialog-top-transition"
            width="50rem"
            v-model="isDialogActive"
            class=""
        >
            <template v-slot:default="{ isActive }">
                <v-card>
                    <v-card-title class="pt-4 d-flex justify-space-between">
                        <p class="font-weight-bold">&nbsp;
                            {{ ExpensesRepository.updateDialog ? 'Edit Product' : 'Create Product' }}&nbsp;
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
                                    label=" * Product Name"
                                    style="width: 100%"
                                    class="pb-4"
                                    :return-object="false"
                                    :rules="[rules.required, rules.name]"
                                    density="compact"
                                ></v-text-field>

                                <v-text-field
                                    v-model="formData.unit"
                                    variant="outlined"
                                    style="width: 100%"
                                    label=" * Unit"
                                    :return-object="false"
                                    class="pb-4 rtl-autocomplete"
                                    :rules="[rules.required]"
                                    density="compact"
                                ></v-text-field>
                            </div>
                        </v-form>
                    </v-card-text>
                    <div class="d-flex mb-6 mx-6">
                        <v-btn color="#112F53" @click="submitForm">
                            &nbsp; &nbsp; {{ ExpensesRepository.updateDialog ? 'Update' : 'Create' }} &nbsp; &nbsp;
                        </v-btn>
                    </div>
                </v-card>
            </template>
        </v-dialog>
    </div>
</template>

<script setup>
import { reactive, ref, computed, watch , onMounted  } from "vue";
import { useExpenseRepository } from "./../../store/ExpensesRepository";
 
 const ExpensesRepository = useExpenseRepository();

const formData = reactive({
    name: "",
    unit: "",
});

const formRef = ref(null);

const rules = {
    required: (value) => !!value || "This field is required",
    // name: (value) => /^[a-zA-Z\u0600-\u06FF\s]*$/.test(value) || "Name is not correct",
};

// Computed property to handle the dialog visibility
const isDialogActive = computed({
    get() {
        return ExpensesRepository.createDialog || ExpensesRepository.updateDialog;
    },
    set(value) {
        ExpensesRepository.createDialog = value;
        ExpensesRepository.updateDialog = value;
    },
});

// Watcher to fill form data when editing
// watch(() => ExpensesRepository.updateDialog, (isEditing) => {
//     if (isEditing) {
//         formData.name = ExpensesRepository.saleProduct?.product_name || '';
//         formData.unit = ExpensesRepository.saleProduct?.unit || '';
//     } else {
//         // Reset form when creating a new product
//         formData.name = "";
//         formData.unit = "";
//     }
// });

onMounted(() => {
    if(ExpensesRepository.updateDialog){
        console.log(ExpensesRepository.expenseProduct)
        formData.id =ExpensesRepository.expenseProduct?.id || '';
        formData.name =ExpensesRepository.expenseProduct?.name || '';
        formData.unit = ExpensesRepository.expenseProduct?.unit || '';
    }
    else { 
        formData.id = "";
        formData.name = "";
        formData.unit = "";
    }
});

// Function to close the dialog
const closeDialog = () => {
    ExpensesRepository.createDialog = false;
    ExpensesRepository.updateDialog = false;
};

// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (ExpensesRepository.updateDialog) {
            // Call the update method
            ExpensesRepository.UpdateExpenseProduct(ExpensesRepository.expenseProduct.id,formData);
        } else {
            // Call the create method
            ExpensesRepository.CreateExpenseProduct(formData);
        }
        closeDialog();
    }
};
</script>

<style scoped>

</style>
