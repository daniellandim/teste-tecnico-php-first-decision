<script setup>
import { ref } from 'vue';
import Menubar from 'primevue/menubar';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const items = ref([
    {
        label: 'Home',
        icon: 'pi pi-home',
        command: () => {
            router.push('/');
        }
    },
]);

const logout = () => {
    authStore.logout();
    router.push('/login');
};
</script>

<template>
    <div class="layout-wrapper">
        <header>
            <Menubar :model="items">
                <template #start>
                    <span class="logo">TESTE FIRST</span>
                </template>
                <template #end>
                    <div class="flex align-items-center gap-2" v-if="authStore.logged">
                        <span class="username">{{ authStore.user?.name }}</span>
                        <Button label="Sair" icon="pi pi-power-off" severity="danger" text @click="logout" />
                    </div>
                    <div v-else>
                         <Button label="Login" icon="pi pi-user" text @click="router.push('/login')" />
                    </div>
                </template>
            </Menubar>
        </header>

        <main class="layout-content">
            <div class="card">
                <slot></slot>
            </div>
        </main>

        <footer class="layout-footer">
            <p>&copy; 2026 Minha Aplicação. Todos os direitos reservados.</p>
        </footer>
    </div>
</template>

<style scoped>
.layout-wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.layout-content {
    flex: 1;
    padding: 2rem;
    background-color: var(--surface-ground);
}

.card {
    background: var(--surface-card);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 1px -1px rgba(0,0,0,0.2), 0 1px 1px 0 rgba(0,0,0,0.14), 0 1px 3px 0 rgba(0,0,0,0.12);
}

.layout-footer {
    text-align: center;
    padding: 1rem;
    background-color: var(--surface-section);
    border-top: 1px solid var(--surface-border);
}

.logo {
    font-weight: bold;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.username {
    margin-right: 1rem;
    font-weight: 600;
}
</style>
