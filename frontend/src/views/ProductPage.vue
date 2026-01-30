<script setup>
import {ref, onMounted} from 'vue'
import productsService from "@/services/products.js";
import Swal from 'sweetalert2'
import ProductList from '@/components/products/ProductList.vue'
import {useRouter} from 'vue-router'

const router = useRouter()
const products = ref([])
const loading = ref(false)

const loadProducts = async () => {
  loading.value = true
  products.value = await productsService.all()
  loading.value = false
}

// Função para deletar
const handleDelete = async (product) => {
  const {isConfirmed} = await Swal.fire({
    title: 'Confirmar exclusão',
    text: `Deseja excluir ${product.name}?`,
    icon: 'warning',
    showCancelButton: true,
  })

  if (!isConfirmed) return

  await productsService.delete(product.id)
  products.value = products.value.filter(p => p.id !== product.id)
}

const handleEdit = (product) => {
  router.push({name: 'products.edit', params: {id: product.id}})
}
const handleShow = (product) => {
  router.push({name: 'products.show', params: {id: product.id}})
}

onMounted(() => {
  loadProducts()
})
</script>

<template>
  <ProductList
    :products="products"
    :loading="loading"
    @delete="handleDelete"
    @edit="handleEdit"
    @show="handleShow"
  />
</template>
