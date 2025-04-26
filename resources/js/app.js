import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Items from './components/Items.vue';

// Create the router
const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/items', component: Items }
    ]
});

// Create the app
const app = createApp({
    data() {
        return {
            showItemSection: false
        }
    }
});

// Register components
app.component('items-component', Items);

// Use the router
app.use(router);

// Mount the app
app.mount('#app');
