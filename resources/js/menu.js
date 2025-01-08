import { ref } from "vue";

export const mainMenu = {
    menuExtract: ref([
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
            permission: "viewSale",
        },
        {
            id: 3,
            title: "Products",
            icon: "mdi-circle-small",
            route: "/saleProduct",
            permission: "viewSaleProduct",
        },
    ]),
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
            permission: "viewSale",
        },
        // {
        //     id: 3,
        //     title: "Products",
        //     icon: "mdi-circle-small",
        //     route: "/saleProduct",
        //     permission: "viewSaleProduct",
        // },
    ]),

    menuExpense: ref([
        {
            id: 1,
            title: "Billable Expense",
            icon: "mdi-circle-small",
            route: "/billableExpense",
            permission: "viewbillexpense",
        },
        {
            id: 2,
            title: "Expense Product",
            icon: "mdi-circle-small",
            route: "/expenseProduct",
            permission: "viewExpenseProduct",
        },
        {
            id: 3,
            title: "None Billable Expense",
            icon: "mdi-circle-small",
            route: "noneBillableExpense",
            permission: "viewExpenses",
        },
        {
            id: 4,
            title: "categories",
            icon: "mdi-circle-small",
            route: "/categories",
            permission: "viewexpense_category",
        },
    ]),

    menuPeople: ref([
        {
            id: 1,
            title: "Stakeholders",
            icon: "mdi-circle-small",
            route: "/stakeholders",
            permission: "viewStakeholders",
        },
        {
            id: 2,
            title: "Users",
            icon: "mdi-circle-small",
            route: "/users",
            permission: "viewUser",
        },
        {
            id: 3,
            title: "Owner",
            icon: "mdi-circle-small",
            route: "/owners",
            permission: "viewOwner",
        },
        // {
        //     id: 5,
        //     title: "Loan",
        //     icon: "mdi-circle-small",
        //     route: "/loans",
        //     permission: "loanPeopleView",
        // },
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
            permission: "viewCustomerReport",
        },
        {
            id: 3,
            title: "Supplier Report",
            icon: "mdi-circle-small",
            route: "/suppliersreport",
            permission: "viewSupplierReport",
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
            title: "roles",
            icon: "mdi-circle-small",
            route: "/roles",
            permission: "viewRoles",
        },
        {
            id: 3,
            title: "currency",
            icon: "mdi-circle-small",
            route: "/currency",
            permission: "viewCurrency",
        },
    ]),
};

// Now you can access each menu section individually
console.log(mainMenu.menuExtract.value); // Access the menuSale section
// console.log(mainMenu.menuSale.value); // Access the menuSale section
// console.log(mainMenu.menuExpense.value); // Access the menuExpense section
// console.log(mainMenu.menuPeople.value); // Access the menuPeople section
// console.log(mainMenu.menuReport.value); // Access the menuReport section
// console.log(mainMenu.menuSetting.value); // Access the menuSetting section
