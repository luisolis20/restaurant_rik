<template>
  <div v-if="esCocinero" class="relative" ref="dropdownRef">
    <button
      class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
      @click="toggleDropdown"
    >
      <span v-if="notifying" class="absolute right-0 top-0.5 z-1 h-2 w-2 rounded-full bg-orange-400">
        <span class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 animate-ping"></span>
      </span>
      
      <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
        <path d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z" />
      </svg>
    </button>

    <div
      v-if="dropdownOpen"
      class="absolute -right-[240px] mt-[17px] flex h-[480px] w-[350px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark lg:right-0"
    >
      <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 dark:border-gray-800">
        <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">Pedidos en Cocina</h5>
        <button @click="closeDropdown" class="text-gray-500">
          <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24"><path d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z" /></svg>
        </button>
      </div>

      <ul class="flex flex-col h-auto overflow-y-auto custom-scrollbar">
        <li v-if="notifications.length === 0" class="p-4 text-center text-gray-500">
          No hay pedidos pendientes
        </li>
        <li v-for="notification in notifications" :key="notification.id">
          <router-link 
            to="/pedidos_recientes" 
            @click="closeDropdown"
            class="flex gap-3 rounded-lg border-b border-gray-100 p-3 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-white/5"
          >
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-50 text-orange-500 dark:bg-orange-500/10">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
              </svg>
            </div>
            <div class="block">
              <span class="mb-1 block text-theme-sm text-gray-500">
                <span class="font-bold text-gray-800 dark:text-white">{{ notification.mesa }}</span>
                ha enviado un nuevo pedido.
              </span>
              <span class="text-theme-xs text-orange-400 font-medium">Hace {{ notification.haceCuanto }}</span>
            </div>
          </router-link>
        </li>
      </ul>
      <router-link 
        to="/pedidos_recientes" 
        @click="closeDropdown"
        class="block py-2 mt-2 text-center text-sm font-medium text-orange-500 hover:text-orange-600 border-t border-gray-100 dark:border-gray-800"
      >
        Ver todos los pedidos
      </router-link>
    </div>
  </div>
</template>

<script>
import API from "@/assets/js/services/axios";
import { useUsuario } from '@/composables/useUsuario';
import sonidoNotificacion from '@/assets/sounds/notificacion.mp3';

export default {
  name: 'NotificacionesCocina',
  data() {
    return {
      dropdownOpen: false,
      notifying: false,
      notifications: [],
      pollingInterval: null,
      timeUpdateInterval: null,
      audio: new Audio(sonidoNotificacion)
    };
  },
  setup() {
    const { rolUsuario } = useUsuario();
    return { rolUsuario };
  },
  computed: {
    esCocinero() {
      return this.rolUsuario === 'Cocinero';
    }
  },
  methods: {
    async cargarPedidos() {
      if (!this.esCocinero) return;
      try {
        const res = await API.get('/restrik/notificacionpedidos');
        if (res.data && res.data.data) {
          const nuevosPedidos = res.data.data.map(pedido => ({
            id: pedido.id_pedido,
            mesa: `Mesa ${pedido.codigo_mesa || pedido.id_mesa}`, // Ajusta según tu campo del controlador
            fechaOriginal: pedido.fecha_pedido,
            haceCuanto: this.calcularTiempoRelativo(pedido.fecha_pedido)
          }));

          // Si hay más pedidos que antes, activamos la alerta visual
          if (nuevosPedidos.length > this.notifications.length) {
            this.playNotificacion();
            this.notifying = true;
          }
          this.notifications = nuevosPedidos;
        }
      } catch (error) {
        console.error("Error notificaciones Rico Rico:", error);
      }
    },
    playNotificacion() {
      // Reiniciamos el audio por si suena dos veces seguidas rápido
      this.audio.pause();
      this.audio.currentTime = 0;
      
      // Intentamos reproducir (el navegador requiere interacción previa del usuario)
      this.audio.play().catch(error => {
        console.warn("El navegador bloqueó el audio. Se requiere un clic previo en la página.", error);
      });
    },
    calcularTiempoRelativo(fecha) {
      if (!fecha) return 'ahora';
      const ahora = new Date();
      const pedidoFecha = new Date(fecha);
      const diferenciaMs = ahora - pedidoFecha;
      const minutos = Math.floor(diferenciaMs / 60000);

      if (minutos < 1) return 'segundos';
      if (minutos < 60) return `${minutos} min`;
      const horas = Math.floor(minutos / 60);
      return `${horas} h`;
    },
    actualizarTiemposRelativos() {
      this.notifications = this.notifications.map(n => ({
        ...n,
        haceCuanto: this.calcularTiempoRelativo(n.fechaOriginal)
      }));
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
      if (this.dropdownOpen) this.notifying = false;
    },
    closeDropdown() {
      this.dropdownOpen = false;
    },
    handleClickOutside(event) {
      if (this.$refs.dropdownRef && !this.$refs.dropdownRef.contains(event.target)) {
        this.closeDropdown();
      }
    }
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
    if (this.esCocinero) {
      this.cargarPedidos();
      // Polling de nuevos datos cada 30 segundos
      this.pollingInterval = setInterval(this.cargarPedidos, 30000);
      // Actualizar el texto "Hace 2 min" cada 60 segundos sin llamar a la API
      this.timeUpdateInterval = setInterval(this.actualizarTiemposRelativos, 60000);
    }
  },
  beforeUnmounted() {
    document.removeEventListener('click', this.handleClickOutside);
    if (this.pollingInterval) clearInterval(this.pollingInterval);
    if (this.timeUpdateInterval) clearInterval(this.timeUpdateInterval);
  }
};
</script>