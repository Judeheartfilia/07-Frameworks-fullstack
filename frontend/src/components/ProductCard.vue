<template>
  <div class="border rounded-lg p-4 shadow-md">
    <div class="aspect-w-16 aspect-h-9 mb-4">
      <img 
        :src="imageUrl" 
        :alt="product.name"
        class="w-full h-48 object-cover rounded"
        @error="handleImageError"
      >
    </div>
    
    <h3 class="text-lg font-semibold mb-2">{{ product.name }}</h3>
    <p class="text-gray-600 text-sm mb-3">{{ product.description }}</p>
    
    <div class="flex justify-between items-center mb-3">
      <span class="text-xl font-bold text-green-600">{{ product.price }}€</span>
      <span class="text-sm text-gray-500">{{ product.category }}</span>
    </div>
    
    <div class="flex justify-between items-center">
      <span :class="stockClass">
        {{ stockText }}
      </span>
      
      <button 
        @click="$emit('add-to-cart', product)"
        :disabled="!product.available || product.stock === 0"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
      >
        Ajouter
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductCard',
  props: {
    product: {
      type: Object,
      required: true
    }
  },
  
  computed: {
    imageUrl() {
      return this.product.image ? `/images/${this.product.image}` : '/images/placeholder.jpg'
    },
    
    stockClass() {
      if (this.product.stock === 0) return 'text-red-600'
      if (this.product.stock < 5) return 'text-orange-600'
      return 'text-green-600'
    },
    
    stockText() {
      if (this.product.stock === 0) return 'Rupture'
      if (this.product.stock < 5) return `Plus que ${this.product.stock}`
      return 'Disponible'
    }
  },
  
  methods: {
    handleImageError(event) {
      event.target.src = '/images/placeholder.jpg'
    }
  }
}
</script>