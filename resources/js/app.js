import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './views/App';
import Home from './views/Home';
import Emails from './views/Emails';
import CreateEmail from './views/CreateEmail';
import VueQuill from 'vue-quill';

Vue.use(VueRouter, VueQuill);
Vue.config.productionTip = true;

const router = new VueRouter({
    mode: 'history',
    routes: [{
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/emails',
            name: 'emails',
            component: Emails,
        },
        {
            path: '/create-email',
            name: 'create-email',
            component: CreateEmail,
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router,
});
