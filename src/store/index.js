import { createStore } from 'vuex'

export default createStore({
  state: {
    role: localStorage.getItem('Rol_rest') || null,
    email: localStorage.getItem('email_rest') || null,
    idusu: localStorage.getItem('id_rest') || null,
    name: localStorage.getItem('name_rest') || null,
    token: localStorage.getItem('token_rest') || null,
    token_type: localStorage.getItem('token_type_rest') || null,
  },
  getters: {
    getIdusu: state => state.idusu,
    isAuthenticated: state => !!state.token,
    getFullToken: state => `${state.token_type} ${state.token}`,
  },
  mutations: {
    setRol_rest(state, nuevoRol) {
      state.role = nuevoRol;
      localStorage.setItem('Rol_rest', nuevoRol);
    },
    setemail_rest(state, nuevoemail) {
      state.email = nuevoemail;
      localStorage.setItem('email_rest', nuevoemail);
    },
    setid_rest(state, nuevoid) {
      state.idusu = nuevoid;
      localStorage.setItem('id_rest', nuevoid);
    },
    setname_rest(state, nuevoname) {
      state.name = nuevoname;
      localStorage.setItem('name_rest', nuevoname);
    },
    setToken_rest(state, token) {
      state.token = token;
      localStorage.setItem('token_rest', token);
    },
    setTokenType_rest(state, type) {
      state.token_type = type;
      localStorage.setItem('token_type_rest', type);
    },
    logout_rest(state) {
      // Limpia el state y localStorage al cerrar sesión
      state.role = null;
      state.email = null;
      state.idusu = null;
      state.name = null;
      state.token = null;
      state.token_type = null;

      localStorage.removeItem('Rol_rest');
      localStorage.removeItem('email_rest');
      localStorage.removeItem('id_rest');
      localStorage.removeItem('name_rest');
      localStorage.removeItem('token_rest');
      localStorage.removeItem('token_type_rest');
      localStorage.removeItem('user_rest');
    },
  },
  actions: {},
  modules: {}
})
