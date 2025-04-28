<template>
  <div class="min-h-screen flex flex-col">
    <Header />
    
    <main class="flex-grow">
      <slot></slot>
    </main>
    
    <Footer />
  </div>
</template>

<script>
import Header from './Header.vue'
import Footer from './Footer.vue'

export default {
  name: 'MainLayout',
  components: {
    Header,
    Footer
  }
}
</script> 