import { mostraralertas } from "@/assets/js/function/funciones";
import { enviarsolilogin } from "@/assets/js/function/login_function";
import store from "@/store";
import { getMe } from '@/store/auth';
export default {
  data() {
    return {
      email: "",
      password: "",
      url2: `${__API_RESTAURANT__}/restrik/login`,
    };
  },
  methods: {
    async login() {
      try {
        var parametros = {
          email: this.email.trim(),
          password: this.password.trim(),
        };

        const response = await enviarsolilogin('POST', parametros, this.url2, 'Logueado');
        //console.log(response);
        if (response.error) {
          mostraralertas(response.mensaje, 'warning');
        } else if (response) {
          

          // Redirección según el rol
          
          const tok = response.token;
          //console.log(response.id);
          //console.log(response);
          if (response.RolUs) {
            mostraralertas('LE DAMOS LA BIENVENIDA ADMIN ' + (response.name || ''), 'success');
            this.$router.push('/admin');
          } else if (response.Rolme) {
            mostraralertas('LE DAMOS LA BIENVENIDA' + (response.name || ''), 'success');
            this.$router.push('/home');
          }
        }
      } catch (error) {
        console.error("Error en login:", error);
        if (error.response?.data?.mensaje) {
          mostraralertas(error.response.data.mensaje, 'warning');
        } else {
          mostraralertas('No se pudo conectar con el servidor o error inesperado.', 'error');
        }
      }
    },
  },
};
