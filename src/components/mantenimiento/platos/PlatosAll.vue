<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <!-- Search Form -->
        <form class="flex-grow">
          <div class="relative">
            <button class="absolute -translate-y-1/2 left-4 top-1/2">
              <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                  fill="" />
              </svg>
            </button>
            <!-- @input llama al debouncedFilter, que inicia la nueva consulta al backend -->
            <input type="text" placeholder="Ingresa el nombre del usuario a buscar..." v-model="searchQuery"
              @input="debouncedFilter"
              class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-14 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-gray-900 dark:bg-white/[0.03] dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 xl:w-[430px]" />
          </div>
        </form>
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
            {{ selectedStatus === '' ? 'Filtrar' : 'Estado: ' + selectedStatus }}
          </button>

          <div v-if="isFilterDropdownOpen"
            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800">
            <div class="py-1">
              <button @click="setStatusFilter('')"
                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Todos</button>
              <button @click="setStatusFilter('activos')"
                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Activos</button>
              <button @click="setStatusFilter('inactivos')"
                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Inactivos</button>
            </div>
          </div>
        </div>

        <button @click="isProfileAddressModal = true"
          class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
          Añadir Platos
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
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Platos</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Categoría</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Precio</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t border-gray-100 dark:border-gray-800" v-if="cargando">
            <td class="px-5 py-4 sm:px-6" colspan="9">
              <h3 class="text-center">Cargando....</h3>
            </td>
          </tr>
          <tr v-else v-for="post in filteredobjetoarray" :key="post.id_producto"
            class="border-t border-gray-100 dark:border-gray-800">
            <td class="py-3 whitespace-nowrap">
              <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ post.id_producto }}</p>
            </td>
            <td class="py-3 whitespace-nowrap">
              <div class="flex items-center gap-3">
                <div class="h-[50px] w-[50px] overflow-hidden rounded-md">
                  <img v-if="post.hasPhoto" :src="getPhotoUrl(post.id_producto)" @error="handleImageError" />
                </div>
                <div>
                  <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                    {{ post.name }}
                  </p>
                  <span class="text-gray-500 text-theme-xs dark:text-gray-400">Descripción {{ post.descripcion }}</span>
                </div>
              </div>
            </td>
            <td class="py-3 whitespace-nowrap">
              <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ post.categoria_nombre }}</p>
            </td>
            <td class="py-3 whitespace-nowrap">
              <p class="text-gray-500 text-theme-sm dark:text-gray-400">${{ post.precio }}</p>
            </td>

            <td class="py-3 whitespace-nowrap" v-if="post.estado === 1">
              <span :class="{
                'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
                'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500':
                  post.estado === 1,
              }">
                Activo
              </span>
            </td>
            <td class="py-3 whitespace-nowrap" v-else>
              <span :class="{
                'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
                'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400':
                  post.estado === 0,
              }">
                Inactivo
              </span>
            </td>

            <!-- Acciones de Edición y Eliminación -->
            <td class="py-3 text-right whitespace-nowrap">
              <div class="flex justify-end gap-2">
                <button @click="abrirModalEdicion(post)"
                  class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                  </svg>
                </button>
                <button @click="eliminar(post.id_producto, post.nombre)" v-if="post.estado === 1"
                  class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                  </svg>
                </button>
                <button @click="habilitar(post.id_producto, post.nombre)" v-if="post.estado === 0"
                  class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors dark:text-gray-400 dark:hover:bg-white/10"
                  title="Refrescar lista">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 4v6h-6"></path>
                    <path d="M1 20v-6h6"></path>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
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
        <i class="fas fa-angle-left"></i></button>&nbsp; <span class="text-dark">Página {{ currentPage }} de {{ lastPage
        }}</span>&nbsp;
      <button @click="nextPage" :disabled="currentPage === lastPage || buscando" class="btn btn-primary text-white">
        <i class="fas fa-angle-right"></i>
      </button>
    </div>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <div class="d-flex justify-content-center mb-4" v-if="!cargando">
      &nbsp;&nbsp;&nbsp;
      <button class="btn btn-primary text-white" @click="actualizar">
        Actualizar
      </button>
    </div>
    <!-- Modal de Registro de Usuario -->
    <Modal v-if="isProfileAddressModal" @close="isProfileAddressModal = false">
      <template #body>
        <div
          class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
          <!-- close btn -->
          <button @click="isProfileAddressModal = false"
            class="transition-color absolute right-5 top-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
                fill="" />
            </svg>
          </button>
          <div class="px-2 pr-14">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
              Agregar Plato
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
              Llene todos los campos para agregar un nuevo plato.
            </p>
          </div>
          <form class="flex flex-col">
            <div class="px-2 overflow-y-auto custom-scrollbar">
              <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nombre del Plato
                  </label>
                  <input type="text" v-model="objetoguardar.nombre"
                    class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>

                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Seleccione la categoría del plato
                  </label>
                  <div class="relative z-20 bg-transparent">
                    <select v-model="objetoguardar.id_categoria"
                      class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                      :class="{ 'text-gray-800 dark:text-white/90': objetoguardar.id_categoria }">
                      <option value="" disabled selected>Seleccione una categoría</option>
                      <option v-for="obj in objetoList" :key="obj.id_categoria" :value="obj.id_categoria">{{ obj.nombre
                      }}</option>
                    </select>
                    <span
                      class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400">
                      <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                  </div>
                </div>
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Precio
                  </label>
                  <input type="text" v-model="objetoguardar.precio"
                    class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Seleccione el estado del plato
                  </label>
                  <div class="relative z-20 bg-transparent">
                    <select v-model="objetoguardar.estado"
                      class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                      :class="{ 'text-gray-800 dark:text-white/90': objetoguardar.estado }">
                      <option value="" disabled selected>Seleccione un estado</option>
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                    <span
                      class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400">
                      <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                  </div>
                </div>



              </div>
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Descripción
                </label>
                <textarea v-model="objetoguardar.descripcion" placeholder="Ingrese la descripción de la categoría..."
                  rows="6"
                  class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"></textarea>
              </div>
              <div class="file-uploader">
                <form ref="dropzoneForm" :id="dropzoneId" :action="uploadUrl"
                  class="border-gray-300 border-dashed dropzone rounded-xl bg-gray-50 p-7 hover:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-brand-500 lg:p-10">
                  <div class="dz-message m-0!">
                    <div class="mb-[22px] flex justify-center">
                      <div
                        class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z"
                            fill="" />
                        </svg>
                      </div>
                    </div>

                    <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90">
                      Drag & Drop File Here
                    </h4>
                    <span class="mx-auto mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                      Drag and drop your PNG, JPG, WebP, SVG images here or browse
                    </span>

                    <span class="font-medium underline cursor-pointer text-theme-sm text-brand-500">
                      Browse File
                    </span>
                  </div>
                </form>
              </div>
            </div>
            <div class="flex items-center gap-3 mt-6 lg:justify-end">
              <button @click="isProfileAddressModal = false" type="button"
                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                Cerrar
              </button>
              <button v-if="formIsValid" @click="registrar" type="button"
                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto shadow-lg transition-all">
                Agregar Usuario
              </button>
              <p v-else class="text-xs text-gray-400 italic">Complete todos los campos correctamente para habilitar el
                registro.</p>
            </div>
          </form>
        </div>
      </template>
    </Modal>
    <!-- Modal de Edición de Usuario -->
    <Modal v-if="isEditModalOpen" @close="isEditModalOpen = false">
      <template #body>
        <div
          class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
          <!-- close btn -->
          <button @click="isEditModalOpen = false"
            class="transition-color absolute right-5 top-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
                fill="" />
            </svg>
          </button>
          <div class="px-2 pr-14">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
              Editar Plato
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
              Los datos mostrados son los actuales del plato. Realice los cambios necesarios y guarde.
            </p>
          </div>
          <form class="flex flex-col">
            <div class="px-2 overflow-y-auto custom-scrollbar">
              <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nombre del Plato
                  </label>
                  <input type="text" v-model="objetoeditar.nombre"
                    class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>

                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Seleccione la categoría del plato
                  </label>
                  <div class="relative z-20 bg-transparent">
                    <select v-model="objetoeditar.id_categoria"
                      class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                      :class="{ 'text-gray-800 dark:text-white/90': objetoeditar.id_categoria }">
                      <option value="" disabled selected>Seleccione una categoría</option>
                      <option v-for="obj in objetoList" :key="obj.id_categoria" :value="obj.id_categoria">{{ obj.nombre
                      }}</option>
                    </select>
                    <span
                      class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400">
                      <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                  </div>
                </div>
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Precio
                  </label>
                  <input type="text" v-model="objetoeditar.precio"
                    class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Seleccione el estado del plato
                  </label>
                  <div class="relative z-20 bg-transparent">
                    <select v-model="objetoeditar.estado"
                      class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                      :class="{ 'text-gray-800 dark:text-white/90': objetoeditar.estado }">
                      <option value="" disabled selected>Seleccione un estado</option>
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                    <span
                      class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-4 top-1/2 dark:text-gray-400">
                      <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                  </div>
                </div>



              </div>
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Descripción
                </label>
                <textarea v-model="objetoeditar.descripcion" placeholder="Ingrese la descripción de la categoría..."
                  rows="6"
                  class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"></textarea>
              </div>
              <div class="file-uploader">
                <form ref="dropzoneForm" :id="dropzoneId" :action="uploadUrl"
                  class="border-gray-300 border-dashed dropzone rounded-xl bg-gray-50 p-7 hover:border-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-brand-500 lg:p-10">
                  <div class="dz-message m-0!">
                    <div class="mb-[22px] flex justify-center">
                      <div
                        class="flex h-[68px] w-[68px] items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        <svg class="fill-current" width="29" height="28" viewBox="0 0 29 28" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 10.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z"
                            fill="" />
                        </svg>
                      </div>
                    </div>

                    <h4 class="mb-3 font-semibold text-gray-800 text-theme-xl dark:text-white/90">
                      Drag & Drop File Here
                    </h4>
                    <span class="mx-auto mb-5 block w-full max-w-[290px] text-sm text-gray-700 dark:text-gray-400">
                      Drag and drop your PNG, JPG, WebP, SVG images here or browse
                    </span>

                    <span class="font-medium underline cursor-pointer text-theme-sm text-brand-500">
                      Browse File
                    </span>
                  </div>
                </form>
              </div>
            </div>
            <div class="flex items-center gap-3 mt-6 lg:justify-end">
              <button @click="isEditModalOpen = false" type="button"
                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                Cerrar
              </button>
              <button v-if="formIsValidEdit" @click="EditarUsuario" type="button"
                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto shadow-lg transition-all">
                Editar Usuario
              </button>
              <p v-else class="text-xs text-gray-400 italic">Complete todos los campos correctamente para habilitar el
                botón de edición.</p>
            </div>
          </form>
        </div>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import Modal from '@/components/Modal/Modal.vue'

const isProfileAddressModal = ref(false)
const isEditModalOpen = ref(false)
const showPassword = ref(false)
// Creamos una función para que el bloque de abajo pueda cerrar el modal
const cerrarModalDesdeAfuera = () => {
  isProfileAddressModal.value = false
}

// IMPORTANTE: Exponemos la variable y la función
defineExpose({
  isProfileAddressModal,
  isEditModalOpen,
  cerrarModalDesdeAfuera
})
</script>
<script>
import API from "@/assets/js/services/axios";
import { useRoute } from "vue-router";
import debounce from 'lodash.debounce';
import Modal from '@/components/Modal/Modal.vue'
import { mostraralertas2, enviarsolig, confimar, confimarhabi } from '@/assets/js/function/funciones';

export default {
  data() {
    return {
      idus: 0,
      baseUrl: "/restrik",
      photoCache: {},

      usersarray: [],
      objetoguardar: {
        id_categoria: "",
        nombre: "",
        descripcion: "1",
        precio: "",
        imagen: '',
        previewFoto: '',
        estado: "1",

      },
      objetoeditar: {
        id_producto: 0,
        id_categoria: "",
        nombre: "",
        descripcion: "1",
        precio: "",
        imagen: '',
        previewFoto: '',
        estado: "1",
      },
      filteredobjetoarray: [],
      searchQuery: "",
      selectedStatus: "", // Nuevo: estado seleccionado
      isFilterDropdownOpen: false, // Nuevo: control del menu
      isProfileAddressModal: false,
      cargando: false,
      password: '',
      currentPage: 1,
      lastPage: 1,
      buscando: false, // Mantenido, pero no se usa en la lógica de paginación actual
      debouncedFilter: null,
      objetoList: [],

    };
  },
  created() {
    // Ahora sí puedes usar this.filterAndFetch
    this.debouncedFilter = debounce(() => {
      this.filterAndFetch();
    }, 900);
  },
  async mounted() {
    const ruta = useRoute();
    this.GetData(1, this.searchQuery, this.selectedStatus);
    this.GetObjetoList();

  },
  computed: {

    formIsValid() {
      return (
        this.objetoguardar.nombre !== '' &&
        this.objetoguardar.descripcion !== '' &&
        this.objetoguardar.precio !== ''
      );
    },
    formIsValidEdit() {
      return (
        this.objetoeditar.nombre !== '' &&
        this.objetoeditar.descripcion !== '' &&
        this.objetoeditar.precio !== ''
      );
    },


  },
  methods: {
    abrirModalEdicion(user) {
      // Clonamos el objeto para no modificar la tabla directamente antes de guardar
      this.objetoeditar = {
        id_producto: user.id_producto,
        id_categoria: user.id_categoria,
        nombre: user.nombre,
        descripcion: user.descripcion,
        precio: user.precio,
        imagen: user.imagen,
        previewFoto: 'data:image/jpeg;base64,' + user.imagen,
        estado: user.estado,
      };
      this.$.setupState.isEditModalOpen = true;
    },
    getPhotoUrl(ci) {
      const baseURL2 = API.defaults.baseURL;
      return `${baseURL2}/restrik/imagenprod/${ci}`;
    },
    async GetObjetoList() {
      this.cargando = true;
      try {
        const response = await API.get(`${this.baseUrl}/categorias`);

        this.objetoList = response.data?.data || []

      } catch (error) {
        console.error("❌ Error al obtener categorias:", error);
        this.objetoList = [];
      }
    },
    handleImageError(event) {
      // Reemplaza la imagen con el ícono de usuario por defecto
      event.target.src =
        "https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/480px-User_icon_2.svg.png";
    },
    async GetData(page = 1, searchQuery = "", selectedStatus = "") {
      this.cargando = true;


      try {
        const params = {
          page: page,
          search_query: searchQuery,
          status: selectedStatus, // Parámetro para búsqueda
        };
        const response = await API.get(`${this.baseUrl}/productos`, { params });

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
      let estado;
      if (this.selectedStatus === "activos") {
        estado = 1;
      } else if (this.selectedStatus === "inactivos") {
        estado = 0;
      } else {
        estado = "";
      }
      this.GetData(this.currentPage, this.searchQuery, estado);
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
          id_categoria: this.objetoguardar.id_categoria,
          nombre: this.objetoguardar.nombre,
          descripcion: this.objetoguardar.descripcion,
          precio: this.objetoguardar.precio,
          imagen: this.objetoguardar.imagen,
          estado: 1,
        };
        const exito = await enviarsolig('POST', params, `${this.baseUrl}/productos`, 'Producto registrado con éxito');
        if (exito) {
          this.$.setupState.isProfileAddressModal = false;

          this.objetoguardar = {
            id_categoria: "",
            nombre: "",
            descripcion: "",
            precio: "",
            imagen: '',
            previewFoto: '',
            estado: "1",
          };


          this.actualizar();
        }


      } catch (error) {
        console.error("❌ Error al registrar usuario:", error.response?.data || error);
      }
    },
    async Update() {

      try {
        const params = {
          id_categoria: this.objetoeditar.id_categoria,
          nombre: this.objetoeditar.nombre,
          descripcion: this.objetoeditar.descripcion,
          precio: this.objetoeditar.precio,
          imagen: this.objetoeditar.imagen,
          estado: this.objetoeditar.estado,
        };
        const exito = await enviarsolig('PUT', params, `${this.baseUrl}/productos/${this.objetoeditar.id_categoria}`, 'Categoría Editada con éxito');
        if (exito) {
          this.$.setupState.isEditModalOpen = false;

          this.objetoeditar = {
            id_categoria: "",
            nombre: "",
            descripcion: "",
            precio: "",
            imagen: '',
            previewFoto: '',
            estado: "1",
          };


          this.actualizar();
        }


      } catch (error) {
        console.error("❌ Error al registrar usuario:", error.response?.data || error);
      }
    },
    eliminar(id, nombre) {
      try {
        confimar(
          `${this.baseUrl}/eliminarcategoria/`,
          id,
          'Inhabilitar registro',
          '¿Realmente desea inhabilitar el usuario  ' + nombre + '?',
          this.actualizar   // 👈 callback para refrescar la tabla al confirmar
        );
      } catch (error) {
        console.error("Error al inhabilitar el usuario:", error);
        this.cargando = false;
      }
    },
    habilitar(id, nombre) {
      try {
        confimarhabi(
          `${this.baseUrl}/habilitarcategoria/`,
          id,
          'Habilitar registro',
          '¿Desea habilitar el usuario ' + nombre + '?',
          this.actualizar   // 👈 callback para refrescar la tabla al confirmar
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

  },
};
</script>

<style>
.dropzone {
  border: 1px dashed #d0d5dd;
  transition: all 0.3s ease;
}

.dropzone:hover {
  border-color: #F5B30F;
}

.dropzone .dz-preview {
  margin: 10px;
}

.dropzone .dz-preview .dz-image {
  border-radius: 8px;
}

.dropzone .dz-preview .dz-details {
  padding: 1em;
}

.dropzone .dz-preview .dz-progress {
  height: 10px;
}

.dropzone .dz-preview .dz-progress .dz-upload {
  background: #F5B30F;
}

.dark .dropzone {
  background-color: #111827;
  border-color: #374151;
}

.dark .dropzone:hover {
  border-color: #F5B30F;
}
</style>