<template>
    <shadow-root>
        <div class="pagehome">
            <component is="style" v-html="bootstrapStyles"></component>
            <header id="header" class="header d-flex align-items-center sticky-top">
                <div class="container position-relative d-flex align-items-center justify-content-between">
                    <a href="" class="logo d-flex align-items-center me-auto me-xl-0">
                        <!-- Uncomment the line below if you also wish to use an image logo -->
                        <img src="@/assets/img/logo.png" alt="" />
                    </a>

                    <nav id="navmenu" class="navmenu">
                        <ul>
                            <li>
                                <a href="#hero" class="active">Inicio<br /></a>
                            </li>
                            <li><a href="#about">Nosotros</a></li>
                            <li><a href="#menu">Menu</a></li>
                            <!--
                            <li><a href="#events">Events</a></li>
                            <li><a href="#chefs">Chefs</a></li>
                            <li><a href="#gallery">Gallery</a></li>

                            <li><a href="#contact">Contact</a></li>
                            -->
                        </ul>
                        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                    </nav>

                    <a class="btn-getstarted" href="#" v-on:click="cerrarSesion">Cerrar Sesión</a>
                </div>
            </header>
            <main class="main">
                <section id="hero" class="hero section light-background">
                    <div class="container">
                        <div class="row gy-4 justify-content-center justify-content-lg-between">
                            <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
                                <h1 data-aos="fade-up">Disfruta de una <br />Deliciosa Comida</h1>
                                <p data-aos="fade-up" data-aos-delay="100">
                                    En Rico Rico, nos apasiona ofrecerte una experiencia culinaria
                                </p>
                                <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                                    <a href="#book-a-table" class="btn-get-started">Booka a Table</a>
                                    <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                                        class="glightbox btn-watch-video d-flex align-items-center"><i
                                            class="bi bi-play-circle"></i><span>Watch Video</span></a>
                                </div>
                            </div>
                            <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                                <img src="@/assets/img/hero-img.png" class="img-fluid animated" alt="" />
                            </div>
                        </div>
                    </div>
                </section>
                <!-- About Section -->
                <section id="about" class="about section">
                    <!-- Section Title -->
                    <div class="container section-title">
                        <h2>Sobre Nosotros<br /></h2>
                        <p><span>Nuestra Pasión</span> <span class="description-title">por el Sabor</span></p>
                    </div>
                    <!-- End Section Title -->

                    <div class="container">
                        <div class="row gy-4">
                            <div class="col-lg-7">
                                <img src="@/assets/img/about.jpg" class="img-fluid mb-4" alt="" />
                                <div class="book-a-table">
                                    <h3>Book a Table</h3>
                                    <p>+1 5589 55488 55</p>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="contentito ps-0 ps-lg-5">
                                    <p class="fst-italic">
                                        En Rico Rico nacimos con el propósito de ofrecer platos frescos y llenos de
                                        tradición. Nos especializamos en mariscos,
                                        arroces y encocados preparados con ingredientes seleccionados cuidadosamente
                                        cada día.

                                    </p>
                                    <ul>
                                        <li>
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Ingredientes frescos y de calidad garantizada.</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Recetas tradicionales con sabor auténtico</span>
                                        </li>
                                        <li>
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Atención rápida y personalizada.</span>
                                        </li>
                                    </ul>
                                    <p>
                                        Nuestro compromiso es brindar a cada cliente una experiencia gastronómica
                                        inolvidable en un ambiente acogedor y familiar.
                                        En Rico Rico, cada plato cuenta una historia.
                                    </p>

                                    <div class="position-relative mt-4">
                                        <img src="@/assets/img/about-2.jpg" class="img-fluid" alt="" />
                                        <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                                            class="glightbox pulsating-play-btn"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /About Section -->
                <!-- Why Us Section -->
                <section id="why-us" class="why-us section light-background">

                    <div class="container">

                        <div class="row gy-4">

                            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                                <div class="why-box">
                                    <h3>¿Por qué elegir nuestro restaurante?</h3>
                                    <p>
                                        En Rico Rico combinamos tradición y sabor en cada plato. Utilizamos ingredientes
                                        frescos y recetas auténticas para ofrecerte una experiencia gastronómica única.
                                        Nuestro compromiso es brindarte calidad, buen servicio y el verdadero sabor del
                                        mar en cada visita.
                                    </p>
                                    <div class="text-center">
                                        <a href="#" class="more-btn"><span>Learn More</span> <i
                                                class="bi bi-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div><!-- End Why Box -->

                            <div class="col-lg-8 d-flex align-items-stretch">
                                <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

                                    <div class="col-xl-4">
                                        <div
                                            class="icon-box d-flex flex-column justify-content-center align-items-center">
                                            <i class="bi bi-clipboard-data"></i>
                                            <h4>Ingredientes Frescos</h4>
                                            <p>Seleccionamos productos frescos todos los días para garantizar el mejor
                                                sabor. </p>
                                        </div>
                                    </div><!-- End Icon Box -->

                                    <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                                        <div
                                            class="icon-box d-flex flex-column justify-content-center align-items-center">
                                            <i class="bi bi-gem"></i>
                                            <h4>Calidad Garantizada</h4>
                                            <p>Cada plato es preparado bajo altos estándares de higiene y calidad.</p>
                                        </div>
                                    </div><!-- End Icon Box -->

                                    <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                                        <div
                                            class="icon-box d-flex flex-column justify-content-center align-items-center">
                                            <i class="bi bi-inboxes"></i>
                                            <h4>Atención Personalizada</h4>
                                            <p>Brindamos un servicio amable y rápido para que tu experiencia sea
                                                inolvidable.</p>
                                        </div>
                                    </div><!-- End Icon Box -->

                                </div>
                            </div>

                        </div>

                    </div>

                </section><!-- /Why Us Section -->
                <!-- Stats Section -->
                <section id="stats" class="stats section dark-background">

                    <img src="@/assets/img/stats-bg.jpg" alt="" data-aos="fade-in">

                    <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">

                        <div class="row gy-4">

                            <div class="col-lg-3 col-md-6">
                                <div class="stats-item text-center w-100 h-100">
                                    <span data-purecounter-start="0" data-purecounter-end="232"
                                        data-purecounter-duration="1" class="purecounter"></span>
                                    <p>👨‍👩‍👧Clientes Satisfechos</p>
                                </div>
                            </div><!-- End Stats Item -->

                            <div class="col-lg-3 col-md-6">
                                <div class="stats-item text-center w-100 h-100">
                                    <span data-purecounter-start="0" data-purecounter-end="521"
                                        data-purecounter-duration="1" class="purecounter"></span>
                                    <p>🚀 Servicio Rápido</p>
                                </div>
                            </div><!-- End Stats Item -->

                            <div class="col-lg-3 col-md-6">
                                <div class="stats-item text-center w-100 h-100">
                                    <span data-purecounter-start="0" data-purecounter-end="1453"
                                        data-purecounter-duration="1" class="purecounter"></span>
                                    <p>👨‍🍳 Chefs Especializados</p>
                                </div>
                            </div><!-- End Stats Item -->

                            <div class="col-lg-3 col-md-6">
                                <div class="stats-item text-center w-100 h-100">
                                    <span data-purecounter-start="0" data-purecounter-end="32"
                                        data-purecounter-duration="1" class="purecounter"></span>
                                    <p>🏆 Equipo Profesional</p>
                                </div>
                            </div><!-- End Stats Item -->

                        </div>

                    </div>

                </section><!-- /Stats Section -->
                <!-- Menu Section -->
                <section id="menu" class="mensu section">
                    <SectionMenu @agregar-al-carrito="cargarPedidoPendiente" />


                </section><!-- /Menu Section -->
                <!-- Testimonials Section -->
                <section id="testimonials" class="testimonials section light-background">
                    <Testimonial />


                </section><!-- /Testimonials Section -->
            </main>
            <div class="floating-container">
                <div v-if="mostrarConfirmarEntrega" class="delivery-check-btn floating-anim" @click="abrirCalificacion">
                    <div class="icon-sphere">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div class="text-stack">
                        <span class="main-txt">¿Llegó tu pedido?</span>
                        <span class="sub-txt">¡Queremos saber tu opinión!</span>
                    </div>
                    <i class="bi bi-chevron-right ms-2 arrow-icon"></i>
                </div>
                <div v-if="datosFactura.length > 0" class="bill-floating-btn" @click="consultarFactura">
                    <i class="bi bi-receipt"></i>
                    <span class="fw-bold">Cuenta</span>
                    <span class="cart-badge">{{ datosFactura.length }}</span>
                </div>
                <div v-if="pedidosAgrupados.length > 0" class="timer-floating-btn"
                    @click="mostrarTiempo = !mostrarTiempo">
                    <i class="bi bi-hourglass-split"></i>
                    <span class="fw-bold">{{ tiempoMasCercano }}</span>
                </div>
                <div v-if="carrito.length > 0" class="cart-floating-btn" @click="mostrarDetalle = !mostrarDetalle">
                    <i class="bi bi-cart-fill"></i>
                    <span class="cart-badge">{{ carrito.length }}</span>
                </div>
            </div>

            <div v-if="mostrarTiempo" class="timer-sidebar shadow-lg">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="mb-0">Seguimiento de Cocina</h5>
                    <button class="btn-close btn-close-dark" @click="mostrarTiempo = false"></button>
                </div>

                <div class="p-3">
                    <div v-for="pedido in pedidosAgrupados" :key="pedido.id_pedido" class="card mb-3 border-success">
                        <div class="card-header bg-success text-white d-flex justify-content-between">
                            <span>Pedido #{{ pedido.id_pedido }}</span>
                            <span class="badge bg-light text-dark">Chef: {{ pedido.nombre_chef }}</span>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h2 class="display-6 fw-bold text-success">{{ pedido.cuentaRegresiva }}</h2>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                        :style="{ width: pedido.porcentaje + '%' }"></div>
                                </div>
                            </div>

                            <h6>Platos:</h6>
                            <ul class="list-group list-group-flush mb-3">
                                <li v-for="prod in pedido.productos" :key="prod.id_detalle"
                                    class="list-group-item d-flex justify-content-between py-1 small">
                                    <span>{{ prod.cantidad }}x {{ prod.producto_nombre }}</span>
                                    <span class="text-muted">${{ (prod.precio_unitario * prod.cantidad).toFixed(2)
                                        }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total Pedido:</span>
                                <span>${{ pedido.total }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="mostrarDetalle" class="cart-sidebar shadow-lg">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="mb-0">Mis Pedidos</h5>
                    <button class="btn-close" @click="mostrarDetalle = false"></button>
                </div>
                <div class="cart-items p-3">
                    <div v-for="item in carrito" :key="item.id_detalle"
                        class="d-flex justify-content-between mb-2 border-bottom pb-2">
                        <div>
                            <p class="mb-0 fw-bold">{{ item.producto_nombre || 'Plato' }}</p>
                            <small class="text-muted">Cant: {{ item.cantidad }} x ${{ item.precio_unitario }}</small>
                        </div>
                        <span>${{ (item.precio_unitario * item.cantidad).toFixed(2) }}</span>
                        <button class="btn btn-danger btn-sm rounded-pill mt-2" :disabled="item.cantidad <= 0"
                            @click="eliminarPedido(item.id_detalle, item.cantidad, item.id_producto, item.id_inventario)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="p-3 bg-light mt-auto">
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total:</span>
                        <span>${{ totalPagar.toFixed(2) }}</span>
                    </div>
                    <button class="btn btn-danger w-100 mb-2" @click="vaciarCarritoTotal">Vaciar Carrito</button>
                    <button class="btn btn-primary w-100" @click="confirmarPedido">Confirmar Pedido</button>
                </div>
            </div>
            <div v-if="mostrarFactura" class="bill-sidebar shadow-lg">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-white text-white">
                    <h5 class="mb-0">Mi Cuenta</h5>
                    <button class="btn-close btn-close-black" @click="mostrarFactura = false"></button>
                </div>

                <div class="p-3 bill-content">
                    <div v-if="datosFactura.length > 0">
                        <div v-for="ped in datosFactura" :key="ped.id_pedido" class="mb-4 border-bottom pb-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary">Pedido #{{ ped.id_pedido }}</span>
                                <small class="text-muted">{{ formatearFecha(ped.fecha_pedido) }}</small>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li v-for="item in ped.detalles" :key="item.id_detalle"
                                    class="list-group-item d-flex justify-content-between align-items-start border-0 px-0 py-1">
                                    <div class="ms-2 me-auto">
                                        <div class="small fw-bold">{{ item.producto_nombre }}</div>
                                        <small>x{{ item.cantidad }}</small>
                                    </div>
                                    <span class="small">${{ (item.precio_unitario * item.cantidad).toFixed(2) }}</span>
                                </li>
                            </ul>
                            <div class="text-end mt-1 fw-bold small">Subtotal: ${{ ped.total }}</div>
                        </div>
                    </div>
                    <div v-else class="text-center py-5">
                        <i class="bi bi-emoji-smile text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3">Aún no tienes pedidos confirmados.</p>
                    </div>
                </div>

                <div class="p-3 bg-light border-top mt-auto">
                    <div class="d-flex justify-content-between fs-4 fw-bold mb-3">
                        <span>Total a Pagar:</span>
                        <span class="text-success">${{ totalCuenta.toFixed(2) }}</span>
                    </div>
                    <button class="btn btn-warning w-100 btn-lg rounded-pill" :disabled="datosFactura.length === 0"
                        @click="pedirCuenta">
                        <i class="bi bi-wallet2 me-2"></i> Acércate a caja a cancelar
                    </button>
                </div>
            </div>
            <div v-if="mostrarModalPreguntaEntrega" class="rating-overlay">
                <div class="rating-card shadow-lg text-center">
                    <img src="@/assets/img/dudasin.png" class="img-fluid mb-3 rounded" style="max-height: 150px;"
                        alt="Consulta">

                    <h3 class="fw-bold">¿Ya estás disfrutando?</h3>
                    <p class="text-muted fs-5">¿Tu pedido llegó correctamente a tu mesa?</p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-outline-danger w-100 rounded-pill py-2" @click="pedidoNoLlego">
                            <i class="bi bi-x-circle me-2"></i>No, aún no
                        </button>
                        <button class="btn btn-warning w-100 rounded-pill py-2 fw-bold" @click="pedidoSiLlego">
                            <i class="bi bi-check-lg me-2"></i>¡Sí, llegó!
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="mostrarModalDisculpas" class="rating-overlay">
                <div class="rating-card shadow-lg text-center">
                    <img src="@/assets/img/loses.png" class="img-fluid mb-3" style="max-height: 150px;" alt="Disculpas">

                    <h3 class="fw-bold text-danger">¡Mil disculpas!</h3>
                    <p class="text-muted">Estamos agilizando todo en cocina para que tu pedido llegue de inmediato.
                        ¡Gracias por tu paciencia!</p>

                    <button class="btn btn-dark w-100 rounded-pill mt-3" @click="mostrarModalDisculpas = false">
                        Entendido </button>
                </div>
            </div>
            <div v-if="mostrarModalCalificacion" class="rating-overlay">
                <div class="rating-card shadow-lg">
                    <div class="text-center mb-4">
                        <img src="@/assets/img/biens.png" class="img-fluid mb-2" style="max-height: 120px;"
                            alt="Rico Rico">
                        <h3 class="fw-bold">¡Buen provecho!</h3>
                        <p class="text-muted">¿Qué te pareció tu pedido #{{ idPedidoCalificar }}?</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tu Nombre (Opcional):</label>
                        <input type="text" v-model="nombreCliente" class="form-control rounded-pill"
                            placeholder="Escribe tu nombre aquí...">
                    </div>
                    <div class="stars-container mb-4">
                        <i v-for="star in 5" :key="star" class="bi"
                            :class="star <= puntuacion ? 'bi-star-fill text-warning' : 'bi-star text-muted'"
                            @click="puntuacion = star" style="font-size: 2.5rem; cursor: pointer; padding: 0 5px;">
                        </i>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Cuéntanos más (opcional):</label>
                        <textarea v-model="comentario" class="form-control" rows="3"
                            placeholder="El sabor, la temperatura..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-light w-100 rounded-pill"
                            @click="mostrarModalCalificacion = false">Cerrar</button>
                        <button class="btn btn-warning w-100 rounded-pill" :disabled="puntuacion === 0"
                            @click="guardarCalificacion">
                            Enviar Opinión
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="mostrarModalListo" class="ready-overlay" @click="mostrarModalListo = false">
                <div class="ready-card">
                    <i class="bi bi-check-circle-fill" style="font-size: 8rem;"></i>
                    <h1 class="display-3 fw-bold">¡PEDIDO LISTO!</h1>
                    <p class="fs-4">Tu deliciosa orden está saliendo de la cocina hacia tu mesa.</p>
                    <button class="btn btn-success btn-lg mt-4 px-5 rounded-pill">¡Genial!</button>
                </div>
            </div>
        </div>
    </shadow-root>
</template>
<script>
import { initYummyEffects } from "@/assets/js/function/yummyEffects.js";
import SectionMenu from "@/components/home/section_menu/SectionMenu.vue";
import Testimonial from '@/components/home/testimonial/Testimonial.vue';
import { getMe } from '@/store/auth';
import API from "@/assets/js/services/axios";
import sonidoNotificacion from '@/assets/sounds/notificacion.mp3';
import {
    mostraralertas2,
    enviaractualizacionpedido,
    confimar,
    confimarhabi,
} from "@/assets/js/function/funciones";


export default {
    name: "PageHome",
    components: {
        SectionMenu,
        Testimonial
    },

    data() {
        return {
            carrito: [],
            mostrarDetalle: false,
            mostrarTiempo: false, // Nuevo
            pedidoEnPreparacion: null, // Guardará el ID del pedido enviado
            datosTiempo: {},
            intervaloReloj: null,
            intervaloServidor: null,
            idMesa: null,
            baseUrl: "/restrik",
            pedidosAgrupados: [],
            ahora: new Date(),
            mostrarModalListo: false,
            mostrarFactura: false,
            datosFactura: [],
            totalCuenta: 0,
            tiempoMasCercano: "00:00",
            mostrarConfirmarEntrega: false,
            mostrarModalCalificacion: false,
            idPedidoCalificar: null,
            puntuacion: 0,
            comentario: "",
            nombreCliente: "",
            pollingInterval: null,
            mostrarModalPreguntaEntrega: false,
            mostrarModalDisculpas: false,
            audio: new Audio(sonidoNotificacion),
            bootstrapStyles: `
        @import url("https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css");
        /* Importamos home.css. Nota: Asegúrate que la ruta sea accesible desde la URL pública */
        @import url("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css");
        
        
      `,
        };
    },
    computed: {
        totalPagar() {
            return this.carrito.reduce((acc, item) => acc + (item.precio_unitario * item.cantidad), 0);
        },
        totalItems() {
            return this.carrito.reduce((acc, item) => acc + item.cantidad, 0);
        }
    },

    async mounted() {
        // Ejecutamos los efectos de la plantilla una vez el componente carga
        this.$nextTick(() => {
            initYummyEffects();
        });
        const usuario = await getMe();
        if (usuario) {
            this.idMesa = usuario.id_mesa; // Ajusta según tu objeto user
            this.cargarPedidoPendiente();
            await this.verificarPedidoEnCocina();
        }
        this.intervaloReloj = setInterval(() => {
            this.ahora = new Date();
            this.actualizarContadores();
        }, 1000);

        // Consultar al servidor cada 30 segundos por si hay cambios de estado
        this.intervaloServidor = setInterval(() => {
            this.verificarPedidoEnCocina();
        }, 10000);
        this.pollingInterval = setInterval(() => {
            this.actualizarSilenciosamente();
        }, 10000);
    },
    unmounted() {
        if (this.pollingInterval) clearInterval(this.pollingInterval);
    },
    beforeUnmount() {
        clearInterval(this.intervaloReloj);
        clearInterval(this.intervaloServidor);
    },

    methods: {
        async verificarPedidoEnCocina() {
            if (!this.idMesa) return;
            try {
                const res = await API.get(`${this.baseUrl}/pedidoscocinando/${this.idMesa}`);
                if (res.data.data && res.data.data.length > 0) {
                    this.agruparPedidos(res.data.data);
                } else {
                    this.pedidosAgrupados = [];
                }
            } catch (e) { console.error(e); }
        },
        async actualizarSilenciosamente() {
            try {
                const res = await API.get(`${this.baseUrl}/pedidoslistos/${this.idMesa}`);
                this.datosFactura = res.data.data; // Se asume que el backend agrupa detalles dentro del pedido
                this.idPedidoCalificar = this.datosFactura[0]?.id_pedido;
                // Calcular gran total
                this.totalCuenta = this.datosFactura.reduce((acc, ped) => acc + parseFloat(ped.total), 0);
                if (this.datosFactura && this.datosFactura.length > 0) {
                    this.mostrarFactura = true;
                    this.mostrarConfirmarEntrega = true;
                } else {
                    this.mostrarFactura = false;
                    this.mostrarConfirmarEntrega = false;
                }
            } catch (error) {
                console.warn("Error en actualización silenciosa", error);
            }
        },

        agruparPedidos(dataRaw) {
            const grupos = {};
            dataRaw.forEach(item => {
                if (!grupos[item.id_pedido]) {
                    grupos[item.id_pedido] = {
                        id_pedido: item.id_pedido,
                        nombre_chef: item.nombre + ' ' + item.apellido,
                        fecha_inicio: new Date(item.fecha_inicio.replace(' ', 'T')),
                        fecha_fin: new Date(item.fecha_fin.replace(' ', 'T')),
                        total: item.total,
                        productos: [],
                        cuentaRegresiva: "00:00",
                        porcentaje: 0,
                        yaNotificado: false
                    };
                }
                grupos[item.id_pedido].productos.push(item);
            });
            this.pedidosAgrupados = Object.values(grupos);
        },

        actualizarContadores() {
            let minTime = Infinity;

            this.pedidosAgrupados.forEach(pedido => {
                const totalMs = pedido.fecha_fin - pedido.fecha_inicio;
                const restanteMs = pedido.fecha_fin - this.ahora;

                if (restanteMs <= 0) {
                    pedido.cuentaRegresiva = "¡LISTO!";
                    pedido.porcentaje = 100;

                    // Si acaba de terminar y no hemos mostrado el modal
                    if (!pedido.yaNotificado) {
                        this.mostrarModalListo = true;
                        pedido.yaNotificado = true;
                        // Opcional: Sonido de notificación
                        this.consultarFactura();
                        this.playNotificacion();
                    }
                } else {
                    const totalSegundos = Math.floor(restanteMs / 1000);
                    const minutos = Math.floor(totalSegundos / 60);
                    const segundos = totalSegundos % 60;
                    pedido.cuentaRegresiva = `${minutos}:${segundos.toString().padStart(2, '0')}`;

                    // Calcular progreso de la barra
                    const transcurridoMs = this.ahora - pedido.fecha_inicio;
                    pedido.porcentaje = Math.min((transcurridoMs / totalMs) * 100, 100);

                    if (totalSegundos < minTime) minTime = totalSegundos;
                }
            });

            // Actualizar el tiempo del botón flotante con el pedido más próximo
            if (minTime !== Infinity) {
                const m = Math.floor(minTime / 60);
                const s = minTime % 60;
                this.tiempoMasCercano = `${m}:${s.toString().padStart(2, '0')}`;
            } else {
                this.tiempoMasCercano = "LISTO";
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
        async cerrarSesion() {
            try {
                const token = localStorage.getItem("token_rest");

                if (!token) {
                    console.warn("⚠️ No hay token, cerrando sesión localmente...")
                    localStorage.clear()
                    window.location.href = "/login"
                    return
                }

                const response = await API.get(
                    "/restrik/logout",
                    {},
                    {
                        headers: { Authorization: `Bearer ${token}` }
                    }
                )

                console.log("✅ Sesión cerrada:", response.data)
                localStorage.clear()
                await this.vaciarCarritoTotal()
                window.location.href = "/login"
            } catch (error) {
                console.error("❌ Error al cerrar sesión:", error.response?.data || error)
                localStorage.clear()
                window.location.href = "/login"
            }
        },
        async cargarPedidoPendiente() {
            try {
                // Buscamos el pedido pendiente de esta mesa
                const res = await API.get(`${this.baseUrl}/pedidospedidiente/${this.idMesa}`);

                if (res.data.data) {
                    const idPedido = res.data.data.id_pedido;
                    // Obtenemos los detalles de ese pedido (necesitas este endpoint en Laravel)
                    const resDetalles = await API.get(`${this.baseUrl}/detalle_pedidos/pedido/${idPedido}`);
                    this.carrito = resDetalles.data.data;
                    console.log(this.carrito);
                }
            } catch (error) {
                console.error("Error cargando carrito:", error);
            }
        },
        async eliminarPedido(idDetalle, cantidad, id_producto, id_inventario) {
            try {
                const paramsStock = {
                    id_producto: id_producto,
                    cantidad_disponible: cantidad,
                };
                const stockActualizado = await enviaractualizacionpedido(
                    "PUT",
                    paramsStock,
                    `${this.baseUrl}/inventarios/${id_inventario}`
                );

                if (!stockActualizado) {
                    mostraralertas2('No se pudo actualizar el stock', 'error');
                    return;
                }
                await API.delete(`${this.baseUrl}/detalle_pedidos/${idDetalle}`);
                this.carrito = this.carrito.filter(item => item.id_detalle !== idDetalle);
            } catch (error) {
                console.error("Error eliminando pedido:", error);
            }
        },
        async vaciarCarritoTotal() {
            try {
                if (this.carrito.length === 0) return;
                const idPedido = this.carrito[0].id_pedido;
                const response = await API.delete(`${this.baseUrl}/detalle_pedidos/vaciar/${idPedido}`);

                if (response.status === 200) {
                    this.carrito = []; // Limpiamos la vista
                    //mostraralertas2("Carrito vaciado", "success");
                    // Opcional: recargar menú si el stock se muestra ahí
                }
            }
            catch (error) {
                console.error("❌ Error al vaciar carrito:", error.response?.data || error);
                this.carrito = [];
            }
        },

        async confirmarPedido() {
            if (this.carrito.length === 0) return;
            const idPed = this.carrito[0].id_pedido;

            const params = {
                id_pedido: idPed,
                id_mesa: this.idMesa,
                fecha_pedido: new Date().toISOString(),
                estado_pedido: 'preparacion',
                total: this.totalPagar
            };

            try {
                // 1. Actualizar estado del pedido
                const response = await enviaractualizacionpedido("PUT", params, `${this.baseUrl}/pedidos/${idPed}`);

                if (response) {
                    // 2. Crear registro en tiempos_preparacion
                    const params2 = { id_pedido: idPed };
                    const response2 = await enviaractualizacionpedido("POST", params2, `${this.baseUrl}/tiempos_preparacion`);

                    if (response2) {
                        this.pedidoEnPreparacion = idPed;
                        this.carrito = [];
                        this.mostrarDetalle = false;
                        this.mostrarTiempo = true; // Mostramos el reloj automáticamente

                        mostraralertas2("Pedido enviado a cocina", "success");
                    }
                }
            } catch (error) {
                console.error("❌ Error:", error);
            }
        },
        formatearFecha(fecha) {
            if (!fecha) return '--:--';
            return new Date(fecha).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },
        async consultarFactura() {
            if (!this.idMesa) return;
            try {
                // Obtenemos todos los pedidos de la mesa que NO estén pagados ni cancelados
                const res = await API.get(`${this.baseUrl}/pedidoslistos/${this.idMesa}`);
                this.datosFactura = res.data.data; // Se asume que el backend agrupa detalles dentro del pedido
                this.idPedidoCalificar = this.datosFactura[0]?.id_pedido;
                // Calcular gran total
                this.totalCuenta = this.datosFactura.reduce((acc, ped) => acc + parseFloat(ped.total), 0);
                if (this.datosFactura && this.datosFactura.length > 0) {
                    this.mostrarFactura = true;
                    this.mostrarConfirmarEntrega = true;
                } else {
                    this.mostrarFactura = false;
                    this.mostrarConfirmarEntrega = false;
                }
            } catch (error) {
                console.error("Error al consultar factura:", error);
                mostraralertas2("No se pudo cargar el detalle de la cuenta", "error");
            }
        },

        async pedirCuenta() {
            // Aquí puedes disparar una notificación al mesero o redirigir a pasarela de pago
            //const confirmacion = await confimar("¿Deseas solicitar la cuenta final?", "Un mesero se acercará a tu mesa.", "info");
            this.mostrarFactura = false;

        },
        abrirCalificacion() {
            //this.mostrarConfirmarEntrega = false; // Quitamos el flotante
            this.mostrarModalPreguntaEntrega = true; // Abrimos el modal
        },
        pedidoSiLlego() {
            this.mostrarModalPreguntaEntrega = false;
            this.mostrarModalCalificacion = true;
            this.mostrarConfirmarEntrega = false; // Ocultamos el botón flotante definitivamente
        },

        // 3. Si el cliente dice que NO llegó
        pedidoNoLlego() {
            this.mostrarModalPreguntaEntrega = false;
            this.mostrarModalDisculpas = true;
            // El botón flotante se mantiene visible para que lo intente más tarde
        },

        async guardarCalificacion() {
            try {
                const payload = {
                    id_pedido: this.idPedidoCalificar,
                    cliente: this.nombreCliente.trim() !== "" ? this.nombreCliente : "Anónimo",
                    puntuacion: this.puntuacion,
                    comentario: this.comentario
                };

                const response = await API.post(`${this.baseUrl}/calificaciones`, payload);

                if (response.status === 201 || response.status === 200) {
                    mostraralertas2("¡Gracias por tu opinión!", "success");
                    this.mostrarModalCalificacion = false;
                    // Limpiar datos
                    this.puntuacion = 0;
                    this.comentario = "";
                }
            } catch (error) {
                console.error("Error al calificar:", error);
                mostraralertas2("No se pudo guardar la calificación", "error");
            }
        }

    },
};
</script>
