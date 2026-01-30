<script setup>
import {ref, computed} from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Card from 'primevue/card'

const props = defineProps({
  products: {
    type: Array,
    required: true
  }
})

/**
 * Filtros
 */
const filters = ref({
  name: '',
  amount: null
})

/**
 * Produtos filtrados
 */
const filteredProducts = computed(() => {
  const list = props.products ?? []

  return list.filter(product => {
    const matchName =
      !filters.value.name ||
      (product.name ?? '')
        .toLowerCase()
        .includes(filters.value.name.toLowerCase())

    const matchAmount =
      filters.value.amount === null ||
      Math.abs(
        Number(product.amount) - Number(filters.value.amount)
      ) < 0.01

    return matchName && matchAmount
  })
})


/**
 * Ações
 */

const emit = defineEmits(['delete', 'edit', 'show'])
const editProduct = (product) => {
  emit('edit', product)
}
const deleteProduct = (product) => {
  emit('delete', product)
}
const showProduct = (product) => {
  emit('show', product)
}
</script>

<template>
  <Card>
    <template #title>
      <div class="flex justify-content-between align-items-center">
        <span class="text-xl">📦 Produtos</span>

        <Button
          label="Novo Produto"
          icon="pi pi-plus"
          class="p-button-sm"
          @click="$router.push('/products/create')"
        />
      </div>
    </template>

    <template #content>
      <!-- FILTROS -->
      <div class="filters">
        <div class="field">
          <label>Nome</label>
          <InputText
            v-model="filters.name"
            placeholder="Buscar por nome"
            class="w-full"
          />
        </div>

        <div class="field">
          <label>Valor</label>
          <InputNumber
            v-model="filters.amount"
            placeholder="Buscar por valor"
            mode="decimal"
            :maxFractionDigits="2"
            class="w-full"
          />
        </div>
      </div>

      <!-- TABELA -->
      <DataTable
        :value="filteredProducts"
        paginator
        :rows="10"
        stripedRows
        responsiveLayout="scroll"
        sortField="id"
        :sortOrder="-1"
      >
        <Column field="id" header="ID" sortable/>
        <Column field="name" header="Nome" sortable/>
        <Column field="description" header="Descrição"/>
        <Column field="amount" header="Valor" sortable>
          <template #body="{ data }">
            R$ {{ Number(data.amount).toFixed(2) }}
          </template>
        </Column>
        <Column field="quantity" header="Qtd." sortable/>

        <Column header="Ações" style="width: 150px">
          <template #body="{ data }">
            <Button
              icon="pi pi-eye"
              class="p-button-text p-button-sm"
              @click="showProduct(data)"
            />
            <Button
              icon="pi pi-pencil"
              class="p-button-text p-button-sm"
              @click="editProduct(data)"
            />
            <Button
              icon="pi pi-trash"
              class="p-button-text p-button-danger p-button-sm"
              @click="deleteProduct(data)"
            />
          </template>
        </Column>

        <template #empty>
          Nenhum produto encontrado
        </template>
      </DataTable>
    </template>
  </Card>

</template>

<style scoped>
.filters {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}
</style>
