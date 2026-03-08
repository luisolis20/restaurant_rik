<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Pedidos Recientes</h3>
      </div>

      <div class="flex items-center gap-3">
        <div class="relative">
          <button @click="isFilterDropdownOpen = !isFilterDropdownOpen"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
            <svg class="stroke-current fill-white dark:fill-gray-800" width="20" height="20" viewBox="0 0 20 20"
              fill="none">
              <path d="M2.29004 5.90393H17.7067" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <path d="M17.7075 14.0961H2.29085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <path
                d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
                stroke-width="1.5" />
              <path
                d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
                stroke-width="1.5" />
            </svg>
            {{ selectedStatus === "" ? "Filtrar" : "Estado: " + selectedStatus }}
          </button>

          <div v-if="isFilterDropdownOpen"
            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800">
            <div class="py-1">
              <button @click="setStatusFilter('')"
                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                Todos
              </button>
              <button @click="setStatusFilter('pendiente')"
                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                Pendientes
              </button>
              <button @click="setStatusFilter('preparacion')"
                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                Preparación
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="min-w-full">
        <thead>
          <tr class="border-t border-gray-100 dark:border-gray-800">
            <th class="py-3 text-left">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400"></p>
            </th>
            <th class="py-3 text-left">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Precio Unitario</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">SubTotal</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Estado</p>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-gray-100 dark:border-gray-800" v-if="cargando">
            <td class="px-5 py-4 sm:px-6" colspan="9">
              <h3 class="text-center">Cargando....</h3>
            </td>
          </tr>

          <template v-else v-for="(productos, idPedido) in filteredobjetoarray" :key="idPedido">
            <tr class="bg-gray-50 dark:bg-white/5">
              <td colspan="1" class="px-4 py-2">
                <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                  <div>
                    <span class="font-bold text-theme-sm text-gray-800 dark:text-white">
                      Pedido #{{ idPedido }} — Mesa: {{ productos[0].codigo_mesa }}
                    </span><br>
                    <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                      Cantidad Total: {{productos.reduce((acc, item) => acc + item.cantidad, 0)}} -
                      Precio Total: ${{ productos[0].total }}
                    </span>
                    <br>
                    <span class="text-gray-500 text-theme-xs dark:text-gray-400"
                      v-if="productos[0].estado_pedido === 'cocinando'">
                      El pedido estará listo en: {{ productos[0].tiempo_estimado_minutos }} minutos
                    </span>
                  </div>
                  <div class="flex items-center gap-3">
                    <div class="relative" v-if="productos[0].estado_pedido === 'preparacion'">
                      <button @click="abrirModalEdicion(idPedido, productos)"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        Ver pedido
                      </button>
                    </div>
                    <div class="relative flex items-center gap-2" v-if="productos[0].estado_pedido === 'cocinando'">
                      <div class="flex items-center gap-2 rounded-full bg-brand-50 px-3 py-1 dark:bg-brand-500/10">
                        <svg class="animate-spin-slow text-brand-600" width="16" height="16" viewBox="0 0 24 24"
                          fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"></circle>
                          <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>

                        <span class="font-mono text-sm font-bold text-brand-700 dark:text-brand-400">
                          {{ getTiempoRestante(productos[0].fecha_fin, idPedido) }}
                        </span>
                      </div>

                      <button @click="finalizarPedido(idPedido, productos)"
                        class="text-gray-400 hover:text-success-600 transition-colors" title="Marcar como listo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                          stroke-width="2">
                          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                          <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                      </button>
                    </div>
                    <div class="relative" v-if="productos[0].estado_pedido === 'listo'">
                      <button @click="abrirModalFactura(idPedido)"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                          stroke-width="2">
                          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                          <polyline points="14 2 14 8 20 8"></polyline>
                          <line x1="16" y1="13" x2="8" y2="13"></line>
                          <line x1="16" y1="17" x2="8" y2="17"></line>
                          <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Ver Factura
                      </button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr v-for="post in productos" :key="post.id_detalle" class="border-t border-gray-100 dark:border-gray-800">
              <td class="py-3 whitespace-nowrap">
                <div class="flex items-center gap-3 pl-4">
                  <div class="h-[50px] w-[50px] overflow-hidden rounded-md">
                    <img :src="getPhotoUrl(post.id_producto)" @error="handleImageError" />
                  </div>
                  <div>
                    <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                      {{ post.producto_nombre }}
                    </p>
                    <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                      Cantidad: {{ post.cantidad }}
                    </span>
                  </div>
                </div>
              </td>
              <td class="py-3 whitespace-nowrap">
                <p class="text-gray-500 text-theme-sm dark:text-gray-400">$ {{ post.precio_unitario }}</p>
              </td>
              <td class="py-3 whitespace-nowrap">
                <p class="text-gray-500 text-theme-sm dark:text-gray-400">$ {{ post.precio_unitario * post.cantidad }}
                </p>
              </td>
              <td class="py-3 whitespace-nowrap">
                <span :class="{
                  'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
                  'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400': post.estado_pedido === 'pendiente',
                  'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500': post.estado_pedido === 'preparacion',
                  'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500': post.estado_pedido === 'listo',
                }">
                  {{ post.estado_pedido }}
                </span>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <br /><br />
    <!-- Botones de Paginación -->
    <div class="d-flex justify-content-center mb-4">
      <button @click="previousPage" :disabled="currentPage === 1 || buscando" class="btn btn-primary text-white">
        <i class="fas fa-angle-left"></i></button>&nbsp; <span class="text-dark">Página {{ currentPage }} de {{ lastPage
        }}</span>&nbsp;
      <button @click="nextPage" :disabled="currentPage === lastPage || buscando" class="btn btn-primary text-white">
        <i class="fas fa-angle-right"></i>
      </button>
    </div>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <div class="d-flex justify-content-center mb-4" v-if="!cargando">
      &nbsp;&nbsp;&nbsp;
      <button class="btn btn-primary text-white" @click="actualizar">Actualizar</button>
    </div>
    <Modal v-if="isEditModalOpen" @close="isEditModalOpen = false">
      <template #body>
        <div
          class="relative w-full max-w-[700px] max-h-[90vh] flex flex-col overflow-hidden rounded-3xl bg-white dark:bg-gray-900 shadow-2xl">

          <button @click="isEditModalOpen = false"
            class="transition-color absolute right-5 top-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.07]">
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" />
            </svg>
          </button>

          <div class="px-6 pt-8 lg:px-11 lg:pt-11">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
              Detalle del pedido
            </h4>
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
              Los datos cargados aquí son los detalles del pedido. Establezca el tiempo de preparación.
            </p>
          </div>

          <form class="flex flex-col flex-1 overflow-hidden">
            <div class="px-6 pt-8 lg:px-11 lg:pt-11">
              <h4 class="text-2xl font-semibold text-gray-800 dark:text-white">Pedido #{{ objetoeditar.id_pedido }}</h4>
              <p class="text-sm text-gray-500">Mesa: <span class="font-bold text-brand-500">{{ objetoeditar.codigo_mesa
              }}</span></p>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4 lg:px-11 custom-scrollbar">
              <div class="mb-6 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
                <table class="w-full text-left text-sm">
                  <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                      <th class="p-3">Plato</th>
                      <th class="p-3">Cant.</th>
                      <th class="p-3">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in objetoeditar.productos" :key="item.id_detalle"
                      class="border-t border-gray-100 dark:border-gray-800">
                      <td class="p-3 font-medium">{{ item.producto_nombre }}</td>
                      <td class="p-3">{{ item.cantidad }}</td>
                      <td class="p-3">${{ (item.precio_unitario * item.cantidad).toFixed(2) }}</td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr class="bg-gray-50/50 font-bold">
                      <td colspan="2" class="p-3 text-right">Total Pedido:</td>
                      <td class="p-3 text-brand-600">${{ objetoeditar.total }}</td>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <div
                class="grid grid-cols-1 gap-4 lg:grid-cols-3 bg-gray-50 dark:bg-white/5 p-4 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">

                <div class="lg:col-span-1">
                  <label
                    class="mb-1.5 block text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-wider">
                    Tiempo Estimado (Minutos)
                  </label>
                  <input type="number" v-model.number="objetoeditar.tiempo_estimado_minutos" placeholder="Ej. 20"
                    class="h-12 w-full rounded-xl border-2 border-brand-200 bg-white px-4 text-lg font-bold text-gray-800 focus:border-brand-500 focus:ring-0 dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">
                    Se marca inicio a las:
                  </label>
                  <input type="datetime-local" v-model="objetoeditar.fecha_inicio" disabled
                    class="h-12 w-full rounded-xl border border-gray-200 bg-gray-100 px-4 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900/50" />
                </div>

                <div>
                  <label class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">
                    Finalización estimada:
                  </label>
                  <input type="datetime-local" v-model="objetoeditar.fecha_fin" disabled
                    class="h-12 w-full rounded-xl border border-gray-200 bg-gray-100 px-4 text-sm text-gray-500 dark:border-gray-700 dark:bg-gray-900/50" />
                </div>

              </div>
            </div>

            <div
              class="flex items-center gap-3 border-t border-gray-100 bg-gray-50/50 p-6 dark:border-gray-800 dark:bg-white/[0.02] lg:justify-end lg:px-11">
              <button @click="isEditModalOpen = false" type="button"
                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                Cerrar
              </button>
              <button v-if="formIsValidEdit" @click="Update" type="button"
                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto shadow-lg transition-all">
                Guardar Cambios
              </button>
              <p v-else class="text-[11px] text-gray-400 italic">Complete todos los campos para editar.</p>
            </div>
          </form>
        </div>
      </template>
    </Modal>
    <Modal v-if="isInvoiceModalOpen" @close="isInvoiceModalOpen = false">
      <template #body>
        <div
          class="relative w-full max-w-[600px] max-h-[90vh] flex flex-col overflow-hidden rounded-3xl bg-white dark:bg-gray-900 shadow-2xl">

          <div class="px-8 pt-8 pb-4 border-b border-gray-100 dark:border-gray-800">
            <div class="flex justify-between items-start">
              <div>
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white uppercase">
                  {{ facturaData.tipo_comprobante }}
                </h4>
                <p class="text-brand-600 font-mono font-bold">{{ facturaData.numero_factura }}</p>
              </div>
              <button @click="isInvoiceModalOpen = false" class="text-gray-400 hover:text-gray-600">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M18 6L6 18M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6 text-sm">
              <div>
                <p class="text-gray-500">Fecha de Emisión:</p>
                <p class="font-medium dark:text-gray-300">{{ facturaData.fecha_emision }}</p>
              </div>
              <div class="text-right">
                <p class="text-gray-500">Pedido Origen:</p>
                <p class="font-medium dark:text-gray-300">#{{ facturaData.id_pedido }}</p>
              </div>
            </div>
          </div>

          <div class="flex-1 overflow-y-auto px-8 py-6 custom-scrollbar">
            <table class="w-full text-left text-sm">
              <thead>
                <tr class="text-gray-400 border-b border-gray-100 dark:border-gray-800">
                  <th class="pb-3 font-medium">Descripción</th>
                  <th class="pb-3 font-medium text-center">Cant.</th>
                  <th class="pb-3 font-medium text-right">Precio</th>
                  <th class="pb-3 font-medium text-right">Total</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                <tr v-for="item in facturaDetalle" :key="item.id_detalle_factura">
                  <td class="py-4 dark:text-gray-300">{{ item.descripcion }}</td>
                  <td class="py-4 text-center dark:text-gray-300">{{ item.cantidad }}</td>
                  <td class="py-4 text-right dark:text-gray-300">${{ item.precio_unitario }}</td>
                  <td class="py-4 text-right font-medium dark:text-white">${{ item.subtotal }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="px-8 py-6 bg-gray-50 dark:bg-white/[0.02] border-t border-gray-100 dark:border-gray-800">
            <div class="flex flex-col gap-2 w-full max-w-[200px] ml-auto">
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal:</span>
                <span class="font-medium dark:text-gray-300">${{ facturaData.subtotal }}</span>
              </div>
              <div class="flex justify-between text-xl font-bold border-t border-gray-200 dark:border-gray-700 pt-2">
                <span class="text-gray-800 dark:text-white">TOTAL:</span>
                <span class="text-brand-600">${{ facturaData.total }}</span>
              </div>
            </div>

            <div class="mt-8 flex gap-3">
              <button @click="isInvoiceModalOpen = false"
                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                Cerrar
              </button>
              <button
                class="flex-1 bg-gray-800 dark:bg-brand-500 text-white px-4 py-2.5 rounded-xl font-medium hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path
                    d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2m-2 4H6a2 2 0 0 1-2-2v-4h12v4a2 2 0 0 1-2 2z">
                  </path>
                </svg>
                Imprimir
              </button>
            </div>
          </div>

        </div>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, watch, nextTick, onBeforeUnmount, onMounted, onUnmounted } from "vue";
import Modal from "@/components/Modal/Modal.vue";

const isProfileAddressModal = ref(false);
const isEditModalOpen = ref(false);
/*const ahoraRef = ref(new Date());
let timerInterval = null;*/

const cerrarModalDesdeAfuera = () => {
  isProfileAddressModal.value = false
}


defineExpose({
  isProfileAddressModal,
  isEditModalOpen,
  cerrarModalDesdeAfuera
})
/*onMounted(() => {
  timerInterval = setInterval(() => {
    ahoraRef.value = new Date();
  }, 1000); // Actualiza cada segundo
});

onUnmounted(() => {
  clearInterval(timerInterval);
});*/

</script>
<script>
import API from "@/assets/js/services/axios";
import { useRoute } from "vue-router";
import debounce from "lodash.debounce";
import Modal from "@/components/Modal/Modal.vue";
import { getMe } from '@/store/auth';
import {
  mostraralertas2,
  enviarsolig,
  confimar,
  confimarhabi,
  enviarsoligtiempo,
} from "@/assets/js/function/funciones";

export default {
  data() {
    return {
      idus: 0,
      baseUrl: "/restrik",
      photoCache: {},

      usersarray: [],
      filteredobjetoarray: {},
      isFilterDropdownOpen: false,
      searchQuery: "",
      selectedStatus: "",
      currentPage: 1,
      lastPage: 1,
      buscando: false, // Mantenido, pero no se usa en la lógica de paginación actual
      debouncedFilter: null,
      objetoList: [],
      refreshKey: Date.now(),
      objetoeditar: {
        id_pedido: 0,
        id_tiempo: 0,
        id_mesa: 0,
        fecha_pedido: "",
        codigo_mesa: "",
        total: 0,
        productos: [], // Lista para el modal
        tiempo_estimado_minutos: 0,
        fecha_inicio: "",
        fecha_fin: "",
      },
      isProfileAddressModal: false,
      id_usuario_chef: 0,
      ahora: new Date(), // Timer centralizado aquí
      timerInterval: null,
      pedidosEnProceso: new Set(), // Para evitar llamadas duplicadas
      pollingInterval: null,
      isInvoiceModalOpen: false,
      facturaData: {},
      facturaDetalle: [],

    }
  },
  created() {
    // Ahora sí puedes usar this.filterAndFetch
    this.debouncedFilter = debounce(() => {
      this.filterAndFetch();
    }, 900);
  },
  unmounted() {
    if (this.timerInterval) {
      clearInterval(this.timerInterval);
    }
    if (this.pollingInterval) clearInterval(this.pollingInterval);
  },
  computed: {
    formIsValidEdit() {
      return (
        this.objetoeditar.tiempo_estimado_minutos > 0 &&
        this.objetoeditar.fecha_inicio !== ""
      );
    },
  },
  watch: {
    'objetoeditar.tiempo_estimado_minutos': function (newVal) {
      if (newVal > 0) {
        const ahora = new Date();

        // Formatear fecha para input datetime-local (YYYY-MM-DDTHH:mm)
        const formatFecha = (date) => {
          const tzOffset = date.getTimezoneOffset() * 60000; // offset en ms
          const localISOTime = (new Date(date - tzOffset)).toISOString().slice(0, 16);
          return localISOTime;
        };

        // Fecha Inicio: Ahora
        this.objetoeditar.fecha_inicio = formatFecha(ahora);

        // Fecha Fin: Ahora + N minutos
        const fechaFin = new Date(ahora.getTime() + newVal * 60000);
        this.objetoeditar.fecha_fin = formatFecha(fechaFin);
      } else {
        this.objetoeditar.fecha_inicio = "";
        this.objetoeditar.fecha_fin = "";
      }
    }
  },
  async mounted() {
    this.timerInterval = setInterval(() => {
      this.ahora = new Date();
      // console.log("Reloj funcionando:", this.ahora); // Para debugear
    }, 1000);
    this.pollingInterval = setInterval(() => {
      this.actualizarSilenciosamente();
    }, 30000);
    const ruta = useRoute();
    const usuario = await getMe();
    this.id_usuario_chef = usuario.id_usuario;
    this.GetData(1, this.selectedStatus);
  },
  methods: {
    getPhotoUrl(ci) {
      const baseURL2 = API.defaults.baseURL;
      return `${baseURL2}/restrik/imagenprod/${ci}?v=${this.refreshKey}`;
    },
    async abrirModalFactura(idPedido) {
      try {
        // 1. Buscamos la factura asociada al pedido
        const response = await API.get(`${this.baseUrl}/factura-por-pedido/${idPedido}`);

        if (response.data) {
          this.facturaData = response.data.factura;
          this.facturaDetalle = response.data.detalles;
          this.isInvoiceModalOpen = true;
        } else {
          mostraralertas2("No se encontró la factura para este pedido", "error");
        }
      } catch (error) {
        console.error("Error al obtener factura:", error);
        mostraralertas2("Error al cargar los datos de la factura", "error");
      }
    },
    async actualizarSilenciosamente() {
      try {
        const params = { page: this.currentPage, status: this.selectedStatus };
        const response = await API.get(`${this.baseUrl}/pedidosrecientes`, { params });
        this.filteredobjetoarray = response.data?.data || {};
        this.lastPage = response.data?.pagination?.last_page || 1;
      } catch (error) {
        console.warn("Error en actualización silenciosa", error);
      }
    },
    getTiempoRestante(fechaFin, idPedido) {
      if (!fechaFin) return "Calculando...";

      const fin = new Date(fechaFin.replace(' ', 'T'));
      const dif = fin - this.ahora;

      if (dif <= 0) {
        // Disparamos la verificación sin bloquear el renderizado
        this.verificarYFinalizarAutomatico(idPedido);
        return "¡Listo!";
      }

      const minutos = Math.floor((dif / 1000 / 60));
      const segundos = Math.floor((dif / 1000) % 60);
      return `${minutos}:${segundos.toString().padStart(2, '0')} min`;
    },
    async verificarYFinalizarAutomatico(idPedido) {
      // 1. Si ya se está procesando, ignorar
      if (this.pedidosEnProceso.has(idPedido)) return;

      const pedidoContenedor = this.filteredobjetoarray[idPedido];
      if (pedidoContenedor && pedidoContenedor[0].estado_pedido === 'cocinando') {

        // 2. Bloqueamos localmente antes de la petición
        this.pedidosEnProceso.add(idPedido);

        // Opcional: Cambiar estado visual inmediatamente
        pedidoContenedor[0].estado_pedido = 'listo';

        await this.ejecutarFinalizarPedido(idPedido);

        // 3. Limpiamos el bloqueo tras terminar
        this.pedidosEnProceso.delete(idPedido);
      }
    },
    async finalizarPedido(idPedido, productos) {


      await this.ejecutarFinalizarPedido(idPedido);

    },
    async ejecutarFinalizarPedido(idPedido) {
      try {
        // Llamamos al nuevo endpoint de facturación
        const response = await API.post(`${this.baseUrl}/finalizar-facturar/${idPedido}`);

        if (response.status === 200) {
          mostraralertas2("Pedido finalizado y factura generada", "success");
          this.actualizar(); // Refresca la tabla
        }
      } catch (error) {
        console.error("Error al finalizar pedido:", error);
        mostraralertas2("Error al procesar la factura", "error");
      }
    },
    abrirModalEdicion(idPedido, arrayProductos) {
      const primerProducto = arrayProductos[0];
      console.log(primerProducto);
      // Clonamos el objeto para no modificar la tabla directamente antes de guardar
      const ahora = new Date().toISOString().slice(0, 16);

      this.objetoeditar = {
        id_pedido: idPedido,
        id_tiempo: primerProducto.id_tiempo,
        id_mesa: primerProducto.id_mesa,
        fecha_pedido: primerProducto.fecha_pedido,
        codigo_mesa: primerProducto.codigo_mesa,
        total: primerProducto.total,
        productos: arrayProductos,
        tiempo_estimado_minutos: 0, // Valor sugerido
        fecha_inicio: ahora,
        fecha_fin: "",
      };

      this.$.setupState.isEditModalOpen = true;
    },
    handleImageError(event) {
      // Reemplaza la imagen con el ícono de usuario por defecto
      event.target.src =
        "https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/480px-User_icon_2.svg.png";
    },
    async GetData(page = 1, selectedStatus = "") {
      this.cargando = true;
      try {
        const params = {
          page: page,
          status: selectedStatus,
        };
        // Usamos el endpoint que agrupamos en el paso anterior
        const response = await API.get(`${this.baseUrl}/pedidosrecientes`, { params });

        this.filteredobjetoarray = response.data?.data || {};

        const pagination = response.data?.pagination || {};
        this.currentPage = pagination.current_page || 1;
        this.lastPage = pagination.last_page || 1;

      } catch (error) {
        console.warn("⚠️ Error:", error);
        this.filteredobjetoarray = {};
        this.currentPage = 1;
        this.lastPage = 1;
      } finally {
        this.cargando = false;
      }
    },

    filterAndFetch() {
      this.currentPage = 1;
      this.GetData(this.currentPage, this.selectedStatus);
    },

    nextPage() {
      if (this.currentPage < this.lastPage && !this.cargando) {
        this.GetData(this.currentPage + 1, this.selectedStatus);
      }
    },

    previousPage() {
      if (this.currentPage > 1 && !this.cargando) {
        this.GetData(this.currentPage - 1, this.selectedStatus);
      }
    },

    actualizar() {
      // Simplemente recarga la página actual de datos
      this.GetData(this.currentPage, this.selectedStatus);
    },
    setStatusFilter(status) {
      this.selectedStatus = status;
      this.isFilterDropdownOpen = false;
      this.filterAndFetch();
    },
    async Update() {
      try {
        const params = {
          id_pedido: this.objetoeditar.id_pedido,
          id_usuario_chef: this.id_usuario_chef,
          tiempo_estimado_minutos: this.objetoeditar.tiempo_estimado_minutos,
          fecha_inicio: this.objetoeditar.fecha_inicio,
          fecha_fin: this.objetoeditar.fecha_fin,
        };
        const exito = await enviarsoligtiempo(
          "PUT",
          params,
          `${this.baseUrl}/tiempos_preparacion/${this.objetoeditar.id_tiempo}`
        );
        if (exito) {
          const params2 = {
            id_mesa: this.objetoeditar.id_mesa,
            fecha_pedido: this.objetoeditar.fecha_pedido,
            estado_pedido: "cocinando",
            total: this.objetoeditar.total,
          };
          const exito2 = await enviarsolig(
            "PUT",
            params2,
            `${this.baseUrl}/pedidos/${this.objetoeditar.id_pedido}`,
            "Tiempo del pedido definido y estado actualizado a cocinando"
          );
          if (exito2) {
            this.$.setupState.isEditModalOpen = false;
            this.actualizar();
            this.refreshKey = Date.now();

            this.objetoeditar = {
              tiempo_estimado_minutos: 0,
              fecha_inicio: "",
              fecha_fin: "",
            };
          }
        }
        else {
          this.actualizar();
          this.$.setupState.isProfileAddressModal = false;
        }
      } catch (error) {
        console.error("Error al actualizar:", error);
        mostraralertas2("Ocurrió un error al guardar", "error");
      } finally {
        // Pase lo que pase, nos aseguramos que el estado de carga se limpie
        this.cargando = false;
      }
    },
  },
}
</script>
<style scoped>
@keyframes spin-slow {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}

.animate-spin-slow {
  animation: spin-slow 8s linear infinite;
}
</style>