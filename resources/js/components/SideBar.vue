<template>
    <v-card app flat>
        <v-layout class="full-height overflow-hidden">
            <v-navigation-drawer
                absolute
                permanent
                v-model="drawer"
                :rail="rail"
                @click="rail = false"
                class="full-height sidebar_navigation_drawer"
            >
                <div class="logo-container">
                    <img
                        src="./../assets/navLogo/logo black (1) 3.png"
                        alt="Logo"
                        class="logo rounded-full"
                        max-width="24"
                        max-height="24"
                    />
                </div>

                <transition name="slide-fade">
                    <div class="scrollable-content custom-scrollbar">
                        <v-list density="default" nav>
                            <!-- dashboard start here -->
                            <v-list-item
                                v-if="
                                    AuthRepository.permissions.includes(
                                        'dashboard_view'
                                    )
                                "
                                prepend-icon="mdi-view-dashboard"
                                title="Dashboard"
                                color="#112F53"
                                :class="{ 'active-item': isDashboardVisible }"
                                @click="toggleDashboard"
                            ></v-list-item>
                            <!-- dashboard end here -->

                            <!-- extract and its item start here -->
                            <v-list-item
                                prepend-icon="mdi-cart"
                                title="Extract"
                                color="#112F53"
                                :class="{ 'active-item': isExtractVisible }"
                                @click="toggleExtract"
                                v-if="hasPermissionSale"
                            ></v-list-item>

                            <v-list v-if="isExtractVisible">
                                <router-link
                                    v-for="item in AuthRepository.menubar
                                        .menuExtract"
                                    :key="item.id"
                                    :to="item.route"
                                    class="no-underline menu-title"
                                    :class="{
                                        'active-item': (selectedExtract ==
                                            item.id),
                                    }"
                                    exact-active-class="bg-primary text-white"
                                    @click="setSelectedExtract(item.id)"
                                >
                                    <v-list-item
                                        v-if="
                                            AuthRepository.permissions.includes(
                                                item.permission
                                            )
                                        "
                                        :title="item.title"
                                        :prepend-icon="item.icon"
                                        color="#112F53"
                                        :class="{
                                            'active-sub-item':
                                                selectedExtract === item.id,
                                        }"
                                        class="child submenu-item"
                                    />
                                </router-link>
                            </v-list>
                            <!-- extract and its item end here -->

                            <!-- sales and its item start here -->
                            <v-list-item
                                prepend-icon="mdi-cart"
                                title="Sales"
                                color="#112F53"
                                :class="{ 'active-item': isSaleVisible }"
                                @click="toggleSale"
                                v-if="hasPermissionSale"
                            ></v-list-item>

                            <v-list v-if="isSaleVisible">
                                <router-link
                                    v-for="item in AuthRepository.menubar
                                        .menuSale"
                                    :key="item.id"
                                    :to="item.route"
                                    class="no-underline menu-title"
                                    :class="{
                                        'active-item': selectedSale === item.id,
                                    }"
                                    exact-active-class="bg-primary text-white"
                                    @click="setSelectedSale(item.id)"
                                >
                                    <v-list-item
                                        v-if="
                                            AuthRepository.permissions.includes(
                                                item.permission
                                            )
                                        "
                                        :title="item.title"
                                        :prepend-icon="item.icon"
                                        color="#112F53"
                                        :class="{
                                            'active-sub-item':
                                                selectedSale === item.id,
                                        }"
                                        class="child submenu-item"
                                    />
                                </router-link>
                            </v-list>
                            <!-- sales and its item end here -->

                            <!-- Expense Menu Start -->
<v-list-item
    title="Expense"
    prepend-icon="mdi-account-multiple"
    color="#112F53"
    :class="{ 'active-item': isExpenseVisible }"
    @click="toggleExpense"
    v-if="hasExpensePermission"
/>

<v-list v-if="isExpenseVisible">
    <router-link
        v-for="item in AuthRepository.menubar.menuExpense"
        :key="item.id"
        :to="item.route"
        class="no-underline menu-title"
        :class="{ 'active-item': selectedExpense === item.id }"
        exact-active-class="bg-primary text-white"
        @click="setSelectedExpense(item.id)"
    >
        <v-list-item
            v-if="AuthRepository.permissions.includes(item.permission)"
            :title="item.title"
            :prepend-icon="item.icon"
            color="#112F53"
            :class="{ 'active-sub-item': selectedExpense === item.id }"
            class="child submenu-item"
        />
    </router-link>
</v-list>
<!-- Expense Menu End -->


                            <!-- people start here -->
                            <v-list-item
                                title="People"
                                prepend-icon="mdi mdi-card-account-details-outline"
                                color="#112F53"
                                :class="{ 'active-item': isPeopleVisible }"
                                v-if="hasPermissionPeople"
                                @click="togglePeople"
                            ></v-list-item>

                            <v-list v-if="isPeopleVisible">
                                <router-link
                                    v-for="item in AuthRepository.menubar
                                        .menuPeople"
                                    :key="item.id"
                                    :to="item.route"
                                    class="no-underline menu-title"
                                    :class="{
                                        'active-item':
                                            selectedPeople === item.id,
                                    }"
                                    exact-active-class="bg-primary text-white"
                                    @click="setSelectedPeople(item.id)"
                                >
                                    <v-list-item
                                        v-if="
                                            AuthRepository.permissions.includes(
                                                item.permission
                                            )
                                        "
                                        :title="item.title"
                                        :prepend-icon="item.icon"
                                        color="#112F53"
                                        :class="{
                                            'active-sub-item':
                                                selectedPeople === item.id,
                                        }"
                                        class="child submenu-item"
                                    />
                                </router-link>
                            </v-list>
                            <!-- people end here -->

                            <!-- report start here -->
                            <v-list-item
                                title="Report"
                                prepend-icon="mdi-finance"
                                color="#112F53"
                                :class="{ 'active-item': isReportVisible }"
                                @click="toggleReport"
                                v-if="hasPermissionReport"
                            ></v-list-item>

                            <v-list v-if="isReportVisible">
                                <router-link
                                    v-for="item in AuthRepository.menubar
                                        .menuReport"
                                    :key="item.id"
                                    :to="item.route"
                                    class="no-underline menu-title"
                                    :class="{
                                        'active-item':
                                            selectedReport === item.id,
                                    }"
                                    exact-active-class="bg-primary text-white"
                                    @click="setSelectedReport(item.id)"
                                >
                                    <v-list-item
                                        v-if="
                                            AuthRepository.permissions.includes(
                                                item.permission
                                            )
                                        "
                                        :title="item.title"
                                        :prepend-icon="item.icon"
                                        color="#112F53"
                                        :class="{
                                            'active-sub-item':
                                                selectedReport === item.id,
                                        }"
                                        class="child submenu-item"
                                    />
                                </router-link>
                            </v-list>
                            <!-- report end here -->

                            <!-- setting start here -->
                            <v-list-item
                                :title="$t('settings')"
                                prepend-icon="mdi-cog-outline"
                                color="#112F53"
                                :class="{ 'active-item': isSettingVisible }"
                                @click="toggleSetting"
                                v-if="hasPermissionSetting"
                            />

                            <v-list v-if="isSettingVisible">
                                <router-link
                                    v-for="item in AuthRepository.menubar
                                        .menuSetting"
                                    :key="item.id"
                                    :to="item.route"
                                    class="no-underline menu-title"
                                    :class="{
                                        'active-item':
                                            selectedSetting === item.id,
                                    }"
                                    exact-active-class="bg-primary text-white"
                                    @click="setSelectedSetting(item.id)"
                                >
                                    <v-list-item
                                        v-if="
                                            AuthRepository.permissions.includes(
                                                item.permission
                                            )
                                        "
                                        :title="item.title"
                                        :prepend-icon="item.icon"
                                        color="#112F53"
                                        :class="{
                                            'active-sub-item':
                                                selectedSetting === item.id,
                                        }"
                                        class="child submenu-item"
                                    />
                                </router-link>
                            </v-list>
                            <!-- setting end here -->
                        </v-list>
                    </div>
                </transition>

                <v-list density="default" class="footer-nav" nav>
                    <v-divider
                        :thickness="2"
                        class="border-opacity-50"
                    ></v-divider>
                    <div>
                        <v-card
                            class="mx-auto transparent-card"
                            max-width="425"
                        >
                            <v-list lines="two">
                                <v-menu
                                    activator="parent"
                                    origin="bottom left"
                                    transition="scale-transition"
                                    offset-y
                                    absolute
                                >
                                    <template #activator="{ props }">
                                        <v-list-item
                                            v-bind="props"
                                            prepend-avatar="https://cdn.vuetifyjs.com/images/lists/1.jpg"
                                            title="Khalid Atayee"
                                            class="cursor-pointer"
                                        >
                                            <template #subtitle>
                                                khalid@gmail.com
                                            </template>
                                        </v-list-item>
                                    </template>

                                    <v-card class="dropdown-card">
                                        <v-list>
                                            <v-list-item
                                                @click="navigateToProfile"
                                                class="menu-item"
                                            >
                                                <v-icon class="menu-icon"
                                                    >mdi-account-circle</v-icon
                                                >
                                                <span class="menu-text"
                                                    >Profile</span
                                                >
                                            </v-list-item>

                                            <v-list-item
                                                @click="logout"
                                                class="menu-item"
                                            >
                                                <v-icon class="menu-icon"
                                                    >mdi-logout</v-icon
                                                >
                                                <span class="menu-text">{{
                                                    $t("logout")
                                                }}</span>
                                            </v-list-item>
                                        </v-list>
                                    </v-card>
                                </v-menu>
                            </v-list>
                        </v-card>
                    </div>
                </v-list>
            </v-navigation-drawer>

            <v-divider></v-divider>
            <v-main class="main-content"> </v-main>
        </v-layout>
    </v-card>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router"; // Import useRouter
import { useAuthRepository } from "./../store/AuthRepository";
let AuthRepository = useAuthRepository();

const hasPermission = AuthRepository.hasPermission;

// console.log(AuthRepository.permissions, 'ppermissions')

const hasExpensePermission = computed(() => {
    return AuthRepository.menubar.menuExpense.some((item) =>
        AuthRepository.permissions.includes(item.permission)
    );
});
const hasPermissionSale = computed(() => {
    return AuthRepository.menubar.menuSale.some((item) =>
        AuthRepository.permissions.includes(item.permission)
    );
});

const hasPermissionPeople = computed(() => {
    return AuthRepository.menubar.menuPeople.some((item) =>
        AuthRepository.permissions.includes(item.permission)
    );
});

const hasPermissionReport = computed(() => {
    return AuthRepository.menubar.menuReport.some((item) =>
        AuthRepository.permissions.includes(item.permission)
    );
});

const hasPermissionSetting = computed(() => {
    return AuthRepository.menubar.menuSetting.some((item) =>
        AuthRepository.permissions.includes(item.permission)
    );
});

const router = useRouter(); // Initialize the router
const drawer = ref(true);
const rail = ref(false);
const isExtractVisible = ref(false);
const isSaleVisible = ref(false);
const isDashboardVisible = ref(false);
const isExpenseVisible = ref(false);
const isPeopleVisible = ref(false);
const selectedExtract = ref(null);
const selectedSale = ref(null);
const selectedExpense = ref(null);
const selectedPeople = ref(null);
const selectedReport = ref(null);
const isReportVisible = ref(false);
const selectedSetting = ref(null);
const isSettingVisible = ref(false);

const navigateToProfile = () => {
    router.push("/profile");
};

const toggleExtract = () => {
    isExtractVisible.value = !isExtractVisible.value;
    if (isExtractVisible.value) {
        selectedExtract.value = null;
    }

    isSaleVisible.value = false;
    selectedSale.value = null;

    isExpenseVisible.value = false;
    selectedExpense.value = null;
    isDashboardVisible.value = false;

    isPeopleVisible.value = false;
    selectedPeople.value = null;
    isReportVisible.value = false;
    selectedReport.value = null;
    isSettingVisible.value = false;
    selectedSetting.value = null;
};

const toggleSale = () => {
    isSaleVisible.value = !isSaleVisible.value;
    if (isSaleVisible.value) {
        selectedSale.value = null;
    }
    isExtractVisible.value = false;
    selectedExtract.value = null;

    isExpenseVisible.value = false;
    selectedExpense.value = null;
    isDashboardVisible.value = false;

    isPeopleVisible.value = false;
    selectedPeople.value = null;
    isReportVisible.value = false;
    selectedReport.value = null;
    isSettingVisible.value = false;
    selectedSetting.value = null;
};
const toggleDashboard = () => {
    isDashboardVisible.value = true;
    isExtractVisible.value = false;
    selectedExtract.value = null;
    isSaleVisible.value = false;
    isExpenseVisible.value = false;
    selectedSale.value = null;
    selectedExpense.value = null;
    isReportVisible.value = false;
    selectedReport.value = null;
    isSettingVisible.value = false;
    selectedSetting.value = null;

    // Programmatically navigate to the dashboard route
    router.push("/dashboard");
};

const toggleExpense = () => {
    isExpenseVisible.value = !isExpenseVisible.value;
    if (isExpenseVisible.value) {
        selectedExpense.value = null;
    }
    isExtractVisible.value = false;
    selectedExtract.value = null;

    isSaleVisible.value = false;
    selectedSale.value = null;
    isDashboardVisible.value = false;
    isPeopleVisible.value = false;
    selectedPeople.value = null;
    isReportVisible.value = false;
    selectedReport.value = null;
    isSettingVisible.value = false;
    selectedSetting.value = null;
};

const logout = (currentRoute) => {
    // Clear session data
    sessionStorage.clear();

    // Navigate to login with redirect query
    router.push({ path: "/login", query: { redirect: currentRoute.fullPath } });
};

const toggleReport = () => {
    isReportVisible.value = !isReportVisible.value;
    isDashboardVisible.value = false;
    isExtractVisible.value = false;
    selectedExtract.value = null;
    isSaleVisible.value = false;
    isExpenseVisible.value = false;
    selectedExpense.value = null;
    selectedSale.value = null;
    isPeopleVisible.value = false;
    selectedPeople.value = null;
    isSettingVisible.value = false;
    selectedSetting.value = null;
};

const togglePeople = () => {
    isPeopleVisible.value = !isPeopleVisible.value;
    isDashboardVisible.value = false;
    isExtractVisible.value = false;
    selectedExtract.value = null;
    isSaleVisible.value = false;
    isExpenseVisible.value = false;
    selectedExpense.value = null;
    selectedSale.value = null;
    isReportVisible.value = false;
    selectedReport.value = null;
    isSettingVisible.value = false;
    selectedSetting.value = null;
};

const toggleSetting = () => {
    isSettingVisible.value = !isSettingVisible.value;
    isDashboardVisible.value = false;
    isExtractVisible.value = false;
    selectedExtract.value = null;
    isSaleVisible.value = false;
    isExpenseVisible.value = false;
    selectedExpense.value = null;
    selectedSale.value = null;
    isPeopleVisible.value = false;
    selectedPeople.value = null;
    isReportVisible.value = false;
    selectedReport.value = null;
};

const setSelectedExtract = (id) => {
    selectedExtract.value = id;
};

const setSelectedSale = (id) => {
    selectedSale.value = id;
};

const setSelectedExpense = (id) => {
    selectedExpense.value = id;
};

const setSelectedPeople = (id) => {
    selectedPeople.value = id;
};

const setSelectedReport = (id) => {
    selectedReport.value = id;
};

const setSelectedSetting = (id) => {
    selectedSetting.value = id;
};
</script>

<style scoped>
.full-height {
    height: 100vh;
}

.logo-container {
    display: flex;
    justify-content: center;
    padding: 16px;
}

.logo {
    object-fit: contain;
}

.no-underline {
    text-decoration: none;
}

.main-content {
    min-height: 100vh;
}

.child {
    text-decoration: none;
}

.active-item {
    font-weight: bold;
    background-color: #112f53 !important;
    color: white !important;
}

.active-sub-item {
    /* background-color: #e0f7fa; */
    font-weight: 900;
    border-radius: 4px;
    font-size: 30px !important;
}

.menu-title > *,
.submenu-item > * {
    font-size: 30px;
}

/* .submenu-item > * {
  font-size: 25px;
  opacity: 0.5;
} */

.submenu-item > * {
    font-size: 30px;
    opacity: 1; /* Set full opacity for all submenu items */
    font-weight: 900;
    color: #112f53;
}

.submenu-item > :nth-child(2) {
    width: 50px;
}

.submenu-item:not(.active-sub-item) > * {
    font-weight: 100;
    color: #b3bcc7; /* Reduce opacity for non-active submenu items */
}

.v-list-item {
    color: black;
}

.transparent-card {
    background-color: transparent !important;
    box-shadow: none !important;
}

/* Custom Scrollbar Styles for RTL */
.scrollable-content {
    /* margin-top: 25%; */
    padding-top: 50px; /* Adjust to lower the menus */
    height: calc(100vh - 130px); /* Make space for logo and footer */
    overflow-y: auto;
    /* direction: ltr; */
}
.scrollable-content::-webkit-scrollbar {
    width: 4px;
}

.scrollable-content::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.scrollable-content::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.scrollable-content::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Dropdown menu styling */
.dropdown-card {
    min-width: 200px;
    padding: 0;
    border: 1px solid #e0e0e0;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* Styling for menu items */
.menu-item {
    display: flex;
    align-items: center;
    /* padding: 10px 15px; */
    transition: background-color 0.2s ease;
}

.menu-item:hover {
    background-color: #f5f5f5;
    cursor: pointer;
}

/* Aligning icon and text */
.menu-icon {
    margin-right: 20px; /* Increased space between icon and text */
    font-size: 20px;
    color: #606060;
}

.menu-text {
    flex: 1;
    color: #333333;
    font-size: 16px;
}

/* Styling the avatar */
.footer-nav .v-list-item__avatar {
    border-radius: 50%;
    border: 2px solid #e0e0e0;
    overflow: hidden;
}

/* Ensure dropdown visibility */
.footer-nav .v-menu__content {
    z-index: 2000;
}
</style>

<!-- \******************************** -->
