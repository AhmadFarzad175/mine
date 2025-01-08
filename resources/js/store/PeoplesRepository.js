import { defineStore } from "pinia";
import {ref, reactive} from "vue"
import { axios, contentType } from "../axios";
import { useRouter } from "vue-router";
// import { toast } from "vue3-toastify";
export let usePeopleRepository = defineStore("PeopleRepository", {
    state() {
        return {
            updateDialog : ref(false),  // Ensure it's reactive
            createDialog : ref(false),

            ownerUpdateDialog : ref(false),  // Ensure it's reactive
            ownerCreateDialog : ref(false),
            createPaymentSentDialog : ref(false), // Ensure it's reactive
            updatePaymentSentDialog : ref(false), // Ensure it's reactive
            createPaymentReceivedDialog : ref(false), // Ensure it's reactive and
            updatePaymentReceivedDialog : ref(false), // Ensure it's reactive and

        
            loanSearch: ref(""), // Ensure it's reactive
            customerSearch: ref(""),
            stakeholderSearch: ref(""),
            customers: reactive([]),
            stakeholders: reactive([]),
            stakeholder: reactive([]),
            customer: reactive([]),
            supplierSearch: ref(""),
            ownerpickupSearch:ref(""),
            ownerPickups:reactive([]),
            ownerPickup:reactive([]),
            suppliers: reactive([]),
            supplier: reactive([]),
            currencyIndex:0, 
            receivedLoans:reactive([]),
            receivedLoan:reactive([]),
            sentLoans:reactive([]),
            sentLoan:reactive([]),

            owners: reactive([]),
            roles: reactive([]),
            loans: reactive([]),
            loanPeople:reactive([]),
            users: reactive([]),
            user: reactive([]),
            userSearch:ref(""),
            current_owner_id : 1,
            owner: reactive([]),
            ownerSearch: ref(""),
            currencies: reactive([]),

            // expenseProducts: reactive([]),
            // expenseProduct: reactive([]),   
            // category : reactive([]),
        
            // searchFetch: reactive([]),
            // billableExpenseSearch: ref(""),
           
            loadingTable: ref(true),
            loading: ref(false),
            error: null,
            createDailog: false,
            updateDailog: false,
            totalItems: ref(0),
            itemsPerPage: ref(10),
            router: useRouter(),
            // totalItems: 0,
            itemKey: "id",

 

        };
    },

    // ------- action ---------------------\\
    actions: {
       

        //////////////////////////////////////////// fetch none Billable expenses //////////////////////
        async fetchClients({ page, itemsPerPage }){

            this.loading = true;
            try {
                // const response = await axios.get(``);
                const response = await axios.get(
                    `Customers?page=${page}&perPage=${itemsPerPage}&search=${this.customerSearch}`
                );
                

                console.log(response.data.data)
        
                this.customers = response.data.data;
                this.totalItems = response.data.meta.total;
            
                this.loading = false;
            } catch (err) {
                // this.error = err.message;
            }
        },
       
        async CreateCustomer(formData) {
            // Adding a custom header to the Axios request

            const config = {
                method: "POST",
                url: "Customers",
                data: formData,
            };

            // Using Axios to make a GET request with async/await and custom headers
            const response = await axios(config);
           
            this.createDailog = false;
            this.fetchClients({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },
      

        async UpdateCustomer(id, data) {

            // Adding a custom header to the Axios request

            const config = {
                method: "PUT",
                url: `Customers/${id}`,
    
                data: data,
            };
    

            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            // toast.success("Customer Succesfully Updated", {
            //     autoClose: 1000,
            // });

            this.updateDailog = false;
            this.fetchClients({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

        async deleteCustomer(id) {
            const config = {
                method: "DELETE",
                url: "Customers/" + id,
            };
    
            const response = await axios(config);
            this.fetchClients({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

         //////////////////////////////////////////// fetch none Billable expenses //////////////////////
         async fetchStakeholders({ page, itemsPerPage }){

            this.loading = true;
            try {
                // const response = await axios.get(``);
                const response = await axios.get(
                    `stakeholders?page=${page}&perPage=${itemsPerPage}&search=${this.stakeholderSearch}`
                );
                

                console.log(response.data.data)
        
                this.stakeholders = response.data.data;
                this.totalItems = response.data.meta.total;
            
                this.loading = false;
            } catch (err) {
                // this.error = err.message;
            }
        },
    

        async fetchLoans({ page, itemsPerPage }){

            this.loading = true;
            try {
                // const response = await axios.get(``);
                const response = await axios.get(
                    `loan-people?page=${page}&perPage=${itemsPerPage}&search=${this.supplierSearch}`
                );
                

        
                this.loans = response.data.data;
                this.totalItems = response.data.meta.total;
            
                this.loading = false;
            } catch (err) {

                // this.error = err.message;
            }
        },

        async fetchSentLoans({ page, itemsPerPage }) {
            this.loading = true;
            try {
                const response = await axios.get(
                    `loan-payment-sent?page=${page}&perPage=${itemsPerPage}`
                );
        
                console.log(response.data);
                this.sentLoans = response.data.data;
                this.totalItems = response.data.meta.total;

                this.loading = false;
            } catch (err) {
                console.error(err);
            }
        },
        

        async createPaymentSent(formData){
            const config = {
                method: "POST",
                url: "loan-payment-sent",
                data: formData,
            };

            // Using Axios to make a GET request with async/await and custom headers
            const response = await axios(config);
           
            this.createPaymentSentDialog = false;
            this.fetchSentLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });

        },

        async updatePaymentsent(id, data) {

            // Adding a custom header to the Axios request

            const config = {
                method: "PUT",
                url: `loan-payment-sent/${id}`,
    
                data: data,
            };
    

            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            // toast.success("Customer Succesfully Updated", {
            //     autoClose: 1000,
            // });

            this.updatePaymentSentDialog = false;
            this.fetchSentLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },


        async deletePaymentSent(id) {

            const config = {
                method: "DELETE",
                url: "loan-payment-sent/" + id,
            };
            const response = await axios(config);
    
            this.fetchSentLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },
        // { page, itemsPerPage }
        // { page, itemsPerPage }

        async fetchReceivedLoans({ page, itemsPerPage }) {
            this.loading = true;
            try {
                const response = await axios.get(
                    `loan-payment-received?page=${page}&perPage=${itemsPerPage}`
                );
        
                this.receivedLoans = response.data.data; // Ensure this matches your resource response
                this.totalItems = response.data.meta.total; // Ensure this matches your resource response
        
                this.loading = false;
            } catch (err) {
                console.error(err); // Log the error for debugging
                this.loading = false; // Ensure loading is turned off even if there is an error
            }
        },
        
        async createPaymentReceived(formData){
            const config = {
                method: "POST",
                url: "loan-payment-received",
                data: formData,
            };

            // Using Axios to make a GET request with async/await and custom headers
            const response = await axios(config);
           
            this.createPaymentReceivedDialog = false;
            this.fetchReceivedLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });

        },

        async updatePaymentReceived(id, data) {

            // Adding a custom header to the Axios request

            const config = {
                method: "PUT",
                url: `loan-payment-received/${id}`,
    
                data: data,
            };
    

            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            // toast.success("Customer Succesfully Updated", {
            //     autoClose: 1000,
            // });

            this.updatePaymentReceivedDialog = false;
            this.fetchReceivedLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },


        async deletePaymentReceived(id) {

            const config = {
                method: "DELETE",
                url: "loan-payment-received/" + id,
            };
            const response = await axios(config);
    
            this.fetchReceivedLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

        

        async createLoan(formData) {
            // Adding a custom header to the Axios request
            console.log(formData)
            const config = {
                method: "POST",
                url: "loan-people",
                data: formData,
            };

            // Using Axios to make a GET request with async/await and custom headers
            const response = await axios(config);
           
            this.createDailog = false;
            this.fetchLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

        async updateLoan(id, data) {

            // Adding a custom header to the Axios request

            const config = {
                method: "PUT",
                url: `loan-people/${id}`,
    
                data: data,
            };
    

            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            // toast.success("Customer Succesfully Updated", {
            //     autoClose: 1000,
            // });

            this.updateDailog = false;
            this.fetchLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

        async deleteLoan(id) {

            const config = {
                method: "DELETE",
                url: "loan-people/" + id,
            };
            const response = await axios(config);
    
            this.fetchLoans({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },
      

        
        async fetchSuppliers({ page, itemsPerPage }){

            this.loading = true;
            try {
                // const response = await axios.get(``);
                const response = await axios.get(
                    `Suppliers?page=${page}&perPage=${itemsPerPage}&search=${this.supplierSearch}`
                );
                

                console.log(response.data.data)
        
                this.suppliers = response.data.data;
                this.totalItems = response.data.meta.total;
            
                this.loading = false;
            } catch (err) {
                // this.error = err.message;
            }
        },
       

        
        async CreateSupplier(formData) {
            // Adding a custom header to the Axios request

            const config = {
                method: "POST",
                url: "Suppliers",
                data: formData,
            };

            // Using Axios to make a GET request with async/await and custom headers
            const response = await axios(config);
           
            this.createDailog = false;
            this.fetchSuppliers({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },
      

        async UpdateSupplier(id, data) {

            // Adding a custom header to the Axios request

            const config = {
                method: "PUT",
                url: `Suppliers/${id}`,
    
                data: data,
            };
    

            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            // toast.success("Customer Succesfully Updated", {
            //     autoClose: 1000,
            // });

            this.updateDailog = false;
            this.fetchSuppliers({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

        async deleteSupplier(id) {
            const config = {
                method: "DELETE",
                url: "Suppliers/" + id,
            };
    
            const response = await axios(config);
            this.fetchSuppliers({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },
    


        async fetchOwners({ page, itemsPerPage }){

            this.loading = true;
            try {
                // const response = await axios.get(``);
                const response = await axios.get(
                    `Owners?page=${page}&perPage=${itemsPerPage}&search=${this.ownerSearch}`
                );
                

        
                this.owners = response.data.data;
                this.totalItems = response.data.meta.total;
            
                this.loading = false;
            } catch (err) {
                // this.error = err.message;
            }
        },
       
        async CreateOwners(formData) {
            // Adding a custom header to the Axios request
            console.log(formData)
            const config = {
                method: "POST",
                url: "Owners",
                data: formData,
            };

            // Using Axios to make a GET request with async/await and custom headers
            const response = await axios(config);
           
            this.createDailog = false;
            this.fetchOwners({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },
      

        async UpdateOwners(id, data) {

            // Adding a custom header to the Axios request

            const config = {
                method: "PUT",
                url: `Owners/${id}`,
    
                data: data,
            };
    

            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            // toast.success("Customer Succesfully Updated", {
            //     autoClose: 1000,
            // });

            this.updateDailog = false;
            this.fetchOwners({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

        async deleteOwners(id) {
            const config = {
                method: "DELETE",
                url: "Owners/" + id,
            };
    
            const response = await axios(config);
            this.fetchOwners({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },



        async fetchOwnerPickups({ page, itemsPerPage, owner_id }) {
            console.log(page, itemsPerPage, owner_id);
            this.loading = true;
        
            try {
                const response = await axios.get(
                    `OwnerPickup?page=${page}&perPage=${itemsPerPage}&search=${this.ownerpickupSearch}&owner_id=${owner_id}`
                );
                
        
                // Assuming response structure is correct
                this.ownerPickups = response.data.data;
                this.totalItems = response.data.meta.total;
        
            } catch (err) {
                console.error("Error fetching owner pickups:", err.message);
            } finally {
                this.loading = false; // Ensure loading is disabled after the request
            }
        },
        

        async CreateOwnerPickup(formData) {
            // Adding a custom header to the Axios request

            const config = {
                method: "POST",
                url: "OwnerPickup",
                data: formData,
            };

            // Using Axios to make a GET request with async/await and custom headers
            const response = await axios(config);
           
            this.createDailog = false;
            this.fetchOwnerPickups({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
                owner_id: this.current_owner_id
            });
        },
      

        async UpdateOwnersPickup(id, data) {

            // Adding a custom header to the Axios request

            const config = {
                method: "PUT",
                url: `OwnerPickup/${id}`,
    
                data: data,
            };
    

            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            // toast.success("Customer Succesfully Updated", {
            //     autoClose: 1000,
            // });

            this.updateDailog = false;
            this.fetchOwnerPickups({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
                owner_id: this.current_owner_id
            });
        },


        async deleteOwnerPickup(id) {
            const config = {
                method: "DELETE",
                url: "OwnerPickup/" + id,
            };
    
            const response = await axios(config);
            this.fetchOwnerPickups({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
                owner_id :this.current_owner_id
            });
        },
         /////////////////////////// Currencies  /////////////////////////////////////
         async fetchCurrencies() {
            const config = {
                url: "currency",
            };
            const response = await axios(config);
            
            // Mutate the reactive array correctly

            this.currencies = response.data.data;
        
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

///////////////////////////// fetch Expense Category ///////////////////////////////////////////////////
        async fetchExpensesCategory({ page, itemsPerPage }){

            this.loading = true;
            try {
                // const response = await axios.get(``);
                const response = await axios.get(
                    `ExpenseCategory?page=${page}&perPage=${itemsPerPage}&search=${this.categoriesearch}`
                );
                
        
                console.log(response.data);
                this.categories = response.data.data;
                this.totalItems = response.data.meta.total;
                this.loading = false;
            } catch (err) {
                // this.error = err.message;
            }
        },




        // USers Module start here //////////////////////////////////////////////////////////////////

        async fetchUsers({ page, itemsPerPage }) {
            this.loading = true;

            const response = await axios.get(
                `Users?page=${page}&perPage=${itemsPerPage}&search=${this.userSearch}`
            );
            this.users = response.data.data;
            this.totalItems = response.data.meta.total;
            this.loading = false;
        },

        async fetchUser(id) {
            // this.error = null;
            try {
                const response = await axios.get(`Users/${id}`);

                this.user = response.data.data;
            } catch (err) {
                // this.error = err.message;
            }
        },

        async fetchRoles(){

            try {
                // const response = await axios.get(``);
                const response = await axios.get(
                    'roles'
                );
                
                // console.log(response.data)

        
                this.roles = response.data.data;
                this.totalItems = response.data.meta.total;
            
            } catch (err) {
                // this.error = err.message;
            }
        },
       
        async UpdateUser(id, data) {
            try {
                const config = {
                    method: "Post",
                    url: "Users/update/" + id,
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    data: data,
                };

                // Using Axios to make a post request with async/await and custom headers
                const response = await axios(config);
                this.updateDialog = false;

                this.fetchUsers({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
                // this.router.push("/Users");
            } catch (err) {
                // If there's an error, set the error in the store
                this.error = err;
            }
        },


        async DeleteUser(id) {
            this.isLoading = true;
            this.setting = [];
            this.error = null;

            try {
                const config = {
                    method: "DELETE",
                    url: "Users/" + id,
                };

                const response = await axios(config);

                // this.setting = response.data.data;
                this.fetchUsers({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                this.error = err;
            }
        },
        async CreateForSwitch(status, id) {
            console.log(status, "man", id);
            try {
                // Adding a custom header to the Axios request
                const config = {
                    method: "POST",
                    url: "users/switch/" + id,

                    data: status,
                };

                // Using Axios to make a GET request with async/await and custom headers
                const response = await axios(config);
                console.log(status, "man", id);
            } catch (err) {
                // If there's an error, set the error in the stor
            }
        },
        async CreateUser(formData) {
            try {
                // Adding a custom header to the Axios request
                const config = {
                    method: "POST",
                    url: "Users",
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    data: formData,
                };

                // Using Axios to make a GET request with async/await and custom headers
                const response = await axios(config);
                this.createDialog = false;
                this.fetchUsers({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
                // this.router.push("/users");
            } catch (err) {
                // If there's an error, set the error in the stor
            }
        },
        async DeleteUser(id) {
            this.isLoading = true;
            this.setting = [];
            this.error = null;

            try {
                const config = {
                    method: "DELETE",
                    url: "Users/" + id,
                };

                const response = await axios(config);

                // this.setting = response.data.data;
                this.fetchUsers({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                this.error = err;
            }
        },



        // Useers Module end here //////////////////////////////////////////////////////////////////

///////////////////////////////////// Create Expense Category ///////////////////////////////////////////////////////////

        async CreateExpensesCategory(formData) {
            try {
                // Adding a custom header to the Axios request
                const config = {
                    method: "POST",
                    url: "ExpenseCategory",
        
                    data: formData,
                };
        
                // Using Axios to make a GET request with async/await and custom headers
                const response = await axios(config);
                this.createDialog = false;
                // this.FetchSaleProducts({
                //     page: this.page,
                //     itemsPerPage: this.itemsPerPage,
                // });
            } catch (err) {
                // If there's an error, set the error in the stor
            }
        },
/////////////////////////////////// Update Expense Category //////////////////////////////////////////////////////
        async UpdateExpensesCategory(id, data) {
            try {
                const config = {
                    method: "PUT",
                    url: `ExpenseCategory/${id}`,
        
                    data: data,
                };
        
                // Using Axios to make a post request with async/await and custom headers
                const response = await axios(config);
                this.updateDialog = false;
                // this.FetchSaleProducts({
                //     page: this.page,
                //     itemsPerPage: this.itemsPerPage,
                // });
            } catch (err) {
                // If there's an error, set the error in the store
                this.error = err;
            }
        },

////////////////////////////////// Delete Expense Category //////////////////////////////////////////////////////////
        async DeleteExpenseCategory(id) {
            this.isLoading = true;
            this.error = null;
        
            try {
                const config = {
                    method: "DELETE",
                    url: "ExpenseCategory/" + id,
                };
        
                const response = await axios(config);
        
                // this.saleProduct = response.data.data;
                // this.FetchSaleProducts({
                //     page: this.page,
                //     itemsPerPage: this.itemsPerPage,
                // });
            } catch (err) {
                this.error = err;
            }
        },


        getTodaysDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, "0");
            const day = String(today.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        },


         /////////////////////////// Currencies  /////////////////////////////////////
         async fetchCurrencies() {
            const config = {
                url: "currency",
            };
            const response = await axios(config);
            
            // Mutate the reactive array correctly

            this.currencies = response.data.data;
        
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



        ///////////////////////////Fetch all sale Product/////////////////////////////////////
async FetchExpenseProducts({ page, itemsPerPage }) {
    this.loading = true;

    const response = await axios.get(
        `ExpenseProduct?page=${page}&perPage=${itemsPerPage}&search=${this.expenseProductsearch}`
    );
    console.log(response.data)

    this.expenseProducts = response.data.data;
    
    this.totalItems = response.data.meta.total;
    this.loading = false;
},

//////////////////////////////////////////////Fetch Expense Product//////////////////////////////////////////////////////
async FetchExpenseProduct(id) {
    // this.error = null;
    try {
        const response = await axios.get(`ExpenseProduct/${id}`);

        this.expenseProduct = response.data.data;
        // console.log(this.saleProduct);
    } catch (err) {
        // this.error = err.message;
    }
},


/////////////////////////////////////////////////Create Expense Product///////////////////////////////////////////////
async CreateExpenseProduct(formData) {
    try {
        // Adding a custom header to the Axios request
        const config = {
            method: "POST",
            url: "ExpenseProduct",

            data: formData,
        };

        // Using Axios to make a GET request with async/await and custom headers
        const response = await axios(config);
        this.createDialog = false;
        this.FetchExpenseProducts({
            page: this.page,
            itemsPerPage: this.itemsPerPage,
        });
    } catch (err) {
        // If there's an error, set the error in the stor
    }
},

////////////////////////Update Expense Product////////////////////////////////////////
async UpdateExpenseProduct(id, data) {
    console.log(id,data);
    try {
        const config = {
            method: "PUT",
            url: `ExpenseProduct/${id}`,

            data: data,
        };

        // Using Axios to make a post request with async/await and custom headers
        const response = await axios(config);
        this.updateDialog = false;
        this.FetchExpenseProducts({
            page: this.page,
            itemsPerPage: this.itemsPerPage,
        });
    } catch (err) {
        // If there's an error, set the error in the store
        this.error = err;
    }
},


async DeleteExpenseProduct(id) {
    this.isLoading = true;
    this.error = null;

    try {
        const config = {
            method: "DELETE",
            url: "ExpenseProduct/" + id,
        };

        const response = await axios(config);

        this.FetchExpenseProducts({
            page: this.page,
            itemsPerPage: this.itemsPerPage,
        });
    } catch (err) {
        this.error = err;
    }
},


     /////////////////////////// fetching  the searched data from database  /////////////////////////////////////
        
     async SearchFetchData() {
        this.loading = true;
        
        if(this.productBillableExpenseSearch === ""){
            this.searchFetch = [];
        }
        else {
            const response = await axios.get(
                `ExpenseProduct?&search=${this.productBillableExpenseSearch}`
            );
            console.log(response.data);
            this.searchFetch = response.data.data;
            this.loading = false;

        }
        // this.searchFetch = "";
    },



    async FetchSuppliers({ page, itemsPerPage }) {
        this.loading = true;
    
        const response = await axios.get(
            `Suppliers?page=${page}&perPage=${itemsPerPage}&search=${this.expenseProductsearch}`
        );
    
        console.log(response.data)
        this.suppliers = response.data.data;
        
        this.totalItems = response.data.meta.total;
        this.loading = false;
    },
    
    



    /////////////////////////////////////////////////Create Expense Product///////////////////////////////////////////////
  async CreateBillableExpense(formData) {
    try {
        // Adding a custom header to the Axios request
        const config = {
            method: "POST",
            url: "BillExpenses",

            data: formData,
        };

        // Using Axios to make a GET request with async/await and custom headers
        const response = await axios(config);
        this.createDialog = false;
        this.router.push("/billableExpense");
        this.FetchBillableExpense({
            page: this.page,
            itemsPerPage: this.itemsPerPage,
        });
    } catch (err) {
        // If there's an error, set the error in the stor
    }
},

  /////////////////////////////////////////////////Create Expense Product///////////////////////////////////////////////
  async UpdateBillableExpense(id, data) {
        console.log(id,data);
        try {
            const config = {
                method: "PUT",
                url: `BillExpenses/${id}`,
    
                data: data,
            };
    
            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            this.updateDialog = false;
            this.router.push("/billableExpense");
            this.FetchBillableExpense({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
           
        } catch (err) {
            // If there's an error, set the error in the store
            this.error = err;
        }
    },

    async DeleteBillableExpense(id) {
        try {
            const config = {
                method: "DELETE",
                url: "BillExpenses/" + id,
            };
    
            const response = await axios(config);
    
            this.earnings = response.data.data;
            this.FetchBillableExpense({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        } catch (err) {
            this.error = err;
        }
    },


async FetchBillableExpense({ page, itemsPerPage }) {
    this.loading = true;

    const response = await axios.get(
        `BillExpenses?page=${page}&perPage=${itemsPerPage}&search=${this.billableExpenseSearch}`
    );
    // this.clearEarningPage()
    console.log(response.data.data)
    this.billableExpenses = response.data.data;

    this.totalItems = response.data.meta.total;
    this.loading = false;
},


        /////////////////////////////fetch one earning///////////////////////////////////
        async fetchBillabeExpense(id) {
            // this.error = null;
            // this.earning.earningDetails = [];
            // this.clearEarningPage();


            try {
                const response = await axios.get(`BillExpenses/${id}`);


                console.log(response.data)
                this.expense = response.data.data;
                
                // console.log(this.earning)

                // console.log(this.earning);
            } catch (err) {
                // this.error = err.message;
            }
        },



        
    }
})