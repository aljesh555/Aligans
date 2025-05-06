/**
 * Logo Transparency Diagnostic Tool
 * Run this in your browser console when viewing the SPA
 */

console.log('🔍 LOGO TRANSPARENCY DIAGNOSTIC TOOL');
console.log('===================================');

// Clear cached logo first
console.log('Step 1: Clearing cached logo data...');
localStorage.removeItem('cached_logo');
console.log('✅ Logo cache cleared!');

// Find all logo images
console.log('\nStep 2: Analyzing logo images on page...');
const logoImages = document.querySelectorAll('.logo-image, .logo-wrapper img');
console.log(`Found ${logoImages.length} logo images on page`);

// Apply transparency fixes 
logoImages.forEach((img, index) => {
  console.log(`\nLogo #${index + 1}:`);
  console.log('- URL:', img.src);
  console.log('- Size:', img.width + 'x' + img.height + 'px');
  console.log('- Classes:', img.className);
  
  // Apply transparency fixes
  console.log('- Applying transparency fixes...');
  img.style.backgroundColor = 'transparent';
  img.style.backgroundImage = 'none';
  img.style.boxShadow = 'none';
  img.style.border = 'none';
  
  // Add transparent class
  img.classList.add('transparent-img');
  
  // Add debug border to see the image area
  img.style.outline = '1px dashed red';
  
  // Check parent for background
  const parent = img.parentElement;
  if (parent) {
    console.log('- Parent element:', parent.tagName);
    console.log('- Parent background:', getComputedStyle(parent).backgroundColor);
    
    // Fix parent too
    parent.style.backgroundColor = 'transparent';
    parent.style.backgroundImage = 'none';
    parent.classList.add('transparent-container');
  }
});

// Force refresh
console.log('\nStep 3: Forcing image refresh...');
logoImages.forEach(img => {
  // Add cache-busting query param
  const originalSrc = img.src.split('?')[0];
  img.src = originalSrc + '?t=' + new Date().getTime();
});

console.log('\nStep 4: Check if logo component is available');
if (window.app && window.app.$refs && window.app.$refs.header) {
  console.log('✅ Header component found! Refreshing logo...');
  try {
    window.app.$refs.header.fetchLogo()
      .then(() => console.log('✅ Logo refreshed successfully!'))
      .catch(err => console.error('Error refreshing logo:', err));
  } catch (e) {
    console.error('Error refreshing logo:', e);
  }
} else {
  console.log('❌ Header component not directly accessible.');
}

console.log('\n🔧 TRANSPARENCY INSPECTION TOOLS:');
console.log('-----------------------------');
console.log('1. To inspect the image more carefully, run:');
console.log('   document.querySelector(".logo-image").style.outline = "2px solid red";');
console.log('2. To check for unwanted background colors:');
console.log('   getComputedStyle(document.querySelector(".logo-image")).backgroundColor');
console.log('3. To force complete transparency on all logo elements:');
console.log('   document.querySelectorAll(".logo-wrapper, .logo-image, .logo-container").forEach(el => {');
console.log('     el.style.backgroundColor = "transparent";');
console.log('     el.style.backgroundImage = "none";');
console.log('   });');

console.log('\n✅ Diagnostic complete! Your logo should now display with proper transparency.');
console.log('For best results, please refresh the page.'); 