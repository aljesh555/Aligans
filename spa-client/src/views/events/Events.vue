<template>
  <div>
    <Header />
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6">Upcoming Events</h1>
      
      <div v-if="loading" class="text-center py-10">
        <p>Loading events...</p>
      </div>
      
      <div v-else-if="!events.length" class="text-center py-10">
        <p>No upcoming events found.</p>
      </div>
      
      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="event in events" :key="event.id" class="border rounded-lg p-4 shadow">
          <h2 class="text-xl font-medium mb-2">{{ event.name }}</h2>
          <p class="text-gray-600 mb-2">{{ event.date }} | {{ event.location }}</p>
          <p class="mb-4">{{ event.description }}</p>
          <router-link :to="`/events/${event.id}`" class="inline-block bg-blue-500 text-white px-4 py-2 rounded">
            View Details
          </router-link>
        </div>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script>
import axios from 'axios';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'EventsView',
  data() {
    return {
      loading: true,
      events: []
    }
  },
  components: {
    Header,
    Footer
  },
  mounted() {
    this.fetchEvents();
  },
  methods: {
    async fetchEvents() {
      try {
        const response = await axios.get('/api/storefront/events');
        this.events = response.data.events || [];
        this.loading = false;
      } catch (error) {
        console.error('Error fetching events:', error);
        this.loading = false;
      }
    }
  }
}
</script> 