import { defineStore } from "pinia";
import { ref, reactive } from "vue";
import { axios, contentType } from "../axios";
import { useRouter } from "vue-router";
export let useSettingRepository = defineStore("SettingRepository", {
    state() {
        return {
            updateDialog : ref(false),  // Ensure it's reactive
            createDialog : ref(false),
            //  all the variable are in camelCase and except fetch all data all of them don't have sin there
            router: useRouter(),
            search: ref(""),
            logo: null,
            storedData: ref([]),
            serverItems: ref([]),
            loadingTable: ref(true),
            loading: ref(false),
            totalItems: ref(0),
            selectedItems: ref([]),
            itemsPerPage: ref(10),
            settingSearch: ref(""),
            categoriesearch: ref(""),
            setting: reactive([]),
            settings: reactive([]),
            roles: reactive([]),
            Currency: reactive([]),
            // symbol
            currencies: reactive([]),
            generalCurrencyId: 1, 
            currencyIndex:0, 


            // money account
            accountStatements: reactive([]),
            moneyAccounts: reactive([]),
            moneyAccount: reactive([]),
            moneyAccountSearch: ref(""),
            
            createCompony: reactive([]),
            companies: reactive([]),
            // role Permission
            permissions: reactive([]),
            permission: reactive([]),
            permissionSearch: ref(""),
            permissionsForValidation: reactive([]),
        };
    },
    actions: {

    
        async fetchCompany() {
            this.loading = true;
         
            try {
                const response = await axios.get(`SystemSetting`);
                this.companies = response.data.data;
                console.log(this.companies);
            } catch (error) {
                console.error('Error fetching companies:', error);
            } finally {
                this.loading = false;
            }
        },
        

         // create compony Setting
         async CreateCompany(formData) {
            try {
                // Adding a custom header to the Axios request
                const config = {
                    method: "POST",
                    url: "SystemSetting",
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    data: formData,
                };

               
                const response = await axios(config);

            } catch (err) {
                // If there's an error, handle it here
            }
        },

        async fetchRolePermissions({ page, itemsPerPage }) {
            this.loading = true;
            
            const response = await axios.get(
                `roles?page=${page}&perPage=${itemsPerPage}&search=${this.permissionSearch}`
            );
            console.log(response)
            this.roles = response.data.data;
            this.totalItems = response.data.total;
            this.itemsPerPage = response.data.per_page;
            this.loading = false;
        },
       

        async fetchRolePermission(id) {
            // this.error = null;
            try {
                const response = await axios.get(`Rolepermissions/${id}`);

                this.permission = response.data.data;
            } catch (err) {
                // this.error = err.message;
            }
        },
        async UpdateRolePermission(id, data) {
            try {
                const config = {
                    method: "PUT",
                    url: "Rolepermissions/" + id,

                    data: data,
                };

                // Using Axios to make a post request with async/await and custom headers
                const response = await axios(config);
                this.updateDialog = false;
                this.router.push("/roles");
            } catch (err) {
                // If there's an error, set the error in the store
                this.error = err;
            }
        },
        async CreateRolePermission(formData) {
            console.log(formData);
            try {
                // Adding a custom header to the Axios request
                const config = {
                    method: "POST",
                    url: "Rolepermissions",

                    data: formData,
                };

                // Using Axios to make a GET request with async/await and custom headers
                const response = await axios(config);
                this.router.push("/roles");
                // this.fetchRolePermissions({
                //     page: this.page,
                //     itemsPerPage: this.itemsPerPage,
                // });
            } catch (err) {
                // If there's an error, set the error in the stor
            }
        },
        async DeleteRolePermission(id) {
            this.isLoading = true;
            this.setting = [];
            this.error = null;

            try {
                const config = {
                    method: "DELETE",
                    url: "Rolepermissions/" + id,
                };

                const response = await axios(config);

                // this.setting = response.data.data;
                this.fetchRolePermissions({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });

            } catch (err) {
                this.error = err;
            }
            
        },

         /////////////////////////// Currencies  /////////////////////////////////////
         async fetchCurrencies() {
            this.loading = true;
            const config = {
                url: "currency",
            };
            const response = await axios(config);
            
            // Mutate the reactive array correctly

            this.currencies = response.data.data;
            console.log(this.currencies);
        
            this.loading = false;
            // Log the response to see the fetched data
        
            // Update generalCurrencyId and currencyIndex based on the fetched data
            if (this.currencies.length > 0) {
                this.generalCurrencyId = this.currencies[0].id; // Assuming you want to set it to the first currency's ID
                this.currencyIndex = 0; // Set to 0 to reference the first currency
            } else {
                // Handle case when there are no currencies fetched
                this.generalCurrencyId = null; // Or set it to a default value
                this.currencyIndex = null; // Or set it to a default value
            }
          
        },

        async CreateCurrency(formData){

            try {
                // Adding a custom header to the Axios request
                const config = {
                    method: "POST",
                    url: "currency",
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    data: formData,
                };
                const response = await axios(config);
               
                this.fetchCurrencies();

            } catch (err) {
                // If there's an error, handle it here
            }

        },

        async UpdateCurrency(id, data) {
            try {
                const config = {
                    method: "PUT",
                    url: `currency/${id}`,
        
                    data: data,
                };
        
                // Using Axios to make a post request with async/await and custom headers
                const response = await axios(config);
                this.updateDialog = false;
                this.fetchCurrencies({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                // If there's an error, set the error in the store
                this.error = err;
            }
        },

        async DeleteCurrency(id) {
            const config = {
                method: "DELETE",
                url: "currency/" + id,
            };
    
            const response = await axios(config);
            this.fetchCurrencies({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },


         /////////////////////////// Money Account  /////////////////////////////////////
         async fetchMoneyAccounts() {
            this.loading = true;
            const config = {
                url: "moneyAccounts",
            };
            const response = await axios(config);
            
            // Mutate the reactive array correctly

            this.moneyAccounts = response.data.data;
            console.log(this.moneyAccounts);
        
            this.loading = false;

            this.fetchCurrencies();
            // Log the response to see the fetched data
        },

        async CreateMoneyAccount(formData){

            try {
                // Adding a custom header to the Axios request
                const config = {
                    method: "POST",
                    url: "moneyAccounts",
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    data: formData,
                };
                const response = await axios(config);
               
                this.fetchMoneyAccounts();

            } catch (err) {
                // If there's an error, handle it here
            }

        },

        async UpdateMoneyAccount(id, data) {
            try {
                const config = {
                    method: "PUT",
                    url: `moneyAccounts/${id}`,
        
                    data: data,
                };
        
                // Using Axios to make a post request with async/await and custom headers
                const response = await axios(config);
                this.updateDialog = false;
                this.fetchMoneyAccounts({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                // If there's an error, set the error in the store
                this.error = err;
            }
        },

        async DeleteMoneyAccount(id) {
            const config = {
                method: "DELETE",
                url: "moneyAccounts/" + id,
            };
    
            const response = await axios(config);
            this.fetchMoneyAccounts({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

         /////////////////////////// Money Account  /////////////////////////////////////
         async fetchAccountStatements(id) {
            this.loading = true;
            const config = {
                url: "moneyAccounts/statements/" + id,
            };
            const response = await axios(config);
            
            // Mutate the reactive array correctly

            this.accountStatements = response.data.data;
            console.log('+++++')
            console.log(this.accountStatements);
        
            this.loading = false;
            // Log the response to see the fetched data
        },


    },
});
