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
                            <button @click="setStatusFilter('libre')"
                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                Libre
                            </button>
                            <button @click="setStatusFilter('ocupada')"
                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                Ocupada
                            </button>
                        </div>
                    </div>
                </div>

                <button @click="isProfileAddressModal = true"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                    Añadir Mesa
                </button>
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
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Capacidad</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Estado</p>
                        </th>
                        <th class="py-3 text-center">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Imagen QR</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-gray-100 dark:border-gray-800" v-if="cargando">
                        <td class="px-5 py-4 sm:px-6" colspan="9">
                            <h3 class="text-center">Cargando....</h3>
                        </td>
                    </tr>
                    <tr v-else v-for="post in filteredobjetoarray" :key="post.id_mesa"
                        class="border-t border-gray-100 dark:border-gray-800">
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ post.id_mesa }}</p>
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
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                {{ post.capacidad }} personas
                            </p>
                        </td>

                        <td class="py-3 whitespace-nowrap">
                            <span :class="{
                                'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
                                'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500':
                                    post.estado === 'libre',
                                'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400':
                                    post.estado === 'ocupada'
                            }">
                                {{ post.estado }}
                            </span>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <div v-if="post.codigo_qr" class="flex flex-col items-center gap-1">
                                <qrcode-vue :value="post.codigo_qr" :size="70" level="H" render-as="canvas"
                                    :id="'qr-' + post.id_mesa" class="p-1 bg-white border rounded" />

                                <span class="text-[10px] text-gray-400">
                                    {{ post.codigo_qr.substring(0, 8) }}...
                                </span>

                                <!-- BOTÓN DESCARGAR PDF -->
                                <button @click="descargarQRPDF(post)"
                                    class="mt-1 px-2 py-1 text-xs rounded bg-blue-600 text-white hover:bg-blue-700">
                                    Descargar PDF
                                </button>
                            </div>

                            <div v-else class="text-gray-400 text-xs italic text-center">
                                Sin QR
                            </div>

                        </td>


                        <!-- Acciones de Edición y Eliminación -->
                        <td class="py-3 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <button @click="abrirModalEdicion(post)"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <button @click="eliminar(post.id_mesa, post.codigo_mesa)" v-if="post.estado === 'libre'"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </button>
                                <button @click="generarQR(post.id_mesa, post.codigo_mesa)" v-if="!post.codigo_qr"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                        <line x1="7" y1="7" x2="7" y2="7"></line>
                                        <line x1="17" y1="7" x2="17" y2="7"></line>
                                        <line x1="17" y1="17" x2="17" y2="17"></line>
                                        <line x1="7" y1="17" x2="7" y2="17"></line>
                                    </svg>
                                </button>
                                <button @click="habilitar(post.id_mesa, post.codigo_mesa)"
                                    v-if="post.estado === 'ocupada'"
                                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors dark:text-gray-400 dark:hover:bg-white/10"
                                    title="Refrescar lista">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M23 4v6h-6"></path>
                                        <path d="M1 20v-6h6"></path>
                                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15">
                                        </path>
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
        <!-- Modal de Registro de Mesa -->
        <Modal v-if="isProfileAddressModal" @close="isProfileAddressModal = false">
            <template #body>
                <div
                    class="relative w-full max-w-[700px] max-h-[90vh] flex flex-col overflow-hidden rounded-3xl bg-white dark:bg-gray-900 shadow-2xl">
                    <button @click="isProfileAddressModal = false"
                        class="transition-color absolute right-5 top-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.07]">
                        <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" />
                        </svg>
                    </button>

                    <div class="px-6 pt-8 lg:px-11 lg:pt-11">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                            Agregar Mesas
                        </h4>
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Llene todos los campos para agregar una nueva mesa.
                        </p>
                    </div>

                    <form class="flex flex-col flex-1 overflow-hidden">
                        <div class="px-6 pb-4 overflow-y-auto custom-scrollbar lg:px-11">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">


                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Código de Mesa
                                    </label>

                                    <div class="flex">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                            RICM-
                                        </span>
                                        <input type="text" v-model="objetoguardar.codigo_mesa" @input="soloNumeros"
                                            placeholder="001" maxlength="3"
                                            class="dark:bg-dark-900 h-11 w-full rounded-r-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white focus:ring-brand-500 focus:border-brand-500" />
                                    </div>
                                    <p class="text-xs text-gray-400 italic">
                                        No borrar las letras iniciales que por defecto se muestran, ya que el código de
                                        mesa
                                        debe empezar con esas letras. Ej: RICM-001
                                    </p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Capacidad
                                    </label>
                                    <input type="number" v-model="objetoguardar.capacidad" min="0"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white" />

                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Seleccione el estado de la mesa
                                    </label>
                                    <div class="relative z-20 bg-transparent">
                                        <select v-model="objetoguardar.estado"
                                            class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                            :class="{ 'text-gray-800 dark:text-white/90': objetoguardar.estado }">
                                            <option value="" disabled selected>Seleccione un estado</option>
                                            <option value="libre">Libre</option>
                                            <option value="ocupada">Ocupada</option>
                                        </select>
                                        <span
                                            class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400">
                                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div
                            class="flex items-center gap-3 border-t border-gray-100 bg-gray-50/50 p-6 dark:border-gray-800 dark:bg-white/[0.02] lg:justify-end lg:px-11">
                            <button @click="isProfileAddressModal = false" type="button"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                                Cerrar
                            </button>
                            <button v-if="formIsValid" @click="registrar" type="button"
                                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto shadow-lg">
                                Agregar Mesa
                            </button>
                            <p v-else class="text-[11px] text-gray-400 italic">
                                Complete todos los campos para registrar.
                            </p>
                        </div>
                    </form>
                </div>
            </template>
        </Modal>
        <!-- Modal de Edición de Usuario -->
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
                            Editar Mesa
                        </h4>
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Los datos mostrados son los actuales de la mesa. Realice los cambios necesarios.
                        </p>
                    </div>

                    <form class="flex flex-col flex-1 overflow-hidden">
                        <div class="px-6 pb-4 overflow-y-auto custom-scrollbar lg:px-11">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">


                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Código de Mesa
                                    </label>
                                    <div class="flex">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                            RICM-
                                        </span>
                                        <input type="text" v-model="objetoeditar.codigo_mesa" @input="soloNumeros"
                                            placeholder="001" maxlength="3"
                                            class="dark:bg-dark-900 h-11 w-full rounded-r-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white focus:ring-brand-500 focus:border-brand-500" />
                                    </div>
                                    <p class="text-xs text-gray-400 italic">
                                        No borrar las letras iniciales que por defecto se muestran, ya que el código de
                                        mesa
                                        debe empezar con esas letras. Ej: RICM-001
                                    </p>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Capacidad
                                    </label>
                                    <input type="number" v-model="objetoeditar.capacidad" min="0"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:text-white" />

                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Seleccione el estado de la mesa
                                    </label>
                                    <div class="relative z-20 bg-transparent">
                                        <select v-model="objetoeditar.estado"
                                            class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                            :class="{ 'text-gray-800 dark:text-white/90': objetoeditar.estado }">
                                            <option value="" disabled selected>Seleccione un estado</option>
                                            <option value="libre">Libre</option>
                                            <option value="ocupada">Ocupada</option>
                                        </select>
                                        <span
                                            class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400">
                                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
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
                            <p v-else class="text-[11px] text-gray-400 italic">Complete todos los campos para editar.
                            </p>
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
import QrcodeVue from 'qrcode.vue';
import jsPDF from "jspdf";
import {
    mostraralertas2,
    enviarsolig,
    confimar,
    confimarhabi,
    qrconfimar
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
    components: {
        QrcodeVue,
    },
    async mounted() {
        const ruta = useRoute();

        this.pollingInterval = setInterval(() => {
            this.actualizarSilenciosamente();
        }, 10000);
        this.GetData(1, this.searchQuery, this.selectedStatus);

    },
    computed: {
        formIsValid() {
            return (
                this.objetoguardar.codigo_mesa !== "" &&
                this.objetoguardar.capacidad !== "" &&
                this.objetoguardar.estado !== ""
            );
        },
        formIsValidEdit() {
            return (
                this.objetoeditar.codigo_mesa !== "" &&
                this.objetoeditar.capacidad !== "" &&
                this.objetoeditar.estado !== ""
            );
        },
    },

    methods: {
        soloNumeros(e) {
            // Elimina cualquier caracter que no sea número
            this.objetoguardar.codigo_mesa = this.objetoguardar.codigo_mesa.replace(/[^0-9]/g, '');
            this.objetoeditar.codigo_mesa = this.objetoeditar.codigo_mesa.replace(/[^0-9]/g, '');
        },
        async actualizarSilenciosamente() {
            try {
                const params = { page: this.currentPage, status: this.selectedStatus };
                const response = await API.get(`${this.baseUrl}/mesas`, { params });
                this.filteredobjetoarray = response.data?.data || {};
                this.lastPage = response.data?.pagination?.last_page || 1;
            } catch (error) {
                console.warn("Error en actualización silenciosa", error);
            }
        },
        abrirModalEdicion(user) {
            // Clonamos el objeto para no modificar la tabla directamente antes de guardar
            this.objetoeditar = {
                id_mesa: user.id_mesa,
                codigo_mesa: user.codigo_mesa.replace("RICM-", ""),
                capacidad: user.capacidad,
                estado: user.estado,
            };
            this.$.setupState.isEditModalOpen = true;
        },
        async GetData(page = 1, searchQuery = "", selectedStatus = "") {
            this.cargando = true;

            try {
                const params = {
                    page: page,
                    search_query: searchQuery,
                    status: selectedStatus, // Parámetro para búsqueda
                };
                const response = await API.get(`${this.baseUrl}/mesas`, { params });
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

        async registrar() {
            try {
                const params = {
                    codigo_mesa: `RICM-${this.objetoguardar.codigo_mesa}`,
                    capacidad: this.objetoguardar.capacidad,
                    estado: this.objetoguardar.estado,
                };
                const exito = await enviarsolig(
                    "POST",
                    params,
                    `${this.baseUrl}/mesas`,
                    "Stock registrado con éxito"
                );
                if (exito) {
                    this.$.setupState.isProfileAddressModal = false;

                    this.objetoguardar = {
                        codigo_mesa: "",
                        capacidad: "",
                        estado: "",
                    };


                    this.actualizar();
                } else {
                    this.$.setupState.isProfileAddressModal = false;
                }
            } catch (error) {
                console.error("❌ Error al registrar usuario:", error.response?.data || error);
            }
        },
        async Update() {
            try {
                const params = {
                    codigo_mesa: `RICM-${this.objetoeditar.codigo_mesa}`,
                    capacidad: this.objetoeditar.capacidad,
                    estado: this.objetoeditar.estado,
                };
                const exito = await enviarsolig(
                    "PUT",
                    params,
                    `${this.baseUrl}/mesas/${this.objetoeditar.id_mesa}`,
                    "Stock actualizado con éxito"
                );
                if (exito) {
                    this.$.setupState.isEditModalOpen = false;

                    this.objetoeditar = {
                        id_mesa: "",
                        codigo_mesa: "",
                        capacidad: "",
                        estado: "",
                    };
                    this.actualizar();
                }
                else {
                    this.$.setupState.isProfileAddressModal = false;
                }
            } catch (error) {
                console.error("❌ Error al registrar usuario:", error.response?.data || error);
            }
        },
        eliminar(id, nombre) {
            try {
                confimar(
                    `${this.baseUrl}/eliminarmesa/`,
                    id,
                    "Inhabilitar registro",
                    "¿Realmente desea inhabilitar la mesa  " + nombre + "?",
                    this.actualizar // 👈 callback para refrescar la tabla al confirmar
                );
            } catch (error) {
                console.error("Error al inhabilitar el usuario:", error);
                this.cargando = false;
            }
        },
        generarQR(id, nombre) {
            try {
                qrconfimar(
                    "POST",
                    `${this.baseUrl}/generarqr`,
                    { id_mesa: id },
                    "Generar QR",
                    "¿Desea generar el QR para la mesa " + nombre + "?",
                    this.actualizar // 👈 callback para refrescar la tabla al confirmar
                );
            } catch (error) {
                console.error("Error al generar QR:", error);
                this.cargando = false;
            }
        },
        habilitar(id, nombre) {
            try {
                confimarhabi(
                    `${this.baseUrl}/habilitarmesa/`,
                    id,
                    "Habilitar registro",
                    "¿Desea habilitar la mesa " + nombre + "?",
                    this.actualizar // 👈 callback para refrescar la tabla al confirmar
                );
            } catch (error) {
                console.error("Error al eliminar la oferta:", error);
                this.cargando = false;
            }
        },
        setStatusFilter(status) {
            this.selectedStatus = status;
            this.isFilterDropdownOpen = false;
            this.filterAndFetch();
        },
        descargarQRPDF(post) {
            const canvas = document.getElementById(`qr-${post.id_mesa}`);

            if (!canvas || canvas.tagName !== "CANVAS") {
                console.error("QR no encontrado");
                alert("No se pudo generar el QR");
                return;
            }

            const imgData = canvas.toDataURL("image/png");

            const pdf = new jsPDF({
                orientation: "portrait",
                unit: "mm",
                format: "a4",
            });

            pdf.setFontSize(16);
            pdf.text("Código QR de Mesa", 105, 20, { align: "center" });

            pdf.setFontSize(12);
            pdf.text(`Mesa: ${post.codigo_mesa}`, 105, 30, { align: "center" });

            pdf.addImage(imgData, "PNG", 65, 45, 80, 80);

            pdf.setFontSize(10);
            pdf.text(
                "Escanea este código para acceder al menú",
                105,
                135,
                { align: "center" }
            );

            pdf.save(`QR_Mesa_${post.codigo_mesa}.pdf`);
        },
    },
};
</script>
