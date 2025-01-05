import { createApp } from "vue";

// Vuetify
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
// import './assets/style.css'
import { aliases, fa } from "vuetify/iconsets/fa";
import { createVuetify } from "vuetify";
import { createPinia } from "pinia";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import router from "./router";
import i18n from './i18n';

// Components
import App from "./App.vue";

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: "myCustomTheme",
        themes: {
            myCustomTheme: {
                dark: false,
                variables: {},
                colors: {
                    primary: "#112F53",
                    secondary: "#00893F",
                    documentBTN: "#0095E8",
                    headerColor: "#e1e4e7",
                },
            },
        },
        icons: {
            defaultSet: "fa",
            aliases,
            sets: {
                fa,
            },
        },
    },
    
});

const app = createApp(App);
app.use(vuetify);
app.use(router);
app.use(createPinia());
app.use(i18n);
app.mount("#app");
