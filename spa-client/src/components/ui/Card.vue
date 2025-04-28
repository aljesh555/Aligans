<template>
  <div 
    class="rounded-lg overflow-hidden"
    :class="[
      shadowClass,
      paddingClass,
      { 'hover:shadow-lg transition-shadow': hover }
    ]"
  >
    <div v-if="$slots.image" class="w-full">
      <slot name="image"></slot>
    </div>
    
    <div :class="paddingClass">
      <div v-if="$slots.header" class="mb-4">
        <slot name="header"></slot>
      </div>
      
      <div v-if="$slots.title" class="mb-2">
        <slot name="title"></slot>
      </div>
      
      <div v-if="$slots.default">
        <slot></slot>
      </div>
      
      <div v-if="$slots.footer" class="mt-4">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    shadow: {
      type: String,
      default: 'md',
      validator: (value) => ['none', 'sm', 'md', 'lg'].includes(value)
    },
    padding: {
      type: String,
      default: 'md',
      validator: (value) => ['none', 'sm', 'md', 'lg'].includes(value)
    },
    hover: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    shadowClass() {
      const shadows = {
        'none': '',
        'sm': 'shadow-sm',
        'md': 'shadow',
        'lg': 'shadow-lg'
      };
      return shadows[this.shadow];
    },
    paddingClass() {
      const paddings = {
        'none': 'p-0',
        'sm': 'p-2',
        'md': 'p-4',
        'lg': 'p-6'
      };
      return paddings[this.padding];
    }
  }
}
</script> 