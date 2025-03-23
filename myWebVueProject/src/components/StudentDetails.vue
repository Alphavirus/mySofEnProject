<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import QRCode from 'qrcode';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const student = ref(null);
const qrCodeUrl = ref('');
const loading = ref(true);
const error = ref(null);

const generateQRCode = async (data) => {
  try {
    const qrData = JSON.stringify({
      id: data.student_id,
      name: data.name
    });
    qrCodeUrl.value = await QRCode.toDataURL(qrData);
  } catch (err) {
    console.error('Error generating QR code:', err);
  }
};

const fetchStudentDetails = async () => {
  try {
    const response = await axios.get(`http://localhost/myWebVueProject/api/students.php?id=${route.params.id}`);
    student.value = response.data;
    await generateQRCode(response.data);
  } catch (err) {
    error.value = 'Failed to load student details';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const goBack = () => {
  router.push('/students');
};

onMounted(() => {
  fetchStudentDetails();
});
</script>

<template>
  <div class="student-details">
    <button class="back-button" @click="goBack">← Back to List</button>
    
    <div v-if="loading" class="loading">
      Loading student details...
    </div>
    
    <div v-else-if="error" class="error">
      {{ error }}
    </div>
    
    <div v-else-if="student" class="details-container">
      <div class="qr-section">
        <img :src="qrCodeUrl" alt="Student QR Code" class="qr-code" />
        <p class="qr-caption">Student ID: {{ student.student_id }}</p>
      </div>
      
      <div class="info-section">
        <h2>{{ student.name }}</h2>
        <div class="info-grid">
          <div class="info-item">
            <label>Email:</label>
            <span>{{ student.email }}</span>
          </div>
          <div class="info-item">
            <label>Phone:</label>
            <span>{{ student.phone }}</span>
          </div>
          <div class="info-item">
            <label>Course:</label>
            <span>{{ student.course }}</span>
          </div>
          <div class="info-item">
            <label>GPA:</label>
            <span>{{ student.gpa }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.student-details {
  max-width: 800px;
  margin: 2rem auto;
  padding: 20px;
}

.back-button {
  background: #27285C;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  margin-bottom: 2rem;
  font-size: 1rem;
}

.back-button:hover {
  background: #1a1b3e;
}

.details-container {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 2rem;
}

.qr-section {
  text-align: center;
}

.qr-code {
  width: 200px;
  height: 200px;
  margin-bottom: 1rem;
  border: 1px solid #eee;
  padding: 10px;
  border-radius: 10px;
}

.qr-caption {
  color: #666;
  font-size: 0.9rem;
}

.info-section h2 {
  color: #27285C;
  margin-bottom: 1.5rem;
  font-size: 1.8rem;
}

.info-grid {
  display: grid;
  gap: 1.5rem;
}

.info-item {
  border-bottom: 1px solid #eee;
  padding-bottom: 0.8rem;
}

.info-item label {
  color: #666;
  font-size: 0.9rem;
  display: block;
  margin-bottom: 0.25rem;
  font-weight: 500;
}

.info-item span {
  color: #333;
  font-size: 1.1rem;
}

.loading, .error {
  text-align: center;
  padding: 2rem;
  color: #666;
  font-size: 1.1rem;
}

.error {
  color: #dc3545;
}

@media (max-width: 768px) {
  .details-container {
    grid-template-columns: 1fr;
  }
  
  .qr-code {
    width: 150px;
    height: 150px;
  }

  .info-section h2 {
    font-size: 1.5rem;
    text-align: center;
  }
}
</style> 