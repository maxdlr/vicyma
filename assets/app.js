import {registerVueControllerComponents} from '@symfony/ux-vue';
import './bootstrap.js';

/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

document.addEventListener('vue:before-mount', (event) => {
    const {
        componentName, // The Vue component's name
        component, // The resolved Vue component
        props, // The props that will be injected to the component
        app, // The Vue application instance
    } = event.detail;

    app.use();
});


registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));