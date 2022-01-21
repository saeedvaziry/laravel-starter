require('./bootstrap');
require('./icons');

// Import modules...
import {createApp} from 'vue';
import {plugin as InertiaPlugin} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

// import app modules
import Root from './root';
import Filters from './filters';

// create app
const app = createApp(Root);

// mixins
app.mixin({methods: {route}})

// plugins
app.use(InertiaPlugin)

// components
app.component('fa-icon', FontAwesomeIcon)

// filters
app.config.globalProperties.$filters = Filters;

// mount app
app.mount('#app');

// initial inertia progress
InertiaProgress.init({
    color: '#4179f5'
});
