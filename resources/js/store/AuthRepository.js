import { defineStore } from "pinia";
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import { axios } from "../axios";

export const useAuthRepository = defineStore("AuthRepository", {
  state: () => ({
    user: null, // User data
    permissions: ref([]), // Ref array for reactivity
    role: null, // User role
    isLoading: false, // Loading state
    error: null, // Error messages
    isLoggedIn: false, // Login state
    router: useRouter(), // Vue Router instance
    rail: false, // Sidebar toggle
    menubar: reactive({
      menuSale: ref([
        {
          id: 1,
          title: "Create Sale",
          icon: "mdi-circle-small",
          route: "/createSales",
          permission: "createSale",
        },
        {
          id: 2,
          title: "All Sales",
          icon: "mdi-circle-small",
          route: "/sales",
          permission: "viewSales",
        },
        {
          id: 3,
          title: "Products",
          icon: "mdi-circle-small",
          route: "/saleProduct",
          permission: "viewSaleProducts",
        },
      ]),
    
      menuExpense: ref([
        {
          id: 1,
          title: "Billable Expense",
          icon: "mdi-circle-small",
          route: "/billableExpense",
          permission: "viewBillexpenses",
        },
        {
          id: 2,
          title: "Expense Product",
          icon: "mdi-circle-small",
          route: "/expenseProduct",
          permission: "viewExpenseProducts",
        },
        {
          id: 3,
          title: "None Billable Expense",
          icon: "mdi-circle-small",
          route: "noneBillableExpense",
          permission: "viewNoneBillExpenses",
        },
        {
          id: 4,
          title: "Categories",
          icon: "mdi-circle-small",
          route: "/categories",
          permission: "viewExpenseCategories",
        },
      ]),
    
      menuPeople: ref([
        {
          id: 1,
          title: "Customer",
          icon: "mdi-circle-small",
          route: "/customers",
          permission: "viewCustomerses",
        },
        {
          id: 2,
          title: "Supplier",
          icon: "mdi-circle-small",
          route: "/suppliers",
          permission: "viewSupplierses",
        },
        {
          id: 3,
          title: "Users",
          icon: "mdi-circle-small",
          route: "/users",
          permission: "viewUsers",
        },
        {
          id: 4,
          title: "Owner",
          icon: "mdi-circle-small",
          route: "/owners",
          permission: "viewOwners",
        },
        {
          id: 5,
          title: "Loan",
          icon: "mdi-circle-small",
          route: "/loans",
          permission: "loanPeopleViews",
        },
      ]),
    
      menuReport: ref([
        {
          id: 1,
          title: "Profit And Loss",
          icon: "mdi-circle-small",
          route: "/ProfitAndLoss",
          permission: "viewProfitLossReport",
        },
        {
          id: 2,
          title: "Customer Report",
          icon: "mdi-circle-small",
          route: "/customerReport",
          permission: "viewCustomerReports",
        },
        {
          id: 3,
          title: "Supplier Report",
          icon: "mdi-circle-small",
          route: "/suppliersreport",
          permission: "viewSupplierReports",
        },
        {
          id: 4,
          title: "Expense Category",
          icon: "mdi-circle-small",
          route: "/expenseCategoryReport",
          permission: "viewExpenseCategoryReport",
        },
        {
          id: 5,
          title: "Product Expense",
          icon: "mdi-circle-small",
          route: "/ExpenseProductReport",
          permission: "viewExpenseProductReport",
        },
        {
          id: 6,
          title: "Owner Pickup",
          icon: "mdi-circle-small",
          route: "/OwnerPickupReport",
          permission: "viewOwnerPickupReport",
        },
      ]),
    
      menuSetting: ref([
        {
          id: 1,
          title: "System Setting",
          icon: "mdi-circle-small",
          route: "/systemSetting",
          permission: "viewSystemSettings",
        },
        {
          id: 2,
          title: "Roles",
          icon: "mdi-circle-small",
          route: "/roles",
          permission: "viewRoleses",
        },
        {
          id: 3,
          title: "Currency",
          icon: "mdi-circle-small",
          route: "/currency",
          permission: "viewCurrencies",
        },
      ]),
    }),
    // Menubar reactive state
  }),

  actions: {
    // Toggle sidebar rail
    toggleRail() {
      this.rail = !this.rail;
    },

    // User login action
    async Login(formData) {
      this.error = null;
      this.isLoading = true; // Start loading

      try {
        const config = {
          method: "POST",
          url: "/login",
          data: formData,
        };

        const response = await axios(config);

        // Set state data
        this.user = response.data.user;
        this.permissions = response.data.permissions;
        this.isLoggedIn = true;

        // Store data in sessionStorage
        sessionStorage.setItem("token", response.data.token);
        sessionStorage.setItem("user", JSON.stringify(response.data.user));
        sessionStorage.setItem("permissions", JSON.stringify(response.data.permissions));

        // Set Axios default Authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${response.data.token}`;

        // Navigate to dashboard
        this.router.push("/dashboard");
      } catch (err) {
        this.error = err.response ? err.response.data.message : "An error occurred!";
      } finally {
        this.isLoading = false; // Stop loading
      }
    },

    // User logout action
    async Logout() {
      this.error = null;
      const token = sessionStorage.getItem("token");

      if (!token) {
        this.clearSession();
        return;
      }

      try {
        const config = {
          method: "POST",
          url: "/logout",
          data: { token },
        };

        await axios(config);
        this.clearSession();
      } catch (err) {
        this.error = err.response ? err.response.data.message : "An error occurred!";
        this.clearSession(); // Clear session even if the logout request fails
      }
    },

    // Clear session data
    clearSession() {
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("permissions");
      sessionStorage.removeItem("user");

      this.user = null;
      this.permissions = [];
      this.isLoggedIn = false;

      setTimeout(() => {
        this.router.push("/");
      }, 1000);
    },

    // Initialize state from sessionStorage
    initialize() {
      // Permissions
      const storedPermissions = sessionStorage.getItem("permissions");
      console.log(storedPermissions);
      this.permissions = storedPermissions ? JSON.parse(storedPermissions) : [];
      console.log(this.permissions , 'authRepository')
      // User data
      const storedUser = sessionStorage.getItem("user");
      this.user = storedUser ? JSON.parse(storedUser) : null;

      // Login state
      const token = sessionStorage.getItem("token");
      this.isLoggedIn = !!token;

      // Set Axios default Authorization header
      if (token) {
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      }
    },
  },

  getters: {
    // Check if the user has a specific permission
    hasPermission: (state) => (permission) => {
      return state.permissions.includes(permission);
    },
  },
});
