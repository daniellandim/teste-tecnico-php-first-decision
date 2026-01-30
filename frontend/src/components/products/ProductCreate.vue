<script setup>
import {ref} from 'vue'
import {useRouter} from 'vue-router'
import {useToast} from 'primevue/usetoast'
import productsService from "@/services/products.js";

import {Form, Field, ErrorMessage} from 'vee-validate'
import * as yup from 'yup'

import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Textarea from 'primevue/textarea'
import Button from 'primevue/button'
import Divider from 'primevue/divider'

const router = useRouter()
const toast = useToast()
const loading = ref(false)

/**
 * Schema de validação
 */
const schema = yup.object({
  name: yup.string().required('Nome é obrigatório'),
  amount: yup
    .number()
    .typeError('Informe um valor válido')
    .required('Valor é obrigatório')
    .moreThan(0, 'Valor deve ser maior que zero'),
  quantity: yup
    .number()
    .typeError('Informe uma quantidade válida')
    .required('Quantidade é obrigatória')
    .min(0, 'Quantidade não pode ser negativa'),
  description: yup.string().nullable()
})

const createProduct = async (values) => {
  loading.value = true
  try {
    const response = await productsService.store(values)

    toast.add({
      severity: 'success',
      summary: 'Sucesso',
      detail: response.data.message || 'Produto cadastrado com sucesso',
      life: 3000
    })

    setTimeout(() => router.push('/products'), 1000)
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: error.response?.data?.message || 'Erro ao cadastrar produto',
      life: 4000
    })
  } finally {
    loading.value = false
  }
}
</script>


<template>
  <div class="p-4 flex justify-content-center">
    <Card class="w-full lg:w-8">

      <template #title>
        <div class="flex flex-column gap-1">
          <span class="text-xl font-semibold">Novo Produto</span>
          <small class="text-500">
            Preencha os dados para cadastrar um novo produto
          </small>
        </div>
      </template>

      <template #content>
        <Form
          :validation-schema="schema"
          @submit="createProduct"
          class="p-fluid"
        >

          <!-- Nome -->
          <div class="mb-4">
            <label class="font-medium mb-2 block">Nome do produto *</label>
            <Field name="name" v-slot="{ field, errors }">
              <InputText
                v-bind="field"
                class="w-full"
                :class="{ 'p-invalid': errors.length }"
                placeholder="Ex: Shampoo Profissional"
                :disabled="loading"
              />
            </Field>
            <ErrorMessage name="name" class="text-red-500 text-sm mt-1"/>
          </div>

          <Divider/>

          <!-- Valor e Quantidade -->
          <div class="grid mb-4">
            <div class="col-12 md:col-6">
              <label class="font-medium mb-2 block">Valor *</label>
              <Field name="amount" v-slot="{ value, handleChange, errors }">
                <InputNumber
                  :modelValue="value"
                  @update:modelValue="handleChange"
                  mode="decimal"
                  locale="pt-BR"
                  :minFractionDigits="2"
                  :maxFractionDigits="2"
                  class="w-full"
                  :class="{ 'p-invalid': errors.length }"
                />
              </Field>
              <ErrorMessage name="amount" class="text-red-500 text-sm mt-1"/>
            </div>

            <div class="col-12 md:col-6">
              <label class="font-medium mb-2 block">Quantidade *</label>
              <Field name="quantity" v-slot="{ value, handleChange, errors }">
                <InputNumber
                  :modelValue="value"
                  @update:modelValue="handleChange"
                  :min="0"
                  class="w-full"
                  :class="{ 'p-invalid': errors.length }"
                />
              </Field>
              <ErrorMessage name="quantity" class="text-red-500 text-sm mt-1" />
            </div>
          </div>

          <!-- Descrição -->
          <div class="mb-5">
            <label class="font-medium mb-2 block">Descrição</label>
            <Field name="description" v-slot="{ field }">
              <Textarea
                v-bind="field"
                rows="5"
                autoResize
                class="w-full"
                placeholder="Detalhes do produto"
                :disabled="loading"
              />
            </Field>
          </div>

          <Divider/>

          <!-- Footer -->
          <div class="flex justify-content-end gap-2">
            <Button
              label="Cancelar"
              severity="secondary"
              outlined
              type="button"
              @click="router.push('/products')"
            />
            <Button
              label="Cadastrar"
              icon="pi pi-plus"
              type="submit"
              :loading="loading"
            />
          </div>

        </Form>
      </template>
    </Card>
  </div>
</template>

