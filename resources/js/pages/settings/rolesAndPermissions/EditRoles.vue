<template>
    <div class="rounded-xl">
      <div class="rounded-xl bg-white">
        <v-form ref="formRef">
  <!-- Permissions Form -->
  <div>
    <v-row class="py-3">
      <v-col>
        <v-text-field
          v-model="formData.role"
          label="Role Name*"
          density="compact"
          variant="outlined"
          :rules="[rules.required, rules.name]"
        ></v-text-field>
      </v-col>
      <v-col>
        <v-text-field
          v-model="formData.description"
          label="Note *"
          density="compact"
          variant="outlined"
          :rules="[rules.required]"
        ></v-text-field>
      </v-col>
    </v-row>

    <!-- Permission Checkboxes with Icons -->
    <v-row>
      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-view-dashboard</v-icon>Dashboard
          </v-card-title>
          <v-card-text>
            <v-checkbox
              v-model="formData.permissions"
              label="View"
              value="dashboard_view"
            ></v-checkbox>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-cart</v-icon>Sales
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewSale"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createSale"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editSale"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteSale"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-package-variant</v-icon>Sales Product
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewSaleProduct"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createSaleProduct"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editSaleProduct"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteSaleProduct"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-currency-usd</v-icon>Billable Expense
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewBillexpense"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createBillexpense"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editBillexpense"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteBillexpense"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-currency-usd-off</v-icon>None Billable Expense
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewNoneBillExpense"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createNoneBillExpense"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editNoneBillExpense"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteNoneBillExpense"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- Expense Product Section -->
      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-package-variant-closed</v-icon>Expense Product
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewExpenseProduct"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createExpenseProduct"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editExpenseProduct"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteExpenseProduct"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-tag</v-icon>Expense Category
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewExpenseCategory"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createExpenseCategory"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editExpenseCategory"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteExpenseCategory"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-account</v-icon>Customer
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewCustomers"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createCustomers"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="updateCustomers"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteCustomers"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-truck</v-icon>Supplier
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewSuppliers"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createSuppliers"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editSuppliers"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteSuppliers"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-home</v-icon>Owner
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewOwner"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createOwner"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editOwner"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteOwner"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-truck-delivery</v-icon>Owner Pickup
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewOwnerPickup"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createOwnerPickup"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editOwnerPickup"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteOwnerPickup"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-account-circle</v-icon>User
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="View"
                  value="viewUser"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Create"
                  value="createUser"
                ></v-checkbox>
              </v-col>
              <v-col>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Edit"
                  value="editUser"
                ></v-checkbox>
                <v-checkbox
                  v-model="formData.permissions"
                  label="Delete"
                  value="deleteUser"
                ></v-checkbox>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-file-chart</v-icon>Reports
          </v-card-title>
          <v-card-text>
            <v-checkbox
              v-model="formData.permissions"
              label="Profit and Loss Report"
              value="viewProfitLossReport"
            ></v-checkbox>
            <v-checkbox
              v-model="formData.permissions"
              label="Customer Report"
              value="viewCustomerReport"
            ></v-checkbox>
            <v-checkbox
              v-model="formData.permissions"
              label="Supplier Report"
              value="viewSupplierReport"
            ></v-checkbox>
            <v-checkbox
              v-model="formData.permissions"
              label="Expense Report"
              value="viewExpenseReport"
            ></v-checkbox>
            <v-checkbox
              v-model="formData.permissions"
              label="Expense Product Report"
              value="viewExpenseProductReport"
            ></v-checkbox>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-lock</v-icon>Roles
          </v-card-title>
          <v-card-text>
            <v-checkbox
              v-model="formData.permissions"
              label="View"
              value="viewRoles"
            ></v-checkbox>
            <v-checkbox
              v-model="formData.permissions"
              label="Create"
              value="createRoles"
            ></v-checkbox>
            <v-checkbox
              v-model="formData.permissions"
              label="Edit"
              value="editRoles"
            ></v-checkbox>
            <v-checkbox
              v-model="formData.permissions"
              label="Delete"
              value="deleteRoles"
            ></v-checkbox>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="4">
        <v-card class="equal-height">
          <v-card-title class="module-title">
            <v-icon class="mr-2">mdi-settings</v-icon>Settings
          </v-card-title>
          <v-card-text>
            <v-checkbox
              v-model="formData.permissions"
              label="View"
              value="viewSystemSettings"
            ></v-checkbox>
          </v-card-text>
        </v-card>
      </v-col>

      
    </v-row>
  </div>
</v-form>


  
        <div class="d-flex mb-6 mx-6 mt-4">
          <v-btn color="#112F53" @click="createRole">
            &nbsp; &nbsp; Save &nbsp; &nbsp;
          </v-btn>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useSettingRepository } from "../../../store/SettingsRepository";
  
import { reactive, ref, watch } from "vue";
import { useRoute } from "vue-router";
const SettingRepository = useSettingRepository();

const routeParams = useRoute();

let formData = reactive({
    id: null,
    role: "",
    description: "",
    permissions: [],
    name: "",
});

SettingRepository.fetchRolePermission(routeParams.params.id).then((res) => {
    // Update the properties of formData inside the then callback
    formData.id = SettingRepository.permission.id;
    formData.role = SettingRepository.permission.name;
    formData.description = SettingRepository.permission.description;
    formData.permissions = SettingRepository.permission.permissions;
    formData.name = SettingRepository.permission.name;
});
const formRef = ref(null);

const rules = {
    required: (v) => !!v || "Field is required",
    name: (v) => {
        if (!v || v.trim() === "") return "Name is required"; // Check for empty or whitespace-only name
        if (v.length > 10) return "Name must be less than 10 characters";
    },
};
const createRole = async () => {
    if (formRef.value) {
        const isValid = await formRef.value.validate();
        if (isValid) {
            SettingRepository.UpdateRolePermission(formData.id, formData);
        }
    } else {
        console.error("Form reference is null");
    }
};
  </script>





<style scoped>
.module-title {
  background-color: #112f53; /* Change this to your desired background color */
  color: white; /* White text for contrast */
  padding: 10px;
  border-radius: 5px 5px 0 0; /* Rounded corners for the top */
  text-align: center; /* Center the title text */
}

.equal-height {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
  border-radius: 8px; /* Rounded corners for the card */
}

.card {
  margin: 20px;
  padding: 20px;
}
.all-expense {
  width: 83.2%;
  background-color: #f8f8f8;
}
.headline {
  background-color: #ecf1f4;
}

.equal-height:hover {
  transform: scale(1.03);
}
/* background-color: #e0f7fa; Change color on hover */

.permission-checkbox {
  transition: background-color 0.2s ease;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>