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
                        <p><span>Nuestra  Pasión</span> <span class="description-title">por el Sabor</span></p>
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
                                        En Rico Rico nacimos con el propósito de ofrecer platos frescos y llenos de tradición. Nos especializamos en mariscos, 
                                        arroces y encocados preparados con ingredientes seleccionados cuidadosamente cada día.
                                        
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
                                        Nuestro compromiso es brindar a cada cliente una experiencia gastronómica inolvidable en un ambiente acogedor y familiar. 
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
                                        En Rico Rico combinamos tradición y sabor en cada plato. Utilizamos ingredientes frescos y recetas auténticas para ofrecerte una experiencia gastronómica única. 
                                        Nuestro compromiso es brindarte calidad, buen servicio y el verdadero sabor del mar en cada visita.
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
                                            <p>Seleccionamos productos frescos todos los días para garantizar el mejor sabor. </p>
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
                                            <p>Brindamos un servicio amable y rápido para que tu experiencia sea inolvidable.</p>
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
            </main>
            <div v-if="carrito.length > 0" class="cart-floating-btn" @click="mostrarDetalle = !mostrarDetalle">
                <i class="bi bi-cart-fill"></i>
                <span class="cart-badge">{{ carrito.length }}</span>
            </div>
            <div v-if="pedidoEnPreparacion" class="timer-floating-btn" @click="mostrarTiempo = !mostrarTiempo">
                <i class="bi bi-clock-history"></i>
            </div>
            <div v-if="mostrarTiempo" class="timer-sidebar shadow-lg">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="mb-0">Estado de Preparación</h5>
                    <button class="btn-close" @click="mostrarTiempo = false"></button>
                </div>
                <div class="p-4 text-center">
                    <div v-if="!datosTiempo.tiempo_estimado_minutos || datosTiempo.tiempo_estimado_minutos === 0">
                        <div class="spinner-border text-danger mb-3" role="status"></div>
                        <p class="text-muted">El pedido aún no llega a manos del chef, apenas el chef lo tome el tiempo del pedido se va a actualizar.</p>
                    </div>
                    <div v-else>
                        <h2 class="display-4 fw-bold text-danger">{{ datosTiempo.tiempo_estimado_minutos }} min</h2>
                        <p>Tiempo estimado de entrega</p>
                        <hr>
                        <small class="text-muted">Iniciado a las: {{ formatearFecha(datosTiempo.fecha_inicio) }}</small>
                    </div>
                    <button class="btn btn-outline-secondary w-100 mt-4" @click="obtenerEstadoTiempo">Actualizar Estado</button>
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
        </div>
    </shadow-root>
</template>
<script>
import { initYummyEffects } from "@/assets/js/function/yummyEffects.js";
import SectionMenu from "@/components/home/section_menu/SectionMenu.vue";
import { getMe } from '@/store/auth';
import API from "@/assets/js/services/axios";
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
    },

    data() {
        return {
            carrito: [],
            mostrarDetalle: false,
            mostrarTiempo: false, // Nuevo
            pedidoEnPreparacion: null, // Guardará el ID del pedido enviado
            datosTiempo: {},
            intervaloTiempo: null,
            idMesa: null,
            baseUrl: "/restrik",
            bootstrapStyles: `
        @import url("https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css");
        /* Importamos home.css. Nota: Asegúrate que la ruta sea accesible desde la URL pública */
        @import url("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css");
        /* Estilos del Carrito Flotante */
                .cart-floating-btn {
                    position: fixed;
                    bottom: 30px;
                    right: 30px;
                    background-color: #ce1212;
                    color: white;
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    cursor: pointer;
                    z-index: 9999;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                }
                .cart-badge {
                    position: absolute;
                    top: 0;
                    right: 0;
                    background: white;
                    color: #ce1212;
                    border-radius: 50%;
                    padding: 2px 7px;
                    font-size: 12px;
                    font-weight: bold;
                }
                .cart-sidebar {
                    position: fixed;
                    right: 0;
                    top: 0;
                    width: 350px;
                    height: 100vh;
                    background: white;
                    z-index: 10000;
                    display: flex;
                    flex-direction: column;
                    border-left: 1px solid #dee2e6;
                }
                .cart-items { overflow-y: auto; flex-grow: 1; }
        
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
        this.intervaloTiempo = setInterval(() => {
            if (this.pedidoEnPreparacion) this.obtenerEstadoTiempo();
        }, 60000);
    },
    beforeUnmount() {
        clearInterval(this.intervaloTiempo);
    },

    methods: {
        async verificarPedidoEnCocina() {
            try {
                const res = await API.get(`${this.baseUrl}/pedidospreparacion/${this.idMesa}`);
                // Si el backend devuelve un pedido pero su estado es 'preparacion'
                // (Nota: Asegúrate que tu endpoint getPedidopendiente devuelva también los de preparacion o crea uno nuevo)
                if (res.data.data && res.data.data.estado_pedido === 'preparacion') {
                    this.pedidoEnPreparacion = res.data.data.id_pedido;
                    this.obtenerEstadoTiempo();
                }
            } catch (e) { console.error(e); }
        },
        async obtenerEstadoTiempo() {
            if (!this.pedidoEnPreparacion) return;
            try {
                // Necesitas este endpoint en Laravel: GET /tiempos_preparacion/{id_pedido}
                const res = await API.get(`${this.baseUrl}/tiempos_preparacion/${this.pedidoEnPreparacion}`);
                if (res.data.data) {
                    this.datosTiempo = res.data.data;
                }
            } catch (e) { console.error("Error al obtener tiempos", e); }
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
                        await this.obtenerEstadoTiempo();
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
        }
        
    },
};
</script>
