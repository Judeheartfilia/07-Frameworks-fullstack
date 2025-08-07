<template>
  <div id="app">
    <nav class="bg-blue-600 text-white p-4">
      <div class="container mx-auto flex justify-between items-center">
        <router-link to="/" class="text-xl font-bold">
          Picard Distributeur
        </router-link>
        
        <div class="flex items-center space-x-4">
          <div v-if="cartItemCount > 0" class="relative">
            <button @click="showCart = !showCart" class="bg-blue-500 px-3 py-2 rounded">
              Panier ({{ cartItemCount }})
            </button>
          </div>
          
          <div v-if="isAuthenticated" class="flex items-center space-x-4">
            <span>{{ user?.firstName }} ({{ user?.loyaltyPoints }} pts)</span>
            <router-link to="/profile" class="hover:underline">Profil</router-link>
            <button @click="logout" class="hover:underline">Déconnexion</button>
          </div>
          
          <div v-else class="space-x-2">
            <router-link to="/login" class="hover:underline">Connexion</router-link>
            <router-link to="/register" class="hover:underline">Inscription</router-link>
          </div>
        </div>
      </div>
    </nav>

    <Cart v-if="showCart" @close="showCart = false" />
    
    <main class="container mx-auto p-4">
      <router-view />
    </main>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import Cart from './components/Cart.vue'

export default {
  name: 'App',
  components: {
    Cart
  },
  
  data() {
    return {
      showCart: false
    }
  },
  
  computed: {
    ...mapState(['user']),
    ...mapGetters(['isAuthenticated', 'cartItemCount'])
  },
  
  methods: {
    logout() {
      this.$store.dispatch('logout')
      this.$router.push('/')
    }
  },
  
  async mounted() {
    if (this.isAuthenticated) {
      await this.$store.dispatch('fetchUserProfile')
    }
    await this.$store.dispatch('fetchProducts')
  }
}
</script>