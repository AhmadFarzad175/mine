<script setup>
// import { useExpenseRepository } from "@/store/ExpenseRepository";
import { reactive, ref } from "vue";
let ExpenseRepository = useExpenseRepository();
const formRef = ref(null);
const formData = reactive({
    name: "",
    details: "",
});

const createCategory = async () => {
    formRef.value.validate().then((validate) => {
        if (validate.valid) {
            ExpenseRepository.CreateExpense(formData);
        }
    });
};

const rules = {
    required: (value) => !!value || "Category Name is Required.",
    counter: (value) => value.length >= 3 || "Min 3 characters",
};
</script>
<template>
    <v-dialog
        transition="dialog-top-transition"
        width="600px"
        v-model="ExpenseRepository.createDailog"
    >
        <template v-slot:default="{ isActive }">
            <v-card class="px-3">
                <v-card-title class="px-6 py-4 d-flex justify-space-between">
                    <h2>Create</h2>

                    <v-btn variant="text" @click="isActive.value = false"
                        ><v-icon> mdi-close </v-icon></v-btn
                    >
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-form ref="formRef">
                        <v-text-field
                            v-model="formData.name"
                            variant="outlined"
                            density="compact"

                            label="Category Name *"
                            :rules="[rules.required, rules.counter]"
                        ></v-text-field>
                        <v-textarea
                            v-model="formData.details"
                            variant="outlined"
                            label="Description"
                            density="compact"

                        ></v-textarea>
                    </v-form>
                </v-card-text>
                <div class="justify-start pl-6 pb-6">
                    <v-btn color="light-blue-darken-1" @click="createCategory"
                        >Submit</v-btn
                    >
                </div>
            </v-card>
        </template>
    </v-dialog>
</template>
