<template>
  <nav class="bg-blue-600 text-white shadow-lg">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center py-3">
        <div class="flex items-center">
          <router-link :to="{ name: 'Home' }" class="text-xl font-bold">
            Sports Management
          </router-link>
        </div>
        <div class="hidden md:flex space-x-6">
          <router-link :to="{ name: 'Home' }" class="hover:text-blue-200">Home</router-link>
          <router-link :to="{ name: 'Products' }" class="hover:text-blue-200">Products</router-link>
          <router-link :to="{ name: 'Events' }" class="hover:text-blue-200">Events</router-link>
          <router-link :to="{ name: 'Teams' }" class="hover:text-blue-200">Teams</router-link>
          <div v-if="isAdmin || isManager" class="relative group">
            <button class="hover:text-blue-200">Management</button>
            <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden group-hover:block">
              <div class="py-1">
                <router-link :to="{ name: 'Users' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Users</router-link>
                <router-link :to="{ name: 'Attendance' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Attendance</router-link>
                <router-link :to="{ name: 'Salaries' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Salaries</router-link>
                <router-link :to="{ name: 'OrdersManagement' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Orders</router-link>
              </div>
            </div>
          </div>
          <router-link :to="{ name: 'Cart' }" class="hover:text-blue-200">Cart</router-link>
          <router-link :to="{ name: 'Orders' }" class="hover:text-blue-200">My Orders</router-link>
          <router-link :to="{ name: 'Profile' }" class="hover:text-blue-200">Profile</router-link>
        </div>
        <div>
          <button @click="logout" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded text-sm">
            Logout
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import { computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
  name: 'Navbar',
  setup() {
    const store = useStore()
    const router = useRouter()

    const user = computed(() => store.getters.user)
    const isAdmin = computed(() => user.value?.role === 'admin')
    const isManager = computed(() => user.value?.role === 'manager')

    const logout = async () => {
      await store.dispatch('logout')
      router.push({ name: 'Login' })
    }

    return {
      isAdmin,
      isManager,
      logout
    }
  }
}
</script> 