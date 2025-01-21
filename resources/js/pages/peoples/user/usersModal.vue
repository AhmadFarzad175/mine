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
                            {{
                                PeopleRepository.updateDialog
                                    ? "Edit User"
                                    : "Create User"
                            }}
                        </p>
                        <v-btn class="px-2" variant="text" @click="closeDialog">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-divider class="mx-6"></v-divider>
                    <v-card-text>
                        <v-form ref="formRef">
                            <v-row class="pb-4">
                                <v-col cols="10">
                                    <v-text-field
                                        v-model="formData.name"
                                        :counter="10"
                                        label=" Full Name  * "
                                        variant="outlined"
                                        density="compact"
                                        class="mb-3 pr-6"
                                        :rules="[rules.required, rules.name]"
                                    ></v-text-field>
                                    <v-text-field
                                        v-model="formData.phone"
                                        :counter="10"
                                        label=" Mobile No "
                                        variant="outlined"
                                        density="compact"
                                        class="pr-6"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="2">
                                    <div class="image-upload-container">
                                        <v-file-input
                                            type="file"
                                            ref="inputRef"
                                            style="display: none"
                                            @change="onChangeImage"
                                        ></v-file-input>

                                        <img
                                            :src="imageSrc"
                                            class="image-preview"
                                            v-show="imageSrc !== null"
                                        />

                                        <div class="image-overlay">
                                            <button
                                                v-if="!imageSrc"
                                                type="button"
                                                @click="OpenWindow(inputRef)"
                                                class="overlay-button"
                                            >
                                                <v-icon
                                                    size="x-large"
                                                    color="blue-grey-lighten-2"
                                                    >mdi-camera</v-icon
                                                >
                                            </button>
                                            <button
                                                v-if="imageSrc"
                                                type="button"
                                                @click="CloseWindow()"
                                                class="close-button"
                                            >
                                                <v-icon size="small"
                                                    >mdi-close</v-icon
                                                >
                                            </button>
                                            <button
                                                v-if="imageSrc"
                                                type="button"
                                                @click="OpenWindow(inputRef)"
                                                class="edit-button"
                                            >
                                                <v-icon size="small"
                                                    >mdi-pencil</v-icon
                                                >
                                            </button>
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>
                            <div class="d-flex w-100 pb-4">
                                <div class="w-50">
                                    <v-text-field
                                        type="email"
                                        v-model="formData.email"
                                        label=" Email *"
                                        variant="outlined"
                                        density="compact"
                                        class="pr-5"
                                        :rules="[rules.required, rules.email]"
                                    ></v-text-field>
                                </div>
                                <div class="w-50">
                                    <v-text-field
                                        counter="8"
                                        type="password"
                                        v-model="formData.password"
                                        label=" Password *"
                                        variant="outlined"
                                        density="compact"
                                        class="pl-2"
                                        :rules="[
                                            rules.required,
                                            rules.password,
                                        ]"
                                    ></v-text-field>
                                </div>
                            </div>

                            <div class="d-flex w-100">
                                <div class="w-50">
                                    <v-autocomplete
                                        v-model="formData.role"
                                        :items="PeopleRepository.roles"
                                        style="width: 100%"
                                        label=" Role  *"
                                        variant="outlined"
                                        density="compact"
                                        item-value="id"
                                        item-title="name"
                                        :rules="[rules.required]"
                                        class="pr-6"
                                    ></v-autocomplete>
                                </div>
                                <div class="w-50 h-100">
                                    <div class="w-100 h-75 d-flex">
                                        <div class="w-100 switch">
                                            <v-switch
                                                v-model="formData.status"
                                                :true-value="true"
                                                :false-value="false"
                                                style="height: 2.5rem"
                                                class="pr-4 pb-2"
                                                :color="
                                                    formData.status
                                                        ? 'primary'
                                                        : 'grey'
                                                "
                                                :label="
                                                    formData.status
                                                        ? 'Active'
                                                        : 'Inactive'
                                                "
                                                hide-details
                                                density="compact"
                                                inset
                                            ></v-switch>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
let imageSrc = ref(null);
const inputRef = ref(null);

const onChangeImage = (e) => {
    if (e.target.files.length) {
        const file = e.target.files[0];
        imageSrc.value = URL.createObjectURL(file); // Create a URL for the selected file
        formData.image = file; // Store the file in formData
    }
};

const OpenWindow = (action) => {
    if (action) {
        ref(action).value.click();
    }
};
const CloseWindow = () => {
    imageSrc.value = null; // Reset the image preview
    formData.image = null;
};

const formData = reactive({
    image: "",
    name: "",
    phone: "",
    email: "",
    password: "",
    role: "",
    status: false,
});

const formRef = ref(null);

const rules = {
    required: (value) => !!value || "This Field is required",
};

// Computed property for dialog visibility
const isDialogActive = computed({
    get() {
        return PeopleRepository.createDialog || PeopleRepository.updateDialog;
    },
    set(value) {
        PeopleRepository.createDialog = value;
        PeopleRepository.updateDialog = value;
    },
});

// Function to close the dialog
const closeDialog = () => {
    PeopleRepository.createDialog = false;
    PeopleRepository.updateDialog = false;

    imageSrc.value = null;
};

// Initialize form data

onMounted(() => {
    PeopleRepository.fetchRoles().then(() => {
        console.log(PeopleRepository.user);

        if (PeopleRepository.updateDialog) {
            // If we are editing an existing User, fill the form with the existing data
            formData.id = PeopleRepository.user?.id || "";
            formData.image = PeopleRepository.user.image || "";
            formData.name = PeopleRepository.user?.name || "";
            // formData.owner_id = PeopleRepository.ownerPickup?.category.id || '';
            formData.email = PeopleRepository.user?.email || "";
            formData.phone = PeopleRepository.user?.phone || "";
            formData.password = "";
            formData.role = PeopleRepository.user.role;
            formData.status = PeopleRepository.user.status;

            if (PeopleRepository.updateDialog) {
                // ... existing code
                imageSrc.value = PeopleRepository.user.image || ""; // Set the image source
            } else {
                imageSrc.value = null; // Reset for new entries
            }
        } else {
            // If we are creating a new User, reset the form

            formData.image = "";
            formData.name = "";
            formData.phone = "";
            formData.email = "";
            formData.password = "";
            formData.role = "";
            formData.status = "";
        }
    });
});

// Function to handle form submission
const submitForm = async () => {
    const isValid = await formRef.value.validate();
    if (isValid) {
        if (PeopleRepository.updateDialog) {
            PeopleRepository.UpdateUser(formData.id, formData);
        } else {
            PeopleRepository.CreateUser(formData);
        }
        closeDialog();
    }
};
</script>

<style scoped>
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
