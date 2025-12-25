<template>
  

  <div class="content" v-if="!selectedCell" :style="{ opacity: (100 - distance) / 100 }">
    <img src="@/assets/img/logo.png" alt="Logo" class="content-logo" />
    <div class="button" @click="toggleFocus">Realizar Pedido</div>
  </div>

  <section class="inf-grid-hero-container" :style="{ '--grid-sz': density, '--rev-dis': distance }">
    <div v-for="dir in directions" :key="dir" :class="[dir, { 'selectedPane': selectedCell?.dir === dir }]">
      <div 
        v-for="n in totalCells" 
        :key="n"
        :class="['cell', { 
          'loaded': gridData[dir][n-1]?.loaded, 
          'selected': selectedCell?.dir === dir && selectedCell?.index === n-1 
        }]"
        :style="{ background: gridData[dir][n-1]?.bg }"
        @click="selectImage(dir, n-1)"
      ></div>
    </div>
  </section>
</template>

<script setup>
import { useBienvenidoLogic } from '@/assets/js/bienvenido.js';

// Extraemos las variables y funciones para que estén disponibles en el template
const {
  density, distance, selectedCell, directions, gridData, totalCells,
  selectImage, resumeInterval, toggleFocus
} = useBienvenidoLogic();
</script>

<script>
export default {
  name: 'Bienvenido'
}
</script>

<style src="@/assets/styles/bienvenido.css"></style>