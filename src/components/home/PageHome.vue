<template>
  <shadow-root>
    <div class="pagehome">
      <component is="style" v-html="bootstrapStyles"></component>
        <header id="header" class="header d-flex align-items-center sticky-top">
            <div
            class="container position-relative d-flex align-items-center justify-content-between"
            >
            <a href="" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="@/assets/img/logo.png" alt="" />
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                <li>
                    <a href="#hero" class="active">Home<br /></a>
                </li>
                <li><a href="#about">About</a></li>
                <li><a href="#menu">Menu</a></li>
                <li><a href="#events">Events</a></li>
                <li><a href="#chefs">Chefs</a></li>
                <li><a href="#gallery">Gallery</a></li>
                <li class="dropdown">
                    <a href="#"
                    ><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i
                    ></a>
                    <ul>
                    <li><a href="#">Dropdown 1</a></li>
                    <li class="dropdown">
                        <a href="#"
                        ><span>Deep Dropdown</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i
                        ></a>
                        <ul>
                        <li><a href="#">Deep Dropdown 1</a></li>
                        <li><a href="#">Deep Dropdown 2</a></li>
                        <li><a href="#">Deep Dropdown 3</a></li>
                        <li><a href="#">Deep Dropdown 4</a></li>
                        <li><a href="#">Deep Dropdown 5</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Dropdown 2</a></li>
                    <li><a href="#">Dropdown 3</a></li>
                    <li><a href="#">Dropdown 4</a></li>
                    </ul>
                </li>
                <li><a href="#contact">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="index.html#book-a-table">Book a Table</a>
            </div>
        </header>
        <main class="main">
            <section id="hero" class="hero section light-background">
                <div class="container">
                    <div class="row gy-4 justify-content-center justify-content-lg-between">
                    <div
                        class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center"
                    >
                        <h1 data-aos="fade-up">Disfruta de una <br />Deliciosa Comida</h1>
                        <p data-aos="fade-up" data-aos-delay="100">
                        En Rico Rico, nos apasiona ofrecerte una experiencia culinaria
                        </p>
                        <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                        <a href="#book-a-table" class="btn-get-started">Booka a Table</a>
                        <a
                            href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                            class="glightbox btn-watch-video d-flex align-items-center"
                            ><i class="bi bi-play-circle"></i><span>Watch Video</span></a
                        >
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
                    <p><span>Lee más</span> <span class="description-title">Sobre Nosotros</span></p>
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
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                    eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                </p>
                                <ul>
                                    <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span
                                        >Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span
                                    >
                                    </li>
                                    <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span
                                        >Duis aute irure dolor in reprehenderit in voluptate velit.</span
                                    >
                                    </li>
                                    <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span
                                        >Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis
                                        aute irure dolor in reprehenderit in voluptate trideta
                                        storacalaperda mastiro dolore eu fugiat nulla pariatur.</span
                                    >
                                    </li>
                                </ul>
                                <p>
                                    Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                    irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                    fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident
                                </p>

                                <div class="position-relative mt-4">
                                    <img src="@/assets/img/about-2.jpg" class="img-fluid" alt="" />
                                    <a
                                    href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                                    class="glightbox pulsating-play-btn"
                                    ></a>
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
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit
                            Asperiores dolores sed et. Tenetur quia eos. Autem tempore quibusdam vel necessitatibus optio ad corporis.
                        </p>
                        <div class="text-center">
                            <a href="#" class="more-btn"><span>Learn More</span> <i class="bi bi-chevron-right"></i></a>
                        </div>
                        </div>
                    </div><!-- End Why Box -->

                    <div class="col-lg-8 d-flex align-items-stretch">
                        <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-xl-4">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-clipboard-data"></i>
                            <h4>Corporis voluptates officia eiusmod</h4>
                            <p>Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip</p>
                            </div>
                        </div><!-- End Icon Box -->

                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-gem"></i>
                            <h4>Ullamco laboris ladore lore pan</h4>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
                            </div>
                        </div><!-- End Icon Box -->

                        <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                            <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-inboxes"></i>
                            <h4>Labore consequatur incidid dolore</h4>
                            <p>Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis facere</p>
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
                            <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Clients</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Projects</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Hours Of Support</p>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Workers</p>
                        </div>
                    </div><!-- End Stats Item -->

                    </div>

                </div>

            </section><!-- /Stats Section -->
            <!-- Menu Section -->
            <section id="menu" class="mensu section">
                <SectionMenu/>
                

            </section><!-- /Menu Section -->
        </main>
    </div>
  </shadow-root>
</template>
<script>
import { initYummyEffects } from "@/assets/js/function/yummyEffects.js";
import SectionMenu from "@/components/home/section_menu/SectionMenu.vue";


export default {
  name: "PageHome",
  components: {
    SectionMenu,
  },

  data() {
    return {
      bootstrapStyles: `
        @import url("https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css");
        /* Importamos home.css. Nota: Asegúrate que la ruta sea accesible desde la URL pública */
        @import url("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css");
        
        
      `,
    };
  },

  mounted() {
    // Ejecutamos los efectos de la plantilla una vez el componente carga
    this.$nextTick(() => {
      initYummyEffects();
    });
  },

  methods: {
    // Tus métodos aquí
  },
};
</script>
