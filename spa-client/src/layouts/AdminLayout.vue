<template>
  <div class="admin-layout">
    <!-- Sidebar -->
    <aside :class="['admin-sidebar', { 'sidebar-collapsed': isSidebarCollapsed }]">
      <div class="sidebar-header">
        <router-link to="/admin/dashboard" class="logo-container">
          <img src="/nepal-logo.png" alt="Nepal Logo" class="logo-image" />
          <span class="logo-text" v-if="!isSidebarCollapsed">Nepal Admin</span>
        </router-link>
        <button @click="toggleSidebar" class="collapse-button">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" v-if="!isSidebarCollapsed" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" v-if="isSidebarCollapsed" />
          </svg>
        </button>
      </div>
      
      <div class="sidebar-content">
        <nav class="sidebar-nav">
          <ul>
            <li>
              <router-link to="/admin/dashboard" class="nav-link" :class="{ active: isActive('/admin/dashboard') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span v-if="!isSidebarCollapsed">Dashboard</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/products" class="nav-link" :class="{ active: isActive('/admin/products') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span v-if="!isSidebarCollapsed">Products</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/categories" class="nav-link" :class="{ active: isActive('/admin/categories') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span v-if="!isSidebarCollapsed">Categories</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/logos" class="nav-link" :class="{ active: isActive('/admin/logos') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span v-if="!isSidebarCollapsed">Logos</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/banners" class="nav-link" :class="{ active: isActive('/admin/banners') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                </svg>
                <span v-if="!isSidebarCollapsed">Banners</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/orders" class="nav-link" :class="{ active: isActive('/admin/orders') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span v-if="!isSidebarCollapsed">Orders</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/events" class="nav-link" :class="{ active: isActive('/admin/events') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span v-if="!isSidebarCollapsed">Events</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/teams" class="nav-link" :class="{ active: isActive('/admin/teams') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span v-if="!isSidebarCollapsed">Teams</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/players" class="nav-link" :class="{ active: isActive('/admin/players') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span v-if="!isSidebarCollapsed">Players</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/attendance" class="nav-link" :class="{ active: isActive('/admin/attendance') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span v-if="!isSidebarCollapsed">Attendance</span>
              </router-link>
            </li>
            
            <li>
              <router-link to="/admin/users" class="nav-link" :class="{ active: isActive('/admin/users') }">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span v-if="!isSidebarCollapsed">Users</span>
              </router-link>
            </li>
          </ul>
        </nav>
      </div>
      
      <div class="sidebar-footer" v-if="!isSidebarCollapsed">
        <div class="user-info">
          <div class="user-avatar">
            {{ userInitials }}
          </div>
          <div class="user-details">
            <p class="user-name">{{ user.name }}</p>
            <p class="user-role">Administrator</p>
          </div>
        </div>
        <button @click="logout" class="logout-button">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Logout
        </button>
      </div>
    </aside>
    
    <!-- Main Content -->
    <main class="admin-main" :class="{ 'expanded': isSidebarCollapsed }">
      <!-- Header -->
      <header class="admin-header">
        <div class="header-left">
          <h1 class="page-title">{{ pageTitle }}</h1>
          <nav class="breadcrumbs">
            <ol>
              <li>
                <router-link to="/admin/dashboard">Admin</router-link>
              </li>
              <li class="current">{{ pageTitle }}</li>
            </ol>
          </nav>
        </div>
        
        <div class="header-right">
          <div class="header-actions">
            <button class="action-button">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </button>
            
            <div class="user-dropdown" v-if="isSidebarCollapsed">
              <div class="user-avatar small">
                {{ userInitials }}
              </div>
              <div class="dropdown-menu">
                <div class="dropdown-item user-info-item">
                  <p class="user-name">{{ user.name }}</p>
                  <p class="user-role">Administrator</p>
                </div>
                <div class="dropdown-divider"></div>
                <button @click="logout" class="dropdown-item logout-item">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  Logout
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>
      
      <!-- Content -->
      <div class="admin-content">
        <slot></slot>
      </div>
    </main>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import { useRouter, useRoute } from 'vue-router';

export default {
  name: 'AdminLayout',
  setup() {
    const store = useStore();
    const router = useRouter();
    const route = useRoute();
    
    const isSidebarCollapsed = ref(false);
    const user = computed(() => store.getters['auth/user'] || { name: 'Admin User' });
    
    const userInitials = computed(() => {
      if (!user.value.name) return 'AU';
      return user.value.name
        .split(' ')
        .map(name => name.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);
    });
    
    const pageTitle = computed(() => {
      const path = route.path;
      if (path.includes('/dashboard')) return 'Dashboard';
      if (path.includes('/products')) return 'Products';
      if (path.includes('/categories')) return 'Categories';
      if (path.includes('/logos')) return 'Logos';
      if (path.includes('/orders')) return 'Orders';
      if (path.includes('/events')) return 'Events';
      if (path.includes('/teams')) return 'Teams';
      if (path.includes('/players')) return 'Players';
      if (path.includes('/attendance')) return 'Attendance';
      if (path.includes('/users')) return 'Users';
      return 'Admin Panel';
    });
    
    const isActive = (path) => {
      return route.path.startsWith(path);
    };
    
    const toggleSidebar = () => {
      isSidebarCollapsed.value = !isSidebarCollapsed.value;
      localStorage.setItem('adminSidebarCollapsed', isSidebarCollapsed.value);
    };
    
    const logout = async () => {
      try {
        await store.dispatch('auth/logout');
        router.push('/admin/login');
      } catch (error) {
        console.error('Error logging out:', error);
      }
    };
    
    onMounted(() => {
      const savedState = localStorage.getItem('adminSidebarCollapsed');
      if (savedState !== null) {
        isSidebarCollapsed.value = savedState === 'true';
      }
    });
    
    return {
      user,
      userInitials,
      pageTitle,
      isSidebarCollapsed,
      isActive,
      toggleSidebar,
      logout
    };
  }
};
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f9fafb;
}

/* Sidebar Styles */
.admin-sidebar {
  width: 280px;
  flex-shrink: 0;
  background-color: #1e293b;
  color: #e2e8f0;
  display: flex;
  flex-direction: column;
  transition: width 0.3s ease;
  box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
  position: relative;
  z-index: 10;
}

.sidebar-collapsed {
  width: 80px;
}

.sidebar-header {
  padding: 1.5rem 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-container {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: #fff;
}

.logo-image {
  width: 32px;
  height: 32px;
  object-fit: contain;
}

.logo-text {
  font-size: 1.25rem;
  font-weight: 600;
  margin-left: 0.75rem;
  white-space: nowrap;
}

.collapse-button {
  background: transparent;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
}

.collapse-button:hover {
  color: #fff;
}

.sidebar-content {
  flex: 1;
  overflow-y: auto;
  padding: 1rem 0;
}

.sidebar-nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  color: #94a3b8;
  text-decoration: none;
  transition: all 0.2s ease;
  border-left: 3px solid transparent;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.05);
  color: #fff;
}

.nav-link.active {
  background-color: rgba(79, 70, 229, 0.1);
  color: #fff;
  border-left-color: #6366f1;
}

.nav-icon {
  width: 24px;
  height: 24px;
  margin-right: 0.75rem;
}

.sidebar-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.user-info {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #4f46e5;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  margin-right: 0.75rem;
  flex-shrink: 0;
}

.user-avatar.small {
  width: 32px;
  height: 32px;
  font-size: 0.875rem;
}

.user-details {
  overflow: hidden;
}

.user-name {
  font-weight: 600;
  color: #fff;
  margin: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-role {
  color: #94a3b8;
  font-size: 0.75rem;
  margin: 0;
}

.logout-button {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem;
  background-color: rgba(255, 255, 255, 0.05);
  color: #e2e8f0;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.logout-button:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Main Content Styles */
.admin-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  transition: margin-left 0.3s ease;
}

.admin-main.expanded {
  margin-left: -200px;
}

.admin-header {
  background-color: #fff;
  border-bottom: 1px solid #e5e7eb;
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.header-left {
  display: flex;
  flex-direction: column;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
  margin-bottom: 0.25rem;
}

.breadcrumbs ol {
  display: flex;
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.875rem;
}

.breadcrumbs li {
  display: flex;
  align-items: center;
}

.breadcrumbs li:not(:last-child)::after {
  content: '/';
  margin: 0 0.5rem;
  color: #9ca3af;
}

.breadcrumbs a {
  color: #6b7280;
  text-decoration: none;
}

.breadcrumbs .current {
  color: #4f46e5;
  font-weight: 500;
}

.header-right {
  display: flex;
  align-items: center;
}

.header-actions {
  display: flex;
  align-items: center;
}

.action-button {
  background-color: transparent;
  border: none;
  padding: 0.5rem;
  margin-left: 0.5rem;
  color: #6b7280;
  cursor: pointer;
  border-radius: 0.375rem;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.action-button:hover {
  background-color: #f3f4f6;
  color: #1f2937;
}

.user-dropdown {
  position: relative;
  margin-left: 0.5rem;
}

.user-dropdown:hover .dropdown-menu {
  display: block;
}

.dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 0.5rem;
  background-color: #fff;
  border-radius: 0.375rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  min-width: 200px;
  z-index: 20;
}

.dropdown-item {
  padding: 0.75rem 1rem;
  cursor: pointer;
}

.dropdown-item:hover {
  background-color: #f3f4f6;
}

.user-info-item {
  padding: 1rem;
  cursor: default;
}

.user-info-item:hover {
  background-color: transparent;
}

.dropdown-divider {
  height: 1px;
  background-color: #e5e7eb;
  margin: 0;
}

.logout-item {
  display: flex;
  align-items: center;
  color: #ef4444;
}

.admin-content {
  flex: 1;
  overflow-y: auto;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .admin-sidebar {
    position: fixed;
    height: 100vh;
    z-index: 30;
    transform: translateX(-100%);
  }
  
  .admin-sidebar:not(.sidebar-collapsed) {
    transform: translateX(0);
  }
  
  .sidebar-collapsed {
    transform: translateX(-100%);
  }
  
  .admin-main {
    margin-left: 0 !important;
  }
  
  .header-left {
    flex-direction: column;
  }
}
</style> 