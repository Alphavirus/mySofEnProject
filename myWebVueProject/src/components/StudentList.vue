n<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import QRCodeVue3 from 'qrcode-vue3';

const students = ref([]);
const newStudent = ref({
  student_id: '',
  name: '',
  email: '',
  phone: '',
  course: '',
  gpa: ''
});

const selectedStudent = ref(null);

const fetchStudents = async () => {
  try {
    console.log('Fetching students...');
    const response = await axios.get('http://localhost/mobileApp/myWebVueProject/api/students.php');
    console.log('Response from server:', response.data);
    students.value = response.data;
    console.log('Updated students list:', students.value);
  } catch (error) {
    console.error('Error fetching students:', error);
    console.error('Error details:', error.response?.data);
  }
};

const handleSubmit = async () => {
  try {
    // Validate student ID length
    const studentId = newStudent.value.student_id.toString();
    if (studentId.length !== 6) {
      alert('Student ID must be 6 digits');
      return;
    }

    console.log('Submitting new student:', newStudent.value);
    const response = await axios.post('http://localhost/mobileApp/myWebVueProject/api/students.php', newStudent.value);
    console.log('Server response:', response.data);
    
    // Reset form
    newStudent.value = {
      student_id: '',
      name: '',
      email: '',
      phone: '',
      course: '',
      gpa: ''
    };
    
    // Fetch updated list
    await fetchStudents();
  } catch (error) {
    console.error('Error adding student:', error);
    console.error('Error details:', error.response?.data);
    alert(error.response?.data?.error || 'Error adding student');
  }
};

const deleteStudent = async (studentId) => {
  try {
    console.log('Deleting student:', studentId);
    const response = await axios.delete(`http://localhost/mobileApp/myWebVueProject/api/students.php?id=${studentId}`);
    console.log('Delete response:', response.data);
    await fetchStudents();
  } catch (error) {
    console.error('Error deleting student:', error);
    console.error('Error details:', error.response?.data);
    alert(error.response?.data?.error || 'Error deleting student');
  }
};

const viewStudentDetails = (student) => {
  selectedStudent.value = student;
};

const closeModal = () => {
  selectedStudent.value = null;
};

// Fetch students when component mounts
onMounted(() => {
  console.log('Component mounted, fetching students...');
  fetchStudents();
});
</script>

<template>
  <div class="student-management">
    <h2>Student Registration</h2>
    
    <form @submit.prevent="handleSubmit" class="student-form">
      <div class="form-group">
        <label for="studentId">Student ID (6 digits):</label>
        <input 
          type="number" 
          id="studentId" 
          v-model="newStudent.student_id" 
          required
          min="100000"
          max="999999"
          placeholder="Enter 6-digit ID (100000-999999)"
        >
      </div>
      
      <div class="form-group">
        <label for="name">Name:</label>
        <input 
          type="text" 
          id="name" 
          v-model="newStudent.name" 
          required
          placeholder="Enter student name"
        >
      </div>
      
      <div class="form-group">
        <label for="email">Email:</label>
        <input 
          type="email" 
          id="email" 
          v-model="newStudent.email" 
          required
          placeholder="Enter student email"
        >
      </div>
      
      <div class="form-group">
        <label for="phone">Phone:</label>
        <input 
          type="tel" 
          id="phone" 
          v-model="newStudent.phone" 
          required
          placeholder="Enter student phone"
        >
      </div>
      
      <div class="form-group">
        <label for="course">Course:</label>
        <input 
          type="text" 
          id="course" 
          v-model="newStudent.course" 
          required
          placeholder="Enter student course"
        >
      </div>

      <div class="form-group">
        <label for="gpa">GPA:</label>
        <input 
          type="number" 
          id="gpa" 
          v-model="newStudent.gpa" 
          required
          step="0.01"
          min="0"
          max="4"
          placeholder="Enter GPA (0-4)"
        >
      </div>
      
      <button type="submit">Add Student</button>
    </form>

    <div class="students-list">
      <h3>Registered Students</h3>
      <div v-if="students.length === 0" class="no-students">
        No students registered yet.
      </div>
      <table v-else>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>QR Code</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in students" :key="student.student_id">
            <td>{{ student.student_id }}</td>
            <td>{{ student.name }}</td>
            <td>
              <QRCodeVue3
                :value="student.student_id.toString()"
                :size="100"
                level="H"
              />
            </td>
            <td>
              <button @click="viewStudentDetails(student)" class="view-btn">View Details</button>
              <button @click="deleteStudent(student.student_id)" class="delete-btn">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Student Details Modal -->
    <div v-if="selectedStudent" class="modal">
      <div class="modal-content">
        <h3>Student Details</h3>
        <div class="student-details">
          <div class="qr-details">
            <QRCodeVue3
              :value="selectedStudent.student_id.toString()"
              :size="200"
              level="H"
            />
          </div>
          <div class="text-details">
            <p><strong>ID:</strong> {{ selectedStudent.student_id }}</p>
            <p><strong>Name:</strong> {{ selectedStudent.name }}</p>
            <p><strong>Email:</strong> {{ selectedStudent.email }}</p>
            <p><strong>Phone:</strong> {{ selectedStudent.phone }}</p>
            <p><strong>Course:</strong> {{ selectedStudent.course }}</p>
            <p><strong>GPA:</strong> {{ selectedStudent.gpa }}</p>
          </div>
        </div>
        <button @click="closeModal" class="close-btn">Close</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.student-management {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h2 {
  color: #42b983;
  margin-bottom: 2rem;
  text-align: center;
}

.student-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
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

button {
  background-color: #42b983;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.2s;
}

button:hover {
  background-color: #3aa876;
}

.students-list {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h3 {
  color: #42b983;
  margin-bottom: 1rem;
}

.no-students {
  text-align: center;
  color: #666;
  padding: 2rem;
  font-style: italic;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}

th, td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #f5f5f5;
  color: #666;
}

.delete-btn {
  background-color: #dc3545;
  padding: 0.5rem 1rem;
}

.delete-btn:hover {
  background-color: #c82333;
}

.view-btn {
  background-color: #4CAF50;
  margin-right: 0.5rem;
  padding: 0.5rem 1rem;
}

.view-btn:hover {
  background-color: #45a049;
}

/* Modal Styles */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  width: 90%;
}

.student-details p {
  margin: 0.5rem 0;
  font-size: 1.1rem;
  color: #333;
}

.student-details strong {
  color: #27285C;
  min-width: 80px;
  display: inline-block;
}

.close-btn {
  margin-top: 1.5rem;
  background-color: #666;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
}

.close-btn:hover {
  background-color: #555;
}

/* Adjust table styles */
td {
  padding: 1rem 0.75rem;
}

button {
  margin: 0 0.25rem;
}

/* QR Code styles */
.qr-details {
  display: flex;
  justify-content: center;
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
}

/* Adjust table styles for QR codes */
td {
  padding: 1rem 0.75rem;
  vertical-align: middle;
  text-align: center;
}

th {
  text-align: center;
}

/* Modal content adjustments */
.student-details {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
}

.text-details {
  width: 100%;
}

/* Make table responsive */
.students-list {
  overflow-x: auto;
}

table {
  min-width: 800px;
}

@media (max-width: 768px) {
  .modal-content {
    padding: 1rem;
  }
  
  .student-details {
    gap: 1rem;
  }
}
</style>
