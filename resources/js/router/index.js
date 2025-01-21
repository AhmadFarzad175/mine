import { createRouter, createWebHistory } from "vue-router";

// Import pages
import Dashboard from "../pages/dashboard/Dashboard.vue";
import CreateSales from "../pages/earning/CreateSales.vue";
import Sales from "../pages/earning/Sales.vue";
import UpdateSale from "../pages/earning/updateSale.vue";
import showSales from "../pages/earning/showSales.vue";
import saleProduct from "../pages/earning/saleProduct.vue";

import ShowBillableExpense from "../pages/expenses/ShowBillableExpense.vue";
import Categories from "../pages/expenses/Categories.vue";
import NoneBillableExpense from "../pages/expenses/NoneBillableExpense.vue";
import BillableExpense from "../pages/expenses/BillableExpense.vue";
import CreateBillableExpense from "../pages/expenses/CreateBillableExpense.vue";
import UpdateBillableExpense from "../pages/expenses/UpdateBillableExpense.vue";
import ExpenseProduct from "../pages/expenses/ExpenseProduct.vue";
import Supplier from "../pages/expenses/Supplier.vue";

import Owners from "../pages/peoples/owner/Owners.vue";
import OwnerPickup from "../pages/peoples/owner/OwnerPickup/OwnerPickup.vue";
import Stakeholders from "../pages/peoples/stakeholder/Stakeholders.vue";
import StakeholderProfile from "../pages/peoples/stakeholder/StakeholderProfile.vue";
import AccountProfile from "../pages/settings/moneyAccount/moneyAccountProfile.vue";

// import Customers from "../pages/peoples/customer/Customers.vue";
// import Partiners from "../pages/peoples/Partiners.vue";
import Suppliers from "../pages/peoples/supplier/Suppliers.vue";
import Users from "../pages/peoples/user/users.vue";

import CustomerReport from "../pages/reports/CustomerReport.vue";
import DetailsCustomerReport from "../pages/reports/DetailsCustomerReport.vue";
import SuppliersReport from "../pages/reports/SuppliersReport.vue";
import DetailsSupplierReport from "../pages/reports/DetailsSupplierReport.vue";
import ExpenseCategoryReport from "../pages/reports/ExpenseCategoryReport.vue";
import ExpenseProductReport from "../pages/reports/ExpenseProductReport.vue";
import OwnerPickupReport from "../pages/reports/OwnerPickupReport.vue";
import ProfitAndLoss from "../pages/reports/ProfitAndLoss.vue";

import Roles from "../pages/settings/rolesAndPermissions/Roles.vue";
import CreateRoles from "../pages/settings/rolesAndPermissions/CreateRoles.vue";
import EditRoles from "../pages/settings/rolesAndPermissions/EditRoles.vue";
import SystemSettings from "../pages/settings/SystemSettings.vue";

import loans from "../pages/peoples/loan/loans.vue";
import LoanPayment from "../pages/peoples/loan/LoanPeople/loanPayment.vue";

import Login from "./../Login.vue";
import currency from "./../pages/settings/currencies/currency.vue";

import moneyAccount from "./../pages/settings/moneyAccount/moneyAccount.vue";

const routes = [
    {
        path: "/",
        redirect: "/dashboard",
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
        meta: {
            layout: "DefaultLayout",
            requiresAuth: true,
            breadcrumb: [{ text: "Dashboard", to: "/dashboard" }],
            permissions: ["dashboard_view"],
        },
    },
    // Sales routes
    {
        path: "/createSales",
        name: "createSales",
        component: CreateSales,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Sales", to: "/sales" },
                { text: "Create Sale", to: null },
            ],

            permissions: ["createSale"],
        },
    },
    {
        path: "/sales",
        name: "sales",
        component: Sales,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Sales", to: "/sales" },
                { text: "All Sales", to: "/sales" },
            ],
            permissions: ["viewSales"],
        },
    },
    {
        path: "/updateSale/:id",
        name: "updateSale",
        props: true,
        component: UpdateSale,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Sales", to: "/sales" },
                { text: "Update Sale", to: null },
            ],
            permissions: ["editSale"],
        },
    },
    {
        path: "/showSales/:id",
        name: "showSales",
        props: true,
        component: showSales,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Sales", to: "/sales" },
                { text: "Show Sale", to: null },
            ],
            permissions: ["viewSale"],
        },
    },
    {
        path: "/saleProduct",
        name: "saleProduct",
        component: saleProduct,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Sales", to: "/sales" },
                { text: "Sale Product", to: null },
            ],
            permissions: ["viewSaleProducts"],
        },
    },
    // Expense routes
    {
        path: "/expenseProduct",
        name: "expenseProduct",
        component: ExpenseProduct,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Expenses", to: "/billableExpense" },
                { text: "Expense Product", to: null },
            ],
            permissions: ["viewExpenseProducts"],
        },
    },
    {
        path: "/categories",
        name: "categories",
        component: Categories,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Expenses", to: "/billableExpense" },
                { text: "Categories", to: null },
            ],
            permissions: ["viewExpenseCategories"],
        },
    },
    {
        path: "/noneBillableExpense",
        name: "noneBillableExpense",
        component: NoneBillableExpense,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Expenses", to: "/noneBillableExpense" },
                { text: "None-Billable Expense", to: null },
            ],
            permissions: ["viewNoneBillExpenses"],
        },
    },
    {
        path: "/billableExpense",
        name: "billableExpense",
        component: BillableExpense,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Expenses", to: "/billableExpense" },
                { text: "Billable Expenses", to: "/billableExpense" },
            ],
            permissions: ["viewBillexpenses"],
        },
    },
    {
        path: "/createBillableExpense",
        name: "createBillableExpense",
        component: CreateBillableExpense,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Billable Expenses", to: "/billableExpense" },
                { text: "Create Billable Expense", to: null },
            ],
            permissions: ["createBillexpense"],
        },
    },
    {
        path: "/updateBillableExpense/:id",
        name: "updateBillableExpense",
        props: true,
        component: UpdateBillableExpense,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Billable Expenses", to: "/billableExpense" },
                { text: "Update Billable Expense", to: null },
            ],
            permissions: ["editBillexpense"],
        },
    },
    {
        path: "/showBillableExpense/:id",
        name: "showBillableExpense",
        props: true,
        component: ShowBillableExpense,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Billable Expenses", to: "/billableExpense" },
                { text: "Show Billable Expense", to: null },
            ],
            permissions: ["viewBillexpense"],
        },
    },

    // People routes
    // {
    //   path: "/customers",
    //   name: "customers",
    //   component: Customers,
    //   meta: {
    //     requiresAuth: true,
    //     breadcrumb: [
    //       { text: "People", to: "/owners" },

    //       { text: "Customers", to: null }

    //     ],
    //     permissions: ['viewCustomerses']

    //   },
    // },
    {
        path: "/stakeholders",
        name: "stakeholders",
        component: Stakeholders,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "People", to: "/stakeholders" },

                { text: "Stakeholders", to: null },
            ],
            permissions: ["viewStakeholders"],
        },
    },

    {
        path: "/stakeholderProfile/:stakeId",
        name: "stakeholderProfile",
        component: StakeholderProfile,
        props: true, // Pass route params as props
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "People", to: "/stakeholders" },

                { text: "Stakeholders", to: null },
            ],
            permissions: ["viewStakeholders"],
        },
    },

    {
        path: "/suppliers",
        name: "suppliers",
        component: Suppliers,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "People", to: "/owners" },

                { text: "Suppliers", to: null },
            ],
            permissions: ["viewSupplierses"],
        },
    },

    {
        path: "/users",
        name: "users",
        component: Users,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "People", to: "/owners" },

                { text: "User", to: null },
            ],
            permissions: ["viewUsers"],
        },
    },

    {
        path: "/owners",
        name: "owners",
        component: Owners,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "People", to: "/owners" },

                { text: "Owners", to: null },
            ],
            permissions: ["viewOwners"],
        },
    },
    {
        path: "/owner/:id",
        name: "ownerPickup",
        props: true,
        component: OwnerPickup,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Owners", to: "/owners" },
                { text: "Owner Pickup", to: null },
            ],
            permissions: ["viewOwnerPickup"],
        },
    },
    {
        path: "/loans",
        name: "loans",
        component: loans,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "People", to: "/owners" },

                { text: "Loans", to: null },
            ],
            permissions: ["loanPeopleViews"],
        },
    },
    {
        path: "/loan/:id",
        name: "loanPayment",
        props: true,
        component: LoanPayment,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Loans", to: "/loans" },
                { text: "Loan Payment", to: null },
            ],
            permissions: ["loanPeopleView"],
        },
    },
    // Report routes
    {
        path: "/profitAndLoss",
        name: "profitAndLoss",
        component: ProfitAndLoss,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Reports", to: "/reports" },

                { text: "Profit and Loss", to: null },
            ],

            permissions: ["viewProfitLossReport"],
        },
    },
    {
        path: "/customerReport",
        name: "customerReport",
        component: CustomerReport,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Reports", to: "/reports" },
                { text: "Customer Report", to: null },
            ],
            permissions: ["viewCustomerReports"],
        },
    },
    {
        path: "/detailsCustomer/:id",
        name: "detailsCustomerReport",
        props: true,
        component: DetailsCustomerReport,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Reports", to: "/reports" },
                { text: "Customer Details", to: null },
            ],
            permissions: ["viewCustomerReport"],
        },
    },
    {
        path: "/detailsSupplier/:id",
        name: "detailsSupplierReport",
        props: true,
        component: DetailsSupplierReport,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Reports", to: "/reports" },
                { text: "Supplier Details", to: null },
            ],
            permissions: ["viewSupplierReport"],
        },
    },
    {
        path: "/suppliersReport",
        name: "suppliersReport",
        component: SuppliersReport,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Reports", to: "/reports" },
                { text: "Supplier Report", to: null },
            ],
            permissions: ["viewSupplierReports"],
        },
    },

    {
        path: "/expenseCategoryReport",
        name: "expenseCategoryReport",
        component: ExpenseCategoryReport,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Reports", to: "/reports" },
                { text: "Expense Category Report", to: null },
            ],
            permissions: ["viewExpenseCategoryReport"],
        },
    },

    {
        path: "/ExpenseProductReport",
        name: "ExpenseProductReport",
        component: ExpenseProductReport,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Reports", to: "/reports" },
                { text: "Expense Product Report", to: null },
            ],
            permissions: ["viewExpenseProductReport"],
        },
    },

    {
        path: "/OwnerPickupReport",
        name: "OwnerPickupReport",
        component: OwnerPickupReport,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Reports", to: "/reports" },
                { text: "Owner Pickup Report", to: null },
            ],
            permissions: ["viewOwnerPickupReport"],
        },
    },

    // Settings routes
    {
        path: "/roles",
        name: "roles",
        component: Roles,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Settings", to: "/systemSetting" },

                { text: "Roles", to: "/roles" },
            ],
            permissions: ["viewRoleses"],
        },
    },
    {
        path: "/createRoles",
        name: "createRoles",
        component: CreateRoles,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Roles", to: "/roles" },
                { text: "Create Role", to: null },
            ],
            permissions: ["createRoles"],
        },
    },
    {
        path: "/editRoles/:id",
        name: "editRoles",
        props: true,
        component: EditRoles,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Roles", to: "/roles" },
                { text: "Edit Role", to: null },
            ],
            permissions: ["editRoles"],
        },
    },

    {
        path: "/systemSetting",
        name: "systemSetting",
        component: SystemSettings,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Settings", to: "/systemSetting" },
                { text: "System Settings", to: null },
            ],
            permissions: ["viewSystemSettings"],
        },
    },
    {
        path: "/currency",
        name: "currency",
        component: currency,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Settings", to: "/systemSetting" },
                { text: "Currency", to: null },
            ],
            permissions: ["viewCurrency"],
        },
    },
    {
        path: "/moneyAccount",
        name: "moneyAccount",
        component: moneyAccount,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Settings", to: "/systemSetting" },
                { text: "MoneyAccount", to: null },
            ],
            permissions: ["viewMoneyAccount"],
        },
    },
    {
        path: "/moneyAccountProfile/:accountId",
        name: "moneyAccountProfile",
        props: true,
        component: AccountProfile,
        meta: {
            requiresAuth: true,
            breadcrumb: [
                { text: "Setting", to: "/moneyAccountProfile" },
                { text: "Money Account Profile", to: null },
            ],
            permissions: ["viewMoneyAccount"],
        },
    },

    // {
    //     path: "/moneyAccount/:accountId",
    //     name: "moneyAccount",
    //     component: AccountProfile,
    //     props: true, // Pass route params as props
    //     meta: {
    //         requiresAuth: true,
    //         breadcrumb: [
    //             { text: "People", to: "/moneyAccount" },
    //             { text: "Money Account", to: null },
    //         ],
    //         permissions: ["viewMoneyAccount"],
    //     },
    // },

    // Auth routes
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: {
            layout: "BlankLayout",
            breadcrumb: [{ text: "Login", to: null }],
        },
    },

    {
        path: "/profile",
        name: "profile",
        component: () => import("../pages/settings/profile.vue"), // Lazy loading the component
        meta: {
            requiresAuth: true,
            breadcrumb: [{ text: "Profile", to: null }],
        },
    },

    // Catch-all route for 404
    {
        path: "/:pathMatch(.*)*",
        redirect: "/dashboard",
        meta: {
            breadcrumb: [{ text: "Dashboard", to: "/dashboard" }],
        },
    },

    // Add remaining routes here in the same format...
];

// Create router
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Add navigation guard
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!sessionStorage.getItem("token"); // Check authentication

    if (
        to.matched.some((record) => record.meta.requiresAuth) &&
        !isAuthenticated
    ) {
        // Redirect to login with redirect query
        return next({ path: "/login", query: { redirect: to.fullPath } });
    }

    if (to.path === "/login" && isAuthenticated) {
        // Redirect logged-in users trying to access login page
        return next({ path: "/dashboard" });
    }

    next(); // Proceed to the requested route
});

export default router;
