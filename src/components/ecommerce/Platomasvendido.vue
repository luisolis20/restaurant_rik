<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Plato más vendido</h3>
        <p class="text-sm text-gray-500">Año seleccionado: {{ selectedYear }}</p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="downloadPDF"
          class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
          :disabled="loading">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
          </svg>
          PDF
        </button>
        <div class="relative h-fit">
          <DropdownMenu :menu-items="yearMenuItems">
            <template #icon>
              <div
                class="flex items-center gap-2 cursor-pointer bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <span class="text-sm font-medium text-gray-700 dark:text-white">{{ selectedYear }}</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </div>
            </template>
          </DropdownMenu>
        </div>
      </div>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <div id="chartOne" class="-ml-5 min-w-[650px] xl:min-w-full pl-2">
        <VueApexCharts v-if="!loading" type="bar" height="180" :options="chartOptions" :series="series" />
        <div v-else class="h-[180px] flex items-center justify-center">
          <span class="text-gray-400 animate-pulse">Cargando estadísticas...</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import API from "@/assets/js/services/axios";
import VueApexCharts from 'vue3-apexcharts';
import DropdownMenu from '../common/DropdownMenu.vue';
import { jsPDF } from "jspdf";
import html2canvas from "html2canvas";
import logoRico from '@/assets/img/logo.png'

export default {
  name: 'Platomasvendido',
  components: {
    VueApexCharts,
    DropdownMenu
  },
  data() {
    return {
      loading: true,
      baseUrl: "/restrik",
      pollingInterval: null,
      selectedYear: new Date().getFullYear(),
      availableYears: [],
      topQuantities: [],
      series: [{
        name: 'Ventas',
        data: []
      }],
      topProducts: [], // Nuevo array para guardar los nombres
      chartOptions: {
        colors: ['#F5B30F'],
        chart: {
          fontFamily: 'Outfit, sans-serif',
          type: 'bar',
          toolbar: { show: false },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '35%',
            borderRadius: 5,
            borderRadiusApplication: 'end',
          },
        },
        dataLabels: { enabled: false },
        xaxis: {
          categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
          axisBorder: { show: false },
          axisTicks: { show: false },
        },
        yaxis: {
          labels: {
            formatter: (val) => `$${val.toFixed(0)}`
          }
        },
        tooltip: {
          shared: true,
          intersect: false, // Obligatorio para que funcione con shared: true
          custom: ({ series, seriesIndex, dataPointIndex, w }) => {
            const val = series[seriesIndex][dataPointIndex];
            // Validamos que existan datos para ese mes
            const producto = this.topProducts && this.topProducts[dataPointIndex]
              ? this.topProducts[dataPointIndex]
              : 'Sin datos';

            return `
        <div class="p-2 shadow-lg border-0 bg-white dark:bg-gray-800">
          <div class="text-[10px] text-gray-500 mb-1 font-medium">Ventas: ${w.globals.labels[dataPointIndex].toUpperCase()}</div>
          <div class="flex flex-col gap-1">
            <span class="text-sm font-bold text-gray-800 dark:text-white">$${val.toFixed(2)}</span>
            <div class="flex items-center gap-1 text-[11px] text-orange-500 font-semibold border-t border-gray-100 dark:border-gray-700 mt-1 pt-1">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 9l6 6 6-6"/>
              </svg>
              <span>TOP: ${producto}</span>
            </div>
          </div>
        </div>
      `;
          }
        }
      }
    };
  },
  unmounted() {
    if (this.pollingInterval) clearInterval(this.pollingInterval);
  },
  computed: {
    // Transformamos la lista de años en el formato que espera tu DropdownMenu
    yearMenuItems() {
      return this.availableYears.map(year => ({
        label: year.toString(),
        onClick: () => this.fetchSalesData(year)
      }));
    }
  },
  methods: {
    async actualizarSilenciosamente() {
      try {
        const response = await API.get(`${this.baseUrl}/dashboard/platomasvendido?year=${this.selectedYear}`);
        this.series[0].data = response.data.sales_data;
        this.topProducts = response.data.top_products; // Guardamos los platos
        this.availableYears = response.data.years;
        this.selectedYear = response.data.selected_year;
        this.topQuantities = response.data.top_quantities;
      } catch (error) {
        console.warn("Error en actualización silenciosa", error);
      }
    },
    async fetchSalesData(year) {
      this.loading = true;
      try {
        const response = await API.get(`${this.baseUrl}/dashboard/platomasvendido?year=${year}`);

        // Actualizamos los datos del gráfico
        this.series[0].data = response.data.sales_data;
        this.topProducts = response.data.top_products; // Guardamos los platos
        this.availableYears = response.data.years;
        this.selectedYear = response.data.selected_year;
        this.topQuantities = response.data.top_quantities;
      } catch (error) {
        console.error("Error al obtener datos de ventas:", error);
      } finally {
        this.loading = false;
      }
    },
    async downloadPDF() {
      const doc = new jsPDF('p', 'mm', 'a4');
      const dateStr = new Date().toLocaleString();

      // 1. Encabezado y Logo (Igual que antes)
      try { doc.addImage(logoRico, 'PNG', 85, 10, 40, 25); } catch (e) { }
      doc.setFont("helvetica", "bold");
      doc.setFontSize(20);
      doc.setTextColor(245, 179, 15);
      doc.text("RESTAURANTE RICO RICO", 105, 45, { align: "center" });

      // 2. Información General
      doc.setFontSize(12);
      doc.setTextColor(40);
      doc.text(`Reporte de Ventas Anual: ${this.selectedYear}`, 20, 58);
      doc.setFontSize(9);
      doc.text(`Fecha: ${dateStr}`, 20, 64);
      doc.line(20, 67, 190, 67);

      // 3. Captura de Gráfica (ID: chartOne)
      const chartElement = document.getElementById('chartOne');
      if (chartElement) {
        const canvas = await html2canvas(chartElement, { scale: 2, backgroundColor: "#ffffff" });
        const imgData = canvas.toDataURL("image/png");
        doc.addImage(imgData, 'PNG', 15, 75, 180, 50);
      }

      // 4. TABLA CON COLUMNA DE UNIDADES
      doc.setFont("helvetica", "bold");
      doc.setFontSize(10);
      doc.text("Detalle Mensual de Ventas y Platos Estrella", 20, 140);

      // Encabezados de tabla
      const tableTop = 148;
      doc.setFillColor(245, 245, 245);
      doc.rect(20, tableTop - 5, 170, 7, 'F');
      doc.setFontSize(8);
      doc.text("MES", 25, tableTop);
      doc.text("PLATO ESTRELLA", 55, tableTop);
      doc.text("CANT.", 145, tableTop, { align: "center" });
      doc.text("VENTA MENSUAL", 185, tableTop, { align: "right" });

      let totalAnual = 0;
      let totalUnidades = 0;
      const startY = 155;
      const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

      months.forEach((month, i) => {
        const yPos = startY + (i * 7);
        const valorVenta = Number(this.series[0].data[i]) || 0;
        const unidades = this.topQuantities[i] || 0;
        const nombreProducto = this.topProducts[i] || '---';

        totalAnual += valorVenta;
        totalUnidades += unidades;

        doc.setFont("helvetica", "bold");
        doc.text(month, 25, yPos);

        doc.setFont("helvetica", "normal");
        doc.text(nombreProducto, 55, yPos);

        doc.text(unidades.toString(), 145, yPos, { align: "center" }); // Columna Unidades

        doc.setFont("helvetica", "bold");
        doc.text(`$${valorVenta.toFixed(2)}`, 185, yPos, { align: "right" });

        doc.setDrawColor(230);
        doc.line(20, yPos + 2, 190, yPos + 2);
      });

      // 5. RESUMEN TOTAL FINAL
      const finalY = startY + (12 * 7) + 5;
      doc.setFillColor(245, 179, 15);
      doc.rect(20, finalY, 170, 10, 'F');

      doc.setFontSize(11);
      doc.setTextColor(255, 255, 255);
      doc.text("RESUMEN ANUAL TOTAL:", 25, finalY + 7);

      // Mostramos total unidades y total dinero
      doc.setFontSize(9);
      doc.text(`${totalUnidades} u.`, 145, finalY + 7, { align: "center" });
      doc.setFontSize(11);
      doc.text(`$${totalAnual.toFixed(2)}`, 185, finalY + 7, { align: "right" });

      doc.save(`Reporte_Ventas_RicoRico_${this.selectedYear}.pdf`);
    }
  },
  mounted() {
    // Carga inicial de datos
    this.pollingInterval = setInterval(() => {
      this.actualizarSilenciosamente();
    }, 10000);
    this.fetchSalesData(this.selectedYear);
  }
};
</script>