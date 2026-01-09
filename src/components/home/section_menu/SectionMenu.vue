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
          <span class="input-group-text bg-white border-end-0"
            ><i class="bi bi-search"></i
          ></span>
          <input
            type="text"
            v-model="searchQueryGeneral"
            @input="debouncedSearchGeneral"
            class="form-control border-start-0"
            placeholder="Buscar plato en todo el menú..."
          />
        </div>
      </div>
    </div>
    <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
      <li class="nav-item">
        <a class="nav-link" :class="{ active: selectedCategoryId === '' }" @click="selectCategory('')" style="cursor:pointer">
          <h4>Todos</h4>
        </a>
      </li>
      <li v-for="cat in objetoListCategory" :key="cat.id_categoria" class="nav-item">
        <a 
          class="nav-link" 
          :class="{ active: selectedCategoryId === cat.id_categoria }" 
          @click="selectCategory(cat.id_categoria)"
          style="cursor:pointer"
        >
          <h4>{{ cat.nombre }}</h4>
        </a>
      </li>
    </ul>
    <div v-if="selectedCategoryId !== ''" class="row mt-3 justify-content-center" data-aos="fade-up">
      <div class="col-md-4">
        <input 
          type="text" 
          v-model="searchQueryCategory" 
          @input="debouncedSearchCategory"
          class="form-control form-control-sm text-center" 
          :placeholder="'Buscar en ' + currentCategoryName + '...'"
        />
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
            <a :href="getPhotoUrl(plato.id_producto)" class="glightbox">
              <img :src="getPhotoUrl(plato.id_producto)" @error="handleImageError" class="mensu-img img-fluid" alt="" />
            </a>
            <h4>{{ plato.nombre }}</h4>
            <p class="ingredientes">{{ truncateText(plato.descripcion, 100) || 'Sin descripción disponible' }}</p>
            <p class="precio">${{ plato.precio }}</p>
          </div>
        </div>

        <div v-if="lastPagePlatos > 1" class="d-flex justify-content-center mt-5 gap-3">
          <button 
            class="btn btn-outline-danger btn-sm" 
            @click="previousPage" 
            :disabled="currentPagePlatos === 1 || cargando"
          >
            <i class="bi bi-chevron-left"></i> Anterior
          </button>
          <span class="align-self-center">Página {{ currentPagePlatos }} de {{ lastPagePlatos }}</span>
          <button 
            class="btn btn-outline-danger btn-sm" 
            @click="nextPage" 
            :disabled="currentPagePlatos === lastPagePlatos || cargando"
          >
            Siguiente <i class="bi bi-chevron-right"></i>
          </button>
        </div>

      </div>
    </div>
  </div>
</template>
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
      
      // Búsquedas
      searchQueryGeneral: "",
      searchQueryCategory: "",
      selectedCategoryId: "",
      
      // Paginación
      currentPagePlatos: 1,
      lastPagePlatos: 1,
    };
  },
  computed: {
    currentCategoryName() {
      const cat = this.objetoListCategory.find(c => c.id_categoria === this.selectedCategoryId);
      return cat ? cat.nombre : '';
    }
  },
  async mounted() {
    const ruta = useRoute();

    // 1. Cargar todas las categorías una sola vez
    await this.GetDataCategory();
    // 2. Cargar platos iniciales (todos)
    await this.GetDataPlatos();
  },
  created() {
    // Ahora sí puedes usar this.filterAndFetch
    this.debouncedFilter = debounce(() => {
      this.filterAndFetch();
    }, 900);
  },
  methods: {
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
    async GetDataPlatos(page = 1) {
      this.cargando = true;
      try {
        const params = {
          page: page,
          // Si hay búsqueda general, ignora la categoría. Si no, usa la seleccionada + búsqueda local.
          search_query: this.searchQueryGeneral || this.searchQueryCategory,
          id_categoria: this.selectedCategoryId, 
          status: 1
        };
        
        const response = await API.get(`${this.baseUrl}/productos`, { params });
        this.objetoListPlatos = response.data?.data || [];
        this.currentPagePlatos = response.data?.pagination?.current_page || 1;
        this.lastPagePlatos = response.data?.pagination?.last_page || 1;
      } catch (error) {
        this.objetoListPlatos = [];
      } finally {
        this.cargando = false;
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

    debouncedSearchGeneral: debounce(function() {
      this.selectedCategoryId = ""; // Al buscar general, reseteamos categoría
      this.searchQueryCategory = "";
      this.currentPagePlatos = 1;
      this.GetDataPlatos(1);
    }, 500),

    debouncedSearchCategory: debounce(function() {
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
  },
};
</script>
