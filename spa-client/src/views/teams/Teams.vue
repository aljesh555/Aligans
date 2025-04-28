<template>
  <div>
    <Header />
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6">Our Teams</h1>
      
      <div v-if="loading" class="text-center py-10">
        <p>Loading teams...</p>
      </div>
      
      <div v-else-if="!teams.length" class="text-center py-10">
        <p>No teams found.</p>
      </div>
      
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="team in teams" :key="team.id" class="border rounded-lg p-4 shadow">
          <h2 class="text-xl font-medium mb-2">{{ team.name }}</h2>
          <p class="text-gray-600 mb-4">{{ team.description }}</p>
          <router-link :to="`/teams/${team.id}`" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">
            View Team
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
  name: 'TeamsView',
  data() {
    return {
      loading: true,
      teams: [],
      mockTeams: [
        {
          id: 1,
          name: "Wildcats Basketball",
          description: "Professional basketball team with a strong defensive lineup",
          founded_year: 2005,
          logo_url: null
        },
        {
          id: 2,
          name: "Eagles Soccer Club",
          description: "Premier league soccer team known for fast-paced attacks",
          founded_year: 1998,
          logo_url: null
        },
        {
          id: 3,
          name: "Titans Baseball",
          description: "Championship baseball team with excellent pitching rotation",
          founded_year: 2010,
          logo_url: null
        }
      ]
    }
  },
  components: {
    Header,
    Footer
  },
  mounted() {
    this.fetchTeams();
  },
  methods: {
    async fetchTeams() {
      try {
        const response = await axios.get('/api/storefront/teams');
        this.teams = response.data.teams || [];
        this.loading = false;
      } catch (error) {
        console.error('Error fetching teams, using mock data:', error);
        this.teams = this.mockTeams;
        this.loading = false;
      }
    }
  }
}
</script> 