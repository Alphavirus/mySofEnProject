<template>
  <div class="student-info-page">
    <div class="info-card">
      <h2>Student Information</h2>
      <div class="info-content" v-if="studentInfo">
        <div class="info-row">
          <strong>Student ID:</strong>
          <span>{{ studentInfo.id }}</span>
        </div>
        <div class="info-row">
          <strong>Name:</strong>
          <span>{{ studentInfo.name }}</span>
        </div>
        <div class="info-row">
          <strong>Email:</strong>
          <span>{{ studentInfo.email }}</span>
        </div>
        <div class="info-row">
          <strong>Phone:</strong>
          <span>{{ studentInfo.phone }}</span>
        </div>
        <div class="info-row">
          <strong>Course:</strong>
          <span>{{ studentInfo.course }}</span>
        </div>
        <div class="info-row">
          <strong>GPA:</strong>
          <span>{{ studentInfo.gpa }}</span>
        </div>
      </div>
      <div v-else class="error-message">
        No student information found
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const studentInfo = ref(null);

onMounted(() => {
  // Get student information from URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const info = {};
  for (const [key, value] of urlParams) {
    info[key] = value;
  }
  if (Object.keys(info).length > 0) {
    studentInfo.value = info;
  }
});
</script>

<style scoped>
.student-info-page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
  background-color: #f5f5f5;
}

.info-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 500px;
}

h2 {
  color: #27285C;
  margin-bottom: 1.5rem;
  text-align: center;
}

.info-content {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #eee;
}

.info-row strong {
  color: #27285C;
}

.error-message {
  text-align: center;
  color: #666;
  padding: 1rem;
}
</style> 