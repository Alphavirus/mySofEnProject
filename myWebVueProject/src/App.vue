<script setup>
import { ref } from 'vue';
import StudentList from './components/StudentList.vue';
import Login from './components/Login.vue';

const currentPage = ref('login');

const handleLoginSuccess = () => {
  currentPage.value = 'student-registration';
};
</script>

<template>
  <header>
    <div class="header-content">
      <img alt="UGB logo" class="logo" src="@/assets/ugb-logo.png" width="120" height="120" />
      <div class="university-title">
        <h1>University of Grand-Bassam</h1>
        <div class="subtitle">Excellence • Innovation • Leadership</div>
      </div>
    </div>
  </header>

  <main>
    <Login v-if="currentPage === 'login'" @login-success="handleLoginSuccess" />
    <StudentList v-else />
  </main>
</template>

<style scoped>
header {
  background: #27285C;  /* Dark blue matching the logo */
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  margin-bottom: 2rem;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2rem;
}

.logo {
  background: white;
  padding: 10px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.university-title {
  text-align: center;
}

h1 {
  color: white;
  font-size: 2.5rem;
  margin: 0;
  font-weight: 600;
  letter-spacing: 0.5px;
  font-family: 'Georgia', serif;
}

.subtitle {
  color: #e8eaf6;
  font-size: 1.2rem;
  margin-top: 0.5rem;
  font-style: italic;
}

main {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  background: #f5f5f5;
  min-height: calc(100vh - 200px);
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    text-align: center;
  }

  h1 {
    font-size: 1.8rem;
  }

  .subtitle {
    font-size: 1rem;
  }

  .logo {
    width: 100px;
    height: 100px;
  }
}
</style>
