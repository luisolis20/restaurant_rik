<template>
  <div class="container section-title" data-aos="fade-up">
    <h2>CALIFICACIONES</h2>
    <p>Lo que nuestros <span class="description-title">clientes dicen</span></p>
  </div>

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div v-if="calificaciones.length > 0" class="vue-carousel">
      
      <div class="vue-carousel-wrapper">
        <transition-group :name="slideDirection">
          <div 
            v-for="(item, index) in calificaciones" 
            :key="item.id" 
            v-show="currentIndex === index"
            class="vue-slide"
          >
            <div class="testimonial-item">
              <div class="row gy-4 justify-content-center align-items-center">
                <div class="col-lg-6">
                  <div class="testimonial-content">
                    <p>
                      <i class="bi bi-quote quote-icon-left"></i>
                      <span>{{ item.comentario || '¡Excelente servicio y sabor inolvidable!' }}</span>
                      <i class="bi bi-quote quote-icon-right"></i>
                    </p>
                    <h3>{{ item.nombre_cliente || 'Cliente Rico Rico' }}</h3>
                    <div class="stars">
                      <i v-for="n in 5" :key="n" class="bi" 
                         :class="n <= item.puntuacion ? 'bi-star-fill' : 'bi-star'"></i>
                    </div>
                  </div>
                </div>
                <div class="col-lg-2 text-center">
                  <div v-if="!item.foto" class="avatar-placeholder">
                    <i class="bi bi-person-circle"></i>
                  </div>
                  <img v-else :src="item.foto" class="img-fluid testimonial-img" :alt="item.nombre_cliente">
                </div>
              </div>
            </div>
          </div>
        </transition-group>
      </div>

      <div class="vue-pagination">
        <span 
          v-for="(_, index) in calificaciones" 
          :key="index"
          class="vue-bullet"
          :class="{ active: currentIndex === index }"
          @click="setSlide(index)"
        ></span>
      </div>
    </div>
    
    <div v-else class="text-center py-5">
      <div v-if="cargando" class="spinner-border text-warning" role="status"></div>
      <p v-else class="text-muted">Aún no hay testimonios, ¡sé el primero en calificar!</p>
    </div>
  </div>
</template>

<script>
import API from "@/assets/js/services/axios";

export default {
  name: "Testimonial",
  data() {
    return {
      baseUrl: "/restrik",
      cargando: false,
      calificaciones: [],
      currentIndex: 0,
      timer: null,
      slideDirection: 'slide-right' // Dirección por defecto
    };
  },
  methods: {
    startTimer() {
      this.timer = setInterval(() => {
        this.nextSlide();
      }, 5000);
    },
    stopTimer() {
      clearInterval(this.timer);
    },
    nextSlide() {
      this.slideDirection = 'slide-right'; // Al ir al siguiente, deslizamos a la derecha
      this.currentIndex = (this.currentIndex + 1) % this.calificaciones.length;
    },
    prevSlide() {
      this.slideDirection = 'slide-left'; // Al ir al anterior, deslizamos a la izquierda
      this.currentIndex = (this.currentIndex - 1 + this.calificaciones.length) % this.calificaciones.length;
    },
    setSlide(index) {
      // Determinamos la dirección según si el índice seleccionado es mayor o menor al actual
      if (index > this.currentIndex) {
        this.slideDirection = 'slide-right';
      } else if (index < this.currentIndex) {
        this.slideDirection = 'slide-left';
      }
      
      this.currentIndex = index;
      this.stopTimer();
      this.startTimer();
    },
    async getCalificaciones() {
      this.cargando = true;
      try {
        const response = await API.get(`${this.baseUrl}/calificaciones_recientes`);
        this.calificaciones = response.data.data || [];
        if (this.calificaciones.length > 0) {
          this.startTimer();
        }
      } catch (error) {
        console.error("Error al traer calificaciones:", error);
      } finally {
        this.cargando = false;
      }
    }
  },
  async mounted() {
    await this.getCalificaciones();
  },
  beforeUnmount() {
    this.stopTimer();
  }
};
</script>