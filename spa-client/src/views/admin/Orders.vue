<template>
  <div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Order Management</h1>
      <div class="batch-actions" v-if="selectedOrders.length">
        <span class="text-sm text-gray-600">{{ selectedOrders.length }} orders selected</span>
        <button @click="exportSelected" class="btn-secondary">
          <i class="fas fa-file-export mr-1"></i> Export
        </button>
        <button @click="batchUpdateStatus = true" class="btn-primary">
          <i class="fas fa-pencil-alt mr-1"></i> Update Status
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="stats-card total">
        <div class="number">{{ stats.total }}</div>
        <div class="label">Total Orders</div>
      </div>
      <div class="stats-card processing">
        <div class="number">{{ stats.processing }}</div>
        <div class="label">Processing</div>
      </div>
      <div class="stats-card completed">
        <div class="number">{{ stats.completed }}</div>
        <div class="label">Completed</div>
      </div>
      <div class="stats-card cancelled">
        <div class="number">{{ stats.cancelled }}</div>
        <div class="label">Cancelled</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
          <input type="text" v-model="filters.orderId" class="w-full rounded border-gray-300 shadow-sm" placeholder="Order ID">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
          <input type="text" v-model="filters.customer" class="w-full rounded border-gray-300 shadow-sm" placeholder="Name or Email">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select v-model="filters.status" class="w-full rounded border-gray-300 shadow-sm">
            <option value="">All Statuses</option>
            <option v-for="status in orderStatuses" :key="status" :value="status">
              {{ status.charAt(0).toUpperCase() + status.slice(1) }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
          <input type="date" v-model="filters.dateFrom" class="w-full rounded border-gray-300 shadow-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
          <input type="date" v-model="filters.dateTo" class="w-full rounded border-gray-300 shadow-sm">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Min Amount</label>
          <input type="number" v-model="filters.minAmount" class="w-full rounded border-gray-300 shadow-sm" placeholder="Min $">
        </div>
      </div>
      <div class="flex justify-between mt-4">
        <button @click="resetFilters" class="btn btn-secondary">Reset Filters</button>
        <button @click="applyFilters" class="btn btn-primary">Apply Filters</button>
      </div>
    </div>

    <!-- Batch Actions -->
    <div class="flex justify-between mb-4">
      <div>
        <span class="mr-2">{{ selectedOrders.length }} orders selected</span>
        <button 
          v-if="selectedOrders.length > 0" 
          @click="exportSelectedOrders" 
          class="btn btn-sm btn-secondary mr-2"
        >
          Export CSV
        </button>
        <button 
          v-if="selectedOrders.length > 0" 
          class="btn btn-sm btn-primary"
          @click="showBatchUpdateModal = true"
        >
          Update Status
        </button>
      </div>
      <button @click="fetchOrders" class="btn btn-sm btn-secondary">
        <i class="fas fa-sync-alt mr-1"></i> Refresh
      </button>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
      <table class="order-table">
        <thead>
          <tr>
            <th class="w-10">
              <div class="checkbox-wrapper">
                <input 
                  type="checkbox" 
                  :checked="orders.length > 0 && selectedOrders.length === orders.length" 
                  @change="toggleAllOrders"
                  class="checkbox-input"
                />
              </div>
            </th>
            <th>Order ID</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody v-if="!loading && orders.length > 0">
          <tr v-for="order in orders" :key="order.id" :class="{ 'bg-blue-50': selectedOrders.includes(order.id) }">
            <td>
              <div class="checkbox-wrapper">
                <input 
                  type="checkbox" 
                  :checked="selectedOrders.includes(order.id)" 
                  @change="toggleOrderSelection(order.id)"
                  class="checkbox-input"
                />
              </div>
            </td>
            <td>#{{ order.id }}</td>
            <td>{{ formatDate(order.created_at) }}</td>
            <td>
              <div>{{ order.billing_name || order.user?.name || 'Guest' }}</div>
              <div class="text-xs text-gray-500">{{ order.billing_email || order.user?.email }}</div>
            </td>
            <td>{{ order.order_items?.length || 0 }} items</td>
            <td>{{ formatPrice(order.total_amount) }}</td>
            <td>
              <span :class="['status-badge', order.status.toLowerCase()]">
                {{ order.status }}
              </span>
            </td>
            <td>
              <div class="table-actions">
                <button @click="viewOrderDetails(order)" class="btn btn-sm btn-secondary">
                  <i class="fas fa-eye"></i>
                </button>
                <button 
                  v-if="['pending', 'processing'].includes(order.status.toLowerCase())" 
                  @click="updateOrderStatus(order.id, 'processing')" 
                  class="btn btn-sm btn-primary"
                >
                  Process
                </button>
                <button 
                  v-if="['pending', 'processing'].includes(order.status.toLowerCase())" 
                  @click="sendConfirmationEmail(order.id)" 
                  class="btn btn-sm btn-success"
                >
                  <i class="fas fa-envelope"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
        <tbody v-else-if="loading">
          <tr>
            <td colspan="8">
              <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
              </div>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <div class="empty-state-icon">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="empty-state-text">No orders found matching your criteria</div>
                <button @click="resetFilters" class="btn btn-primary">Clear Filters</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Order Details Modal -->
    <div v-if="showOrderDetails" class="modal-overlay" @click.self="showOrderDetails = false">
      <div class="modal">
        <div class="modal-header">
          <h2 class="text-xl font-bold">Order #{{ currentOrder.id }}</h2>
          <button @click="showOrderDetails = false" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="flex justify-between mb-4">
            <span :class="['status-badge', currentOrder.status?.toLowerCase()]">
              {{ currentOrder.status }}
            </span>
            <span>{{ formatDate(currentOrder.created_at) }}</span>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Information -->
            <div class="order-detail-section">
              <h3>Customer Information</h3>
              <div class="detail-row">
                <div class="detail-label">Customer:</div>
                <div class="detail-value">{{ currentOrder.user?.name || currentOrder.billing_name || 'Guest' }}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Email:</div>
                <div class="detail-value">{{ currentOrder.user?.email || currentOrder.billing_email }}</div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Phone:</div>
                <div class="detail-value">{{ currentOrder.billing_phone || 'N/A' }}</div>
              </div>
            </div>
            
            <!-- Shipping Information -->
            <div class="order-detail-section">
              <h3>Shipping Information</h3>
              <div class="detail-row">
                <div class="detail-label">Address:</div>
                <div class="detail-value">
                  {{ currentOrder.shipping_address || currentOrder.billing_address || 'N/A' }}
                </div>
              </div>
              <div class="detail-row">
                <div class="detail-label">City:</div>
                <div class="detail-value">
                  {{ currentOrder.shipping_city || currentOrder.billing_city || 'N/A' }}
                </div>
              </div>
              <div class="detail-row">
                <div class="detail-label">State/ZIP:</div>
                <div class="detail-value">
                  {{ (currentOrder.shipping_state || currentOrder.billing_state || 'N/A') + 
                     ' ' + 
                     (currentOrder.shipping_zip || currentOrder.billing_zip || '') }}
                </div>
              </div>
              <div class="detail-row">
                <div class="detail-label">Country:</div>
                <div class="detail-value">
                  {{ currentOrder.shipping_country || currentOrder.billing_country || 'N/A' }}
                </div>
              </div>
            </div>
          </div>
          
          <!-- Order Items -->
          <div class="order-detail-section mt-6">
            <h3>Order Items</h3>
            <table class="items-table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-right">Price</th>
                  <th class="text-right">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in currentOrder.order_items" :key="index">
                  <td>
                    <div class="font-medium">{{ item.product?.name || 'Product #' + item.product_id }}</div>
                    <div class="text-xs text-gray-500" v-if="item.options">
                      {{ Object.entries(item.options || {}).map(([key, value]) => `${key}: ${value}`).join(', ') }}
                    </div>
                  </td>
                  <td class="text-center">{{ item.quantity }}</td>
                  <td class="text-right">{{ formatPrice(item.price) }}</td>
                  <td class="text-right">{{ formatPrice(item.price * item.quantity) }}</td>
                </tr>
              </tbody>
            </table>
            
            <!-- Order Summary -->
            <div class="mt-4 w-1/3 ml-auto">
              <div class="summary-row">
                <span>Subtotal:</span>
                <span>{{ formatPrice(calculateSubtotal()) }}</span>
              </div>
              <div class="summary-row" v-if="currentOrder.tax_amount">
                <span>Tax:</span>
                <span>{{ formatPrice(currentOrder.tax_amount) }}</span>
              </div>
              <div class="summary-row" v-if="currentOrder.shipping_amount">
                <span>Shipping:</span>
                <span>{{ formatPrice(currentOrder.shipping_amount) }}</span>
              </div>
              <div class="summary-row" v-if="currentOrder.discount_amount">
                <span>Discount:</span>
                <span>-{{ formatPrice(currentOrder.discount_amount) }}</span>
              </div>
              <div class="total-row">
                <span>Total:</span>
                <span>{{ formatPrice(currentOrder.total_amount) }}</span>
              </div>
            </div>
          </div>
          
          <!-- Payment Information -->
          <div class="order-detail-section mt-6">
            <h3>Payment Information</h3>
            <div class="detail-row">
              <div class="detail-label">Payment Method:</div>
              <div class="detail-value">{{ currentOrder.payment_method || 'N/A' }}</div>
            </div>
            <div class="detail-row" v-if="currentOrder.transaction_id">
              <div class="detail-label">Transaction ID:</div>
              <div class="detail-value">{{ currentOrder.transaction_id }}</div>
            </div>
            <div class="detail-row">
              <div class="detail-label">Payment Status:</div>
              <div class="detail-value">
                <span :class="{'text-green-600': currentOrder.is_paid, 'text-red-600': !currentOrder.is_paid}">
                  {{ currentOrder.is_paid ? 'Paid' : 'Unpaid' }}
                </span>
              </div>
            </div>
          </div>
          
          <!-- Notes -->
          <div class="order-detail-section mt-6" v-if="currentOrder.notes">
            <h3>Notes</h3>
            <div class="p-3 bg-gray-50 rounded">
              {{ currentOrder.notes }}
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="printOrder" class="btn btn-secondary">
            <i class="fas fa-print mr-1"></i> Print
          </button>
          <div class="flex-grow"></div>
          <button 
            v-if="['pending', 'processing'].includes(currentOrder.status?.toLowerCase())" 
            @click="updateOrderStatus(currentOrder.id, 'shipped')" 
            class="btn btn-primary"
          >
            Mark as Shipped
          </button>
          <button 
            v-if="['shipped'].includes(currentOrder.status?.toLowerCase())" 
            @click="updateOrderStatus(currentOrder.id, 'delivered')" 
            class="btn btn-success"
          >
            Mark as Delivered
          </button>
          <button 
            v-if="!['cancelled', 'refunded'].includes(currentOrder.status?.toLowerCase())" 
            @click="updateOrderStatus(currentOrder.id, 'cancelled')" 
            class="btn btn-danger"
          >
            Cancel Order
          </button>
        </div>
      </div>
    </div>
    
    <!-- Batch Update Modal -->
    <div v-if="showBatchUpdateModal" class="modal-overlay" @click.self="showBatchUpdateModal = false">
      <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
          <h2 class="text-xl font-bold">Update {{ selectedOrders.length }} Orders</h2>
          <button @click="showBatchUpdateModal = false" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
            <select v-model="batchUpdateStatus" class="w-full rounded border-gray-300 shadow-sm">
              <option v-for="status in orderStatuses" :key="status" :value="status">
                {{ status.charAt(0).toUpperCase() + status.slice(1) }}
              </option>
            </select>
          </div>
          <div class="mb-4">
            <label class="flex items-center">
              <input type="checkbox" v-model="sendEmailsForBatch" class="mr-2">
              Send notification emails to customers
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="showBatchUpdateModal = false" class="btn btn-secondary">Cancel</button>
          <button @click="applyBatchUpdate" class="btn btn-primary">Update Orders</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import { ref, computed, onMounted, reactive, watch } from 'vue';

export default {
  name: 'Orders',
  setup() {
    const toast = {
      success: (message) => alert(message),
      error: (message) => alert('Error: ' + message),
      warning: (message) => alert('Warning: ' + message)
    };
    const loading = ref(true);
    const orders = ref([]);
    const selectedOrder = ref(null);
    const selectedOrders = ref([]);
    const orderStatusUpdate = ref('');
    const batchUpdateStatus = ref(false);
    const newStatus = ref('');
    const notifyCustomers = ref(true);
    
    const orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled', 'refunded'];
    
    const filters = reactive({
      status: '',
      dateFrom: '',
      dateTo: '',
      search: ''
    });
    
    const stats = reactive({
      total: 0,
      processing: 0,
      completed: 0,
      cancelled: 0
    });
    
    const showOrderDetails = ref(false);
    const currentOrder = ref({});
    const showBatchUpdateModal = ref(false);
    const sendEmailsForBatch = ref(false);
    
    const fetchOrders = async () => {
      loading.value = true;
      try {
        const response = await axios.get('/api/orders', { params: buildFilterParams() });
        orders.value = response.data.data;
        
        // Update statistics
        const statsResponse = await axios.get('/api/orders/statistics');
        if (statsResponse.data) {
          stats.total = statsResponse.data.total || 0;
          stats.processing = statsResponse.data.processing || 0;
          stats.completed = statsResponse.data.completed || 0;
          stats.cancelled = statsResponse.data.cancelled || 0;
        }
      } catch (error) {
        console.error('Error fetching orders:', error);
        toast.error('Failed to load orders. Please try again.');
      } finally {
        loading.value = false;
      }
    };
    
    const buildFilterParams = () => {
      const params = {};
      
      if (filters.status) params.status = filters.status;
      if (filters.dateFrom) params.date_from = filters.dateFrom;
      if (filters.dateTo) params.date_to = filters.dateTo;
      if (filters.search) params.search = filters.search;
      
      return params;
    };
    
    const applyFilters = () => {
      fetchOrders();
    };
    
    const resetFilters = () => {
      Object.keys(filters).forEach(key => {
        filters[key] = '';
      });
      fetchOrders();
    };
    
    const viewOrderDetails = (order) => {
      currentOrder.value = order;
      showOrderDetails.value = true;
    };
    
    const updateOrderStatus = async (orderId, status) => {
      try {
        const response = await axios.put(`/api/orders/${orderId}/status`, {
          status: status,
          send_notification: true
        });
        
        toast.success(`Order #${orderId} status updated to ${status}`);
        
        // Update local order status
        const orderIndex = orders.value.findIndex(o => o.id === orderId);
        if (orderIndex !== -1) {
          orders.value[orderIndex].status = status;
        }
        
        // If current order is open in details view, update it
        if (currentOrder.value.id === orderId) {
          currentOrder.value.status = status;
        }
        
        // Refresh statistics
        fetchOrders();
      } catch (error) {
        console.error('Error updating order status:', error);
        toast.error('Failed to update order status. Please try again.');
      }
    };
    
    const sendConfirmationEmail = async (orderId) => {
      try {
        await axios.post(`/api/orders/${orderId}/send-confirmation`);
        toast.success(`Confirmation email sent for order #${orderId}`);
      } catch (error) {
        console.error('Error sending confirmation email:', error);
        toast.error('Failed to send confirmation email. Please try again.');
      }
    };
    
    const toggleOrderSelection = (orderId) => {
      const index = selectedOrders.value.indexOf(orderId);
      if (index === -1) {
        selectedOrders.value.push(orderId);
      } else {
        selectedOrders.value.splice(index, 1);
      }
    };
    
    const toggleAllOrders = (event) => {
      if (event.target.checked) {
        selectedOrders.value = orders.value.map(order => order.id);
      } else {
        selectedOrders.value = [];
      }
    };
    
    const exportSelectedOrders = () => {
      if (selectedOrders.value.length === 0) {
        toast.warning('Please select at least one order to export');
        return;
      }
      
      const selectedOrderData = orders.value.filter(order => 
        selectedOrders.value.includes(order.id)
      );
      
      // Create CSV content
      let csvContent = 'data:text/csv;charset=utf-8,';
      csvContent += 'Order ID,Date,Customer,Email,Total,Status\n';
      
      selectedOrderData.forEach(order => {
        const row = [
          order.id,
          order.created_at,
          order.billing_name || order.user?.name || 'Guest',
          order.billing_email || order.user?.email || 'N/A',
          order.total_amount,
          order.status
        ].join(',');
        csvContent += row + '\n';
      });
      
      // Create download link
      const encodedUri = encodeURI(csvContent);
      const link = document.createElement('a');
      link.setAttribute('href', encodedUri);
      link.setAttribute('download', `orders-export-${new Date().toISOString().slice(0, 10)}.csv`);
      document.body.appendChild(link);
      
      // Trigger download and clean up
      link.click();
      document.body.removeChild(link);
    };
    
    const applyBatchUpdate = async () => {
      if (selectedOrders.value.length === 0) {
        toast.warning('Please select at least one order to update');
        return;
      }
      
      try {
        await axios.post('/api/orders/batch-update', {
          order_ids: selectedOrders.value,
          status: batchUpdateStatus.value,
          send_notification: sendEmailsForBatch.value
        });
        
        toast.success(`${selectedOrders.value.length} orders updated to ${batchUpdateStatus.value}`);
        showBatchUpdateModal.value = false;
        fetchOrders();
      } catch (error) {
        console.error('Error batch updating orders:', error);
        toast.error('Failed to update orders. Please try again.');
      }
    };
    
    const calculateSubtotal = () => {
      if (!currentOrder.value.order_items) return 0;
      
      return currentOrder.value.order_items.reduce((total, item) => {
        return total + (item.price * item.quantity);
      }, 0);
    };
    
    const printOrder = () => {
      window.print();
    };
    
    const formatDate = (dateString) => {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    };
    
    const formatPrice = (price) => {
      if (price === undefined || price === null) return '$0.00';
      return '$' + parseFloat(price).toFixed(2);
    };
    
    onMounted(() => {
      fetchOrders();
    });
    
    return {
      loading,
      orders,
      selectedOrder,
      selectedOrders,
      orderStatusUpdate,
      batchUpdateStatus,
      newStatus,
      notifyCustomers,
      orderStatuses,
      filters,
      stats,
      showOrderDetails,
      currentOrder,
      showBatchUpdateModal,
      sendEmailsForBatch,
      fetchOrders,
      applyFilters,
      resetFilters,
      viewOrderDetails,
      updateOrderStatus,
      sendConfirmationEmail,
      toggleOrderSelection,
      toggleAllOrders,
      exportSelectedOrders,
      applyBatchUpdate,
      calculateSubtotal,
      printOrder,
      formatDate,
      formatPrice
    };
  }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.status-badge {
  @apply px-3 py-1 rounded-full text-xs font-medium;
}

.dashboard-card {
  @apply flex items-center p-6 bg-white rounded-lg shadow-md border border-gray-100 transition-all duration-300;
}

.dashboard-card:hover {
  @apply shadow-lg border-gray-300;
  transform: translateY(-2px);
}

.dashboard-icon {
  @apply p-3 rounded-lg mr-4;
}

.dashboard-value {
  @apply text-2xl font-bold text-gray-900;
}

.dashboard-label {
  @apply text-sm text-gray-500;
}

.action-button {
  @apply px-3 py-1.5 rounded text-sm font-medium transition-colors duration-200;
}

.action-button.primary {
  @apply bg-blue-600 text-white hover:bg-blue-700;
}

.action-button.secondary {
  @apply bg-gray-100 text-gray-800 hover:bg-gray-200;
}

.action-button.danger {
  @apply bg-red-600 text-white hover:bg-red-700;
}

.modal-container {
  @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50;
}

.modal-content {
  @apply bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto;
}

.modal-header {
  @apply p-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10;
}

.modal-body {
  @apply p-6;
}

.modal-footer {
  @apply p-4 border-t border-gray-200 flex justify-end sticky bottom-0 bg-white z-10;
}

.order-table {
  @apply min-w-full divide-y divide-gray-200;
}

.order-table-header {
  @apply bg-gray-50 sticky top-0 z-10;
}

.order-table-head {
  @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
}

.order-table-row {
  @apply hover:bg-gray-50 transition-colors duration-150;
}

.order-table-row.selected {
  @apply bg-blue-50;
}

.order-table-cell {
  @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
}

.filter-section {
  @apply bg-white p-4 rounded-lg shadow mb-6 border border-gray-100;
}

.filter-dropdown {
  @apply block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md;
}

.search-input {
  @apply block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.loading-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@media (max-width: 768px) {
  .dashboard-grid {
    @apply grid-cols-1 gap-3;
  }
  
  .filters-grid {
    @apply grid-cols-1 gap-3;
  }
  
  .order-table-head {
    @apply px-3 py-2;
  }
  
  .order-table-cell {
    @apply px-3 py-2;
  }
}

.stats-card {
  @apply bg-white rounded-lg shadow-md p-4 flex flex-col text-center transition-all duration-300;
}

.stats-card:hover {
  @apply shadow-lg transform -translate-y-1;
}

.stats-card .number {
  @apply text-2xl font-bold mb-1;
}

.stats-card .label {
  @apply text-gray-600 text-sm;
}

.stats-card.total .number {
  @apply text-blue-600;
}

.stats-card.completed .number {
  @apply text-green-600;
}

.stats-card.processing .number {
  @apply text-yellow-600;
}

.stats-card.cancelled .number {
  @apply text-red-600;
}

.filter-section {
  @apply bg-white rounded-lg shadow-md p-4 mb-4;
}

.status-badge {
  @apply text-xs font-medium px-2.5 py-0.5 rounded-full;
}

.status-badge.pending {
  @apply bg-yellow-100 text-yellow-800;
}

.status-badge.processing {
  @apply bg-blue-100 text-blue-800;
}

.status-badge.shipped {
  @apply bg-purple-100 text-purple-800;
}

.status-badge.delivered {
  @apply bg-green-100 text-green-800;
}

.status-badge.completed {
  @apply bg-green-100 text-green-800;
}

.status-badge.cancelled {
  @apply bg-red-100 text-red-800;
}

.status-badge.refunded {
  @apply bg-gray-100 text-gray-800;
}

.order-table {
  @apply min-w-full divide-y divide-gray-200;
}

.order-table th {
  @apply px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
}

.order-table td {
  @apply px-4 py-3 whitespace-nowrap text-sm;
}

.order-table tr {
  @apply bg-white;
}

.order-table tr:nth-child(odd) {
  @apply bg-gray-50;
}

.order-table tr:hover {
  @apply bg-gray-100;
}

.action-button {
  @apply p-1 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
}

.modal-backdrop {
  @apply fixed inset-0 bg-black bg-opacity-50 transition-opacity;
}

.modal-content {
  @apply fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto;
}

.modal-header {
  @apply sticky top-0 bg-white p-4 border-b border-gray-200 flex justify-between items-center;
}

.modal-body {
  @apply p-4;
}

.modal-footer {
  @apply sticky bottom-0 bg-white p-4 border-t border-gray-200 flex justify-end;
}

.address-box {
  @apply border border-gray-200 rounded-lg p-3 text-sm;
}

.detail-section {
  @apply bg-white rounded-lg shadow-sm p-4 mb-4;
}

.detail-section h3 {
  @apply text-lg font-medium mb-2 text-gray-800;
}

.item-row {
  @apply border-b border-gray-200 py-2 flex justify-between;
}

.item-row:last-child {
  @apply border-b-0;
}

.order-summary {
  @apply bg-gray-50 rounded-lg p-4;
}

.summary-row {
  @apply flex justify-between py-1 text-sm;
}

.summary-row.total {
  @apply border-t border-gray-300 mt-2 pt-2 font-bold text-base;
}

.batch-actions {
  @apply flex gap-2 items-center;
}

.search-input {
  @apply border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50;
}

.empty-state {
  @apply flex flex-col items-center justify-center py-12 text-center text-gray-500;
}

.empty-state svg {
  @apply w-16 h-16 mb-4 text-gray-400;
}

.empty-state h3 {
  @apply text-lg font-medium text-gray-900 mb-1;
}

.empty-state p {
  @apply text-sm text-gray-500 max-w-md;
}

@media print {
  body * {
    visibility: hidden;
  }
  
  .modal-content, .modal-content * {
    visibility: visible;
  }
  
  .modal-content {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    transform: none;
  }
  
  .modal-header button, .modal-footer, .no-print {
    display: none !important;
  }
}

.order-dashboard {
  @apply bg-white rounded-lg shadow-md p-6;
}

.stats-card {
  @apply bg-white rounded-lg shadow p-4 flex flex-col items-center justify-center;
  min-height: 100px;
}

.stats-card.total {
  @apply bg-blue-50 border-l-4 border-blue-500;
}

.stats-card.processing {
  @apply bg-yellow-50 border-l-4 border-yellow-500;
}

.stats-card.completed {
  @apply bg-green-50 border-l-4 border-green-500;
}

.stats-card.cancelled {
  @apply bg-red-50 border-l-4 border-red-500;
}

.stats-number {
  @apply font-bold text-3xl mb-1;
}

.stats-label {
  @apply text-gray-600 text-sm;
}

.filter-section {
  @apply bg-gray-50 rounded-lg p-4 mb-6;
}

.order-table {
  @apply w-full border-collapse;
}

.order-table th {
  @apply bg-gray-50 px-4 py-2 text-left font-medium text-gray-600 border-b;
}

.order-table td {
  @apply px-4 py-3 border-b border-gray-200;
}

.table-actions {
  @apply flex space-x-2;
}

.status-badge {
  @apply px-2 py-1 rounded-full text-xs font-medium text-white;
}

.status-badge.pending {
  @apply bg-gray-500;
}

.status-badge.processing {
  @apply bg-yellow-500;
}

.status-badge.shipped {
  @apply bg-blue-500;
}

.status-badge.delivered {
  @apply bg-green-500;
}

.status-badge.cancelled {
  @apply bg-red-500;
}

.status-badge.refunded {
  @apply bg-purple-500;
}

.btn {
  @apply px-4 py-2 rounded font-medium transition-colors;
}

.btn-primary {
  @apply bg-blue-600 text-white hover:bg-blue-700;
}

.btn-secondary {
  @apply bg-gray-200 text-gray-800 hover:bg-gray-300;
}

.btn-success {
  @apply bg-green-600 text-white hover:bg-green-700;
}

.btn-danger {
  @apply bg-red-600 text-white hover:bg-red-700;
}

.btn-sm {
  @apply px-2 py-1 text-sm;
}

.modal-overlay {
  @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50;
}

.modal {
  @apply bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto;
}

.modal-header {
  @apply p-4 border-b flex justify-between items-center sticky top-0 bg-white z-10;
}

.modal-body {
  @apply p-6;
}

.modal-footer {
  @apply p-4 border-t flex justify-end space-x-3 sticky bottom-0 bg-white z-10;
}

.order-detail-section {
  @apply mb-6;
}

.order-detail-section h3 {
  @apply text-lg font-medium mb-3 pb-2 border-b;
}

.detail-grid {
  @apply grid grid-cols-2 gap-4;
}

.detail-row {
  @apply flex items-start mb-2;
}

.detail-label {
  @apply font-medium text-gray-600 w-1/3;
}

.detail-value {
  @apply text-gray-900 w-2/3;
}

.items-table {
  @apply w-full border-collapse mb-4;
}

.items-table th {
  @apply bg-gray-50 px-4 py-2 text-left font-medium text-gray-600 border-b;
}

.items-table td {
  @apply px-4 py-2 border-b border-gray-200;
}

.summary-row {
  @apply flex justify-between py-1;
}

.total-row {
  @apply flex justify-between py-2 font-bold text-lg border-t mt-2;
}

.order-print-section {
  display: none;
}

@media print {
  .order-print-section {
    display: block;
  }
  
  .no-print {
    display: none;
  }
}

.checkbox-wrapper {
  @apply relative inline-flex items-center;
}

.checkbox-input {
  @apply w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500;
}

.loading-spinner {
  @apply flex justify-center items-center p-8;
}

.empty-state {
  @apply flex flex-col items-center justify-center py-12 px-4 text-center;
}

.empty-state-icon {
  @apply text-gray-400 mb-4 text-5xl;
}

.empty-state-text {
  @apply text-gray-600 mb-6;
}

.orders-container {
  @apply w-full bg-white shadow-md rounded-lg overflow-x-auto;
}

.status-badge {
  @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.status-pending {
  @apply bg-gray-200 text-gray-800;
}

.status-processing {
  @apply bg-blue-100 text-blue-800;
}

.status-shipped {
  @apply bg-purple-100 text-purple-800;
}

.status-delivered {
  @apply bg-green-100 text-green-800;
}

.status-completed {
  @apply bg-green-200 text-green-800;
}

.status-cancelled {
  @apply bg-red-100 text-red-800;
}

.status-refunded {
  @apply bg-yellow-100 text-yellow-800;
}

.stats-card {
  @apply bg-white shadow-md rounded-lg p-4 flex flex-col;
}

.stats-card-title {
  @apply text-gray-500 text-sm font-medium mb-2;
}

.stats-card-value {
  @apply text-2xl font-bold;
}

.filters-section {
  @apply p-4 bg-gray-50 rounded-lg shadow-sm mb-6;
}

.order-row {
  @apply hover:bg-gray-50 transition-colors;
}

.order-row td {
  @apply border-b border-gray-200 p-3;
}

.order-detail-item {
  @apply py-2 flex justify-between border-b border-gray-200;
}

.order-detail-item:last-child {
  @apply border-b-0;
}

.order-items-table {
  @apply w-full mt-4;
}

.order-items-table th {
  @apply text-left py-2 text-xs text-gray-500 uppercase;
}

.action-button {
  @apply inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
}

.action-button-primary {
  @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700;
}

.action-button-secondary {
  @apply bg-gray-100 text-gray-800 border-gray-300 hover:bg-gray-200;
}

.action-button-success {
  @apply bg-green-600 text-white border-green-600 hover:bg-green-700;
}

.action-button-danger {
  @apply bg-red-600 text-white border-red-600 hover:bg-red-700;
}

.modal-backdrop {
  @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50;
}

.modal-content {
  @apply relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto;
}

.modal-header {
  @apply bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10;
}

.modal-body {
  @apply p-6;
}

.modal-footer {
  @apply bg-gray-50 px-6 py-3 border-t border-gray-200 flex justify-end gap-2 sticky bottom-0 z-10;
}

@media print {
  .no-print {
    display: none !important;
  }
  
  body * {
    visibility: hidden;
  }
  
  .modal-content, .modal-content * {
    visibility: visible;
  }
  
  .modal-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
  }
}
</style> 