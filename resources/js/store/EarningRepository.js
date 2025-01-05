import { defineStore } from "pinia";
import {ref, reactive} from "vue"
import { axios, contentType } from "../axios";
import { useRouter } from "vue-router";


export let useEarningRepository = defineStore("EarningRepository",{
    state(){
        return {
             updateDialog : ref(false),  // Ensure it's reactive
             createDialog : ref(false), 

            createPaymentDialog : ref(false),
            updatePaymentDialog : ref(false),
            viewPaymentDialog: ref(false),
            customers: reactive([]), 
            currencies: reactive([]), // Using reactive for arrays
            salePayments: reactive([]),
            earningSearch: ref(""),
            searchFetch: reactive([]), // Avoid duplicate state declarations
            Search: ref(""),
            serverItems: ref([]),
            loadingTable: ref(true),
            loading: ref(false),
            totalItems: ref(0),
            sales_id: ref(1),
            
            selectItems: ref([]),
            
            createDialog: ref(false),
            updateDialog: ref(false),

            saleProductSearch: ref(""),
            
            saleProducts: reactive([]),
            saleProduct: reactive([]),
            // Use ref for primitive types
            currencyIndex:0, 
            generalCurrencyId: 1,
            itemsPerPage: ref(10),
            earning: reactive({
                earningDetails: []
            }),
            earnings: reactive([]),


            earningalldata: reactive([]),
            router: useRouter(),
        };

    },
    actions: {


        async fetchEarnings({ page, itemsPerPage }) {
            this.loading = true;

            const response = await axios.get(
                `sales?page=${page}&perPage=${itemsPerPage}&search=${this.earningSearch}`
            );
            this.clearEarningPage()
            this.earnings = response.data.data;

            this.fetchCurrencies()
            console.log(this.earnings);
            this.totalItems = response.data.meta.total;
            this.loading = false;
        },

        getTodaysDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, "0");
            const day = String(today.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        },

        // async EarningAllData() {
        //     const config = {
        //         url: "earnings_all_data",
        //     };
        //     const response = await axios(config);
        //     this.earningalldata = response.data.data;
        // },


        /////////////////////////// customers  /////////////////////////////////////
        async fetchCustomers(){
            const config = {
                url: "Customers",
            }
            const response = await axios(config);
            this.customers = response.data.data;
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
        }
        
        ,

        /////////////////////////// fetching  the searched data from database  /////////////////////////////////////
        
        async SearchFetchData() {
            this.loading = true;
            
            if(this.earningSearch === ""){
                this.searchFetch = [];
            }
            else {
                const response = await axios.get(
                    `sales_products?&search=${this.earningSearch}`
                );
                this.searchFetch = response.data.data;
                this.loading = false;

            }
            // this.searchFetch = "";
        },

        


        /////////////////////////////// create earning /////////////////////////////////

        async CreateEarning(formData) {
            try {
                // Configuring Axios request
                const config = {
                    method: "POST",
                    url: "sales",
                    data: formData,
                };
        
                // Making Axios request
                const response = await axios(config);
        
                // Checking if response is successful
                if (response.status === 200 || response.statusText === "OK") {
        
                    // Clear earnings and fetch new data before redirecting
        
                    this.fetchEarnings({
                        page: this.page,
                        itemsPerPage: this.itemsPerPage,
                    });
        
                    // Redirect to /sales after the fetch is done
                    this.router.push("/sales");
                    
                }
            } catch (err) {
                // Handle errors (add logging or display messages)
                console.error(err);
            }
        },

        ///////////////////////////// Update Earning ///////////////////////////////////
        async UpdateEarning(id, data) {
            try {
                const config = {
                    method: "PUT",
                    url: "sales/" + id,
                    data: data,
                };

                // Using Axios to make a post request with async/await and custom headers
                const response = await axios(config);
                this.earning = [];
                this.router.push("/sales");

                this.updateDialog = false;
                this.fetchEarnings({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                // If there's an error, set the error in the store
                this.error = err;
            }
        },


        /////////////////////////////fetch one earning///////////////////////////////////
        async fetchEarning(id) {
            // this.error = null;
            // this.earning.earningDetails = [];
            this.clearEarningPage();


            try {
                const response = await axios.get(`sales/${id}`);

                

                this.earning = response.data.data;
                
                console.log(this.earning)

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
                    url: "sales_Payments",
                    data: formData,
                };
        
                // Making Axios request
                const response = await axios(config);
        
                this.fetchEarnings({
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


        async FetchEarningPayments(earningId) {

            const response = await axios.get(
                `sales_Payments?saleId=${earningId}`
            );
            console.log(response.data.data)
            this.salePayments = response.data.data;
            this.totalItems = response.data.meta.total;
        },


        async FetchEarningPayment(id) {
            // this.error = null;
            try {
                const response = await axios.get(`sales_Payments/${id}`);

                this.earning = response.data.data;
            } catch (err) {
                // this.error = err.message;
            }
        },
        async UpdateEarningPayment(id, data) {
            console.log(data);
            try {
                const config = {
                    method: "PUT",
                    url: "sales_Payments/" + id,

                    data: data,
                };

                // Using Axios to make a post request with async/await and custom headers
                const response = await axios(config);
                this.updateDialog = false;

                this.FetchEarningPayments(this.sales_id);
                this.fetchEarnings({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                // If there's an error, set the error in the store
                this.error = err;
            }
        },


        async DeleteEarningPayment(id) {
            try {
                const config = {
                    method: "DELETE",
                    url: "sales_Payments/" + id,
                };

                const response = await axios(config);

                this.earningPayments = response.data.data;
                this.FetchEarningPayments(this.sales_id);
                this.fetchEarnings({
                    page: this.page,
                    itemsPerPage: this.itemsPerPage,
                });
            } catch (err) {
                this.error = err;
            }
        },



        

///////////////////////Clearn Earning Page/////////////////////////////////////////
      clearEarningPage (){
        this.earning.earningDetails = [];
        this.earning.total = 0;
        this.earning.discount = 0;
        this.earning.customer_id = "";
        this.earning.currency_id = "";
        this.earning.date = "";


        },


/////////////////////////////Remove single Earning ///////////////////////////////////////////

async DeleteEarning(id) {
    try {
        const config = {
            method: "DELETE",
            url: "sales/" + id,
        };

        const response = await axios(config);

        this.earnings = response.data.data;
        this.fetchEarnings({
            page: this.page,
            itemsPerPage: this.itemsPerPage,
        });
    } catch (err) {
        this.error = err;
    }
},





///////////////////////////Fetch all sale Product/////////////////////////////////////
async FetchSaleProducts({ page, itemsPerPage }) {
    this.loading = true;

    const response = await axios.get(
        `sales_products?page=${page}&perPage=${itemsPerPage}&search=${this.saleProductSearch}`
    );
    this.saleProducts = response.data.data;
    this.totalItems = response.data.meta.total;
    this.loading = false;
},

//////////////////////////////////////////////Fetch Salw Product//////////////////////////////////////////////////////
async FetchSaleProduct(id) {
    // this.error = null;
    try {
        const response = await axios.get(`sales_products/${id}`);

        this.saleProduct = response.data.data;
        // console.log(this.saleProduct);
    } catch (err) {
        // this.error = err.message;
    }
},


/////////////////////////////////////////////////Create Sale Product///////////////////////////////////////////////
async CreateSaleProduct(formData) {
    console.log(formData);
    try {
        // Adding a custom header to the Axios request
        const config = {
            method: "POST",
            url: "sales_products",

            data: formData,
        };

        // Using Axios to make a GET request with async/await and custom headers
        const response = await axios(config);
        this.createDialog = false;
        this.FetchSaleProducts({
            page: this.page,
            itemsPerPage: this.itemsPerPage,
        });
    } catch (err) {
        // If there's an error, set the error in the stor
    }
},

////////////////////////Update Sale Product////////////////////////////////////////
async UpdateSaleProduct(id, data) {
    console.log(id,data);
    try {
        const config = {
            method: "PUT",
            url: `sales_products/${id}`,

            data: data,
        };

        // Using Axios to make a post request with async/await and custom headers
        const response = await axios(config);
        this.updateDialog = false;
        this.FetchSaleProducts({
            page: this.page,
            itemsPerPage: this.itemsPerPage,
        });
    } catch (err) {
        // If there's an error, set the error in the store
        this.error = err;
    }
},
async DeleteSaleProduct(id) {
    this.isLoading = true;
    this.error = null;

    try {
        const config = {
            method: "DELETE",
            url: "sales_products/" + id,
        };

        const response = await axios(config);

        // this.saleProduct = response.data.data;
        this.FetchSaleProducts({
            page: this.page,
            itemsPerPage: this.itemsPerPage,
        });
    } catch (err) {
        this.error = err;
    }
},



        

        // async fetchProduct(id, isUpdate = false) {
        //     // this.error = null;
        //     try {
        //         const response = await axios.get(`products/${id}`);

        //         // console.log("id", response.data.data.id);
        //         if (isUpdate) {
        //             delete response.data.data.id;
        //         }
        //         this.product.push(response.data.data);
        //         this.earning.earningDetails.push(response.data.data);

        //         this.searchFetch = [];
        //     } catch (err) {
        //         // this.error = err.message;
        //     }
        // },
    }





})
