<script setup>
import {ref} from 'vue'
import {Form, Field, ErrorMessage} from 'vee-validate'
import {useRouter} from 'vue-router'
import {useAuthStore} from '@/stores/auth'
import authService from '@/services/auth'
import { useToast } from 'primevue/usetoast'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'

const formData = ref({
  email: '',
  password: '',
  remember: false
})

const store = useAuthStore()
const router = useRouter()
const toast = useToast()

const logar = async (values) => {
  try {
    const user = await authService.login(values)
    store.setUser(user)
    await router.push('/products')
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Erro ao entrar',
      detail: 'E-mail ou senha inválidos',
      life: 4000
    })
  }
}
</script>

<template>
  <div class="login-wrapper">
    <Card class="login-card">
      <template #title>
        🔐 Entrar
      </template>

      <template #content>
        <Form  @submit="logar" :initial-values="formData" class="login-form">

          <!-- EMAIL -->
          <div class="field">
            <label for="email">E-mail</label>
            <Field name="email" rules="required|email" v-slot="{ field, errors }">
              <InputText
                v-bind="field"
                id="email"
                type="email"
                class="w-full"
                :invalid="!!errors.length"
              />
            </Field>
            <ErrorMessage name="email" class="error"/>
          </div>

          <!-- SENHA -->
          <div class="field">
            <label for="password">Senha</label>
            <Field name="password" rules="required" v-slot="{ field, errors }">
              <Password
                v-bind="field"
                id="password"
                class="w-full"
                inputClass="w-full"
                toggleMask
                :feedback="false"
                :invalid="!!errors.length"
              />
            </Field>
            <ErrorMessage name="password" class="error" />
          </div>

          <!-- BOTÃO -->
          <Button
            type="submit"
            label="Entrar"
            class="w-full"
            size="large"
          />
        </Form>
      </template>
    </Card>
  </div>
</template>

<style scoped>
.login-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--surface-ground);
}

.login-card {
  width: 100%;
  max-width: 420px;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.remember {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.error {
  font-size: 0.8rem;
  color: var(--red-500);
}
</style>
