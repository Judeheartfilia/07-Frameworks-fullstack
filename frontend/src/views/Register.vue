<template>
  <div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Créer un compte</h1>
    
    <form @submit.prevent="register" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Prénom</label>
        <input 
          v-model="form.firstName"
          type="text" 
          required
          class="w-full border rounded px-3 py-2"
        >
      </div>
      
      <div>
        <label class="block text-sm font-medium mb-1">Nom</label>
        <input 
          v-model="form.lastName"
          type="text" 
          required
          class="w-full border rounded px-3 py-2"
        >
      </div>
      
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
          minlength="6"
          class="w-full border rounded px-3 py-2"
        >
      </div>
      
      <div>
        <label class="block text-sm font-medium mb-1">Confirmer le mot de passe</label>
        <input 
          v-model="form.confirmPassword"
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
        :disabled="loading || !passwordsMatch"
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 disabled:bg-gray-400"
      >
        {{ loading ? 'Création...' : 'Créer le compte' }}
      </button>
    </form>
    
    <p class="mt-4 text-center">
      Déjà un compte ? 
      <router-link to="/login" class="text-blue-600 hover:underline">
        Se connecter
      </router-link>
    </p>
  </div>
</template>

<script>
export default {
  name: 'Register',
  
  data() {
    return {
      form: {
        firstName: '',
        lastName: '',
        email: '',
        password: '',
        confirmPassword: ''
      },
      error: '',
      loading: false
    }
  },
  
  computed: {
    passwordsMatch() {
      return this.form.password === this.form.confirmPassword
    }
  },
  
  methods: {
    async register() {
      if (!this.passwordsMatch) {
        this.error = 'Les mots de passe ne correspondent pas'
        return
      }
      
      this.loading = true
      this.error = ''
      
      const result = await this.$store.dispatch('register', this.form)
      
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