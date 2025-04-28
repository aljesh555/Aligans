import { createStore } from 'vuex'
import auth from './modules/auth'
import cart from './modules/cart'

export default createStore({
  state: {
    token: localStorage.getItem('token') || null,
    user: JSON.parse(localStorage.getItem('user')) || null,
    products: [],
    product: null,
    cart: null,
    orders: [],
    order: null,
    events: [],
    event: null,
    teams: [],
    team: null
  },
  getters: {
    isAuthenticated: state => !!state.token,
    user: state => state.user,
    products: state => state.products,
    product: state => state.product,
    cart: state => state.cart,
    orders: state => state.orders,
    order: state => state.order,
    events: state => state.events,
    event: state => state.event,
    teams: state => state.teams,
    team: state => state.team
  },
  mutations: {
    setToken(state, token) {
      state.token = token
    },
    setUser(state, user) {
      state.user = user
    },
    clearAuth(state) {
      state.token = null
      state.user = null
    },
    setProducts(state, products) {
      state.products = products
    },
    setProduct(state, product) {
      state.product = product
    },
    setCart(state, cart) {
      state.cart = cart
    },
    setOrders(state, orders) {
      state.orders = orders
    },
    setOrder(state, order) {
      state.order = order
    },
    setEvents(state, events) {
      state.events = events
    },
    setEvent(state, event) {
      state.event = event
    },
    setTeams(state, teams) {
      state.teams = teams
    },
    setTeam(state, team) {
      state.team = team
    }
  },
  actions: {
    async login({ commit }, credentials) {
      try {
        const response = await authService.login(credentials)
        const { token, user } = response.data
        localStorage.setItem('token', token)
        localStorage.setItem('user', JSON.stringify(user))
        commit('setToken', token)
        commit('setUser', user)
        return response
      } catch (error) {
        throw error
      }
    },
    async register({ commit }, userData) {
      try {
        const response = await authService.register(userData)
        const { token, user } = response.data
        localStorage.setItem('token', token)
        localStorage.setItem('user', JSON.stringify(user))
        commit('setToken', token)
        commit('setUser', user)
        return response
      } catch (error) {
        throw error
      }
    },
    async logout({ commit }) {
      try {
        await authService.logout()
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        commit('clearAuth')
      } catch (error) {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        commit('clearAuth')
        throw error
      }
    }
  },
  modules: {
    auth,
    cart
  }
}) 