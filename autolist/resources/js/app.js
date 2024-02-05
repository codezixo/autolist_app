

import './bootstrap';
import { createApp } from 'vue';
import {createRouter, createWebHashHistory} from 'vue-router';


import App from './components/App.vue';
import AutolistIndex from './components/autolist/AutolistIndex.vue';
import AutolistNew from './components/autolist/AutolistNew.vue';
import AutolistEdit from './components/autolist/AutolistEdit.vue';

// app.component('example-component', ExampleComponent);

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            component: AutolistIndex,
            name: 'listAuto'
        },
        {
            path: '/create',
            component: AutolistNew,
            name: 'createAuto'
        },
        {
            path: '/edit/:id',
            component: AutolistEdit,
            name: "editAuto"
        }
    ],
})

const app = createApp(App);
app.use(router);
app.mount('#app');
