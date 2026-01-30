<script setup>
import {ref, onMounted} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import productsService from "@/services/products.js";
import {useToast} from 'primevue/usetoast'

import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Textarea from 'primevue/textarea'
import Button from 'primevue/button'
import Divider from 'primevue/divider'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const loading = ref(false)

const product = ref({
  name: '',
  description: '',
  amount: null,
  quantity: null,
})

const loadProduct = async () => {
  loading.value = true
  try {
    const {data} = await productsService.show(route.params.id)
    product.value = data.data
  } finally {
    loading.value = false
  }
}

const updateProduct = async () => {
  loading.value = true
  try {
    const response = await productsService.update(`${route.params.id}`, product.value)
    setTimeout(() => {
      router.push('/products')
    })

    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: response.data.message,
      life: 3000
    })

  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: error.response?.data?.message || 'Erro ao atualizar produto',
      life: 4000
    })
  } finally {
    loading.value = false
  }
}

onMounted(loadProduct)
</script>

<template>
  <div class="p-4 flex justify-content-center">
    <Card class="w-full lg:w-8">

      <!-- Header -->
      <template #title>
        <div class="flex flex-column gap-1">
          <span class="text-xl font-semibold">Editar Produto</span>
          <small class="text-500">
            Atualize as informações do produto abaixo
          </small>
        </div>
      </template>

      <template #content>
        <form @submit.prevent="updateProduct" class="p-fluid">

          <!-- Nome -->
          <div class="mb-4">
            <label class="font-medium mb-2 block">Nome do produto</label>
            <InputText
              v-model="product.name"
              placeholder="Ex: Shampoo Profissional"
              class="w-full"
              :disabled="loading"
            />
          </div>

          <Divider/>

          <!-- Valor e Quantidade -->
          <div class="grid mb-4">
            <div class="col-12 md:col-6">
              <label class="font-medium mb-2 block">Valor</label>
              <InputNumber
                v-model="product.amount"
                mode="currency"
                currency="BRL"
                locale="pt-BR"
                class="w-full"
                :disabled="loading"
              />
            </div>

            <div class="col-12 md:col-6">
              <label class="font-medium mb-2 block">Quantidade em estoque</label>
              <InputNumber
                v-model="product.quantity"
                :min="0"
                class="w-full"
                :disabled="loading"
              />
            </div>
          </div>

          <!-- Descrição -->
          <div class="mb-5">
            <label class="font-medium mb-2 block">Descrição</label>
            <Textarea
              v-model="product.description"
              rows="5"
              autoResize
              placeholder="Detalhes do produto, composição, observações..."
              class="w-full"
              :disabled="loading"
            />
          </div>

          <!-- Footer -->
          <Divider/>

          <div class="flex justify-content-end gap-2">
            <Button
              label="Cancelar"
              severity="secondary"
              outlined
              type="button"
              @click="router.push('/products')"
            />
            <Button
              label="Salvar alterações"
              icon="pi pi-check"
              type="submit"
              :loading="loading"
            />
          </div>

        </form>
      </template>
    </Card>
  </div>
</template>



