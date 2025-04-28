import axios from 'axios'
import { API_URL } from './config.js'

const authService = {
  async login(credentials) {
    return axios.post(`${API_URL}/auth/login`, credentials)
  },

  async register(userData) {
    return axios.post(`${API_URL}/auth/register`, userData)
  },

  async logout() {
    const token = localStorage.getItem('token')
    return axios.post(`${API_URL}/auth/logout`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    })
  },

  async getUser() {
    const token = localStorage.getItem('token')
    return axios.get(`${API_URL}/auth/me`, {
      headers: { Authorization: `Bearer ${token}` }
    })
  },

  getAuthHeader() {
    const token = localStorage.getItem('token')
    return { Authorization: `Bearer ${token}` }
  }
}

export default authService 