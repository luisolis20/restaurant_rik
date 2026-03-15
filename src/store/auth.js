//Codigo para la autenticación del usuario
import { ref } from 'vue';// Librería para poder usar el ref
import axios from 'axios'; //Librería para poder usar el axios
import store from "@/store";
import {
    mostraralertas2,
} from "@/assets/js/function/funciones";
// Importación de la librería para poder usar el ref
const logged = ref(false); // Variable para almacenar si el usuario está logueado o no
const user = ref('');// Variable para almacenar el usuario logueado
const meURL = `${__API_RESTAURANT__}/restrik/me`; // URL para el endpoint de login

// Creación de un cliente de axios
const apiClient = axios.create({
  baseURL: `${__API_RESTAURANT__}/restrik`, // Solo la base
  headers: { 'Content-Type': 'application/json' },
});
// Interceptor para agregar el token al header de la petición
apiClient.interceptors.request.use(
  (config) => {
    // IMPORTANTE: Asegúrate de que los nombres coincidan con los que guardas en el login
    const token = localStorage.getItem('token_rest'); 
    const tokenType = localStorage.getItem('token_type_rest') || 'Bearer';
    
    if (token) {
      config.headers.Authorization = `${tokenType} ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);
export const logout = async (showMsg = false) => {
  try {
    // Intentamos avisar al servidor para que libere la mesa
    await apiClient.get('/logout');
  } catch (error) {
    // Si falla el logout (ej. token ya expiró), procedemos igual con la limpieza local
    console.warn("No se pudo completar el logout en el servidor:", error);
  } finally {
    if (showMsg) {
      // Usamos una alerta nativa o puedes cambiarlo por SweetAlert: Swal.fire(...)
      mostraralertas2("Tu sesión ha expirado por seguridad", "success");
    }
    // Limpieza absoluta del cliente
    localStorage.removeItem('token_rest');
    localStorage.removeItem('token_type_rest');
    localStorage.removeItem('user_rest');
    logged.value = false;
    user.value = null;
    
    // Redirección
    window.location.href = '/login';
  }
};
// Función para obtener el usuario logueado
export const getMe = async () => {
  try {
    const response = await apiClient.get('/me');
    localStorage.setItem('user_rest', JSON.stringify(response.data));
    logged.value = true;
    user.value = response.data;
    //console.log(response.data);
    return response.data;
  } catch (error) {
    if (error.response && error.response.status === 401) {
        console.error('Error al obtener perfil data:', error);
       await logout(true);
    }
    throw error;
  }
};
