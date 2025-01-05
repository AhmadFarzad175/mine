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
                            {{ EarningRepository.updateDialog ? 'Edit Product' : 'Create Product' }}&nbsp;
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
                                    v-model="formData.product_name"
                                    variant="outlined"
                                    style="width: 100%"
                                    :return-object="false"
                                    
                                    label=" * Product Name"
                                    class="pb-4 rtl-autocomplete"
                                    :rules="[rules.required]"
                                    density="compact"
                                ></v-text-field>

                                <v-text-field
                                    v-model="formData.unit"
                                    variant="outlined"
                                    style="width: 100%"
                                    :return-object="false"
                                    label=" * Unit"
                                    class="pb-4 rtl-autocomplete"
                                    :rules="[rules.required]"
                                    density="compact"
                                ></v-text-field>
                            </div>
                        </v-form>
                    </v-card-text>
                    <div class="d-flex mb-6 mx-6">
                        <v-btn color="#112F53" @click="submitForm">
                            &nbsp; &nbsp; {{ EarningRepository.updateDialog ? 'Update' : 'Create' }} &nbsp; &nbsp;
                        </v-btn>
                    </div>
                </v-card>
            </template>
        </v-dialog>
    </div>
</template>

<script setup>
import { reactive, ref, computed, watch , onMounted  } from "vue";
import { useEarningRepository } from "../../store/EarningRepository";

const EarningRepository = useEarningRepository();

const formData = reactive({
    product_name: "",
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
        return EarningRepository.createDialog || EarningRepository.updateDialog;
    },
    set(value) {
        EarningRepository.createDialog = value;
        EarningRepository.updateDialog = value;
    },
});

// Watcher to fill form data when editing
// watch(() => EarningRepository.updateDialog, (isEditing) => {
//     if (isEditing) {
//         formData.name = EarningRepository.saleProduct?.product_name || '';
//         formData.unit = EarningRepository.saleProduct?.unit || '';
//     } else {
//         // Reset form when creating a new product
//         formData.name = "";
//         formData.unit = "";
//     }
// });

onMounted(() => {
    if(EarningRepository.updateDialog){
        formData.id =EarningRepository.saleProduct?.id || '';
        formData.product_name =EarningRepository.saleProduct?.product_name || '';
        formData.unit = EarningRepository.saleProduct?.unit || '';
    }
    else { 
        formData.id = "";
        formData.product_name = "";
        formData.unit = "";
    }
});

// Function to close the dialog
const closeDialog = () => {
    EarningRepository.createDialog = false;
    EarningRepository.updateDialog = false;
};

// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (EarningRepository.updateDialog) {
            // Call the update method
            EarningRepository.UpdateSaleProduct(EarningRepository.saleProduct.id,formData);
        } else {
            // Call the create method
            EarningRepository.CreateSaleProduct(formData);
        }
        closeDialog();
    }
};
</script>

<style scoped>

</style>
