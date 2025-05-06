// Test script to clear the logo cache and force a fresh fetch
// Run this in your browser console when on the SPA site

console.log('Clearing cached logo from localStorage...');
localStorage.removeItem('cached_logo');
console.log('✅ Logo cache cleared!');

console.log('To refresh the logo in Header:');
console.log('1. If you have direct access to the Vue component:');
console.log('   await this.$refs.header.fetchLogo()');
console.log('   -- OR -- ');
console.log('2. Simply refresh the page to fetch a fresh logo');

// Show current route
console.log('Current route:', window.location.pathname);

// Check if Header component is available
console.log('Checking for Header component...');
if (window.app && window.app.$refs && window.app.$refs.header) {
  console.log('✅ Header component found! Attempting to refresh logo...');
  try {
    window.app.$refs.header.fetchLogo()
      .then(() => console.log('✅ Logo refreshed successfully!'))
      .catch(err => console.error('Error refreshing logo:', err));
  } catch (e) {
    console.error('Error refreshing logo:', e);
  }
} else {
  console.log('❌ Header component not directly accessible.');
  console.log('Please manually refresh the page to see the updated logo.');
}

// Print the URL to monitor in Network tab
console.log('Monitor this URL in the Network tab:');
console.log('http://localhost:8000/api/settings/logo');

console.log('After refresh, the image URL should be:');
console.log('http://localhost:8000/storage/settings/[your-new-filename]?t=[timestamp]'); 