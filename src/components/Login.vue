<template>
  <div id="contenedor">
    <div class="login">
      <div class="contenido">
        <img src="@/assets/img/logo.png" alt="Logo" class="contenti-logo" />
        <div v-if="!manualMode">
          <p class="qr-subtitle">Escanee el código QR de su mesa para realizar el pedido</p>
          <div class="qr-wrapper">
            <div class="qr-scanner">
              <div id="reader"></div>
              <div v-if="isScanning" class="scanner-line"></div>
              <div class="corner top-left"></div>
              <div class="corner top-right"></div>
              <div class="corner bottom-left"></div>
              <div class="corner bottom-right"></div>
            </div>
          </div>
          <button class="botonl secondary" @click="stopScanner" v-if="isScanning">Detener scanner</button>
          <button class="botonl secondary" @click="startScanner" v-else>Iniciar scanner</button>

          <p class="manual-link">
            ¿Problemas con el scanner?
            <span @click="toggleManual(true)">clic aquí para ingresar datos manualmente</span>
          </p>
        </div>

        <div v-else class="manual-form">
          <h2>Ingreso Manual</h2>
          <p class="qr-subtitle">Seleccione su mesa para continuar</p>

          <form class="formulario" @submit.prevent="handleSubmit2">
            <select v-model="selectedTable" class="manual-input">
              <option value="" disabled selected>Seleccione una mesa</option>
              <option v-for="obj in objetoList" :key="obj.id_mesa" :value="obj.codigo_mesa">Mesa {{ obj.codigo_mesa }}
              </option>
            </select>

            <input type="text" v-model="waiterCode" placeholder="Código de validación (opcional)" class="manual-input">

            <button type="submit" class="botonl">Verificar Mesa</button>
            <button type="button" class="botonl secondary" @click="toggleManual(false)">Volver al Scanner</button>
          </form>
        </div>
      </div>
    </div>

    <div class="page front">
      <div class="contenido">

        <h1>¡Hey, Amigos!</h1>
        <p>Da clic en el siguiente botón para ingresar con un usuario dado por el administrador del sitio</p>
        <button id="register" class="botonl" @click="setActive">Ingresar datos de usuario</button>
      </div>
    </div>

    <div class="page back">
      <div class="contenido">

        <h1>¿Eres cliente?</h1>
        <p>Da clic en el botón de abajo para scanear el código QR de tu mesa</p>
        <button id="login" class="botonl" @click="setClose">Scanear código QR</button>
      </div>
    </div>

    <div class="register">
      <div class="contenido">
        <img src="@/assets/img/logo.png" alt="Logo" class="content-logo" />
        <h1>Iniciar Sesión</h1>
        <p class="qr-subtitle">Ingrese el usuario y clave proporcionados por el administrador del sitio</p>
        <form class="formulario" @submit.prevent="handleSubmit">
          <input type="email" placeholder="email" v-model="email">
          <div class="password-wrapper">
            <input type="password" id="reg-pass" placeholder="password" v-model="password">
            <span class="toggle-password" @click="togglePassword('reg-pass')">
              <svg id="icon-reg-pass" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-eye">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
            </span>
          </div>
          <button class="botonl" type="submit">Acceder</button>
        </form>
      </div>
    </div>
  </div>
</template>



<script>
import script3 from "@/assets/js/login";
import { Html5Qrcode } from "html5-qrcode";
import API from "@/assets/js/services/axios";
import {
    mostraralertas2,
} from "@/assets/js/function/funciones";

export default {
  name: 'Login',
  data() {
    return {
      // Variables originales del script setup
      html5QrCode: null,
      qrResult: "",
      isScanning: false,
      manualMode: false,
      selectedTable: "",
      waiterCode: "",
      email: "",
      password: "",

      // Variables del script previo
      objetoList: [],
      idus: 0,
      baseUrl: "/restrik",

      // Instancias de login (clonadas de script3)
      loginInstance: null,
      mesaInstance: null,
      mesaQrInstance: null
    };
  },
  unmounted() {
    if (this.pollingInterval) clearInterval(this.pollingInterval);
  },
  created() {
    // Inicializamos las instancias de login al crear el componente
    this.loginInstance = { ...script3.data(), $router: this.$router, ...script3.methods };
    this.mesaInstance = { ...script3.data(), $router: this.$router, ...script3.methods };
    this.mesaQrInstance = { ...script3.data(), $router: this.$router, ...script3.methods };
  },

  watch: {
    // Sincronización de inputs con las instancias de login
    email(val) { this.loginInstance.email = val; },
    password(val) { this.loginInstance.password = val; },
    selectedTable(val) { this.mesaInstance.email = val; },
    waiterCode(val) { this.mesaInstance.password = val; }
  },

  async mounted() {
    this.GetObjetoList();
    this.startScanner();

    // Responsive: Inicializar en modo close para móviles
    if (window.innerWidth <= 768) {
      const container = document.getElementById('contenedor');
      if (container) container.classList.add('close');
    }
    this.pollingInterval = setInterval(() => {
      this.actualizarSilenciosamente();
    }, 10000);
  },

  beforeUnmount() {
    this.stopScanner();
  },

  methods: {
    // --- MÉTODOS DE DATOS ---
    async GetObjetoList() {
      try {
        const response = await API.get(`${this.baseUrl}/mesas_disponibles`);
        this.objetoList = response.data?.data || [];
      } catch (error) {
        console.error("❌ Error al obtener mesas:", error);
        this.objetoList = [];
      }
    },
    async actualizarSilenciosamente() {
      try {
        const response = await API.get(`${this.baseUrl}/mesas_disponibles`);
        this.objetoList = response.data?.data || [];
      } catch (error) {
        console.warn("Error en actualización silenciosa", error);
      }
    },

    // --- MÉTODOS DE LOGIN ---
    handleSubmit() {
      this.loginInstance.login.call(this.loginInstance);
    },

    handleSubmit2() {
      this.mesaInstance.login.call(this.mesaInstance);
    },

    async loginAutomaticoPorQR(codigoqr, codigoMesa) {
      try {
        this.mesaQrInstance.email = codigoMesa;
        this.mesaQrInstance.password = codigoqr;
        await this.mesaQrInstance.login.call(this.mesaQrInstance);
      } catch (error) {
        console.error("Error en login automático:", error);
      }
    },

    // --- MÉTODOS DEL SCANNER ---
    async startScanner() {
      this.html5QrCode = new Html5Qrcode("reader");

      const qrCodeSuccessCallback = async (decodedText) => {
        await this.stopScanner();
        console.log(`Código detectado: ${decodedText}`);
        this.qrResult = decodedText;

        try {
          const responseVerif = await API.get(`/restrik/verificar_qr/${decodedText}`);
          if (responseVerif.data) {
            
            if (responseVerif.data.data.estado === 'ocupada') {
              mostraralertas2("La mesa ya está ocupada", "error");
              this.startScanner();
            } else {
              await this.loginAutomaticoPorQR(decodedText, responseVerif.data.data.codigo_mesa);
            }
            
          } else {
            mostraralertas2("Código QR no válido o mesa inactiva", "error");
            this.startScanner();
          }
        } catch (err) {
          console.error("Error al validar QR:", err);
          mostraralertas2("Error de conexión al validar el código", "error");
          this.startScanner();
        }
      };

      const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
      };

      try {
        await this.html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
        this.isScanning = true;
      } catch (err) {
        console.error("Error al iniciar cámara:", err);
        this.isScanning = false;
      }
    },

    async stopScanner() {
      if (this.html5QrCode && this.html5QrCode.isScanning) {
        try {
          await this.html5QrCode.stop();
          this.html5QrCode.clear();
          this.isScanning = false;
        } catch (err) {
          console.error("Error al detener scanner:", err);
        }
      }
    },

    toggleManual(value) {
      this.manualMode = value;
      if (value) {
        this.stopScanner();
      } else {
        setTimeout(() => this.startScanner(), 100);
      }
    },

    // --- MÉTODOS DE INTERFAZ ---
    setActive() {
      document.getElementById('contenedor').className = 'active';
    },

    setClose() {
      document.getElementById('contenedor').className = 'close';
    },

    togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const iconSpan = document.getElementById(`icon-${inputId}`);

      if (input.type === 'password') {
        input.type = 'text';
        iconSpan.parentElement.innerHTML = `<svg id="icon-${inputId}" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
      } else {
        input.type = 'password';
        iconSpan.parentElement.innerHTML = `<svg id="icon-${inputId}" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
      }
    }
  }
};
</script>
