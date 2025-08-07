<template>
  <div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Connexion</h1>
    
    <form @submit.prevent="login" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input 
          v-model="form.email"
          type="email" 
          required
          class="w-full border rounded px-3 py-2"
        >
      </div>
      
      <div>
        <label class="block text-sm font-medium mb-1">Mot de passe</label>
        <input 
          v-model="form.password"
          type="password" 
          required
          class="w-full border rounded px-3 py-2"
        >
      </div>
      
      <div v-if="error" class="text-red-600 text-sm">
        {{ error }}
      </div>
      
      <button 
        type="submit" 
        :disabled="loading"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:bg-gray-400"
      >
        {{ loading ? 'Connexion...' : 'Se connecter' }}
      </button>
    </form>
    
    <p class="mt-4 text-center">
      Pas de compte ? 
      <router-link to="/register" class="text-blue-600 hover:underline">
        Créer un compte
      </router-link>
    </p>
  </div>
</template>

<script>
export default {
  name: 'Login',
  
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      error: '',
      loading: false
    }
  },
  
  methods: {
    async login() {
      this.loading = true
      this.error = ''
      
      const result = await this.$store.dispatch('login', this.form)
      
      if (result.success) {
        this.$router.push('/')
      } else {
        this.error = result.message
      }
      
      this.loading = false
    }
  }
}
</script>