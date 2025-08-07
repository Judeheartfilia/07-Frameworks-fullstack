<template>
  <div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Finaliser la commande</h1>
    
    <div v-if="cart.length === 0" class="text-center">
      <p>Votre panier est vide</p>
      <router-link to="/" class="text-blue-600 hover:underline">
        Retourner aux produits
      </router-link>
    </div>
    
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h2 class="text-xl font-semibold mb-4">Récapitulatif</h2>
        
        <div class="border rounded p-4 mb-4">
          <div v-for="item in cart" :key="item.id" class="flex justify-between py-2 border-b last:border-b-0">
            <div>
              <span class="font-medium">{{ item.name }}</span>
              <span class="text-gray-600 block text-sm">{{ item.quantity }} x {{ item.price }}€</span>
            </div>
            <span class="font-medium">{{ (item.price * item.quantity).toFixed(2) }}€</span>
          </div>
          
          <div class="flex justify-between pt-3 font-bold text-lg">
            <span>Total</span>
            <span>{{ cartTotal.toFixed(2) }}€</span>
          </div>
        </div>
      </div>
      
      <div>
        <h2 class="text-xl font-semibold mb-4">Paiement</h2>
        
        <form @submit.prevent="processPayment" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Numéro de carte</label>
            <input 
              v-model="payment.cardNumber"
              type="text" 
              placeholder="1234 5678 9012 3456"
              maxlength="19"
              required
              @input="formatCardNumber"
              class="w-full border rounded px-3 py-2"
            >
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Date d'expiration</label>
              <input 
                v-model="payment.expiryDate"
                type="text" 
                placeholder="MM/AA"
                maxlength="5"
                required
                @input="formatExpiryDate"
                class="w-full border rounded px-3 py-2"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium mb-1">CVV</label>
              <input 
                v-model="payment.cvv"
                type="text" 
                placeholder="123"
                maxlength="3"
                required
                class="w-full border rounded px-3 py-2"
              >
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium mb-1">Nom sur la carte</label>
            <input 
              v-model="payment.cardName"
              type="text" 
              required
              class="w-full border rounded px-3 py-2"
            >
          </div>
          
          <div v-if="error" class="text-red-600 text-sm">
            {{ error }}
          </div>
          
          <button 
            type="submit" 
            :disabled="loading || !isFormValid"
            class="w-full bg-green-600 text-white py-3 rounded hover:bg-green-700 disabled:bg-gray-400"
          >
            {{ loading ? 'Traitement...' : `Payer ${cartTotal.toFixed(2)}€` }}
          </button>
        </form>
      </div>
    </div>
    
    <div v-if="showSuccess" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg max-w-md mx-4">
        <h3 class="text-xl font-bold text-green-600 mb-4">✅ Commande confirmée !</h3>
        <p class="mb-4">Votre commande a été traitée avec succès.</p>
        <p class="text-sm text-gray-600 mb-4">
          Vous avez gagné {{ loyaltyPointsEarned }} points de fidélité !
        </p>
        <button 
          @click="goToProfile"
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
        >
          Voir mes commandes
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'Checkout',
  
  data() {
    return {
      payment: {
        cardNumber: '',
        expiryDate: '',
        cvv: '',
        cardName: ''
      },
      error: '',
      loading: false,
      showSuccess: false,
      loyaltyPointsEarned: 0
    }
  },
  
  computed: {
    ...mapState(['cart']),
    ...mapGetters(['cartTotal']),
    
    isFormValid() {
      return this.payment.cardNumber.replace(/\s/g, '').length >= 16 &&
             this.payment.expiryDate.length === 5 &&
             this.payment.cvv.length === 3 &&
             this.payment.cardName.trim().length > 0
    }
  },
  
  methods: {
    formatCardNumber(event) {
      let value = event.target.value.replace(/\s/g, '').replace(/\D/g, '')
      value = value.substring(0, 16)
      value = value.replace(/(.{4})/g, '$1 ').trim()
      this.payment.cardNumber = value
    },
    
    formatExpiryDate(event) {
      let value = event.target.value.replace(/\D/g, '')
      if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4)
      }
      this.payment.expiryDate = value
    },
    
    async processPayment() {
      this.loading = true
      this.error = ''
      
      const result = await this.$store.dispatch('createOrder', {
        payment: {
          cardNumber: this.payment.cardNumber.replace(/\s/g, ''),
          expiryDate: this.payment.expiryDate
        }
      })
      
      if (result.success) {
        this.loyaltyPointsEarned = result.data.loyaltyPointsEarned
        this.showSuccess = true
      } else {
        this.error = result.message
      }
      
      this.loading = false
    },
    
    goToProfile() {
      this.showSuccess = false
      this.$router.push('/profile')
    }
  }
}
</script>