<template>
  <admin-layout>
    <div class="dashboard-container">
      <!-- Welcome Header -->
      <div class="welcome-header">
        <div class="welcome-content">
          <h2 class="welcome-title">Welcome back, {{ userName }}!</h2>
          <p class="welcome-subtitle">Here's what's happening with your store today.</p>
        </div>
        <div class="header-actions">
          <button @click="logout" class="logout-button">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
          </button>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="stats-container">
        <div class="stat-card">
          <div class="stat-icon orders-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <div class="stat-content">
            <h3 class="stat-title">Total Orders</h3>
            <p class="stat-value">{{ stats.totalOrders }}</p>
            <p class="stat-change increase">+12.5% from last month</p>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon revenue-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="stat-content">
            <h3 class="stat-title">Revenue</h3>
            <p class="stat-value">${{ stats.totalRevenue.toLocaleString() }}</p>
            <p class="stat-change increase">+8.2% from last month</p>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon products-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <div class="stat-content">
            <h3 class="stat-title">Products</h3>
            <p class="stat-value">{{ stats.totalProducts }}</p>
            <p class="stat-change increase">+4.6% from last month</p>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon users-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <div class="stat-content">
            <h3 class="stat-title">Customers</h3>
            <p class="stat-value">{{ stats.totalCustomers }}</p>
            <p class="stat-change increase">+6.8% from last month</p>
          </div>
        </div>
      </div>

      <!-- Sales Overview -->
      <div class="dashboard-row">
        <div class="sales-overview-container">
          <div class="card-header">
            <h3 class="card-title">Sales Overview</h3>
            <div class="card-actions">
              <select v-model="salesPeriod" class="period-select">
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
              </select>
            </div>
          </div>
          <div class="sales-chart">
            <!-- Chart Placeholder - In a real app, would be a chart component -->
            <div class="chart-placeholder">
              <div class="chart-bar" v-for="(value, day) in sampleChartData" :key="day" :style="{ height: `${value}%` }">
                <span class="bar-tooltip">${{ (value * 100).toFixed(0) }}</span>
              </div>
            </div>
            <div class="chart-labels">
              <span v-for="(_, day) in sampleChartData" :key="day">{{ day }}</span>
            </div>
          </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions-container">
          <h3 class="card-title">Quick Actions</h3>
          <div class="quick-actions-grid">
            <router-link to="/admin/products/create" class="quick-action-card">
              <div class="action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
              </div>
              <span class="action-text">Add New Product</span>
            </router-link>
            
            <router-link to="/admin/categories/create" class="quick-action-card">
              <div class="action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
              </div>
              <span class="action-text">Add Category</span>
            </router-link>
            
            <router-link to="/admin/events/create" class="quick-action-card">
              <div class="action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <span class="action-text">Add Event</span>
            </router-link>
            
            <button class="quick-action-card">
              <div class="action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <span class="action-text">Generate Reports</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="recent-orders-container">
        <div class="card-header">
          <h3 class="card-title">Recent Orders</h3>
          <router-link to="/admin/orders" class="view-all-link">
            View All Orders
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </router-link>
        </div>
        
        <div class="orders-table-container">
          <table class="orders-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in recentOrders" :key="order.id">
                <td>#{{ order.id }}</td>
                <td>{{ order.customer_name }}</td>
                <td>{{ formatDate(order.created_at) }}</td>
                <td>${{ order.total.toLocaleString() }}</td>
                <td>
                  <span class="status-badge" :class="getStatusClass(order.status)">
                    {{ order.status }}
                  </span>
                </td>
                <td>
                  <div class="table-actions">
                    <router-link :to="`/admin/orders/${order.id}`" class="action-button view">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </router-link>
                  </div>
                </td>
              </tr>
              
              <!-- Empty state if no orders -->
              <tr v-if="recentOrders.length === 0">
                <td colspan="6" class="empty-state">
                  <p>No recent orders found</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </admin-layout>
</template>

<script>
import AdminLayout from '@/layouts/AdminLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
  name: 'AdminDashboard',
  components: {
    AdminLayout
  },
  setup() {
    const store = useStore();
    const router = useRouter();
    
    const stats = ref({
      totalOrders: 128,
      totalRevenue: 28976.54,
      totalProducts: 342,
      totalCustomers: 573
    });
    
    const recentOrders = ref([
      {
        id: 1043,
        customer_name: 'Michael Johnson',
        created_at: '2023-06-12T08:32:10',
        total: 255.99,
        status: 'Delivered'
      },
      {
        id: 1042,
        customer_name: 'Sarah Williams',
        created_at: '2023-06-11T14:15:22',
        total: 125.50,
        status: 'Processing'
      },
      {
        id: 1041,
        customer_name: 'David Thompson',
        created_at: '2023-06-10T19:45:37',
        total: 450.00,
        status: 'Shipped'
      },
      {
        id: 1040,
        customer_name: 'Emily Davis',
        created_at: '2023-06-09T11:20:15',
        total: 89.99,
        status: 'Pending'
      },
      {
        id: 1039,
        customer_name: 'Robert Smith',
        created_at: '2023-06-08T16:08:45',
        total: 178.25,
        status: 'Delivered'
      }
    ]);
    
    const salesPeriod = ref('month');
    
    const sampleChartData = ref({
      'Mon': 65,
      'Tue': 59,
      'Wed': 80,
      'Thu': 81,
      'Fri': 56,
      'Sat': 55,
      'Sun': 40
    });
    
    const userName = computed(() => {
      const user = store.getters['auth/user'];
      return user ? user.name : 'Admin';
    });
    
    const formatDate = (dateString) => {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    };
    
    const getStatusClass = (status) => {
      switch (status.toLowerCase()) {
        case 'delivered':
          return 'status-success';
        case 'shipped':
          return 'status-info';
        case 'processing':
          return 'status-warning';
        case 'pending':
          return 'status-pending';
        case 'cancelled':
          return 'status-danger';
        default:
          return '';
      }
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
      // In a real application, you would fetch actual data here
      console.log('Admin Dashboard Mounted');
    });
    
    return {
      stats,
      recentOrders,
      salesPeriod,
      sampleChartData,
      userName,
      formatDate,
      getStatusClass,
      logout
    };
  }
};
</script>

<style scoped>
.dashboard-container {
  padding: 1.5rem;
}

/* Welcome Header */
.welcome-header {
  background: linear-gradient(to right, #4f46e5, #7c3aed);
  border-radius: 0.5rem;
  padding: 2rem;
  margin-bottom: 1.5rem;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
}

.welcome-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0;
  margin-bottom: 0.5rem;
}

.welcome-subtitle {
  font-size: 1rem;
  opacity: 0.8;
  margin: 0;
}

.logout-button {
  display: flex;
  align-items: center;
  padding: 0.5rem 1rem;
  background-color: rgba(255, 255, 255, 0.2);
  border: none;
  border-radius: 0.375rem;
  color: white;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.logout-button:hover {
  background-color: rgba(255, 255, 255, 0.3);
}

/* Stats Container */
.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.stat-card {
  background-color: white;
  border-radius: 0.5rem;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
  flex-shrink: 0;
}

.stat-icon svg {
  width: 24px;
  height: 24px;
  color: white;
}

.orders-icon {
  background-color: #3b82f6;
}

.revenue-icon {
  background-color: #10b981;
}

.products-icon {
  background-color: #f59e0b;
}

.users-icon {
  background-color: #8b5cf6;
}

.stat-content {
  flex: 1;
}

.stat-title {
  font-size: 0.875rem;
  color: #6b7280;
  margin: 0;
  margin-bottom: 0.25rem;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
  margin-bottom: 0.25rem;
}

.stat-change {
  font-size: 0.75rem;
  display: flex;
  align-items: center;
  margin: 0;
}

.increase {
  color: #10b981;
}

.decrease {
  color: #ef4444;
}

/* Dashboard Row */
.dashboard-row {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

/* Card Header */
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.card-actions {
  display: flex;
  align-items: center;
}

.period-select {
  padding: 0.375rem 0.75rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  background-color: white;
  color: #4b5563;
  font-size: 0.875rem;
  cursor: pointer;
}

/* Sales Overview */
.sales-overview-container {
  background-color: white;
  border-radius: 0.5rem;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.sales-chart {
  height: 200px;
  margin-top: 1rem;
}

.chart-placeholder {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  height: 180px;
  margin-bottom: 0.5rem;
}

.chart-bar {
  width: 12%;
  background-color: #4f46e5;
  border-radius: 4px 4px 0 0;
  position: relative;
  transition: height 0.5s ease;
}

.chart-bar:hover {
  background-color: #6366f1;
}

.chart-bar:hover .bar-tooltip {
  opacity: 1;
}

.bar-tooltip {
  position: absolute;
  top: -25px;
  left: 50%;
  transform: translateX(-50%);
  background-color: #1f2937;
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  opacity: 0;
  transition: opacity 0.2s ease;
}

.chart-labels {
  display: flex;
  justify-content: space-between;
  font-size: 0.75rem;
  color: #6b7280;
}

/* Quick Actions */
.quick-actions-container {
  background-color: white;
  border-radius: 0.5rem;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.quick-actions-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  margin-top: 1rem;
}

.quick-action-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background-color: #f9fafb;
  border-radius: 0.375rem;
  color: #4b5563;
  text-decoration: none;
  transition: background-color 0.2s ease, transform 0.2s ease;
  cursor: pointer;
  border: none;
  text-align: center;
}

.quick-action-card:hover {
  background-color: #f3f4f6;
  transform: translateY(-2px);
}

.action-icon {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #e5e7eb;
  border-radius: 50%;
  margin-bottom: 0.5rem;
}

.action-icon svg {
  color: #4f46e5;
}

.action-text {
  font-size: 0.875rem;
  font-weight: 500;
}

/* Recent Orders */
.recent-orders-container {
  background-color: white;
  border-radius: 0.5rem;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.view-all-link {
  display: flex;
  align-items: center;
  font-size: 0.875rem;
  color: #4f46e5;
  text-decoration: none;
  font-weight: 500;
}

.orders-table-container {
  overflow-x: auto;
  margin-top: 1rem;
}

.orders-table {
  width: 100%;
  border-collapse: collapse;
}

.orders-table th {
  padding: 0.75rem 1rem;
  text-align: left;
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
  border-bottom: 1px solid #e5e7eb;
  background-color: #f9fafb;
}

.orders-table td {
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  color: #4b5563;
  border-bottom: 1px solid #e5e7eb;
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.status-success {
  background-color: #d1fae5;
  color: #065f46;
}

.status-info {
  background-color: #dbeafe;
  color: #1e40af;
}

.status-warning {
  background-color: #fef3c7;
  color: #92400e;
}

.status-pending {
  background-color: #e5e7eb;
  color: #4b5563;
}

.status-danger {
  background-color: #fee2e2;
  color: #b91c1c;
}

.table-actions {
  display: flex;
  gap: 0.5rem;
}

.action-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 0.25rem;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.action-button.view {
  background-color: #dbeafe;
  color: #1e40af;
}

.action-button.view:hover {
  background-color: #bfdbfe;
}

.empty-state {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
}

/* Responsive Adjustments */
@media (max-width: 1024px) {
  .dashboard-row {
    grid-template-columns: 1fr;
  }
  
  .welcome-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .header-actions {
    margin-top: 1rem;
  }
}

@media (max-width: 640px) {
  .stats-container {
    grid-template-columns: 1fr;
  }
  
  .quick-actions-grid {
    grid-template-columns: 1fr;
  }
}
</style> 