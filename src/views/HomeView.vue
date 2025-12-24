<template>
  <div class="container-fluid">
    
    <Bienvenido/>
  </div>
</template>

<script>
// @ is an alias to /src
import Bienvenido from '@/components/Bienvenido.vue'

export default {
  name: 'HomeView',
  components: {
    Bienvenido
  }
}
</script>
