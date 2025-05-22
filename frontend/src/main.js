import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

// Importar Bootstrap CSS (via CDN no index.html)
// Importar FontAwesome (via CDN no index.html)

// Criar e montar a aplicação Vue
const app = createApp(App);

app.use(router);
app.use(store);

app.mount('#app');