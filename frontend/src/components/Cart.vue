<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50" @click="$emit('close')">
    <div class="fixed right-0 top-0 h-full w-96 bg-white shadow-lg" @click.stop>
      <div class="p-4 border-b">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-bold">Panier</h2>
          <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
            ✕
          </button>
        </div>
      </div>
      
      <div class="p-4 flex-1 overflow-y-auto" style="height: calc(100% - 140px)">
        <div v-if="cart.length === 0" class="text-center text-gray-500">
          Votre panier est vide
        </div>
        
        <div v-else>
          <div v-for="item in cart" :key="item.id" class="flex items-center justify-between border-b py-3">
            <div class="flex-1">
              <h4 class="font-medium">{{ item.name }}</h4>
              <p class="text-sm text-gray-600">{{ item.price }}€ x {{ item.quantity }}</p>
            </div>
            
            <div class="flex items-center space-x-2">
              <button 
                @click="updateQuantity(item.id, item.quantity - 1)"
                class="w-6 h-6 bg-gray-200 rounded text-sm"
              >
                -
              </button>
              <span class="w-8 text-center">{{ item.quantity }}</span>
              <button 
                @click="updateQuantity(item.id, item.quantity + 1)"
                class="w-6 h-6 bg-gray-200 rounded text-sm"
              >
                +
              </button>
              <button 
                @click="removeItem(item.id)"
                class="text-red-600 ml-2"
              >
                🗑️
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <div v-if="cart.length > 0" class="p-4 border-t">
        <div class="flex justify-between items-center mb-3">
          <span class="font-bold">Total: {{ cartTotal.toFixed(2) }}€</span>
        </div>
        
        <button 
          v-if="isAuthenticated"
          @click="goToCheckout"
          class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700"
        >
          Commander
        </button>
        
        <div v-else class="text-center">
          <p class="text-sm text-gray-600 mb-2">Connectez-vous pour commander</p>
          <router-link 
            to="/login" 
            class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            @click="$emit('close')"
          >
            Se connecter
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'Cart',
  
  computed: {
    ...mapState(['cart']),
    ...mapGetters(['cartTotal', 'isAuthenticated'])
  },
  
  methods: {
    updateQuantity(productId, quantity) {
      this.$store.commit('UPDATE_CART_QUANTITY', { productId, quantity })
    },
    
    removeItem(productId) {
      this.$store.commit('REMOVE_FROM_CART', productId)
    },
    
    goToCheckout() {
      this.$emit('close')
      this.$router.push('/checkout')
    }
  }
}
</script>