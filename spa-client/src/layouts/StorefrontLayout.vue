<template>
  <div class="storefront-layout">
    <header class="storefront-header">
      <div class="container">
        <div class="header-top d-flex justify-content-between align-items-center py-3">
          <router-link to="/" class="logo">
            <h1 class="m-0">AlIgans</h1>
          </router-link>
          
          <div class="nav-controls d-flex align-items-center">
            <router-link to="/cart" class="cart-link mx-3">
              <i class="fas fa-shopping-cart"></i>
              <span v-if="cartCount" class="cart-count">{{ cartCount }}</span>
            </router-link>
            
            <div v-if="isLoggedIn" class="user-menu dropdown">
              <button class="btn dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                {{ user.name }}
              </button>
              <ul class="dropdown-menu" aria-labelledby="userMenuButton">
                <li><router-link class="dropdown-item" to="/profile">My Profile</router-link></li>
                <li><router-link class="dropdown-item" to="/orders">My Orders</router-link></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" @click.prevent="logout">Logout</a></li>
              </ul>
            </div>
            
            <div v-else class="auth-links">
              <router-link to="/login" class="btn btn-outline-primary mx-2">Login</router-link>
              <router-link to="/register" class="btn btn-primary">Register</router-link>
            </div>
          </div>
        </div>
        
        <nav class="main-nav py-2">
          <ul class="nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/products">Shop</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/categories">Categories</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/events">Events</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/teams">Teams</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/players">Players</router-link>
            </li>
          </ul>
        </nav>
      </div>
    </header>
    
    <main class="storefront-content">
      <div class="container py-4">
        <slot></slot>
      </div>
    </main>
    
    <footer class="storefront-footer py-4 mt-5">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h5>AlIgans</h5>
            <p>Your one-stop shop for all things Igans</p>
          </div>
          <div class="col-md-4">
            <h5>Quick Links</h5>
            <ul class="list-unstyled">
              <li><router-link to="/products">Products</router-link></li>
              <li><router-link to="/events">Events</router-link></li>
              <li><router-link to="/teams">Teams</router-link></li>
              <li><router-link to="/players">Players</router-link></li>
            </ul>
          </div>
          <div class="col-md-4">
            <h5>Contact</h5>
            <ul class="list-unstyled">
              <li>Email: info@aligans.com</li>
              <li>Phone: +1 234 567 8900</li>
              <li>Address: 123 Igans Street, Sports City</li>
            </ul>
          </div>
        </div>
        <div class="text-center mt-4">
          <p>&copy; {{ new Date().getFullYear() }} AlIgans. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'StorefrontLayout',
  computed: {
    ...mapGetters({
      isLoggedIn: 'auth/isLoggedIn',
      user: 'auth/user',
      cartCount: 'cart/itemCount'
    })
  },
  methods: {
    ...mapActions({
      logoutAction: 'auth/logout'
    }),
    logout() {
      this.logoutAction();
      this.$router.push('/login');
    }
  }
}
</script>

<style scoped>
.storefront-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.storefront-header {
  background-color: #f8f9fa;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.logo h1 {
  font-weight: bold;
  color: #3490dc;
}

.cart-link {
  position: relative;
  font-size: 1.25rem;
  color: #333;
}

.cart-count {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: #e3342f;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 0.7rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.main-nav .nav {
  justify-content: center;
}

.main-nav .nav-link {
  font-weight: 500;
  color: #333;
  padding: 0.5rem 1rem;
}

.main-nav .nav-link:hover,
.main-nav .router-link-active {
  color: #3490dc;
}

.storefront-content {
  flex: 1;
}

.storefront-footer {
  background-color: #f8f9fa;
  border-top: 1px solid #dee2e6;
}

.storefront-footer a {
  color: #3490dc;
  text-decoration: none;
}

.storefront-footer a:hover {
  text-decoration: underline;
}
</style> 