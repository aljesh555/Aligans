<template>
  <div class="container mx-auto px-4 py-8">
    <div v-if="loading" class="text-center py-10">
      <p>Loading event details...</p>
    </div>
    
    <div v-else-if="!event" class="text-center py-10">
      <p>Event not found.</p>
      <router-link to="/events" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">
        Back to Events
      </router-link>
    </div>
    
    <div v-else>
      <h1 class="text-3xl font-bold mb-4">{{ event.name }}</h1>
      
      <div class="flex flex-wrap gap-4 mb-6">
        <div class="flex items-center text-gray-700">
          <span class="font-semibold mr-2">Date:</span> {{ event.date }}
        </div>
        <div class="flex items-center text-gray-700">
          <span class="font-semibold mr-2">Location:</span> {{ event.location }}
        </div>
      </div>
      
      <div class="bg-gray-100 p-6 rounded-lg mb-6">
        <p class="text-gray-800">{{ event.description }}</p>
      </div>
      
      <div class="flex flex-wrap gap-4">
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
          Register
        </button>
        <router-link to="/events" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded">
          Back to Events
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'EventDetailView',
  props: {
    id: {
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      loading: true,
      event: null
    }
  },
  mounted() {
    this.fetchEventDetails();
  },
  methods: {
    async fetchEventDetails() {
      try {
        // We don't have a specific endpoint for single events in the StorefrontController,
        // but we can use the regular events endpoint for demonstration purposes
        const response = await axios.get(`/api/events/${this.id}`);
        this.event = response.data.data || null;
        this.loading = false;
      } catch (error) {
        console.error('Error fetching event details:', error);
        this.loading = false;
      }
    }
  }
}
</script> 