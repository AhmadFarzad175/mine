<template>
    <div class="rounded-xl m-4">
        <div class="card rounded-xl bg-white">
            <!-- <Menu mainTitle="تنضیمات" subTitle="  تنضیمات سیستم" /> -->
         

            <div class="overflow-x-hidden pt-6" >
                <v-app>
                    <div class="main border rounded-xl">

                        
                        <h1 class="px-12 py-4">System Setting</h1>
                        
                        <v-divider
                        :thickness="1"
                        class="border-opacity-100"
                        color="#b3bcc7"
                        ></v-divider>
                        <div class="px-12 py-6">
                        
                        <form
                            @submit.prevent="CreateComponySetting"
                            class=""
                        >
                            <v-row>
                                  <v-col cols="10">
                                    <v-text-field
                                        v-model="
                                            SettingRepository.companies.company_name
                                        "
                                        :rules="[rules.required, rules.company_name]"
                                        :counter="10"
                                        label="Company Name *  "
                                        variant="outlined"
                                        density="compact"
                                        class="mb-4"
                                    ></v-text-field>
                                    <v-text-field
                                        v-model="
                                            SettingRepository.companies.phone
                                        "
                                        :rules="[rules.required, rules.number]"
                                        :counter="10"
                                        label="Mobile Number *"
                                        variant="outlined"
                                        density="compact"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="2" class="pb-10">
                                    <button
                                        @click="triggerFileInput"
                                        class="file-input-button"
                                    >
                                        <v-icon
                                            v-if="
                                                !SettingRepository.companies
                                                    .logo_url
                                            "
                                            size="x-large"
                                            color="blue-grey-lighten-2"
                                        >
                                            mdi-camera
                                        </v-icon>
                                        <img
                                            v-else
                                            :src="
                                                SettingRepository.companies
                                                    .logo_url
                                            "
                                            alt="Selected Image"
                                            class="w-full h-full object-cover"
                                        />
                                    </button>
                                    <input
                                        type="file"
                                        ref="fileInput"
                                        accept="image/*"
                                        @change="handleFileChange"
                                        class="hidden"
                                    />
                                </v-col>

                              
                            </v-row>

                            <div class="d-flex">
                                <v-text-field
                                    v-model="
                                        SettingRepository.companies.address
                                    "
                                    :rules="[rules.required]"
                                    label="Address *"
                                    variant="outlined"
                                    density="compact"
                                    class="pr-3"
                                ></v-text-field>
                                <v-text-field
                                    v-model="SettingRepository.companies.email"
                                    :rules="[rules.required, rules.email]"
                                    label="Email * "
                                    variant="outlined"
                                    density="compact"
                                    type="email"
                                    class="pl-3"
                                ></v-text-field>
                            </div>

                            <div>
                                <v-btn
                                    class="me-4 mt-5"
                                    color="#112F53"
                                    type="submit"
                                    >submit</v-btn
                                >
                            </div>
                        </form>
                    </div>

                    </div>
                </v-app>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useSettingRepository } from "../../store/SettingsRepository";
const SettingRepository = useSettingRepository();
// import Menu from "@/components/UI/Menu.vue";

import { reactive , onMounted } from "vue";

let formData = {}; // Change to object instead of array

const handleFileChange = (event) => {
    const file = event.target.files[0];
    SettingRepository.companies.logo_url = URL.createObjectURL(file);
    SettingRepository.companies.logo = file;
    // localStorage.removeItem("imageData");
};
const triggerFileInput = () => {
    const fileInput = document.querySelector('input[type="file"]');
    fileInput.click();
};
const rules = {
    required: (value) => !!value || "This field is required",
    name: (value) =>
        /^[a-zA-Z\s\u0600-\u06FF]*$/.test(value) || "This name is not valid",
    email: (value) =>
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || "This Email is not valid",
    number: (value) => /^\d+$/.test(value) || "Number is not valid",
};

const CreateComponySetting = async () => {
    formData = reactive({
        id: "",
        company_name: SettingRepository.companies.company_name,
        phone: SettingRepository.companies.phone,
        email: SettingRepository.companies.email,
        address: SettingRepository.companies.address,
        imageUrl: SettingRepository.companies.logo_url,
        logo: SettingRepository.companies.logo,
    });

    await SettingRepository.CreateCompany(formData);
};

onMounted(() => {
    
    SettingRepository.fetchCompany();
})
</script>

<style scoped>
.file-input-button {
    display: block;
    width: 150px;
    height: 150px;
    border: 2px dashed #ccc;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    position: relative;
}

.file-input-button img {
    width: 100%;
    height: 100%;
    object-fit: cover;

    /* Make the image cover the entire area */
}

.file-input-button::before {
    content: ""; /* Hide the "Choose File" text */
    display: block;
    text-align: center;
    font-size: 16px;
    color: #666;
}

.file-input-button:hover {
    background-color: #f0f0f0; /* Change the background color on hover */
}

.hidden {
    display: none;
}

</style>
