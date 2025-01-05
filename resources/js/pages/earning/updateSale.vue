<template >
    <!-- <CreateProduct v-if="EarningRepository.createDialog" /> -->
    <v-skeleton-loader
            v-if="loading"
            type="card, list-item, table"
            max-width="100%"
            class="my-4"
        />
    <div v-else class="all-expense rounded-xl">
        <div class="card rounded-xl bg-white">
            <v-form ref="formRef">
                <div
                    class="d-flex justify-space-between gap-2"
                    style="gap: 2em; margin-top: 1em"
                >
                    <!--frontend  -->
                    <v-text-field
                        type="Date"
                        v-model="formData.date"
                        :return-object="false"
                        variant="outlined"
                        label=" * Date"
                        color="#d3e2f8"
                        style="width: 45%"
                        density="compact"
                    ></v-text-field>
                    <!-- style="width: 50%" -->
                    <v-autocomplete
                        :items="EarningRepository.customers"
                        v-model="formData.customer_id"
                        :return-object="false"
                        variant="outlined"
                        label="Customer *"
                        item-value="id"
                        item-title="name"
                        density="compact"
                        style="width: 45%"
                        :rules="[rules.required]"
                    ></v-autocomplete>

                    <!-- class="pb-4"
                style="width: 45%" -->
                    <!-- <v-autocomplete
                    v-model="formData.currency"
                    :items="EarningRepository.currencies"
                    :return-object="false"
                    variant="outlined"
                    label="Currency *"
                    item-value="id"
                    item-title="code"
                    color="customPrimary"
                    base-color="customPrimary"
                    density="compact"
                    style="width: 30%"
                ></v-autocomplete> -->
                </div>

                <v-row no-gutters>
                    <v-col
                        cols="full"
                        sm="12"
                        md="12"
                        style="position: relative"
                    >
                        <!-- Make parent relative -->
                        <div class="d-flex">
                            <v-text-field
                                v-model="EarningRepository.earningSearch"
                                @keyup.enter="EarningRepository.SearchFetchData"
                                @input="EarningRepository.SearchFetchData"
                                @click:clear="clearSearch"
                                variant="outlined"
                                label="Search Products"
                                density="compact"
                                append-inner-icon="mdi-magnify"
                                clearable
                            >
                            </v-text-field>
                        </div>

                        <div
                            class="d-flex"
                            style="
                                background-color: #f8f8f8;
                                margin-top: -1.2em;
                                max-height: 300px;
                                overflow-y: auto;
                                position: absolute; /* Make the dropdown float */
                                z-index: 1000;
                                width: 100%; /* Ensure the width matches the search bar */
                            "
                            v-if="EarningRepository.searchFetch.length > 0"
                        >
                            <v-col cols="full" sm="12" md="12">
                                <div
                                    v-for="(
                                        products, index
                                    ) in EarningRepository.searchFetch"
                                    :key="index"
                                >
                                    <p
                                        @click="CalcFetchProduct(products)"
                                        class="cursor-pointer px-4 py-2 hover-bg hover:text-bold selected-item"
                                        style="
                                            transition: background-color 0.3s
                                                ease;
                                        "
                                    >
                                        {{ products.product_name }}
                                    </p>
                                </div>
                            </v-col>
                        </div>
                    </v-col>

                    <!-- ==================================================================== -->

                    <div class="overflow-x-auto pb-6 table w-full">
                        <v-table
                            class="text-sm text-left"
                            density="compact"
                            style="width: 150rem"
                        >
                            <thead
                                class="text-xs uppercase thead"
                                style="height: 3.5rem !important"
                            >
                                <tr>
                                    <th scope="col" class="py-3 text-start">
                                        #
                                    </th>
                                    <th scope="col" class="py-3 text-start">
                                        Product
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-start"
                                    >
                                        Quentity
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-start"
                                    >
                                        Price
                                    </th>
                                    <!-- <th scope="col" class="px-6 py-3 text-start">
                                قیمت فی واحد
                            </th> -->
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-start"
                                    >
                                        SubTotal
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-end">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    style="
                                        height: 2px !important;
                                        border: 3px solid red;
                                    "
                                    v-for="(pro, index) in EarningRepository
                                        .earning.SaleDetails"
                                    :key="index"
                                >
                                    <td class="text-start">
                                        {{ index + 1 }}
                                    </td>
                                    <td class="text-start">
                                        {{ pro.product.product_name }}
                                    </td>

                                    <td class="text-center">
                                        <v-text-field
                                            v-model="pro.quantity"
                                            variant="outlined"
                                            density="compact"
                                            type="number"
                                            style="
                                                width: 8rem;
                                                margin-top: 15px;
                                            "
                                        ></v-text-field>
                                    </td>
                                    <td class="text-center">
                                        <!-- 
                                    <v-text-field
                                        v-model="pro.price"
                                        variant="outlined"
                                        density="compact"
                                        style="width: 12rem; margin-top: 15px"
                                        :prefix="formData.currency?.code"
                                        label="price"
                                    >
                                      

                                    
                                    </v-text-field> -->

                                        <v-text-field
                                            v-model="pro.unit_price"
                                            variant="outlined"
                                            density="compact"
                                            label="price"
                                            type="number"
                                            style="
                                                width: 12rem;
                                                margin-top: 15px;
                                            "
                                        >
                                            <span class="bg-[#ecf1f4] span">
                                                {{ formData.currency?.code }}
                                            </span>
                                        </v-text-field>
                                    </td>

                                    <!-- <td
                                class="px-3 py-3 text-center"
                                
                            > -->
                                    <!-- <v-text-field
                                    v-model="pro.subtotal"
                                    
                                    variant="outlined"
                                    density="compact"
                                    style="width: 12rem"
                                >
                                    <span class="bg-[#ecf1f4] span" dir="ltr">{{
                                        pro.symbol
                                    }}</span>
                                </v-text-field> -->
                                    <!-- </td> -->
                                    <td class="text-start">
                                        <span
                                            >{{ pro.total }}
                                            {{ formData.currency?.code }}
                                        </span>
                                        <!-- <span>{{ multiple(pro) }}</span> -->
                                    </td>

                                    <td class="py-3 px-6 text-end">
                                        <v-icon
                                            color="red"
                                            @click="removeProduct(index)"
                                            class="mdi mdi-trash-can-outline"
                                        ></v-icon>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                </v-row>

                <div class="pt-12 discount">
                    <div>
                        <table class="custom-table">
                            <tr>
                                <td>Total</td>
                                <td>
                                    {{ formData.total }}
                                </td>
                                <td style="text-align: center">
                                    <span class="bg-[#ecf1f4]">
                                        {{ formData.currency?.code }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td>
                                    {{ formData.discount }}
                                </td>
                                <td style="text-align: center">
                                    <span class="bg-[#ecf1f4]">
                                        {{ formData.currency?.code }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Grand Total</td>
                                <td>{{ formData.total_amount }}</td>
                                <td
                                    style="text-align: center; cursor: pointer"
                                    @click="swapeCurrency"
                                >
                                    <span class="bg-[#ecf1f4]">
                                        {{ formData.currency?.code }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="w-16 px-2 py-2 text-center justify-center">
                        <!-- <v-text-field
                        v-model="formData.discount"
                        variant="outlined"
                        density="compact"
                        style="width: 20rem"
                        prefix="USD"
                        label="Discount"
                    >
                    </v-text-field> -->

                        <v-text-field
                            v-model="formData.discount"
                            variant="outlined"
                            density="compact"
                            label="Discount"
                            type="number"
                            style="width: 15rem"
                        >
                            <span class="bg-[#ecf1f4] span">
                                {{ formData.currency?.code }}
                            </span>
                        </v-text-field>
                    </div>
                </div>

                <div class="d-flex mt-8 pt-8">
                    <v-textarea
                        v-model="formData.description"
                        class="textArea"
                        label="Note"
                        variant="outlined"
                    >
                    </v-textarea>
                </div>
                <div class="d-flex mb-1 mx-6">
                    <v-btn color="#112F53" @click="UpdateEarning">
                        &nbsp; &nbsp; Save &nbsp; &nbsp;</v-btn
                    >
                </div>
            </v-form>
        </div>
    </div>
</template>



<script setup>
// import Menu fr   om "@/components/UI/Menu.vue";
import { reactive, computed, ref, watch, onMounted } from "vue";

// import CreateProduct from "../products/UpdateEarningProduct.vue";
import { useRoute } from "vue-router";
import { useEarningRepository } from "@/store/EarningRepository";
const EarningRepository = useEarningRepository();

// EarningRepository.earning.earningDetails = [];

const formRef = ref(null);
const loading = ref(true);


let formData = reactive({
    id:"",
    currency: "",
    SaleDetails: [],  // Ensure SaleDetails is reactive
    total_amount: "",
    discount: "",
    total: "",
    currencyIndex: "",
    date: "",
    note: "",
    customer_id: "",
    currency_id: "",
    deletedIds: [],  // Track deleted products
});

const CalcFetchProduct = (product) => {
    const newProduct = reactive({
        ...product,
        quantity: 1,
        sale_products_id : product.id,
        unit_price: 1,
        total: 1,
        product: {
            product_name: product.product_name,
            id: product.id,
        },
    });

    EarningRepository.earning.SaleDetails.push(newProduct);
    clearSearch();
};


const clearSearch = () => {
    EarningRepository.earningSearch = ""; // Clear repository's search
    EarningRepository.searchFetch = [];
};

const removeProduct = (index) => {
    const removedProduct = EarningRepository.earning.SaleDetails[index];
    
    // Check if the product has a sales_id and track it in the deletedIds array
    if (removedProduct && removedProduct.sales_id) {
        formData.deletedIds.push(removedProduct.sales_id);
    } 

    // Remove the product from SaleDetails
    EarningRepository.earning.SaleDetails.splice(index, 1);
};





const routeParams = useRoute();


const swapeCurrency = () => {
    formData.currencyIndex =
        (formData.currencyIndex + 1) % EarningRepository.currencies.length;

    formData.currency = EarningRepository.currencies[formData.currencyIndex];

    // console.log(EarningRepository.currencies[EarningRepository.currencyIndex])
};

const multiple = (pro) => {
    const add = pro.quantity * pro.unit_price;
    return add || 0;
};
watch(
    () => formData.SaleDetails,
    () => {
        formData.SaleDetails.forEach((product) => {
            // Update the 'subtotal' property for each service
            product.total = multiple(product);
        });
    },
    { deep: true }
);

const total = computed(() => {
    return formData.SaleDetails.reduce((acc, product) => {
        return acc + (product.quantity * product.unit_price || 0);
    }, 0);
});

// Watch total changes to update total_amount
watch(
    total,
    (newTotal) => {
        formData.total = newTotal;
        formData.total_amount = newTotal - formData.discount;
    }
);


watch(
    () => formData.discount, // Watch the discount value
    (newDiscount) => {
        formData.total_amount = formData.total - newDiscount;
    }
);

const rules = {
    required: (value) => !!value || "This field is required",
    name: (value) =>
        /^[a-zA-Z\u0600-\u06FF\s]*$/.test(value) || "Name is required",
};

const UpdateEarning = async () => {
    // const isValid = await formRef.value.validate();

    // if (isValid) {
        formData.currency_id = formData.currency.id;
        await EarningRepository.UpdateEarning(formData.id, formData);
    // }
};

// Function to initialize the currency after fetching
const initializeFormData = async () => {
    await EarningRepository.fetchCustomers();
    await EarningRepository.fetchCurrencies();

    // Update formData properties instead of re-wrapping it with reactive()
    formData.currency = EarningRepository.earning.currency || {};
    formData.id = EarningRepository.earning.id || {};
    formData.SaleDetails = EarningRepository.earning.SaleDetails || [];
    formData.total_amount = parseFloat(EarningRepository.earning.total_amount);
    formData.discount = parseFloat(EarningRepository.earning.discount) || 0;
    formData.total = formData.total_amount + formData.discount;
    formData.currencyIndex = parseInt(EarningRepository.currentIndex) || 0;
    formData.date = EarningRepository.earning.date || '';
    formData.note = EarningRepository.earning.description || '';
    formData.customer_id = parseInt(EarningRepository.earning.customer_id) || null;
    formData.currency_id = parseInt(formData.currency.id) || null;
};

formData.date = EarningRepository.getTodaysDate();

onMounted(async () => {
    loading.value = true; // Start loading

    await EarningRepository.fetchEarning(routeParams.params.id);
    await initializeFormData();

    loading.value = false; // Data is fetched, stop loading
});


// ==================================

// swapCurrency(previousCurrencyId, i) {
//             console.log(previousCurrencyId)

//             this.details[i].currentIndexPrice =
//                 (this.details[i].currentIndexPrice + 1) % this.currencies.length;
//             // console.log(this.currencies[this.details[i].currentIndexPrice]);

//             this.details[i].price_currency_id =
//                 this.currencies[this.details[i].currentIndexPrice].id;

//             this.details[i].Net_price =
//                 (this.details[i].Net_price /
//                     this.currencies[previousCurrencyId].rate) *
//                 this.currencies[this.details[i].currentIndexPrice].rate;

//             this.details[i].average_cost =
//                 (this.details[i].average_cost /
//                     this.currencies[previousCurrencyId].rate) *
//                 this.currencies[this.details[i].currentIndexPrice].rate;

//             this.Update_Detail();

//         },

// changeTheGeneralCurrency(previousCurrencyId) {
//             this.totalCurrencyId =
//                 (this.totalCurrencyId + 1) % this.currencies.length;
//             this.sale.discount =
//                 (this.sale.discount /
//                     this.currencies[previousCurrencyId].rate) *
//                 this.currencies[this.totalCurrencyId].rate;
//             this.sale.TaxNet =
//                 (this.sale.TaxNet / this.currencies[previousCurrencyId].rate) *
//                 this.currencies[this.totalCurrencyId].rate;
//             this.sale.shipping =
//                 (this.sale.shipping /
//                     this.currencies[previousCurrencyId].rate) *
//                 this.currencies[this.totalCurrencyId].rate;

//             this.generalCurrencyId = this.currencies[this.totalCurrencyId].id;
//             // detail.currentIndex
//             this.Calcul_Total();
//             this.details.forEach((element, index) => {
//                 // console.log(element)
//                 this.swapCurrency(element.currentIndex, index);
//             });

// @click="
//                                                                                 swapCurrency(
//                                                                                     detail.currentIndexPrice,
//                                                                                     index
//                                                                                 )
//                                                                             "
// const totalDiscount = computed(() => {
//     return EarningRepository.earning.earningDetails.reduce((total, product) => {
//         const totalDiscount = total + Number(product.discount || 0);
//         formData.discount = totalDiscount;
//         return totalDiscount;
//     }, 0);
// });
// const finalAmount = computed(() => {
//     const FinalAmount = formData.total - formData.discount;
//     console.log(finalAmount);
// formData.finalAmount = FinalAmount;
// return FinalAmount;
// });
</script>

<style scoped>
/* background-color: green; */
/* .card {
    margin: 1.4rem;
    padding: 1.4rem;
} */
.all-expense {
    width: 100%;
}

/* .input > :nth-child(1) > :nth-child(1) {
    height: 3rem;
    border: none;
} */
/* .v-input > :nth-child(1) > :nth-child(1) {
    direction: rtl !important; */
/* } */
/* color: #5784c8; */
/* .total {
    border-top: 2px dashed #d3e2f8;
    border-bottom: 2px dashed #d3e2f8;
} */
/* .product-table {
    display: flex;
    justify-content: space-between;
    background-color: #ecf1f4;
    border-right: 4px solid #fecd07;
} */
/* .table-row {
    display: flex;
    justify-content: space-between;
    padding: 20px;
} */
/* .table {
    background-color: #fecd07;
    width: 120rem;
} */
/* .thead {
    border-right: 4px solid #fecd07;
    background-color: #ecf1f4;
    max-width: 120rem;
}
.v-input__control {
    width: 70rem;
}
.discount {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
} */

.discount {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
}

.custom-table {
    /* border-collapse: collapse; */

    width: 350px;
    /* border: 1px solid #ecf1f4; */
    margin-top: 20px;
}

.custom-table td {
    border: 2px solid #ecf1f4;
    padding: 4px;
    text-align: left;
}

.custom-table tr td:first-child {
    font-weight: bold;
}
.custom-table > :nth-child(3) {
    background-color: #f8f8f8;
}

.selected-item:hover {
    background-color: #d3d3d3; /* Light grey hover color */
    font-weight: bold; /* Make text bold on hover */
}

.span {
    background-color: #ecf1f4;

    position: absolute;
    right: 1px; /* Adjust this value to change the distance from the left edge */
    top: 50%;
    transform: translateY(-50%);
    padding: 0.5rem 0.6rem;
}

.table tbody tr:hover {
    background-color: #f1f1f1; /* light gray background */
}

.table thead tr {
    background: #ecf1f4 !important;
}

/* ===================== */
</style>
