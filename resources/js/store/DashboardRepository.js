import { defineStore } from "pinia";
import { ref, reactive } from "vue";
import {axios} from "../axios"; // remove curly braces to import axios correctly

export let useDashboardRepository = defineStore("DashboardRepository", {
    state() {
        return {
            dashboards: reactive({}),
            totalExpenses: ref(0),
            expenses: reactive([]),
            dialog: false,
            isLoading: false,
            error: null,
            loading: true,
            itemsPerPage: 10,
            page: 1,
            showSelect: true,
            totalItems: 0,
            itemKey: "id",
            visaId: reactive([]),
            symbol: ref(null),
            search: "",
            earnings: 0,
            expensesList: [],
            todayExpenses: [],
            thisMonthExpenses: [],
            thisYearExpenses: [],
        };
    },
    actions: {
        async fetchDashboardData() {
            try {
                this.isLoading = true;
                const response = await axios.get("dashboards");

                // Assuming response structure with a "data" object containing dashboard information
                const data = response.data;


                this.dashboards = data;
                this.expensesList = data.todayExpenses ? data.todayExpenses.expenses : [],
               
                this.isLoading = false;
            } catch (error) {
                this.error = error;
                this.isLoading = false;
            }
        },
        updateExpenses(expenses, color) {
            if (!Array.isArray(expenses)) {
                return;
            }
            this.expensesList = expenses
  
        },

        processExpenses(expenses, color) {
            
            if (!this.totalExpenses) this.totalExpenses = expenses.reduce(
                (acc, exp) => acc + parseFloat((exp.expAmount) || 0),
                0
            );
            return expenses.map((exp) => ({
                percentage: ((parseFloat( exp.expAmount) || 0) / parseFloat(this.totalExpenses)) * 100,
                color,
            }));
        },
    },
});
