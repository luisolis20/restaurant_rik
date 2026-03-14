//Codigo para la autenticación del usuario
import { ref } from 'vue';// Librería para poder usar el ref
import axios from 'axios'; //Librería para poder usar el axios
import store from "@/store";
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
      const token = localStorage.getItem("token_rest");
      await axios.get(
        `${__API_RESTAURANT__}/restrik/logout`,
        {},
        {
          headers: { Authorization: `Bearer ${token}` }
        }
      )
      console.error('Error al obtener perfil data:', error);
      localStorage.clear();
      window.location.href = '/login';
    }
    throw error;
  }
};
