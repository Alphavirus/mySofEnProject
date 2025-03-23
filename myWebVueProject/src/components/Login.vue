<template>
  <div class="login-container">
    <div class="login-box">
      <h2>Admin Login</h2>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="username">Username:</label>
          <input 
            type="text" 
            id="username" 
            v-model="username" 
            required
          >
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input 
            type="password" 
            id="password" 
            v-model="password" 
            required
          >
        </div>
        <div class="button-group">
          <button type="submit">Login</button>
          <button type="button" @click="autoFill" class="auto-fill-btn">Auto Fill</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['login-success']);

const username = ref('');
const password = ref('');

const autoFill = () => {
  username.value = 'admin';
  password.value = 'admin';
};

const handleLogin = () => {
  if (username.value === 'admin' && password.value === 'admin') {
    emit('login-success');
  }
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f5f5f5;
}

.login-box {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

h2 {
  text-align: center;
  color: #42b983;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #666;
}

input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.button-group {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
}

button {
  flex: 1;
  padding: 0.75rem;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

button:hover {
  background-color: #3aa876;
}

.auto-fill-btn {
  background-color: #4a90e2;
}

.auto-fill-btn:hover {
  background-color: #357abd;
}
</style> 