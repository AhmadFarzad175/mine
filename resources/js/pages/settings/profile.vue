<template>
    <div class="rounded-xl m-4">
      <div class="card rounded-xl bg-white">
        <div class="overflow-x-hidden pt-6">
          <v-app>
            <div class="main border rounded-xl">
              <h1 class="px-12 py-4">User Profile</h1>
  
              <v-divider :thickness="1" class="border-opacity-100" color="#b3bcc7"></v-divider>
  
              <div class="px-12 py-6">
                <form @submit.prevent="submitProfile">
                  <v-row>
                    <v-col cols="10">
                      <v-text-field
                        v-model="formData.name"
                        :rules="[rules.required, rules.name]"
                        label="Full Name *"
                        variant="outlined"
                        density="compact"
                        class="mb-4"
                      ></v-text-field>
                      <v-text-field
                        v-model="formData.phone"
                        :rules="[rules.required, rules.number]"
                        label="Mobile Number *"
                        variant="outlined"
                        density="compact"
                        class="mb-4"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="2">
                      <button @click="triggerFileInput" class="file-input-button">
                        <v-icon
                          v-if="!imageSrc"
                          size="x-large"
                          color="blue-grey-lighten-2"
                        >
                          mdi-camera
                        </v-icon>
                        <img
                          v-else
                          :src="imageSrc"
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
  
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        v-model="formData.email"
                        :rules="[rules.required, rules.email]"
                        label="Email *"
                        variant="outlined"
                        density="compact"
                        type="email"
                        class="mb-4"
                      ></v-text-field>
                      <v-text-field
                        v-model="formData.password"
                        :rules="[rules.required, rules.password]"
                        label="New Password *"
                        variant="outlined"
                        density="compact"
                        type="password"
                        class="mb-4"
                      ></v-text-field>
                    </v-col>
                  </v-row>
  
                  <div>
                    <v-btn class="me-4 mt-5" color="#112F53" type="submit">Save</v-btn>
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
  import { reactive, ref, onMounted } from "vue";
  import { usePeopleRepository } from "../../store/PeoplesRepository";
  const PeopleRepository = usePeopleRepository();
  const formData = reactive({
    id: null,
    name: "",
    image: null,
    phone: "",
    email: "",
    password: "",
  });
  
  let imageSrc = ref(null);
  const fileInput = ref(null);
  
  const rules = {
    required: (value) => !!value || "This field is required",
    name: (value) => /^[a-zA-Z\s]*$/.test(value) || "This name is not valid",
    password: (value) =>
      value.length >= 3 || "Password must be at least 8 characters long",
  };
  
  const triggerFileInput = () => {
    fileInput.value.click();
  };
  
  const handleFileChange = (event) => {
    if (event.target.files.length) {
        const file = event.target.files[0];
        imageSrc.value = URL.createObjectURL(file); // Create a URL for the selected file
        formData.image = file; // Store the file in formData
    }
  };
  
  const submitProfile = async () => {
    // Save the updated data back to sessionStorage
    sessionStorage.setItem("user", JSON.stringify(formData));
    PeopleRepository.UpdateUser(formData.id, formData)
  };
  
  onMounted(() => {
    // Fetch existing profile data from sessionStorage
    const storedData = sessionStorage.getItem("user");
    const parsedData = JSON.parse(storedData);

    PeopleRepository.fetchUser(parsedData.id).then(()=>{

      // if (parsedData) {
        formData.id = PeopleRepository.user.id || null;
        formData.name = PeopleRepository.user.name || "";
        formData.phone = PeopleRepository.user.phone || "";
        formData.email = PeopleRepository.user.email || "";
        imageSrc.value = PeopleRepository.user.image || null;
      // }
    });

  });
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
  }
  
  .hidden {
    display: none;
  }
  
  .v-btn {
    color: white !important;
  }
  </style>
  