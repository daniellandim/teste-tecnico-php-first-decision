<script setup>
import {RouterView, useRoute} from 'vue-router'
import Toast from 'primevue/toast';
import {useToast} from 'primevue/usetoast';
import emitter from '@/config/emitter';
import AppLayout from '@/components/AppLayout.vue';
import { computed } from 'vue';

const toast = useToast();
const route = useRoute();

emitter.on('*', (type, event) => {
  if (['error', 'success', 'warn'].includes(type)) {
    toast.add({severity: type, summary: event, life: type === 'error' ? null : 5000});
  }
});

const isLayoutEnabled = computed(() => {
  return route.meta.layout !== 'empty';
});

</script>

<template>
  <AppLayout v-if="isLayoutEnabled">
    <RouterView/>
  </AppLayout>
  <RouterView v-else />

  <Toast/>
</template>
