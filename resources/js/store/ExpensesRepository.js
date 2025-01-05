import { defineStore } from "pinia";
import {ref, reactive} from "vue"
import { axios, contentType } from "../axios";
import { useRouter } from "vue-router";
// import { toast } from "vue3-toastify";
export let useExpenseRepository = defineStore("ExpenseRepository", {
    state() {
        return {
            updateDialog : ref(false),  // Ensure it's reactive
            createDialog : ref(false),
            createPaymentDialog : ref(false),
            updatePaymentDialog : ref(false),
            viewPaymentDialog: ref(false),
            noneBillableExpense: reactive([]),
            billableExpenses: reactive([]),
            expense: reactive({
                ExpenseDetails: [],
            }),
            dateRange: ref([new Date(), new Date()]),
            billExpensePayments: reactive([]),
            bill_expenses_id : ref(1),
            expenseProductsearch: ref(""),
            singleExpense: reactive([]),
            NoneBillableExpenseSearch: ref(""),
            billableExpenses: reactive([]),
         
            expenseProductsearch: ref(""),
            singleExpense: reactive([]),
            NoneBillableExpenseSearch: ref(""),
            categories: reactive([]),
            expenseProducts: reactive([]),
            expenseProduct: reactive([]),   
            expenseProducts: reactive([]),
            expenseProduct: reactive([]),   
            category : reactive([]),
            categoriesearch : ref(""),
            currencies: reactive([]), // Using reactive
            suppliers: reactive([]), // Using reactive
           
            searchFetch: reactive([]),
            billableExpenseSearch: ref(""),
            productBillableExpenseSearch: ref(""),
            generalCurrencyId: 1, 
            currencies: reactive([]), // Using reactive
            suppliers: reactive([]), // Using reactive
            currencyIndex:0, 
            searchFetch: reactive([]),
            billableExpenseSearch: ref(""),
            productBillableExpenseSearch: ref(""),
            generalCurrencyId: 1, 
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
        async fetchNoneBillableExpense({ page, itemsPerPage }){

            this.loading = true;
            try {
                // const response = await axios.get(``);
                const response = await axios.get(
                    `Expenses?page=${page}&perPage=${itemsPerPage}&search=${this.NoneBillableExpenseSearch}`
                    );
                


        
                this.noneBillableExpense = response.data.data;
                this.totalItems = response.data.meta.total;
            
            
                this.loading = false;
                
                this.fetchCurrencies();
                this.fetchExpensesCategory({page , itemsPerPage});
            } catch (err) {
                // this.error = err.message;
            }
        },
       
        async CreateNoneBillableExpense(formData) {
            // Adding a custom header to the Axios request

            const config = {
                method: "POST",
                url: "Expenses",
                url: "Expenses",
                data: formData,
            };

            // Using Axios to make a GET request with async/await and custom headers
            const response = await axios(config);
           
            this.createDailog = false;
            this.fetchNoneBillableExpense({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },
      

    
      

        async UpdateNoneBillableExpense(id, data) {

            // Adding a custom header to the Axios request

            const config = {
                method: "PUT",
                url: `Expenses/${id}`,
    
                url: `Expenses/${id}`,
    
                data: data,
            };
    
    

            // Using Axios to make a post request with async/await and custom headers
            const response = await axios(config);
            // toast.success("Customer Succesfully Updated", {
            //     autoClose: 1000,
            // });

            this.updateDailog = false;
            this.fetchNoneBillableExpense({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
        },

        async DeleteNoneBillableExpense(id) {
            const config = {
                method: "DELETE",
                url: "Expenses/" + id,
            };
    
            const response = await axios(config);
            this.fetchNoneBillableExpense({
                page: this.page,
                itemsPerPage: this.itemsPerPage,
            });
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
                console.log(response.data);
                this.categories = response.data.data;
                this.totalItems = response.data.meta.total;
                this.loading = false;
            } catch (err) {
                // this.error = err.message;
            }
        },

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
                this.fetchExpensesCategory({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
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
                this.fetchExpensesCategory({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
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
                this.fetchExpensesCategory({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
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


        async createPayment(formData) {
            try {
                // Configuring Axios request
                const config = {
                    method: "POST",
                    url: "ExpensePayment",
                    data: formData,
                };
        
                // Making Axios request
                const response = await axios(config);
        
                this.FetchBillableExpense({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
                // Checking if response is successful
                // if (response.status === 200 || response.statusText === "OK") {
        
                //     // Clear earnings and fetch new data before redirecting
        
        
                //     // Redirect to /sales after the fetch is done
                //     // this.router.push("/sales");
                    
                // }
            } catch (err) {
                // Handle errors (add logging or display messages)
                console.error(err);
            }
        },


        async FetchBillExpensePayments(billExpensePayment) {

            const response = await axios.get(
                `ExpensePayment?billExpensePayment=${billExpensePayment}`
            );
            this.billExpensePayments = response.data.data;
            this.totalItems = response.data.meta.total;
        },

        async FetchBillExpensePayment(id) {
            // this.error = null;
            try {
                const response = await axios.get(`ExpensePayment/${id}`);

                console.log(response.data)
                this.expense = response.data.data;
            } catch (err) {
                // this.error = err.message;
            }
        },

        async UpdateBillExpensePayment(id, data) {
            console.log(data);
            try {
                const config = {
                    method: "PUT",
                    url: "ExpensePayment/" + id,

                    data: data,
                };

                // Using Axios to make a post request with async/await and custom headers
                const response = await axios(config);
                this.updateDialog = false;

                this.FetchBillExpensePayments(this.bill_expense_id);
                this.FetchBillableExpense({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                // If there's an error, set the error in the store
                this.error = err;
            }
        },


        async DeleteBillExpensePayment(id) {
            try {
                const config = {
                    method: "DELETE",
                    url: "ExpensePayment/" + id,
                };

                const response = await axios(config);

                this.FetchBillExpensePayments(this.bill_expense_id);
                this.FetchBillableExpense({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                this.error = err;
            }
        },




        
    }
})