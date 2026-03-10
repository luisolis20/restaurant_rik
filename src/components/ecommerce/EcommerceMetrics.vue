<template>
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
        <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24">
          <path
            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.39 2.1-1.39 1.47 0 2.01.59 2.06 1.47h1.73c-.05-1.55-1.01-2.67-2.61-3.03V5h-2.13v1.51c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.47H8.33c.08 1.63 1.22 2.71 2.85 3.09V19h2.13v-1.51c1.55-.32 2.81-1.21 2.81-2.81 0-2.18-1.89-2.69-3.82-3.21z" />
        </svg>
      </div>

      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Total Ventas</span>
          <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
            ${{ stats.total_ventas.toLocaleString('en-US', { minimumFractionDigits: 2 }) }}
          </h4>
        </div>
        <span class="text-xs font-medium text-success-600 bg-success-50 rounded-full px-2 py-0.5">Hoy</span>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
        <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24">
          <path
            d="M13.0066 2.41456C12.3732 2.09786 11.6277 2.09786 10.9942 2.41456L4.03676 5.89319C3.27449 6.27432 2.79297 7.05342 2.79297 7.90566V16.0946C2.79297 16.9469 3.27448 17.726 4.03676 18.1071L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.726 21.2079 16.9469 21.2079 16.0946V7.90566C21.2079 7.05342 20.7264 6.27432 19.9641 5.89319L13.0066 2.41456Z" />
        </svg>
      </div>

      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Pedidos Totales</span>
          <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
            {{ stats.total_pedidos }}
          </h4>
        </div>
        <span class="text-xs font-medium text-gray-600 bg-gray-50 rounded-full px-2 py-0.5">Global</span>
      </div>
    </div>
  </div>
</template>

<script>
import API from "@/assets/js/services/axios";
export default {
  name: 'DashboardStats',
  data() {
    return {
      stats: {
        total_ventas: 0,
        total_pedidos: 0
      },
      baseUrl: "/restrik",
      pollingInterval: null,
      loading: true
    };
  },
  unmounted() {
    if (this.pollingInterval) clearInterval(this.pollingInterval);
  },
  mounted() {
    this.pollingInterval = setInterval(() => {
      this.actualizarSilenciosamente();
    }, 10000);
    this.fetchStats();
  },
  methods: {
    async actualizarSilenciosamente() {
      try {
        const response = await API.get(`${this.baseUrl}/dashboard/stats`);
        this.stats = response.data;
      } catch (error) {
        console.warn("Error en actualización silenciosa", error);
      }
    },
    async fetchStats() {
      try {
        const response = await API.get(`${this.baseUrl}/dashboard/stats`);
        this.stats = response.data;
      } catch (error) {
        console.error("Error al obtener estadísticas:", error);
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>