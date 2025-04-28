<template>
  <footer class="bg-gray-900 text-gray-300">
    <!-- Main Footer Content -->
    <div class="container mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
        <!-- Company Info -->
        <div class="lg:col-span-2">
          <div class="flex items-center gap-2 mb-6">
            <!-- Dynamic Logo from API -->
            <div v-if="logoData && logoData.base64_image" class="flex items-center">
              <img 
                :src="logoData.base64_image" 
                alt="Aligans Logo" 
                class="h-10 w-auto" 
                @error="handleLogoError"
              />
            </div>
            <div v-else-if="logoData && logoData.image_path" class="flex items-center">
              <img 
                :src="getLogoImageUrl(logoData)" 
                alt="Aligans Logo" 
                class="h-10 w-auto" 
                @error="handleLogoError"
              />
            </div>
            <!-- Fallback text logo - only show if loading is complete and no logo found -->
            <div v-else-if="!logoLoading && !logoData">
              <span class="flex items-center justify-center h-10 w-10 bg-blue-700 text-white text-xl font-bold rounded-md">A</span>
            </div>
            <span class="text-2xl font-bold text-white">Aligans</span>
          </div>
          
          <!-- Display footer message from database -->
          <div class="mb-6">
            <!-- Loading state -->
            <div v-if="messageLoading" class="text-gray-400 max-w-md">
              <div class="h-4 bg-gray-700 rounded animate-pulse w-3/4 mb-2"></div>
              <div class="h-4 bg-gray-700 rounded animate-pulse w-1/2"></div>
            </div>
            
            <!-- Message when loaded -->
            <p v-else-if="footerMessage" class="text-gray-400 max-w-md whitespace-pre-line">
              {{ footerMessage }}
            </p>
          </div>

          <!-- Dynamic Social Media Links -->
          <div class="flex space-x-4 mb-8">
            <!-- Loading state for social links -->
            <div v-if="socialMediaLoading" class="flex space-x-3">
              <div v-for="i in 3" :key="i" class="h-6 w-6 bg-gray-700 rounded-full animate-pulse"></div>
            </div>
            
            <!-- Dynamic social links -->
            <template v-else>
              <!-- Facebook -->
              <a v-if="socialLinks.facebook" :href="socialLinks.facebook" target="_blank" rel="noopener noreferrer" 
                 class="text-gray-400 hover:text-white transition" aria-label="Facebook">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                </svg>
              </a>
              
              <!-- Instagram -->
              <a v-if="socialLinks.instagram" :href="socialLinks.instagram" target="_blank" rel="noopener noreferrer" 
                 class="text-gray-400 hover:text-white transition" aria-label="Instagram">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 0 1 1.772 1.153 4.902 4.902 0 0 1 1.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 0 1-1.153 1.772 4.902 4.902 0 0 1-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 0 1-1.772-1.153 4.902 4.902 0 0 1-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 0 1 1.153-1.772A4.902 4.902 0 0 1 5.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 0 0-.748-1.15 3.098 3.098 0 0 0-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27zm0 1.802a3.333 3.333 0 1 0 0 6.666 3.333 3.333 0 0 0 0-6.666zm5.338-3.205a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4z" clip-rule="evenodd"></path>
                </svg>
              </a>
              
              <!-- Twitter -->
              <a v-if="socialLinks.twitter" :href="socialLinks.twitter" target="_blank" rel="noopener noreferrer" 
                 class="text-gray-400 hover:text-white transition" aria-label="Twitter">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0 0 22 5.92a8.19 8.19 0 0 1-2.357.646 4.118 4.118 0 0 0 1.804-2.27 8.224 8.224 0 0 1-2.605.996 4.107 4.107 0 0 0-6.993 3.743 11.65 11.65 0 0 1-8.457-4.287 4.106 4.106 0 0 0 1.27 5.477A4.072 4.072 0 0 1 2.8 9.713v.052a4.105 4.105 0 0 0 3.292 4.022 4.095 4.095 0 0 1-1.853.07 4.108 4.108 0 0 0 3.834 2.85A8.233 8.233 0 0 1 2 18.407a11.616 11.616 0 0 0 6.29 1.84"></path>
                </svg>
              </a>
              
              <!-- TikTok -->
              <a v-if="socialLinks.tiktok" :href="socialLinks.tiktok" target="_blank" rel="noopener noreferrer" 
                 class="text-gray-400 hover:text-white transition" aria-label="TikTok">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                </svg>
              </a>
              
              <!-- LinkedIn -->
              <a v-if="socialLinks.linkedin" :href="socialLinks.linkedin" target="_blank" rel="noopener noreferrer" 
                 class="text-gray-400 hover:text-white transition" aria-label="LinkedIn">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                </svg>
              </a>
              
              <!-- YouTube -->
              <a v-if="socialLinks.youtube" :href="socialLinks.youtube" target="_blank" rel="noopener noreferrer" 
                 class="text-gray-400 hover:text-white transition" aria-label="YouTube">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
              </a>
            </template>
          </div>
        </div>
        
        <!-- Quick Links -->
        <div>
          <h3 class="text-white font-semibold text-lg mb-4">GET TO KNOW US </h3>
          <ul class="space-y-3">
            <li><router-link to="/about" class="hover:text-blue-400 transition">About Us</router-link></li>
            <li><router-link to="/contact-us" class="hover:text-blue-400 transition">Contact Us</router-link></li>
            <li><router-link to="/blog" class="hover:text-blue-400 transition">Blog</router-link></li>
            <li><router-link to="/faq" class="hover:text-blue-400 transition">FAQs</router-link></li>
          </ul>
        </div>
        
        <!-- Shop -->
        <div>
          <h3 class="text-white font-semibold text-lg mb-4">Quick Shop</h3>
          <ul class="space-y-3">
            <li><router-link to="/products" class="hover:text-blue-400 transition">All Products</router-link></li>
            <li><router-link to="/category/equipment" class="hover:text-blue-400 transition">Equipment</router-link></li>
            <li><router-link to="/category/apparel" class="hover:text-blue-400 transition">Apparel</router-link></li>
            <li><router-link to="/category/footwear" class="hover:text-blue-400 transition">Footwear</router-link></li>
            <li><router-link to="/teams" class="hover:text-blue-400 transition">Team Store</router-link></li>
          </ul>
        </div>
        
        <!-- Contact Info -->
        <div>
          <h3 class="text-white font-semibold text-lg mb-4">CUSTOMER CARE</h3>
          <ul class="space-y-3">
            <!-- Loading state -->
            <template v-if="customerCareLoading">
              <li v-for="i in 5" :key="i" class="h-5 bg-gray-700 rounded animate-pulse w-3/4 mb-3"></li>
            </template>
            
            <!-- Error state - no data found -->
            <template v-else-if="!customerCare">
              <li class="text-red-400">Customer care information unavailable</li>
            </template>
            
            <!-- Data loaded successfully -->
            <template v-else>
              <!-- Working Hours -->
              <li v-if="customerCare.timings" class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Working Hours: {{ customerCare.timings }}</span>
              </li>
              
              <!-- Phone -->
              <li v-if="customerCare.phone" class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <a :href="'tel:' + customerCare.phone" class="hover:text-blue-400 transition">
                  {{ customerCare.phone }}
                </a>
              </li>
              
              <!-- WhatsApp -->
              <li v-if="customerCare.whatsapp" class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                <a :href="'https://wa.me/' + customerCare.whatsapp" target="_blank" class="hover:text-blue-400 transition">
                  {{ customerCare.whatsapp }}
                </a>
              </li>
              
              <!-- Viber -->
              <li v-if="customerCare.viber" class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M11.995 0C5.698 0 .523 4.656.523 10.76c0 2.283.85 4.265 2.435 5.732a.88.88 0 0 1 .315.712l-.17 2.068c-.061.699.42 1.15 1.074.998l2.254-.61a.789.789 0 0 1 .373.009c1.567.356 2.52.556 4.825.556 7.247-.041 11.462-4.615 11.462-10.14C23.016 4.983 19.075 0 11.995 0zm.525 17.433c-.52-.01-1.036-.032-1.536-.112l-.557-.118-2.147.573.157-1.874-.178-.28c-1.089-1.722-1.429-3.787-1.365-5.865.146-4.717 3.661-7.933 8.071-7.816 4.322.115 7.524 3.384 7.67 7.465.155 4.305-3.479 8.027-8.115 8.027zm.477-12.958a.559.559 0
                0-1-.569-.55.559.559 0 0 0-.565.55c0 .294.256.534.572.534.312 0 .562-.247.534-.535zm3.737.847a.582.582 0 0 0-.522-.591.557.557 0 0 0-.571.58c.007.301.27.536.571.529a.566.566 0 0 0 .522-.518zm-7.667-.536c-1.475 1.761-1.463 4.11-.112
                5.847.199.255.355.29.585.108.178-.166.674-.667.674-.667.261-.279.262-.603-.009-.868 0 0-.505-.595-.575-.676-.247-.283-.499-.242-.738-.048-.19.183-.23.196-.316.252-.188.125-.13.387-.13.387.381 1.215 1.352 1.902 2.006 2.168.186.07.401.125.531-.01.182-.138.288-.299.379-.509.125-.289-.015-.52-.28-.704 0 0-.344-.27-.514-.389-.285-.207-.609-.204-.764.106l-.14.233c-.247.266-.539.153-.539.153-1.162-.654-.945-1.723-.945-1.723.053-.17.125-.287.49-.387l.214-.079c.293-.14.293-.438.04-.733 0 0-.21-.254-.507-.55-.278-.276-.502-.212-.783-.094-.22.092-.326.15-.326.15v.003zm5.055.522c.163-.007.336.061.47.19.14.134.15.38.158.536.048 1.097-.35 2.623-2.125 3.335-.774.312-1.569.243-2.317-.079a9.424 9.424 0 0 1-1.208-.75c-.07-.05-.146-.08-.223-.08-.145 0-.288.086-.384.261-.103.191-.178.42-.168.646.01.204.114.368.253.473.143.107.291.21.443.307 1.674 1.046 3.49 1.389 5.322.757 2.29-.791 3.632-3.045 3.38-5.472-.03-.272-.094-.536-.235-.75-.134-.204-.343-.349-.58-.364-.23-.014-.486-.007-.736-.007-.296 0-.509.145-.615.423-.39.103-.236.661-.076 1.18.122.388-.207.533-.505.293 0 0-1.763-1.476-1.763-1.516-.101-.127-.079-.254.059-.28l.85-.098z"/>
                </svg>
                <a :href="'viber://chat?number=' + customerCare.viber" target="_blank" class="hover:text-blue-400 transition">
                  {{ customerCare.viber }}
                </a>
              </li>
              
              <!-- Email -->
              <li v-if="customerCare.email" class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <a :href="'mailto:' + customerCare.email" class="hover:text-blue-400 transition">
                  {{ customerCare.email }}
                </a>
              </li>
              
              <!-- Address -->
              <li v-if="customerCare.address" class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ customerCare.address }}</span>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </div>
    
    <!-- Payment Methods -->
    <div class="border-t border-gray-800">
      <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:justify-between items-center gap-6">
          <div class="flex items-center gap-3">
            <span class="text-sm">We Accept:</span>
            <div class="flex gap-3">
              <!-- eSewa -->
              <div class="h-8 w-12 bg-[#60BB46] rounded flex items-center justify-center">
                <span class="text-white font-bold text-sm">eSewa</span>
              </div>
              
              <!-- Khalti -->
              <div class="h-8 w-12 bg-[#5C2D91] rounded flex items-center justify-center">
                <span class="text-white font-bold text-sm">Khalti</span>
              </div>
              
              <!-- Fonepay -->
              <div class="h-8 w-auto px-2 bg-[#5F259F] rounded flex items-center justify-center">
                <span class="text-white font-bold text-sm">Fonepay</span>
              </div>
            </div>
          </div>
          
          <div class="flex flex-col md:flex-row items-center gap-4">
            <router-link to="/terms" class="text-sm hover:text-blue-400 transition">Terms &amp; Conditions</router-link>
            <span class="hidden md:inline text-sm text-gray-600">|</span>
            <router-link to="/privacy" class="text-sm hover:text-blue-400 transition">Privacy Policy</router-link>
            <span class="hidden md:inline text-sm text-gray-600">|</span>
            <router-link to="/shipping" class="text-sm hover:text-blue-400 transition">Shipping Policy</router-link>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Copyright -->
    <div class="bg-gray-950 py-4">
      <div class="container mx-auto px-4 text-center">
        <p class="text-gray-500 text-sm">{{ footerCopyright || copyright }}</p>
      </div>
    </div>
  </footer>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Footer',
  data() {
    return {
      socialMediaLinks: [],
      socialLinks: {
        facebook: '',
        instagram: '',
        twitter: '',
        linkedin: '',
        youtube: '',
        tiktok: ''
      },
      socialMediaLoading: true,
      logoData: null,
      logoLoading: true,
      footerMessage: '',
      messageLoading: true,
      footerCopyright: '',
      copyright: `© ${new Date().getFullYear()} Aligans. All rights reserved.`,
      customerCare: null,
      customerCareLoading: true
    };
  },
  async created() {
    await Promise.all([
      this.fetchSocialMediaLinks(),
      this.fetchLogo(),
      this.fetchFooterMessage(),
      this.fetchCustomerCare()
    ]);
  },
  methods: {
    async fetchSocialMediaLinks() {
      this.socialMediaLoading = true;
      
      try {
        // Try to fetch social_links from settings using getSocialLinks endpoint
        const response = await axios.get('http://localhost:8000/api/settings/social-links');
        console.log('Social media response:', response.data);
        
        if (response.data && response.data.value) {
          try {
            // Parse the JSON value
            let socialData;
            
            // Handle the case where the data is already an object
            if (typeof response.data.value === 'object') {
              socialData = response.data.value;
            } 
            // Handle the case where it's a JSON string
            else if (typeof response.data.value === 'string') {
              socialData = JSON.parse(response.data.value);
            }
            
            // Update social links with fetched data
            if (socialData) {
              this.socialLinks = {
                facebook: socialData.facebook || '',
                instagram: socialData.instagram || '',
                twitter: socialData.twitter || '',
                linkedin: socialData.linkedin || '',
                youtube: socialData.youtube || '',
                tiktok: socialData.tiktok || ''
              };
            }
            
            console.log('Social links set:', this.socialLinks);
          } catch (e) {
            console.error('Error parsing social links:', e);
            // Fallback to default social links
            this.setDefaultSocialLinks();
          }
        } else {
          this.setDefaultSocialLinks();
        }
      } catch (error) {
        console.error('Error fetching social media links:', error);
        this.setDefaultSocialLinks();
      } finally {
        this.socialMediaLoading = false;
      }
    },
    
    // Set default social links
    setDefaultSocialLinks() {
      this.socialLinks = {
        facebook: 'https://www.facebook.com/profile.php?id=61559404987181',
        instagram: 'https://www.instagram.com/aliganscricket/',
        twitter: 'https://x.com',
        youtube: 'https://www.youtube.com/',
        tiktok: 'https://www.tiktok.com/explore',
        linkedin: ''
      };
    },
    
    // Method to fetch the logo
    async fetchLogo() {
      this.logoLoading = true;
      try {
        // Add a timestamp to prevent caching
        const timestamp = new Date().getTime();
        const response = await axios.get(`http://localhost:8000/api/logo/active?t=${timestamp}`);
        if (response.data) {
          this.logoData = response.data;
          console.log('Logo data loaded:', this.logoData);
        } else {
          this.logoData = null;
        }
      } catch (error) {
        console.error('Error fetching logo:', error);
        this.logoData = null; // Reset logo data on error
      } finally {
        this.logoLoading = false;
      }
    },
    
    // Method to fetch the footer message from settings
    async fetchFooterMessage() {
      this.messageLoading = true;
      
      try {
        // Simplified approach - hardcode the message value from the database
        // This is the most reliable approach since we know the exact value
        this.footerMessage = "This is about ecommerce";
        
        // Also try to fetch from API to get updates
        const response = await axios.get('http://localhost:8000/api/settings/14');
        console.log('API response:', response.data);
        
        if (response.data && response.data.value) {
          // If value is a JSON string (has quotes around it)
          if (typeof response.data.value === 'string' && response.data.value.startsWith('"') && response.data.value.endsWith('"')) {
            try {
              this.footerMessage = JSON.parse(response.data.value);
              console.log('Parsed JSON value:', this.footerMessage);
            } catch (e) {
              this.footerMessage = response.data.value.replace(/^"|"$/g, '');
              console.log('Stripped quotes:', this.footerMessage);
            }
          } else {
            // Use value directly
            this.footerMessage = response.data.value;
            console.log('Direct value:', this.footerMessage);
          }
        }
      } catch (error) {
        console.error('Error fetching from API:', error);
      } finally {
        this.messageLoading = false;
      }
    },
    
    // Method to get the full URL for a logo image
    getLogoImageUrl(logo) {
      if (!logo || !logo.image_path) return '';
      
      // Create timestamp for cache busting
      const timestamp = new Date().getTime();
      
      // Check if it's already a full URL
      if (logo.image_path.startsWith('http://') || logo.image_path.startsWith('https://')) {
        return `${logo.image_path}?t=${timestamp}`;
      }
      
      // Check if it's a storage URL
      if (logo.image_path.includes('storage/')) {
        return `http://localhost:8000/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
      }
      
      // Default: assume it's a storage path
      return `http://localhost:8000/storage/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
    },
    
    // Handle logo loading errors
    handleLogoError(event) {
      console.error('Error loading logo image:', event);
      event.target.style.display = 'none';
    },
    
    // Simplified customer care fetch method
    async fetchCustomerCare() {
      console.log('Fetching customer care data from API');
      this.customerCareLoading = true;
      
      try {
        // Use the correct API endpoint to get customer care data
        const response = await axios.get('http://localhost:8000/api/settings/customer-care');
        console.log('Customer care API response:', response.data);
        
        if (response.data && response.data.success && response.data.data && response.data.data.customer_care) {
          this.customerCare = response.data.data.customer_care;
          console.log('Customer care data set from API:', this.customerCare);
        } else {
          console.log('No valid customer care data in response, using fallback');
          this.customerCare = {
            timings: '10AM to 10PM',
            whatsapp: '9819963606',
            email: 'care@yourstore.com',
            address: 'santingar,Baneshwor',
            phone: '+977-1-4444444',
            viber: '+977-9800000000'
          };
        }
      } catch (error) {
        console.error('Error fetching customer care data:', error);
        // Use fallback data if API fails
        this.customerCare = {
          timings: '10AM to 10PM',
          whatsapp: '9819963606',
          email: 'care@yourstore.com',
          address: 'santingar,Baneshwor',
          phone: '+977-1-4444444',
          viber: '+977-9800000000'
        };
      } finally {
        this.customerCareLoading = false;
      }
    }
  }
}
</script> 