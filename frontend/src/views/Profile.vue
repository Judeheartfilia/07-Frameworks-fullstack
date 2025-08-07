<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Mon profil</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-gray-50 p-6 rounded-lg">
        <h2 class="text-xl font-semibold mb-4">Informations personnelles</h2>
        
        <div class="space-y-3">
          <div>
            <span class="font-medium">Nom:</span> 
            {{ user?.firstName }} {{ user?.lastName }}
          </div>
          <div>
            <span class="font-medium">Email:</span> 
            {{ user?.email }}
          </div>
          <div>
            <span class="font-medium">Points de fidélité:</span> 
            <span class="text-lg font-bold text-green-600">{{ user?.loyaltyPoints }}</span>
          </div>
          <div>
            <span class="font-medium">Commandes totales:</span> 
            {{ orders.length }}
          </div>
        </div>
      </div>
      
      <div class="bg-blue-50 p-6 rounded-lg">
        <h2 class="text-xl font-semibold mb-4">Programme de fidélité</h2>
        
        <p class="text-sm text-gray-600 mb-3">
          Gagnez 1 point pour chaque 10€ d'achat
        </p>
        
        <div class="space-y-2">
          <div class="flex justify-between">
            <span>Points actuels:</span>
            <span class="font-bold">{{ user?.loyaltyPoints }}</span>
          </div>
          <div class="flex justify-between text-sm text-gray-600">
            <span>Prochaine récompense à:</span>
            <span>{{ nextRewardThreshold }} points</span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="mt-8">
      <h2 class="text-xl font-semibold mb-4">Historique des commandes</h2>
      
      <div v-if="orders.length === 0" class="text-center text-gray-500 py-8">
        Aucune commande pour le moment
      </div>
      
      <div v-else class="space-y-4">
        <div 
          v-for="order in orders" 
          :key="order.id"
          class="border rounded-lg p-4"
        >
          <div class="flex justify-between items-start mb-3">
            <div>
              <h3 class="font-semibold">Commande #{{ order.id }}</h3>
              <p class="text-sm text-gray-600">{{ formatDate(order.createdAt) }}</p>
            </div>
            <div class="text-right">
              <span class="font-bold text-lg">{{ order.totalAmount.toFixed(2) }}€</span>
              <p class="text-sm text-gray-600">Carte ****{{ order.cardLastFour }}</p>
            </div>
          </div>
          
          <div class="border-t pt-3">
            <h4 class="font-medium mb-2">Articles commandés:</h4>
            <div class="space-y-1">
              <div 
                v-for="item in order.items" 
                :key="item.productId"
                class="flex justify-between text-sm"
              >
                <span>{{ item.productName }} x{{ item.quantity }}</span>
                <span>{{ item.subtotal.toFixed(2) }}€</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'Profile',
  
  computed: {
    ...mapState(['user', 'orders']),
    
    nextRewardThreshold() {
      const current = this.user?.loyaltyPoints || 0
      const thresholds = [100, 250, 500, 1000, 2000]
      return thresholds.find(t => t > current) || (Math.ceil(current / 1000) * 1000 + 1000)
    }
  },
  
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }
  },
  
  async mounted() {
    await this.$store.dispatch('fetchOrders')
  }
}
</script>