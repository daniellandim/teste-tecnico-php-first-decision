<script setup>
import {ref, onMounted} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import productsService from "@/services/products.js";

import Card from 'primevue/card'
import Button from 'primevue/button'
import Divider from 'primevue/divider'

const route = useRoute()
const router = useRouter()

const loading = ref(false)
const product = ref(null)

const loadProduct = async () => {
  loading.value = true
  try {
    const {data} = await productsService.show(route.params.id)
    product.value = data.data
  } finally {
    loading.value = false
  }
}

onMounted(loadProduct)
</script>
<template>
  <div class="p-4 flex justify-content-center">
    <Card class="w-full lg:w-8" v-if="product">

      <template #title>
        <div class="flex justify-content-between align-items-center">
          <span class="text-xl font-semibold">📦 {{ product.name }}</span>
          <Button
            label="Editar"
            icon="pi pi-pencil"
            class="p-button-sm"
            @click="router.push(`/products/${product.id}/edit`)"
          />
        </div>
      </template>

      <template #content>
        <div class="grid">

          <div class="col-12 md:col-6">
            <strong>Valor</strong>
            <p>R$ {{ Number(product.amount).toFixed(2) }}</p>
          </div>

          <div class="col-12 md:col-6">
            <strong>Quantidade</strong>
            <p>{{ product.quantity }}</p>
          </div>

          <div class="col-12">
            <Divider/>
            <strong>Descrição</strong>
            <p>{{ product.description || '—' }}</p>
          </div>

        </div>

        <Divider/>

        <div class="flex justify-content-end">
          <Button
            label="Voltar"
            severity="secondary"
            outlined
            @click="router.push('/products')"
          />
        </div>
      </template>

    </Card>
  </div>
</template>
