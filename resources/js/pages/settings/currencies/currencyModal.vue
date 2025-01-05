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
                        <p class="font-weight-bold">&nbsp;
                            {{ SettingRepository.updateDialog ? 'Edit Currency' : 'Create Currency' }}&nbsp;
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
                                    label=" * Currency Name"
                                    style="width: 100%"
                                    class="pb-4"
                                    :return-object="false"
                                    :rules="[rules.required, rules.name]"
                                    density="compact"
                                ></v-text-field>

                                <v-text-field
                                    v-model="formData.code"
                                    variant="outlined"
                                    style="width: 100%"
                                    label="code"
                                    :return-object="false"
                                    class="pb-4 rtl-autocomplete"
                                    density="compact"
                                ></v-text-field>

                                <v-text-field
                                    v-model="formData.symbol"
                                    variant="outlined"
                                    style="width: 100%"
                                    label="symbol"
                                    :return-object="false"
                                    class="pb-4 rtl-autocomplete"
                                    density="compact"
                                ></v-text-field>
                            </div>
                        </v-form>
                    </v-card-text>
                    <div class="d-flex mb-6 mx-6">
                        <v-btn color="#112F53" @click="submitForm">
                            &nbsp; &nbsp; {{ SettingRepository.updateDialog ? 'Update' : 'Create' }} &nbsp; &nbsp;
                        </v-btn>
                    </div>
                </v-card>
            </template>
        </v-dialog>
    </div>
</template>

<script setup>
import { reactive, ref, computed, watch , onMounted  } from "vue";
import { useSettingRepository } from "../../../store/SettingsRepository";
const SettingRepository = useSettingRepository();

const formData = reactive({
    name: "",
    details: "",
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
    if(SettingRepository.updateDialog){
        formData.id =SettingRepository.Currency?.id || '';
        formData.name =SettingRepository.Currency?.name || '';
        formData.code = SettingRepository.Currency?.code || '';
        formData.symbol = SettingRepository.Currency?.symbol || '';
    }
    else { 
        formData.id = "";
        formData.name = "";
        formData.code = "";
        formData.symbol = "";
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
            SettingRepository.UpdateCurrency(formData.id,formData);
        } else {
            // Call the create method
            SettingRepository.CreateCurrency(formData);
        }
        closeDialog();
    }
};
</script>

<style scoped>

</style>
