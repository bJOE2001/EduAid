<template>
  <q-page class="flex flex-center">
    <div class="text-center">
      <div class="text-h1">404</div>
      <div class="text-h5 q-mt-md">Page Not Found</div>
      <q-btn color="primary" label="Go Home" to="/" class="q-mt-md" />
    </div>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
export default defineComponent({ name: 'ErrorNotFound' })
</script>
