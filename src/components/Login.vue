<template>
  <div id="contenedor">
    <div class="login">
      <div class="contenido">
        <img src="@/assets/img/logo.png" alt="Logo" class="content-logo" />
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

          <form class="formulario" @submit.prevent="verifyTable">
            <select v-model="selectedTable" class="manual-input">
              <option value="" disabled selected>Seleccione una mesa</option>
              <option v-for="n in 20" :key="n" :value="n">Mesa {{ n }}</option>
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
        <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="feather feather-qr-code">
          <rect x="3" y="3" width="7" height="7" />
          <rect x="14" y="3" width="7" height="7" />
          <rect x="3" y="14" width="7" height="7" />
          <path d="M7 7h.01M17 7h.01M7 17h.01" />
          <path d="M14 14h3m3 0h.01M14 17h.01M17 17h3M14 21h7M21 14v7" />
        </svg>
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
        <form class="formulario">
          <input type="email" placeholder="email">
          <div class="password-wrapper">
            <input type="password" id="reg-pass" placeholder="password">
            <span class="toggle-password" @click="togglePassword('reg-pass')">
              <svg id="icon-reg-pass" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-eye">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
            </span>
          </div>
          <button class="botonl" @click.prevent="">Acceder</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { Html5Qrcode } from "html5-qrcode";
let html5QrCode = null;
const qrResult = ref("");
const isScanning = ref(false);
const manualMode = ref(false); // Nueva variable
const selectedTable = ref(""); // Datos de la mesa
const waiterCode = ref("");


const startScanner = async () => {
  html5QrCode = new Html5Qrcode("reader");

  const qrCodeSuccessCallback = (decodedText, decodedResult) => {
    // Aquí manejas el éxito del escaneo
    console.log(`Código escaneado: ${decodedText}`);
    qrResult.value = decodedText;

    // Ejemplo: Redirigir o hacer login
    alert("Acceso concedido para: " + decodedText);
    stopScanner();
  };

  const config = {
    fps: 10,
    qrbox: { width: 250, height: 250 },
    // Esto ayuda a que se vea bien en el contenedor pequeño
    aspectRatio: 1.0
  };

  try {
    await html5QrCode.start(
      { facingMode: "environment" },
      config,
      qrCodeSuccessCallback
    );
    isScanning.value = true; // Actualizamos estado al tener éxito
  } catch (err) {
    console.error("Error al iniciar cámara:", err);
    isScanning.value = false;
  }
};

const stopScanner = async () => {
  if (html5QrCode && html5QrCode.isScanning) {
    try {
      await html5QrCode.stop();
      html5QrCode.clear();
      isScanning.value = false; // Actualizamos estado al detener
    } catch (err) {
      console.error("Error al detener:", err);
    }
  }
};
const toggleManual = (value) => {
  manualMode.value = value;
  if (value) {
    stopScanner(); // Detenemos la cámara si pasamos a modo manual
  } else {
    // Si volvemos a modo scanner, esperamos un tick de Vue y reiniciamos
    setTimeout(() => startScanner(), 100);
  }
};
const verifyTable = () => {
  if (!selectedTable.value) {
    alert("Por favor seleccione una mesa");
    return;
  }
  alert(`Verificando Mesa ${selectedTable.value}...`);
  // Aquí iría tu lógica de API para validar la mesa
};
const setActive = () => {
  const container = document.getElementById('contenedor');
  container.className = 'active';
};

const setClose = () => {
  const container = document.getElementById('contenedor');
  container.className = 'close';
};

const togglePassword = (inputId) => {
  const input = document.getElementById(inputId);
  const iconSpan = document.getElementById(`icon-${inputId}`);

  if (input.type === 'password') {
    input.type = 'text';
    // SVG Ojo tachado
    iconSpan.parentElement.innerHTML = `<svg id="icon-${inputId}" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
  } else {
    input.type = 'password';
    // SVG Ojo abierto
    iconSpan.parentElement.innerHTML = `<svg id="icon-${inputId}" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
  }
};

onMounted(() => {
  startScanner();
  // Inicializar en modo close para que se vea el login en móviles
  if (window.innerWidth <= 768) {
    document.getElementById('contenedor').classList.add('close');
  }
});
onUnmounted(() => {
  stopScanner();
});
</script>

<script>
export default {
  name: 'Login'
}
</script>

<style src="@/assets/styles/login.css"></style>