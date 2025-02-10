import apiClient from '../../plugins/axios';

const state = {
    user: null,
    token: localStorage.getItem('token') || '',
};

const getters = {
    isAuthenticated: (state) => !!state.token, // Returns true if token exists
    user: (state) => state.user,
};

const actions = {
    async login({ commit }, credentials) {
        const response = await apiClient.post('/login', credentials);
        const token = response.data.access_token;
        const user = response.data.user;

        localStorage.setItem('token', token);  // Store token in local storage
        commit('SET_TOKEN', token);
        commit('SET_USER', user);

        apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },
    async register({ commit }, credentials) {
        const response = await apiClient.post('/register', credentials);
        const token = response.data.access_token;
        const user = response.data.user;

        localStorage.setItem('token', token);  // Store token in local storage
        commit('SET_TOKEN', token);
        commit('SET_USER', user);

        apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    },
    async logout({ commit }) {
        try {
          // Call API endpoint if exists (optional)
          await apiClient.post('/logout');
          localStorage.removeItem('token'); // Clear token from localStorage
          commit('SET_TOKEN', '');
          commit('SET_USER', null);
        } catch (error) {
          console.error('Logout API call failed:', error);
        }
      },
    async fetchUser({ commit }) {
        const response = await apiClient.get('/user');
        commit('SET_USER', response.data);
    },
};

const mutations = {
    SET_USER(state, user) {
        state.user = user;
    },
    SET_TOKEN(state, token) {
        state.token = token;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
