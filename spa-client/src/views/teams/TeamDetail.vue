<template>
  <div class="container mx-auto px-4 py-8">
    <div v-if="loading" class="text-center py-10">
      <p>Loading team details...</p>
    </div>
    
    <div v-else-if="!team" class="text-center py-10">
      <p>Team not found.</p>
      <router-link to="/teams" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">
        Back to Teams
      </router-link>
    </div>
    
    <div v-else>
      <h1 class="text-3xl font-bold mb-4">{{ team.name }}</h1>
      <p class="text-gray-600 mb-6">{{ team.description }}</p>
      
      <h2 class="text-2xl font-semibold mb-4">Players</h2>
      <div v-if="!team.players || !team.players.length" class="text-gray-600 mb-6">
        No players in this team.
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div v-for="player in team.players" :key="player.id" class="border rounded-lg p-4 shadow">
          <h3 class="text-xl font-medium mb-2">{{ player.name }}</h3>
          <p class="text-gray-600">Position: {{ player.position }}</p>
          <p class="text-gray-600">Jersey Number: {{ player.jersey_number }}</p>
        </div>
      </div>
      
      <router-link to="/teams" class="inline-block bg-blue-500 text-white px-4 py-2 rounded">
        Back to Teams
      </router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'TeamDetailView',
  props: {
    id: {
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      loading: true,
      team: null
    }
  },
  mounted() {
    this.fetchTeamDetails();
  },
  methods: {
    async fetchTeamDetails() {
      try {
        const response = await axios.get(`/api/storefront/teams/${this.id}`);
        this.team = response.data.team || null;
        this.loading = false;
      } catch (error) {
        console.error('Error fetching team details:', error);
        this.loading = false;
      }
    }
  }
}
</script> 