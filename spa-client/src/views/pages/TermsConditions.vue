<template>
  <div class="page-container">
    <div class="container py-5">
      <TermsConditions />
    </div>
  </div>
</template>

<script>
import TermsConditions from '@/components/TermsConditions.vue';

export default {
  name: 'TermsConditionsPage',
  components: {
    TermsConditions
  },
  metaInfo() {
    return {
      title: 'Terms & Conditions'
    };
  }
};
</script> 