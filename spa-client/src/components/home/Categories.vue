<template>
  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Shop By Category</h2>
        <p class="text-gray-600 max-w-xl mx-auto">Explore our wide range of product categories designed to meet all your sporting needs</p>
      </div>
      
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div
          v-for="(category, index) in categories"
          :key="index"
          class="category-card group relative rounded-lg overflow-hidden bg-white shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
        >
          <router-link :to="`/category/${category.id}`">
            <div class="relative h-48 overflow-hidden">
              <img
                :src="category.image"
                :alt="category.name"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-70"></div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
              <h3 class="text-lg font-semibold">{{ category.name }}</h3>
              <p class="text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                {{ category.itemCount }} Products
              </p>
            </div>
          </router-link>
        </div>
      </div>
      
      <div class="mt-10 text-center">
        <router-link
          to="/categories"
          class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-300"
        >
          View All Categories
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'Categories',
  data() {
    return {
      categories: [
        {
          name: 'Jerseys',
          slug: 'jerseys',
          image: 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8Zm9vdGJhbGwlMjBqZXJzZXl8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60',
          itemCount: 42
        },
        {
          name: 'Footwear',
          slug: 'footwear',
          image: 'https://images.unsplash.com/photo-1543508282-6319a3e2621f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NXx8Zm9vdGJhbGwlMjBib290c3xlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60',
          itemCount: 36
        },
        {
          name: 'Equipment',
          slug: 'equipment',
          image: 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8Zm9vdGJhbGx8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60',
          itemCount: 28
        },
        {
          name: 'Accessories',
          slug: 'accessories',
          image: 'https://images.unsplash.com/photo-1585670149915-6d87f9de751d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OXx8c3BvcnRzJTIwYWNjZXNzb3JpZXN8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60',
          itemCount: 53
        },
        {
          name: 'Training Gear',
          slug: 'training-gear',
          image: 'https://images.unsplash.com/photo-1518214598173-1666bc921d66?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8c3BvcnRzJTIwdHJhaW5pbmd8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60',
          itemCount: 39
        },
        {
          name: 'Fan Merchandise',
          slug: 'fan-merchandise',
          image: 'https://images.unsplash.com/photo-1590075865003-e48b56637fbe?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8c3BvcnRzJTIwbWVyY2hhbmRpc2V8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60',
          itemCount: 67
        },
        {
          name: 'Apparel',
          slug: 'apparel',
          image: 'https://images.unsplash.com/photo-1515186913869-f40e496eecd4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NXx8c3BvcnRzJTIwYXBwYXJlbHxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60',
          itemCount: 48
        },
        {
          name: 'Sale Items',
          slug: 'sale-items',
          image: 'https://images.unsplash.com/photo-1561414927-6d86591d0c4f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8ZGlzY291bnR8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60',
          itemCount: 25
        }
      ]
    };
  }
};
</script>

<style scoped>
.category-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 50%);
  z-index: 1;
  opacity: 0.7;
  transition: opacity 0.3s ease;
}

.category-card:hover::after {
  opacity: 0.9;
}

.category-card h3, .category-card p {
  position: relative;
  z-index: 2;
}
</style> 