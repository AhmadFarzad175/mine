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
                            {{ PeopleRepository.updateDialog ? 'Edit Stakeholder' : 'Create Stakeholder' }}&nbsp;
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
                                    label=" * Stakeholder Name"
                                    style="width: 100%"
                                    class="pb-4"
                                    :return-object="false"
                                    :rules="[rules.required, rules.name]"
                                    density="compact"
                                ></v-text-field>

                                <v-text-field
                                    v-model="formData.phone"
                                    variant="outlined"
                                    label=" Stakeholder Mobile No"
                                    style="width: 100%"
                                    class="pb-4"
                                    :return-object="false"
                                    density="compact"
                                ></v-text-field>


                                <v-text-field
                                    v-model="formData.address"
                                    variant="outlined"
                                    label="Stakeholder Address"
                                    style="width: 100%"
                                    class="pb-4"
                                    :return-object="false"
                                    density="compact"
                                ></v-text-field>

                             
                            </div>
                        </v-form>
                    </v-card-text>
                    <div class="d-flex mb-6 mx-6">
                        <v-btn color="#112F53" @click="submitForm">
                            &nbsp; &nbsp; {{ PeopleRepository.updateDialog ? 'Update' : 'Create' }} &nbsp; &nbsp;
                        </v-btn>
                    </div>
                </v-card>
            </template>
        </v-dialog>
    </div>
</template>

<script setup>
import { reactive, ref, computed, watch , onMounted  } from "vue";
import { usePeopleRepository } from "../../../store/PeoplesRepository";

 
 const PeopleRepository = usePeopleRepository();

const formData = reactive({
    name: "",
    phone: "",
    address: "",
});

const formRef = ref(null);

const rules = {
    required: (value) => !!value || "This field is required",
    name: (value) => /^[a-zA-Z\u0600-\u06FF\s]*$/.test(value) || "Name is not correct",
};

// Computed property to handle the dialog visibility
const isDialogActive = computed({
    get() {
        return PeopleRepository.createDialog || PeopleRepository.updateDialog;
    },
    set(value) {
        PeopleRepository.createDialog = value;
        PeopleRepository.updateDialog = value;
    },
});

// Watcher to fill form data when editing
// watch(() => PeopleRepository.updateDialog, (isEditing) => {
//     if (isEditing) {
//         formData.name = PeopleRepository.saleProduct?.product_name || '';
//         formData.details = PeopleRepository.saleProduct?.details || '';
//     } else {
//         // Reset form when creating a new product
//         formData.name = "";
//         formData.details = "";
//     }
// });

onMounted(() => {
    if(PeopleRepository.updateDialog){
        formData.id =PeopleRepository.stakeholder?.id || '';
        formData.name =PeopleRepository.stakeholder?.name || '';
        formData.phone = PeopleRepository.stakeholder?.phone || '';
        formData.address = PeopleRepository.stakeholder?.address || '';
    }
    else { 

        formData.id = "";
formData.name = "";
formData.phone = "";
formData.address = "";
        
        
        
    }
});

// Function to close the dialog
const closeDialog = () => {
    PeopleRepository.createDialog = false;
    PeopleRepository.updateDialog = false;
};

// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (PeopleRepository.updateDialog) {
            // Call the update method
            PeopleRepository.UpdateCustomer(formData.id,formData);
        } else {
            // Call the create method
            PeopleRepository.CreateCustomer(formData);
        }
        closeDialog();
    }
};
</script>

<style scoped>

</style>
