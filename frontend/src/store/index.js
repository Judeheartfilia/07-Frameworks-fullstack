import { createStore } from 'vuex'
import axios from 'axios'

export default createStore({
  state: {
    user: null,
    token: localStorage.getItem('token'),
    products: [],
    cart: [],
    orders: [],
    loading: false
  },
  
  mutations: {
    SET_USER(state, user) {
      state.user = user
    },
    SET_TOKEN(state, token) {
      state.token = token
      localStorage.setItem('token', token)
    },
    CLEAR_AUTH(state) {
      state.user = null
      state.token = null
      localStorage.removeItem('token')
    },
    SET_PRODUCTS(state, products) {
      state.products = products
    },
    SET_LOADING(state, status) {
      state.loading = status
    },
    ADD_TO_CART(state, product) {
      const existingItem = state.cart.find(item => item.id === product.id)
      if (existingItem) {
        existingItem.quantity++
      } else {
        state.cart.push({ ...product, quantity: 1 })
      }
    },
    REMOVE_FROM_CART(state, productId) {
      state.cart = state.cart.filter(item => item.id !== productId)
    },
    UPDATE_CART_QUANTITY(state, { productId, quantity }) {
      const item = state.cart.find(item => item.id === productId)
      if (item) {
        if (quantity <= 0) {
          state.cart = state.cart.filter(item => item.id !== productId)
        } else {
          item.quantity = quantity
        }
      }
    },
    CLEAR_CART(state) {
      state.cart = []
    },
    SET_ORDERS(state, orders) {
      state.orders = orders
    }
  },
  
  actions: {
    async login({ commit }, credentials) {
      try {
        const response = await axios.post('/auth/login', credentials)
        const { token, user } = response.data
        
        commit('SET_TOKEN', token)
        commit('SET_USER', user)
        
        return { success: true }
      } catch (error) {
        return { 
          success: false, 
          message: error.response?.data?.error || 'Erreur de connexion' 
        }
      }
    },
    
    async register({ commit }, userData) {
      try {
        const response = await axios.post('/auth/register', userData)
        const { token, user } = response.data
        
        commit('SET_TOKEN', token)
        commit('SET_USER', user)
        
        return { success: true }
      } catch (error) {
        return { 
          success: false, 
          message: error.response?.data?.error || 'Erreur d\'inscription' 
        }
      }
    },
    
    logout({ commit }) {
      commit('CLEAR_AUTH')
      commit('CLEAR_CART')
    },
    
    async fetchProducts({ commit }) {
      try {
        commit('SET_LOADING', true)
        const response = await axios.get('/products')
        commit('SET_PRODUCTS', response.data)
      } catch (error) {
        console.error('Erreur lors du chargement des produits:', error)
      } finally {
        commit('SET_LOADING', false)
      }
    },
    
    async fetchUserProfile({ commit, state }) {
      if (!state.token) return
      
      try {
        const response = await axios.get('/users/profile')
        commit('SET_USER', response.data)
      } catch (error) {
        console.error('Erreur lors du chargement du profil:', error)
      }
    },
    
    async createOrder({ commit, state }, orderData) {
      try {
        const items = state.cart.map(item => ({
          productId: item.id,
          quantity: item.quantity
        }))
        
        const response = await axios.post('/orders', {
          items,
          payment: orderData.payment
        })
        
        commit('CLEAR_CART')
        await this.dispatch('fetchUserProfile')
        
        return { success: true, data: response.data }
      } catch (error) {
        return { 
          success: false, 
          message: error.response?.data?.error || 'Erreur lors de la commande' 
        }
      }
    },
    
    async fetchOrders({ commit }) {
      try {
        const response = await axios.get('/orders')
        commit('SET_ORDERS', response.data)
      } catch (error) {
        console.error('Erreur lors du chargement des commandes:', error)
      }
    }
  },
  
  getters: {
    isAuthenticated: state => !!state.token,
    cartTotal: state => {
      return state.cart.reduce((total, item) => {
        return total + (item.price * item.quantity)
      }, 0)
    },
    cartItemCount: state => {
      return state.cart.reduce((count, item) => count + item.quantity, 0)
    }
  }
})