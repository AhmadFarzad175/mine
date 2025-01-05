<template>
    <v-container class="icon toolbar" fluid>
        <div class="d-flex justify-space-between align-center">
            <!-- Breadcrumbs on the left -->
            <v-breadcrumbs :items="breadcrumbs" divider="-">
                <template v-slot:item="{ item }">
                    <span v-if="item.to" text >{{
                        item.text
                    }}</span>
                    <span v-else>{{ item.text }}</span>
                </template>
            </v-breadcrumbs>
        </div>

        <!-- Icons on the right -->
        <div class="toolbar">
            <v-btn
                icon
                style="background-color: #f8f8f8"
                @click="toggleMaximize"
                aria-label="Toggle Fullscreen"
            >
                <v-icon>{{
                    isMaximized ? "mdi-fullscreen-exit" : "mdi-fullscreen"
                }}</v-icon>
            </v-btn>

            <!-- Language Selector Button with Dropdown -->
            <v-menu offset-y>
                <template #activator="{ props }">
                    <v-btn
                        v-bind="props"
                        icon
                        style="background-color: #f8f8f8"
                        aria-label="Change Language"
                    >
                        <v-icon>mdi-web</v-icon>
                    </v-btn>
                </template>

                <v-list style="width: 150px">
                    <v-list-item @click="changeLanguage('en')">
                        <span class="language-item">
                            <img
                                src="/public/flags/usa.png"
                                alt="USA"
                                class="flag"
                            />
                            <span>English</span>
                        </span>
                    </v-list-item>
                    <v-list-item @click="changeLanguage('fa')">
                        <span class="language-item">
                            <img
                                src="/public/flags/afg.png"
                                alt="Afghanistan"
                                class="flag"
                            />
                            <span>Dari</span>
                        </span>
                    </v-list-item>
                </v-list>
            </v-menu>

            <v-btn
                icon
                style="background-color: #f8f8f8"
                aria-label="Notifications"
            >
                <v-icon>mdi-bell-ring-outline</v-icon>
            </v-btn>
        </div>
    </v-container>
</template>

<script>
import { useI18n } from "vue-i18n";
export default {
    name: "toolbar",

    setup() {
        const { locale } = useI18n();

        const changeLanguage = (lang) => {
            locale.value = lang;
            localStorage.setItem("language", lang);
        };

        return { changeLanguage, locale };
    },

    data() {
        return {
            breadcrumbs: [],
            isMaximized: false, // Initialize fullscreen state
        };
    },

    methods: {
        toggleMaximize() {
            if (!this.isMaximized) {
                this.enterFullscreen();
            } else {
                this.exitFullscreen();
            }
            this.isMaximized = !this.isMaximized;
        },

        enterFullscreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) elem.requestFullscreen();
            else if (elem.webkitRequestFullscreen)
                elem.webkitRequestFullscreen();
            else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
        },

        exitFullscreen() {
            if (document.exitFullscreen) document.exitFullscreen();
            else if (document.webkitExitFullscreen)
                document.webkitExitFullscreen();
            else if (document.msExitFullscreen) document.msExitFullscreen();
        },

        updateBreadcrumbs() {
            // Get matched routes from the current route
            const matchedRoutes = this.$route.matched;

            // Initialize the breadcrumbs array
            this.breadcrumbs = [];

            // Loop through matched routes and extract breadcrumbs
            matchedRoutes.forEach((route) => {
                if (route.meta.breadcrumb) {
                    this.breadcrumbs.push(...route.meta.breadcrumb);
                }
            });
        },

        formatBreadcrumbText(segment) {
            return segment
                .replace(/-/g, " ")
                .replace(/\b\w/g, (char) => char.toUpperCase());
        },
    },

    watch: {
        $route: "updateBreadcrumbs",
    },

    created() {
        const savedLanguage = localStorage.getItem("language") || "en";
        this.changeLanguage(savedLanguage);
        this.updateBreadcrumbs();
    },
};
</script>
<style scoped>
.toolbar {
    display: flex;
    justify-content: space-between;
    gap: 16px;
}

.language-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.flag {
    width: 24px;
    height: 16px;
    border-radius: 4px;
}
</style>
