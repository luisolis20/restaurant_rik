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
  // 1. Obtener tokens antes de borrar localstorage
  const token = localStorage.getItem('token_rest');
  const tokenType = localStorage.getItem('token_type_rest') || 'Bearer';

  try {
    if (token) {
      // Forzamos el envío del token manualmente por si el interceptor falla en el cierre
      await apiClient.get('/logout', {
        headers: {
          'Authorization': `${tokenType} ${token}`
        }
      });
    }
  } catch (error) {
    console.warn("El servidor no pudo liberar la mesa, procediendo a limpiar local:", error);
  } finally {
    // 2. Limpieza local (Siempre se ejecuta)
    localStorage.removeItem('token_rest');
    localStorage.removeItem('token_type_rest');
    localStorage.removeItem('user_rest');
    
    logged.value = false;
    user.value = null;

    if (showMsg) {
      mostraralertas2("Sesión finalizada. ¡Vuelve pronto!", "success");
    }

    // 3. Redirección con un pequeño delay si hay mensaje
    setTimeout(() => {
      window.location.href = '/login';
    }, showMsg ? 1500 : 0);
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
