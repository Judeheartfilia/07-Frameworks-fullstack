<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">Distributeur Picard</h1>
    
    <div v-if="loading" class="text-center">
      Chargement des produits...
    </div>
    
    <div v-else>
      <div class="mb-6">
        <select v-model="selectedCategory" class="border p-2 rounded">
          <option value="">Toutes les catégories</option>
          <option v-for="category in categories" :key="category" :value="category">
            {{ category }}
          </option>
        </select>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <ProductCard 
          v-for="product in filteredProducts" 
          :key="product.id" 
          :product="product"
          @add-to-cart="addToCart"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import ProductCard from '../components/ProductCard.vue'

export default {
  name: 'Home',
  components: {
    ProductCard
  },
  
  data() {
    return {
      selectedCategory: ''
    }
  },
  
  computed: {
    ...mapState(['products', 'loading']),
    
    filteredProducts() {
      if (!this.selectedCategory) {
        return this.products
      }
      return this.products.filter(product => product.category === this.selectedCategory)
    },
    
    categories() {
      const cats = [...new Set(this.products.map(p => p.category))]
      return cats.sort()
    }
  },
  
  methods: {
    addToCart(product) {
      this.$store.commit('ADD_TO_CART', product)
    }
  }
}
</script>