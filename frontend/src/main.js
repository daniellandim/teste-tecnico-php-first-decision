import {createApp} from 'vue'
import {createPinia} from 'pinia'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import ToastService from 'primevue/toastservice';
import Button from 'primevue/button';
import emitter from '@/config/emitter';

import {configure, defineRule} from 'vee-validate';
import {required, email, max_value, min} from '@vee-validate/rules';
import {localize, setLocale} from '@vee-validate/i18n';
import pt_BR from '@vee-validate/i18n/dist/locale/pt_BR.json';

import App from './App.vue'
import router from './router'

import 'primeicons/primeicons.css'
import './assets/main.css'
import 'primeflex/primeflex.css'


const app = createApp(App)
app.config.globalProperties.emitter = emitter;
app.use(createPinia())
app.use(PrimeVue, {
  theme: {
    preset: Aura
  }
});
app.use(ToastService);
app.use(router)

app.component('Button', Button);

defineRule('required', required);
defineRule('email', email);
defineRule('max_value', max_value);
defineRule('min', min);
configure({
  generateMessage: localize({pt_BR}),
  validateOnBlur: false,
  validateOnChange: false,
  validateOnModelUpdate: false,
});
setLocale('pt_BR');

app.mount('#app')
