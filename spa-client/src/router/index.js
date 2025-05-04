import { createRouter, createWebHistory } from 'vue-router'
import store from '../store'

// Lazy-loaded views for components we've already created
const Home = () => import('../views/Home.vue')
const Login = () => import('../views/auth/Login.vue')
const Register = () => import('../views/auth/Register.vue')
const Products = () => import('../views/products/Products.vue')
const ProductDetail = () => import('../views/products/ProductDetail.vue')
const Sale = () => import('../views/products/Sale.vue')
const NewArrivals = () => import('../views/products/NewArrivals.vue')
const Events = () => import('../views/events/Events.vue')
const EventDetail = () => import('../views/events/EventDetail.vue')
const Teams = () => import('../views/teams/Teams.vue')
const TeamDetail = () => import('../views/teams/TeamDetail.vue')
const SimpleProducts = () => import('../views/SimpleProducts.vue')
const CategoryProducts = () => import('../views/CategoryProducts.vue')
const SearchResults = () => import('../views/products/SearchResults.vue')

// Cart and Checkout components
const ShoppingCart = () => import('../views/cart/ShoppingCart.vue')
const CheckoutShipping = () => import('../views/checkout/CheckoutShipping.vue')
const CheckoutPayment = () => import('../views/checkout/CheckoutPayment.vue')
const CheckoutConfirmation = () => import('../views/checkout/CheckoutConfirmation.vue')
const Wishlist = () => import('../views/wishlist/Wishlist.vue')
const OrderHistory = () => import('../views/account/OrderHistory.vue')
const Account = () => import('../views/account/Account.vue')

// Page components
const AboutUs = () => import('../views/pages/AboutUs.vue')
const ContactUs = () => import('../views/pages/ContactUs.vue')
const Blog = () => import('../views/pages/Blog.vue')
const BlogPost = () => import('../views/pages/BlogPost.vue')
const Faq = () => import('../views/pages/Faq.vue')
const TermsConditions = () => import('../views/pages/TermsConditions.vue')

// Public routes that don't require authentication
const publicRoutes = [
  // Public routes
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guestOnly: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { guestOnly: true }
  },
  // Storefront public routes
  {
    path: '/products',
    name: 'Products',
    component: Products
  },
  {
    path: '/products/sale',
    name: 'Sale',
    component: Sale
  },
  {
    path: '/new-arrivals',
    name: 'NewArrivals',
    component: NewArrivals
  },
  {
    path: '/search',
    name: 'SearchResults',
    component: SearchResults
  },
  {
    path: '/products/:id',
    name: 'ProductDetail',
    component: ProductDetail,
    props: true
  },
  {
    path: '/category/:id',
    name: 'CategoryProducts',
    component: CategoryProducts,
    props: true
  },
  {
    path: '/categories/:id',
    redirect: to => {
      return { path: `/category/${to.params.id}` }
    }
  },
  {
    path: '/simple-products',
    name: 'SimpleProducts',
    component: SimpleProducts
  },
  {
    path: '/events',
    name: 'Events',
    component: Events
  },
  {
    path: '/events/:id',
    name: 'EventDetail',
    component: EventDetail,
    props: true
  },
  {
    path: '/teams',
    name: 'Teams',
    component: Teams
  },
  {
    path: '/teams/:id',
    name: 'TeamDetail',
    component: TeamDetail,
    props: true
  },
  // Shopping Cart and Checkout routes
  {
    path: '/cart',
    name: 'ShoppingCart',
    component: ShoppingCart
  },
  {
    path: '/checkout/shipping',
    name: 'CheckoutShipping',
    component: CheckoutShipping,
    meta: { requiresAuth: true }
  },
  {
    path: '/checkout/payment',
    name: 'CheckoutPayment',
    component: CheckoutPayment,
    meta: { requiresAuth: true }
  },
  {
    path: '/checkout/confirmation',
    name: 'CheckoutConfirmation',
    component: CheckoutConfirmation,
    meta: { requiresAuth: true }
  },
  {
    path: '/checkout/mock-esewa',
    name: 'MockEsewa',
    component: () => import('@/views/checkout/MockEsewa.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/checkout/esewa-success',
    name: 'EsewaSuccess',
    component: () => import('@/views/checkout/EsewaSuccess.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/checkout/esewa-failure',
    name: 'EsewaFailure',
    component: () => import('@/views/checkout/EsewaFailure.vue'),
    meta: { requiresAuth: true }
  },
  // Wishlist route
  {
    path: '/wishlist',
    name: 'Wishlist',
    component: Wishlist
  },
  // Account routes
  {
    path: '/account',
    name: 'Account',
    component: Account,
    meta: { requiresAuth: true }
  },
  {
    path: '/account/orders',
    name: 'OrderHistory',
    component: OrderHistory,
    meta: { requiresAuth: true }
  },
  {
    path: '/orders',
    redirect: '/account/orders'
  },
  // Page routes
  {
    path: '/about',
    name: 'AboutUs',
    component: AboutUs
  },
  {
    path: '/contact-us',
    name: 'ContactUs',
    component: ContactUs
  },
  {
    path: '/blog',
    name: 'Blog',
    component: Blog,
    meta: { title: 'Blog' }
  },
  {
    path: '/blog/:slug',
    name: 'BlogPost',
    component: BlogPost,
    props: true,
    meta: { title: 'Blog Post' }
  },
  {
    path: '/terms',
    name: 'Terms',
    component: () => import('@/views/pages/Terms.vue'),
    meta: { title: 'Terms & Conditions' }
  },
  {
    path: '/privacy',
    name: 'Privacy',
    component: () => import('@/views/pages/Privacy.vue'),
    meta: { title: 'Privacy Policy' }
  },
  {
    path: '/shipping',
    name: 'Shipping',
    component: () => import('@/views/pages/Shipping.vue'),
    meta: { title: 'Shipping Policy' }
  },
  {
    path: '/blogs',
    redirect: '/blog'
  },
  {
    path: '/faq',
    name: 'Faq',
    component: Faq
  },
  {
    path: '/brands',
    name: 'BrandsPage',
    component: () => import('../views/brands/BrandsPage.vue'),
    meta: {
      requiresAuth: false,
      title: 'Shop By Brand'
    }
  },
  {
    path: '/brands/:slug',
    name: 'BrandProducts',
    component: () => import('../views/brands/BrandProducts.vue'),
    meta: {
      requiresAuth: false,
      title: 'Brand Products'
    }
  },
  // 404 route
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../views/NotFound.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes: publicRoutes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

router.beforeEach((to, from, next) => {
  // For debugging
  console.log('Navigating to:', to.path)
  
  const isLoggedIn = store.getters['auth/isLoggedIn']
  const isAdmin = store.getters['auth/isAdmin']
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin)
  const guestOnly = to.matched.some(record => record.meta.guestOnly)

  if (requiresAuth && !isLoggedIn) {
    next({ name: 'Login', query: { redirect: to.fullPath } })
  } else if (requiresAdmin && !isAdmin) {
    next({ name: 'Home' })
  } else if (guestOnly && isLoggedIn) {
    next({ name: 'Home' })
  } else {
    next()
  }
})

export default router 