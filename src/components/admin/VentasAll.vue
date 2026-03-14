<template>
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <!-- Search Form -->
                <form class="flex-grow">
                    <div class="relative">
                        <button class="absolute -translate-y-1/2 left-4 top-1/2">
                            <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                                    fill="" />
                            </svg>
                        </button>
                        <!-- @input llama al debouncedFilter, que inicia la nueva consulta al backend -->
                        <input type="text" placeholder="Ingresa el código de la mesa a buscar..." v-model="searchQuery"
                            @input="debouncedFilter"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:bg-white/[0.03] dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 xl:w-[430px]" />
                    </div>
                </form>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <button @click="isFilterDropdownOpen = !isFilterDropdownOpen"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        <svg class="stroke-current fill-white dark:fill-gray-800" width="20" height="20"
                            viewBox="0 0 20 20" fill="none">
                            <path d="M2.29004 5.90393H17.7067" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M17.7075 14.0961H2.29085" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
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
                                Pendiente
                            </button>
                            <button @click="setStatusFilter('autorizada')"
                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                Autorizada
                            </button>
                            <button @click="setStatusFilter('pagada')"
                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                Pagada
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
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">ID</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">COD Mesa</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Número</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tipo Comprobante</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Subtotal</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Total</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Estado</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Fecha Emisión</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100 dark:border-gray-800" v-if="cargando">
                        <td class="px-5 py-4 sm:px-6" colspan="9">
                            <h3 class="text-center">Cargando....</h3>
                        </td>
                    </tr>
                    <tr v-else v-for="post in filteredobjetoarray" :key="post.id_factura"
                        class="border-t border-gray-100 dark:border-gray-800">
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ post.id_factura }}</p>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div>
                                    <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                        {{ post.codigo_mesa }}
                                    </p>

                                </div>
                            </div>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    {{ post.numero_factura }}
                                </p>
                            </div>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    {{ post.tipo_comprobante }}
                                </p>
                            </div>

                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${{ post.subtotal }}
                                </p>
                            </div>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    ${{ post.total }}
                                </p>
                            </div>
                        </td>

                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <span :class="{
                                    'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
                                    'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500':
                                        post.estado_factura === 'pagada',
                                    'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400':
                                        post.estado_factura === 'pendiente',
                                    'bg-info-50 text-info-600 dark:bg-info-500/15 dark:text-blue-400':
                                        post.estado_factura === 'autorizada',
                                }">
                                    {{ post.estado_factura }}
                                </span>
                            </div>

                        </td>
                        <!-- Fecha de emisión Fecha y hora formateada-->
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                    {{ (post.fecha_emision) }}
                                </p>
                            </div>
                        </td>
                        <!-- Acciones de Edición y Eliminación -->
                        <td class="py-3 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <button @click="abrirModalFactura(post.id_pedido)"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <line x1="10" y1="9" x2="8" y2="9"></line>
                                    </svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br /><br />
        <!-- Botones de Paginación -->
        <div class="d-flex justify-content-center mb-4">
            <button @click="previousPage" :disabled="currentPage === 1 || buscando" class="btn btn-primary text-white">
                <i class="fas fa-angle-left"></i></button>&nbsp; <span class="text-dark">Página {{ currentPage }} de {{
                    lastPage
                }}</span>&nbsp;
            <button @click="nextPage" :disabled="currentPage === lastPage || buscando"
                class="btn btn-primary text-white">
                <i class="fas fa-angle-right"></i>
            </button>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <div class="d-flex justify-content-center mb-4" v-if="!cargando">
            &nbsp;&nbsp;&nbsp;
            <button class="btn btn-primary text-white" @click="actualizar">Actualizar</button>
        </div>
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
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
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
                    <div class="px-6 pb-4 overflow-y-auto custom-scrollbar lg:px-11">
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
                                        <td class="py-4 text-right font-medium dark:text-white">${{ item.subtotal }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div
                            class="px-8 py-6 bg-gray-50 dark:bg-white/[0.02] border-t border-gray-100 dark:border-gray-800">
                            <div class="flex flex-col gap-2 w-full max-w-[200px] ml-auto">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Subtotal:</span>
                                    <span class="font-medium dark:text-gray-300">${{ facturaData.subtotal }}</span>
                                </div>
                                <div
                                    class="flex justify-between text-xl font-bold border-t border-gray-200 dark:border-gray-700 pt-2">
                                    <span class="text-gray-800 dark:text-white">TOTAL:</span>
                                    <span class="text-brand-600">${{ facturaData.total }}</span>
                                </div>
                            </div>

                            <div class="mt-8 flex gap-3">
                                <button @click="isInvoiceModalOpen = false"
                                    class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                                    Cerrar
                                </button>
                                <button @click="imprimirFactura"
                                    class="flex-1 bg-gray-800 dark:bg-brand-500 text-white px-4 py-2.5 rounded-xl font-medium hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2m-2 4H6a2 2 0 0 1-2-2v-4h12v4a2 2 0 0 1-2 2z">
                                        </path>
                                    </svg>
                                    Imprimir
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </template>
        </Modal>
        <div id="print-section" class="ticket-container">
            <div class="ticket-wrapper">
                <div class="ticket-header">
                    <img src="@/assets/img/logo.png" alt="Logo" class="ticket-logo" width="60%">
                    <h2 class="ticket-title">{{ facturaData.tipo_comprobante }}</h2>
                    <p class="ticket-number">{{ facturaData.numero_factura }}</p>
                </div>

                <div class="ticket-info">
                    <p>FECHA: {{ facturaData.fecha_emision }}</p>
                    <p>PEDIDO: #{{ facturaData.id_pedido }}</p>
                    <p>MESA: {{ facturaData.mesa?.codigo_mesa || 'Cargando...' }}</p>
                    <p>--------------------------------</p>
                </div>

                <table class="ticket-table">
                    <thead>
                        <tr>
                            <th class="text-left">DESC.</th>
                            <th class="text-center">CANT.</th>
                            <th class="text-right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in facturaDetalle" :key="item.id_detalle_factura">
                            <td class="desc-col">{{ item.descripcion }}</td>
                            <td class="text-center">{{ item.cantidad }}</td>
                            <td class="text-right">${{ item.subtotal }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="ticket-divider">--------------------------------</div>

                <div class="ticket-totals">
                    <div class="total-row">
                        <span>SUBTOTAL:</span>
                        <span>${{ facturaData.subtotal }}</span>
                    </div>
                    <div class="total-row bold Large">
                        <span>TOTAL:</span>
                        <span>${{ facturaData.total }}</span>
                    </div>
                </div>

                <div class="ticket-footer">
                    <p>Gracias por su preferencia.</p>
                    <p>¡Vuelva pronto!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 space-y-6 xl:col-span-7">
      <MonthlySale />
    </div>
</template>

<script setup>
import { ref, watch, nextTick, onBeforeUnmount } from "vue";
import Modal from "@/components/Modal/Modal.vue";
import MonthlySale from '@/components/ecommerce/MonthlySale.vue'

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
            objetoguardar: {
                codigo_mesa: "",
                capacidad: "",
                estado: "",
            },
            objetoeditar: {
                id_mesa: "",
                codigo_mesa: "",
                capacidad: "",
                estado: "",
            },
            filteredobjetoarray: [],
            searchQuery: "",
            selectedStatus: "", // Nuevo: estado seleccionado
            isFilterDropdownOpen: false, // Nuevo: control del menu
            isProfileAddressModal: false,
            cargando: false,
            password: "",
            currentPage: 1,
            lastPage: 1,
            buscando: false, // Mantenido, pero no se usa en la lógica de paginación actual
            debouncedFilter: null,
            pollingInterval: null,
            objetoList: [],
            isInvoiceModalOpen: false,
            facturaData: {},
            facturaDetalle: [],
        };
    },
    unmounted() {
        if (this.pollingInterval) clearInterval(this.pollingInterval);
    },
    created() {
        // Ahora sí puedes usar this.filterAndFetch
        this.debouncedFilter = debounce(() => {
            this.filterAndFetch();
        }, 900);
    },
    async mounted() {
        const ruta = useRoute();

        this.pollingInterval = setInterval(() => {
            this.actualizarSilenciosamente();
        }, 10000);
        this.GetData(1, this.searchQuery, this.selectedStatus);

    },


    methods: {
        imprimirFactura() {
            window.print();
            this.isInvoiceModalOpen = false;
        },
        async actualizarSilenciosamente() {
            try {
                const params = { page: this.currentPage, status: this.selectedStatus };
                const response = await API.get(`${this.baseUrl}/facturas`, { params });
                this.filteredobjetoarray = response.data?.data || {};
                this.lastPage = response.data?.pagination?.last_page || 1;
            } catch (error) {
                console.warn("Error en actualización silenciosa", error);
            }
        },
        async abrirModalFactura(idPedido) {
            try {
                // 1. Buscamos la factura asociada al pedido
                const response = await API.get(`${this.baseUrl}/factura-por-pedido/${idPedido}`);

                if (response.data) {
                    this.facturaData = response.data.factura;
                    //console.log("Factura encontrada:", response);
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
        async GetData(page = 1, searchQuery = "", selectedStatus = "") {
            this.cargando = true;

            try {
                const params = {
                    page: page,
                    search_query: searchQuery,
                    status: selectedStatus, // Parámetro para búsqueda
                };
                const response = await API.get(`${this.baseUrl}/facturas`, { params });
                const data = response.data?.data || [];
                const pagination = response.data?.pagination || {};

                this.currentPage = pagination.current_page || 1;
                this.lastPage = pagination.last_page || 1;
                this.filteredobjetoarray = data;
            } catch (error) {
                console.warn("⚠️ Error al obtener datos:", error?.response?.data || error);
                this.filteredobjetoarray = [];
                this.currentPage = 1;
                this.lastPage = 1;
            } finally {
                this.cargando = false;
            }
        },

        filterAndFetch() {
            this.currentPage = 1;

            this.GetData(this.currentPage, this.searchQuery, this.selectedStatus);
        },

        nextPage() {
            if (this.currentPage < this.lastPage && !this.cargando) {
                this.GetData(this.currentPage + 1, this.searchQuery, this.selectedStatus);
            }
        },

        previousPage() {
            if (this.currentPage > 1 && !this.cargando) {
                this.GetData(this.currentPage - 1, this.searchQuery, this.selectedStatus);
            }
        },

        actualizar() {
            // Simplemente recarga la página actual de datos
            this.GetData(this.currentPage, this.searchQuery, this.selectedStatus);
        },

        setStatusFilter(status) {
            this.selectedStatus = status;
            this.isFilterDropdownOpen = false;
            this.filterAndFetch();
        },
    },
};
</script>
