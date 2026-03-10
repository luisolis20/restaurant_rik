<template>
  <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
      <div class="w-full">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Estadísticas Anuales</h3>
        <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
          Comparativa de ingresos vs volumen de pedidos
        </p>
      </div>

      <div class="relative">
        <div class="inline-flex items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
          <button
            v-for="option in options"
            :key="option.value"
            @click="selected = option.value"
            :class="[
              selected === option.value
                ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                : 'text-gray-500 dark:text-gray-400',
              'px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:bg-gray-800',
            ]"
          >
            {{ option.label }}
          </button>
        </div>
      </div>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <div id="chartThree" class="-ml-4 min-w-[1000px] xl:min-w-full pl-2">
        <VueApexCharts 
          v-if="!loading"
          type="area" 
          height="310" 
          :options="chartOptions" 
          :series="series" 
        />
        <div v-else class="h-[310px] flex items-center justify-center text-gray-400">
          Cargando datos estadísticos...
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import API from "@/assets/js/services/axios";
import VueApexCharts from 'vue3-apexcharts';

export default {
  name: 'StatisticsChart',
  components: { VueApexCharts },
  data() {
    return {
      loading: true,
      baseUrl: "/restrik",
      pollingInterval: null,
      selected: 'monthly', // Valores: monthly, quarterly, annually
      options: [
        { value: 'monthly', label: 'Mensual' },
        { value: 'quarterly', label: 'Trimestral' },
        { value: 'annually', label: 'Anual' },
      ],
      series: [
        { name: 'Ventas ($)', data: [] },
        { name: 'Pedidos (Cant.)', data: [] }
      ],
      chartOptions: {
        legend: { show: true, position: 'top', horizontalAlign: 'right' },
        colors: ['#F5B30F', '#D99C0D'],
        chart: { type: 'area', toolbar: { show: false } },
        stroke: { curve: 'smooth', width: [3, 3] },
        xaxis: {
          categories: [], // Se llenará dinámicamente
          axisBorder: { show: false },
          axisTicks: { show: false },
        },
        tooltip: {
          shared: true,
          y: {
            formatter: (val, { seriesIndex }) => 
              seriesIndex === 0 ? `$${val.toLocaleString()}` : `${val} pedidos`
          }
        }
      }
    };
  },
  watch: {
    // Cada vez que el usuario cambie el filtro, disparamos la petición
    selected(newVal) {
      this.fetchStatistics(newVal);
    }
  },
  unmounted() {
    if (this.pollingInterval) clearInterval(this.pollingInterval);
  },
  methods: {
    async actualizarSilenciosamente() {
      try {
        const response = await API.get(`${this.baseUrl}/dashboard/statistics2?filter=${this.selected}`);
        this.series[0].data = response.data.sales;
        this.series[1].data = response.data.orders;

        // Actualizar categorías del eje X dinámicamente
        this.chartOptions = {
          ...this.chartOptions,
          xaxis: {
            ...this.chartOptions.xaxis,
            categories: response.data.categories
          }
        };
      } catch (error) {
        console.warn("Error en actualización silenciosa", error);
      }
    },
    async fetchStatistics(filterType = 'monthly') {
      this.loading = true;
      try {
        const response = await API.get(`${this.baseUrl}/dashboard/statistics2?filter=${filterType}`);
        
        // Actualizar datos
        this.series[0].data = response.data.sales;
        this.series[1].data = response.data.orders;

        // Actualizar categorías del eje X dinámicamente
        this.chartOptions = {
          ...this.chartOptions,
          xaxis: {
            ...this.chartOptions.xaxis,
            categories: response.data.categories
          }
        };
      } catch (error) {
        console.error("Error al cargar estadísticas:", error);
      } finally {
        this.loading = false;
      }
    }
  },
  mounted() {
     this.pollingInterval = setInterval(() => {
      this.actualizarSilenciosamente();
    }, 10000);
    this.fetchStatistics();
  }
};
</script>