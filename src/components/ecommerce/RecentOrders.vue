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
                    <span class="text-gray-500 text-theme-xs dark:text-gray-400" v-if="productos[0].estado_pedido === 'cocinando'">
                      El pedido estará listo en: {{ productos[0].tiempo_preparacion }} minutos
                    </span>
                  </div>
                  <div class="flex items-center gap-3" >
                    <div class="relative" v-if="productos[0].estado_pedido === 'preparacion'">
                      <button @click="abrirModalEdicion(productos[0])"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        Ver pedido
                      </button>
                    </div>
                    <div class="relative" v-if="productos[0].estado_pedido === 'cocinando'">
                      <span>En </span>
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
            <div class="px-6 pb-4 overflow-y-auto custom-scrollbar lg:px-11">
              <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Mesa que realiza el pedido 
                  </label>
                  <input type="text" disabled placeholder="0.00" v-model="objetoeditar.codigo_mesa"
                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white" />
                </div>

                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Cantidad Disponible
                  </label>
                  <input type="number" v-model="objetoeditar.cantidad_disponible" min="-100"
                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white" />
                  <p class="text-xs text-gray-400 italic">
                    Si desea restar el stock, añada un valor negativo. Ej. -5
                  </p>
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
  </div>
</template>

<script setup>
import { ref, watch, nextTick, onBeforeUnmount } from "vue";
import Modal from "@/components/Modal/Modal.vue";

const isProfileAddressModal = ref(false);
const isEditModalOpen = ref(false);

const cerrarModalDesdeAfuera = () => {
  isProfileAddressModal.value = false
}


defineExpose({
  isProfileAddressModal,
  isEditModalOpen,
  cerrarModalDesdeAfuera
})

</script>
<script>
import API from "@/assets/js/services/axios";
import { useRoute } from "vue-router";
import debounce from "lodash.debounce";
import Modal from "@/components/Modal/Modal.vue";
import {
  mostraralertas2,
  enviarsolig,
  confimar,
  confimarhabi,
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
        id_tiempo: 0,
        id_pedido: 0,
        tiempo_estimado_minutos: 0,
        fecha_inicio: "",
        fecha_fin: "",
        precio: "",
        imagen: "",
        previewFoto: "",
        nombre: "",
        total: 0,
        codigo_mesa: "",
      },
      isProfileAddressModal: false,
      id_usuario_chef: 0,

    }
  },
  created() {
    // Ahora sí puedes usar this.filterAndFetch
    this.debouncedFilter = debounce(() => {
      this.filterAndFetch();
    }, 900);
  },
  computed: {
    formIsValidEdit() {
      return (
        this.objetoeditar.id_usuario_chef !== 0 &&
        this.objetoeditar.tiempo_estimado_minutos !== 0
      );
    },
  },
  async mounted() {
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
    abrirModalEdicion(user) {
      // Clonamos el objeto para no modificar la tabla directamente antes de guardar
      this.objetoeditar = {
        id_tiempo: user.id_tiempo,
        id_pedido: user.id_pedido,
        tiempo_estimado_minutos: user.tiempo_estimado_minutos,
        fecha_inicio: user.fecha_inicio,
        fecha_fin: user.fecha_fin,
        precio: user.precio,
        imagen: user.imagen,
        previewFoto: "data:image/jpeg;base64," + user.imagen,
        nombre: user.nombre,
        total: user.total,
        codigo_mesa: user.codigo_mesa,
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

        // La data ahora es un objeto: { "1": [...], "2": [...] }
        this.filteredobjetoarray = response.data?.data || {};

        const pagination = response.data?.pagination || {};
        this.currentPage = pagination.current_page || 1;
        this.lastPage = pagination.last_page || 1;

      } catch (error) {
        console.warn("⚠️ Error:", error);
        this.filteredobjetoarray = {};
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
  },
}
</script>
