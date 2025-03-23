import 'package:flutter/material.dart';
import '../models/student.dart';

class StudentDetailsScreen extends StatelessWidget {
  final Student student;

  const StudentDetailsScreen({Key? key, required this.student}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Student Details'),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: CircleAvatar(
                radius: 50,
                backgroundImage: NetworkImage(student.photoUrl),
              ),
            ),
            const SizedBox(height: 24),
            _buildInfoCard(
              title: 'Personal Information',
              children: [
                _buildInfoRow('Name', student.name),
                _buildInfoRow('ID', student.id),
                _buildInfoRow('Email', student.email),
                _buildInfoRow('Phone', student.phone),
              ],
            ),
            const SizedBox(height: 16),
            _buildInfoCard(
              title: 'Academic Information',
              children: [
                _buildInfoRow('Course', student.department),
                _buildInfoRow('GPA', student.gpa.toStringAsFixed(2)),
                _buildInfoRow(
                  'Status',
                  student.isActive ? 'Active' : 'Inactive',
                  valueColor: student.isActive ? Colors.green : Colors.red,
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoCard({
    required String title,
    required List<Widget> children,
  }) {
    return Card(
      elevation: 4,
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            const Divider(),
            ...children,
          ],
        ),
      ),
    );
  }

  Widget _buildInfoRow(String label, String value, {Color? valueColor}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: const TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w500,
            ),
          ),
          Text(
            value,
            style: TextStyle(
              fontSize: 16,
              color: valueColor,
            ),
          ),
        ],
      ),
    );
  }
} 