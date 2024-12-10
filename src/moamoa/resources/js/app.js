require('./bootstrap');
import { createApp } from 'vue';
import AppComponent from '../views/components/AppComponent.vue';
import router from './router';
import store from './store/store';

createApp({
    components: {
        AppComponent,
    }
})
.use(store)
.use(router)
.mount('#app');