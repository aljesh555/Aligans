<template>
  <div class="logo-wrapper" :class="{ 'transparent': transparentBg }">
    <img 
      :src="logoSrc" 
      :alt="alt" 
      class="logo-image"
      @error="handleError"
      @load="handleLoad"
    />
  </div>
</template>

<script>
export default {
  name: 'Logo',
  props: {
    src: {
      type: String,
      required: true
    },
    alt: {
      type: String,
      default: 'Logo'
    },
    transparentBg: {
      type: Boolean,
      default: true
    },
    height: {
      type: [String, Number],
      default: '80px'
    }
  },
  data() {
    return {
      imageLoaded: false,
      hasError: false
    }
  },
  computed: {
    logoSrc() {
      // Add cache-busting timestamp
      return this.src + (this.src.includes('?') ? '&' : '?') + 't=' + new Date().getTime();
    }
  },
  methods: {
    handleError(e) {
      console.error('Error loading logo image:', e);
      this.hasError = true;
      this.$emit('error', e);
    },
    handleLoad() {
      this.imageLoaded = true;
      this.hasError = false;
      this.$emit('load');
    }
  }
}
</script>

<style scoped>
.logo-wrapper {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  margin: 0;
  overflow: hidden;
  background-color: transparent;
}

.logo-wrapper.transparent {
  /* Ensures truly transparent background */
  background-color: transparent !important;
  /* Remove any potential box shadow */
  box-shadow: none !important;
}

.logo-image {
  height: v-bind(height);
  width: auto;
  max-width: 100%;
  object-fit: contain;
  /* Remove any default background settings */
  background-color: transparent !important;
  /* Fix for potential image rendering issues on some browsers */
  image-rendering: -webkit-optimize-contrast;
  image-rendering: crisp-edges;
  /* Ensure the PNG transparency is preserved */
  mix-blend-mode: normal;
}
</style> 