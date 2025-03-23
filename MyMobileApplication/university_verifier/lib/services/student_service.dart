import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/student.dart';

class StudentService {
  // Update with your local Vue.js project path
  static const String baseUrl = 'http://localhost/mobileApp/myWebVueProject/api';

  // Get all students
  Future<List<Student>> getAllStudents() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/students.php'),
      );

      if (response.statusCode == 200) {
        final List<dynamic> studentsJson = json.decode(response.body);
        return studentsJson.map((json) => Student.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      print('Error getting all students: $e');
      return [];
    }
  }

  Future<Student?> verifyStudent(String id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/students.php'),
      );

      if (response.statusCode == 200) {
        final List<dynamic> students = json.decode(response.body);
        final student = students.firstWhere(
          (student) => student['student_id'].toString() == id,
          orElse: () => null,
        );

        if (student != null) {
          return Student.fromJson(student);
        }
      }
      return null;
    } catch (e) {
      print('Error verifying student: $e');
      return null;
    }
  }

  Future<Student?> verifyStudentByQR(String qrData) async {
    try {
      // Assuming the QR code contains the student ID
      return await verifyStudent(qrData);
    } catch (e) {
      print('Error verifying student by QR: $e');
      return null;
    }
  }
} 