<!-- Add import for ProductTabs -->
import ProductTabs from '@/components/product/ProductTabs.vue';

// Add ProductTabs to components
components: {
  // ... existing components
  ProductTabs,
},

<!-- Add ProductTabs component to the template, after the product details section -->
<ProductTabs :productId="product.id" />

<!-- You can place this right before the related products section --> 

<script>
import ProductTabs from '@/components/product/ProductTabs.vue';

export default {
  components: {
    // ... existing components
    ProductTabs,
  },
  // ... rest of component code
}
</script>

<!-- Add this where you want the tabs to appear, typically after the product basic info section -->
<ProductTabs :productId="product.id" /> 