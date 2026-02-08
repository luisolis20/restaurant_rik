//Funcion para el login del usuario
import axios from "axios";
import store from "@/store";
export async function enviarsolilogin(method, parametros, url, mensaje) {
  try {
    const response = await axios({
      method: method,
      url: url,
      data: parametros,
    });
  //console.log(response);

    if (response.data && response.data.token) {
      store.commit("setToken_rest", response.data.token);
      store.commit("setTokenType_rest", response.data.token_type || "Bearer");
      if (response.data.error) {
        return {
          error: response.data.error,
          clave: response.data.clave,
          mensaje: response.data.mensaje,
        };
      }
      
      else if(response.data.rol) {
        store.commit("setRol_rest", response.data.rol);
        store.commit("setemail_rest", response.data.email);
        //store.commit("setid_bio", response.data.id);

        store.commit("setname_rest", response.data.nombre);
        
        return {
          token: response.data.token,
          RolUs: response.data.rol,
          //id: response.data.id,
          name: response.data.nombre,
          email: response.data.email,
        };
      }else if(response.data.rolme) {
        store.commit("setRol_rest", response.data.rolme);
        store.commit("setemail_rest", 'mesa@mesa.com');
        //store.commit("setid_bio", response.data.id);
        store.commit("setname_rest", response.data.codigo_mesa);
        return {
          token: response.data.token,
          Rolme: response.data.rolme,
          //id: response.data.id,
          name: response.data.codigo_mesa,
          email: 'mesa@mesa.com',
        };
      }  
    } else {
      console.error("Respuesta inesperada:", response);
      return null;
    }
  } catch (error) {
    console.error("Error:", error.response.data);
    throw error;
  }
}
