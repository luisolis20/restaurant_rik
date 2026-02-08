<template>
  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Nuesto Menu</h2>
    <p><span>Consulta nuestro</span> <span class="description-title">Rico Menu</span></p>
  </div>
  <!-- End Section Title -->

  <div class="container">
    <div class="row mb-4 justify-content-center">
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" v-model="searchQueryGeneral" @input="debouncedSearchGeneral"
            class="form-control border-start-0" placeholder="Buscar plato en todo el menú..." />
        </div>
      </div>
    </div>
    <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
      <li class="nav-item">
        <a class="nav-link" :class="{ active: selectedCategoryId === '' }" @click="selectCategory('')"
          style="cursor:pointer">
          <h4>Todos</h4>
        </a>
      </li>
      <li v-for="cat in objetoListCategory" :key="cat.id_categoria" class="nav-item">
        <a class="nav-link" :class="{ active: selectedCategoryId === cat.id_categoria }"
          @click="selectCategory(cat.id_categoria)" style="cursor:pointer">
          <h4>{{ cat.nombre }}</h4>
        </a>
      </li>
    </ul>
    <div v-if="selectedCategoryId !== ''" class="row mt-3 justify-content-center" data-aos="fade-up">
      <div class="col-md-4">
        <input type="text" v-model="searchQueryCategory" @input="debouncedSearchCategory"
          class="form-control form-control-sm text-center" :placeholder="'Buscar en ' + currentCategoryName + '...'" />
      </div>
    </div>

    <div class="tab-content mt-4" data-aos="fade-up" data-aos-delay="200">
      <div class="tab-pane fade show active">

        <div v-if="cargando" class="text-center py-5">
          <div class="spinner-border text-danger" role="status"></div>
        </div>

        <div v-else-if="objetoListPlatos.length === 0" class="text-center py-5">
          <p>No se encontraron platos en esta sección.</p>
        </div>

        <div v-else class="row gy-5">
          <div v-for="plato in objetoListPlatos" :key="plato.id_producto" class="col-lg-4 mensu-item">
            <div class="position-relative overflow-hidden">
              <a :href="getPhotoUrl(plato.id_producto)" class="glightbox">
                <img :src="getPhotoUrl(plato.id_producto)" @error="handleImageError" class="mensu-img img-fluid"
                  alt="" />
              </a>

              <div v-if="plato.cantidad_disponible > 0" class="badge-disponible">
                Disponible
              </div>
              <div v-else class="badge-agotado">
                Agotado
              </div>
            </div>

            <h4>{{ plato.productos_nombre }}</h4>
            <p class="ingredientes">{{ truncateText(plato.descripcion, 100) || 'Sin descripción disponible' }}</p>
            <p class="precio">${{ plato.precio }}</p>
            <p class="text-muted" style="font-size: 0.8rem;">Disponible: {{ plato.cantidad_disponible }}</p>
            <button class="btn btn-danger btn-sm rounded-pill mt-2" :disabled="plato.cantidad_disponible <= 0"
              @click="abrirModalPlato(plato)">
              <i class="bi bi-cart-plus-fill"></i> Añadir al carrito
            </button>
          </div>
        </div>
        <div v-if="isProductModalOpen" class="modal-overlay">
          <div class="modal-content-custom animate__animated animate__fadeInDown">
            <div class="modal-header-custom">
              <h3>Detalle del Producto</h3>
              <button @click="cerrarModalPlato" class="btn-close-modal">&times;</button>
            </div>

            <div class="modal-body-custom" v-if="selectedProduct">
              <div class="row">
                <div class="col-md-5">
                  <img :src="getPhotoUrl(selectedProduct.id_producto)" class="img-fluid rounded" alt="">
                </div>
                <div class="col-md-7">
                  <h4>{{ selectedProduct.productos_nombre }}</h4>
                  <p class="text-muted">{{ selectedProduct.descripcion }}</p>
                  <p class="fw-bold">Precio Unitario: ${{ selectedProduct.precio }}</p>

                  <div class="form-group mt-3">
                    <label>Cantidad a pedir:</label>
                    <div class="input-group mb-3" style="max-width: 150px;">
                      <button class="btn btn-outline-secondary" @click="cambiarCantidad(-1)">-</button>
                      <input type="number" v-model.number="pedidoCantidad" class="form-control text-center" readonly>
                      <button class="btn btn-outline-secondary" @click="cambiarCantidad(1)">+</button>
                    </div>
                    <small class="text-danger" v-if="selectedProduct.cantidad_disponible < 10">
                      ¡Solo quedan {{ selectedProduct.cantidad_disponible }} unidades!
                    </small>
                  </div>

                  <div class="total-section mt-4 p-3 bg-light rounded">
                    <h5 class="mb-0">Total: <span class="text-danger">${{ totalCalculado }}</span></h5>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer-custom mt-3">
              <button class="btn btn-secondary" @click="cerrarModalPlato">Cancelar</button> &nbsp;
              <button class="btn btn-danger" :disabled="selectedProduct.cantidad_disponible <= 0 || pedidoCantidad <= 0"
                @click="agregarAlCarrito(selectedProduct)"><i class="bi bi-cart-plus-fill"></i> 
                {{ selectedProduct.cantidad_disponible <= 0 ? 'Agotado' : 'Agregar al Carrito' }} </button>
            </div>
          </div>
        </div>

        <div v-if="lastPagePlatos > 1" class="d-flex justify-content-center mt-5 gap-3">
          <button class="btn btn-outline-danger btn-sm" @click="previousPage"
            :disabled="currentPagePlatos === 1 || cargando">
            <i class="bi bi-chevron-left"></i> Anterior
          </button>
          <span class="align-self-center">Página {{ currentPagePlatos }} de {{ lastPagePlatos }}</span>
          <button class="btn btn-outline-danger btn-sm" @click="nextPage"
            :disabled="currentPagePlatos === lastPagePlatos || cargando">
            Siguiente <i class="bi bi-chevron-right"></i>
          </button>
        </div>

      </div>
    </div>

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
import { debounce } from "lodash";
export default {
  name: "SectionMenu",
  data() {
    return {
      baseUrl: "/restrik",
      objetoListCategory: [],
      objetoListPlatos: [],
      cargando: false,
      pollingTimer: null, // Guardará el temporizador
      isFetching: false,  // Control para evitar peticiones simultáneas

      // Búsquedas
      searchQueryGeneral: "",
      searchQueryCategory: "",
      selectedCategoryId: "",

      // Paginación
      currentPagePlatos: 1,
      lastPagePlatos: 1,
      isProductModalOpen: false,
      selectedProduct: null,
      pedidoCantidad: 1,
    };
  },
  computed: {
    currentCategoryName() {
      const cat = this.objetoListCategory.find(c => c.id_categoria === this.selectedCategoryId);
      return cat ? cat.nombre : '';
    },
    totalCalculado() {
      if (!this.selectedProduct) return 0;
      return (this.selectedProduct.precio * this.pedidoCantidad).toFixed(2);
    }
  },
  async mounted() {
    const ruta = useRoute();

    // 1. Cargar todas las categorías una sola vez
    await this.GetDataCategory();
    // 2. Cargar platos iniciales (todos)
    await this.GetDataPlatos();
    this.startLiveUpdates();
  },
  beforeUnmount() {
    this.stopLiveUpdates();
  },
  created() {
    // Ahora sí puedes usar this.filterAndFetch
    this.debouncedFilter = debounce(() => {
      this.filterAndFetch();
    }, 900);
  },
  watch: {
    // Observamos cuando la lista de platos cambie (por el polling)
    objetoListPlatos: {
      handler(newList) {
        if (this.isProductModalOpen && this.selectedProduct) {
          // Buscamos la versión actualizada del producto que está en el modal
          const platoActualizado = newList.find(
            p => p.id_producto === this.selectedProduct.id_producto
          );

          if (platoActualizado) {
            // Actualizamos la referencia del producto seleccionado
            this.selectedProduct = platoActualizado;

            // Opcional: Si el stock bajó y es menor a lo que el usuario eligió, 
            // ajustamos la cantidad del pedido automáticamente
            if (this.pedidoCantidad > platoActualizado.cantidad_disponible) {
              this.pedidoCantidad = platoActualizado.cantidad_disponible;
            }

            // Si el producto se agotó (stock 0) mientras el modal estaba abierto
            if (platoActualizado.cantidad_disponible <= 0) {
              // Podrías cerrar el modal o mostrar un mensaje
              // this.cerrarModalPlato(); 
            }
          }
        }
      },
      deep: true // Importante para detectar cambios internos en los objetos
    }
  },
  methods: {
    startLiveUpdates() {
      this.pollingTimer = setInterval(async () => {
        // Solo actualiza si:
        // 1. No está cargando ya una petición (isFetching)
        // 2. El usuario NO está escribiendo (searchQueryGeneral vacío o corto)
        if (!this.isFetching && this.searchQueryGeneral === "" && this.searchQueryCategory === "") {
          console.log("Actualizando stock en vivo...");
          await this.GetDataPlatos(this.currentPagePlatos, true); // Pasamos true para modo silencioso
        }
      }, 10000); // 10 segundos es un tiempo prudente para evitar el Error 429
    },
    stopLiveUpdates() {
      if (this.pollingTimer) {
        clearInterval(this.pollingTimer);
      }
    },
    getPhotoUrl(ci) {
      const baseURL2 = API.defaults.baseURL;
      return `${baseURL2}/restrik/imagenprod/${ci}`;
    },
    truncateText(texto, limite) {
      if (!texto) return '';
      return texto.length > limite ? texto.slice(0, limite) + '...' : texto;
    },
    handleImageError(event) {
      // Reemplaza la imagen con el ícono de usuario por defecto
      event.target.src =
        "https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/480px-User_icon_2.svg.png";
    },
    async GetDataPlatos(page = 1, silent = false) {
      if (!silent) this.cargando = true;
      this.isFetching = true;
      try {
        const params = {
          page: page,
          // Si hay búsqueda general, ignora la categoría. Si no, usa la seleccionada + búsqueda local.
          search_query: this.searchQueryGeneral || this.searchQueryCategory,
          id_categoria: this.selectedCategoryId,
          status: 1
        };

        const response = await API.get(`${this.baseUrl}/inventarios`, { params });
        this.objetoListPlatos = response.data?.data || [];
        this.currentPagePlatos = response.data?.pagination?.current_page || 1;
        this.lastPagePlatos = response.data?.pagination?.last_page || 1;
      } catch (error) {
        this.objetoListPlatos = [];
      } finally {
        if (!silent) this.cargando = false;
        this.isFetching = false;
      }
    },
    async GetDataCategory() {
      try {
        const response = await API.get(`${this.baseUrl}/categorias`, { params: { status: 1 } });
        this.objetoListCategory = response.data?.data || [];
      } catch (error) {
        console.error("Error categorías:", error);
      }
    },
    // --- ACCIONES ---
    selectCategory(id) {
      this.selectedCategoryId = id;
      this.searchQueryGeneral = ""; // Limpiamos búsqueda general al filtrar por categoría
      this.searchQueryCategory = "";
      this.currentPagePlatos = 1;
      this.GetDataPlatos(1);
    },

    debouncedSearchGeneral: debounce(function () {
      this.selectedCategoryId = ""; // Al buscar general, reseteamos categoría
      this.searchQueryCategory = "";
      this.currentPagePlatos = 1;
      this.GetDataPlatos(1);
    }, 500),

    debouncedSearchCategory: debounce(function () {
      this.currentPagePlatos = 1;
      this.GetDataPlatos(1);
    }, 500),

    nextPage() {
      if (this.currentPagePlatos < this.lastPagePlatos) {
        this.GetDataPlatos(this.currentPagePlatos + 1);
      }
    },

    previousPage() {
      if (this.currentPagePlatos > 1) {
        this.GetDataPlatos(this.currentPagePlatos - 1);
      }
    },
    abrirModalPlato(plato) {
      this.selectedProduct = plato;
      this.pedidoCantidad = 1;
      this.isProductModalOpen = true;
    },
    cerrarModalPlato() {
      this.isProductModalOpen = false;
      this.selectedProduct = null;
    },
    cambiarCantidad(valor) {
      const nuevaCantidad = this.pedidoCantidad + valor;
      // Validación: No menos de 1 y no más de lo disponible
      if (nuevaCantidad >= 1 && nuevaCantidad <= this.selectedProduct.cantidad_disponible) {
        this.pedidoCantidad = nuevaCantidad;
      }
    },
    agregarAlCarrito(plato) {
      
      this.$emit('agregar-al-carrito', {
        id: plato.id_producto,
        nombre: plato.productos_nombre,
        precio: plato.precio,
        cantidad: this.pedidoCantidad
      });
      this.cerrarModalPlato();
    },
  },
};
</script>
<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1100;
}

.modal-content-custom {
  background: white;
  padding: 2rem;
  border-radius: 15px;
  width: 90%;
  max-width: 700px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header-custom {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px solid #eee;
  margin-bottom: 1rem;
}

.btn-close-modal {
  background: none;
  border: none;
  font-size: 2rem;
  cursor: pointer;
}
</style>
