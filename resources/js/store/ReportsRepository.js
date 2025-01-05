import { defineStore } from "pinia";
import { ref, reactive } from "vue";
import { axios, contentType } from "../axios";
import { useRouter } from "vue-router";
export let useReportRepository = defineStore("ReportRepository", {
    state() {
        return {
            //  all the variable are in camelCase and except fetch all data all of them don't have sin there
            router: useRouter(),
            search: ref(""),
            serverItems: ref([]),
            totalItems: ref(0),
            selectedItems: ref([]),
            itemsPerPage: ref(10),
            ReportSearch: ref(""),
            // symbol
            customersReport: reactive([]),
            customerReportDetails: reactive([]), 
            customerSearch:ref(""),

            suppliersReport: reactive([]),
            supplierReportDetails: reactive([]), 
            supplierSearch:ref(""),
            categoryExpenseReportSearch: ref(""),
            ProductReportSearch:ref(""),
            categoryExpenseReport:reactive([]),
            productExpenseReport:reactive([]),
            ownersReports:reactive([]),
            dateRange: ref([]),
            expenseDateRange: ref([]),
            productDateRange: ref([]),
            ownerDateRange: ref([]),

            // dateRange: {
            //     startDate: '2019-12-26',
            //     endDate: '2019-12-28',
            //   },

            profitAndLoss: reactive([]),
            // role Permission
        };
    },
    actions: {
        async fetchProfitAndloss(startDate = null, endDate = null) {
            this.loading = true;
        
            // Prepare query parameters
            const params = {};
            if (startDate) {
                params.startDate = startDate.toJSON().slice(0, 10);
            }
            if (endDate) {
                params.endDate = endDate.toJSON().slice(0, 10);
            }
        
            try {
                const response = await axios.get('profitLoss', { params });
                this.profitAndLoss = response.data;
            } catch (error) {
                console.error("Error fetching profitAndLoss:", error);
            } finally {
                this.loading = false;
            }
        },

        async FetchCustomerReports({ page, itemsPerPage }) {
            this.loading = true;
        
            const response = await axios.get(
                `customerReports?page=${page}&perPage=${itemsPerPage}&search=${this.customerSearch}`
            );
            // this.clearEarningPage()
            console.log(response.data)
            this.customersReport = response.data.data;
        
            this.totalItems = response.data.total;
            this.loading = false;
        },

        async fetCustomerDetails(id) {
            // this.loading = true;
        
            const response = await axios.get(`customerDetailsReports/${id}`);
            // this.clearEarningPage()
            console.log(response.data)
            this.customerReportDetails = response.data;
            // this.customersReport = response.data.data;
        
            // this.totalItems = response.data.total;
            // this.loading = false;
        },
        
        

        async FetchSupplierReports({ page, itemsPerPage }) {
            this.loading = true;
        
            const response = await axios.get(
                `supplierReports?page=${page}&perPage=${itemsPerPage}&search=${this.supplierSearch}`
            );
            // this.clearEarningPage()
            console.log(response.data)
            this.suppliersReport = response.data.data;
        
            this.totalItems = response.data.total;
            this.loading = false;
        },

        async supplierDetailsReports(id) {
            // this.loading = true;
        
            const response = await axios.get(`supplierDetailsReports/${id}`);
            // this.clearEarningPage()
            console.log(response.data)
            this.supplierReportDetails = response.data;
            // this.customersReport = response.data.data;
        
            // this.totalItems = response.data.total;
            // this.loading = false;
        },


        async fetchExpenseCategoryReports(startDate = null, endDate = null) {
            this.loading = true;
        
            console.log(startDate, endDate);
            // Prepare query parameters
            const params = {};
            if (startDate) {
                params.startDate = startDate ? startDate.toJSON().slice(0, 10) : null;
            }
            if (endDate) {
                params.endDate = endDate? endDate.toJSON().slice(0, 10) : null;
            }

            params.categoryExpenseReportSearch = this.categoryExpenseReportSearch;
            
            try {
                const response = await axios.get('categoryExpenseReport', { params });
                
                console.log(response.data);
                this.categoryExpenseReport = response.data;
            } catch (error) {
                console.error("Error fetching profitAndLoss:", error);
            } finally {
                this.loading = false;
            }
        },

        async fetchExpenseProductReports(startDate = null, endDate = null) {
            this.loading = true;
        
            // console.log(startDate, endDate);
            // Prepare query parameters
            const params = {};
            if (startDate) {
                params.startDate = startDate ? startDate.toJSON().slice(0, 10) : null;
            }
            if (endDate) {
                params.endDate = endDate? endDate.toJSON().slice(0, 10) : null;
            }

            params.ProductReportSearch = this.ProductReportSearch;
            
            try {
                const response = await axios.get('productExpenseReport', { params });
                
                // console.log(response.data);
                this.productExpenseReport = response.data;
            } catch (error) {
                console.error("Error fetching profitAndLoss:", error);
            } finally {
                this.loading = false;
            }
        },


        async fetchOwnersReports(startDate = null, endDate = null) {
            this.loading = true;
        
            // console.log(startDate, endDate);
            // Prepare query parameters
            const params = {};
            if (startDate) {
                params.startDate = startDate ? startDate.toJSON().slice(0, 10) : null;
            }
            if (endDate) {
                params.endDate = endDate? endDate.toJSON().slice(0, 10) : null;
            }

            
            try {
                const response = await axios.get('ownersReport', { params });
                
                this.ownersReports = response.data;
            } catch (error) {
                console.error("Error fetching profitAndLoss:", error);
            } finally {
                this.loading = false;
            }
        },
        
  
    },
});
