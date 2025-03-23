import 'package:flutter/material.dart';
import '../models/student.dart';
import '../services/student_service.dart';
import 'student_details_screen.dart';

class AllStudentsScreen extends StatefulWidget {
  const AllStudentsScreen({Key? key}) : super(key: key);

  @override
  State<AllStudentsScreen> createState() => _AllStudentsScreenState();
}

class _AllStudentsScreenState extends State<AllStudentsScreen> {
  final StudentService _studentService = StudentService();
  List<Student> _allStudents = [];
  List<Student> _filteredStudents = [];
  bool _isLoading = true;
  String _searchQuery = '';

  @override
  void initState() {
    super.initState();
    _loadStudents();
  }

  Future<void> _loadStudents() async {
    setState(() => _isLoading = true);
    try {
      final students = await _studentService.getAllStudents();
      setState(() {
        _allStudents = students;
        _filteredStudents = students;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Error loading students'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  void _filterStudents(String query) {
    setState(() {
      _searchQuery = query;
      _filteredStudents = _allStudents.where((student) {
        final nameLower = student.name.toLowerCase();
        final idLower = student.id.toLowerCase();
        final searchLower = query.toLowerCase();
        return nameLower.contains(searchLower) || idLower.contains(searchLower);
      }).toList();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('All Students'),
        centerTitle: true,
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: TextField(
              onChanged: _filterStudents,
              decoration: InputDecoration(
                labelText: 'Search by name or ID',
                prefixIcon: const Icon(Icons.search),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
                contentPadding: const EdgeInsets.symmetric(horizontal: 16),
              ),
            ),
          ),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _filteredStudents.isEmpty
                    ? Center(
                        child: Text(
                          _searchQuery.isEmpty
                              ? 'No students found'
                              : 'No matching students found',
                          style: const TextStyle(fontSize: 16),
                        ),
                      )
                    : RefreshIndicator(
                        onRefresh: _loadStudents,
                        child: ListView.builder(
                          itemCount: _filteredStudents.length,
                          itemBuilder: (context, index) {
                            final student = _filteredStudents[index];
                            return Card(
                              margin: const EdgeInsets.symmetric(
                                horizontal: 8,
                                vertical: 4,
                              ),
                              child: ListTile(
                                leading: CircleAvatar(
                                  backgroundImage: NetworkImage(student.photoUrl),
                                ),
                                title: Text(student.name),
                                subtitle: Text('ID: ${student.id}'),
                                trailing: Text(
                                  'GPA: ${student.gpa.toStringAsFixed(2)}',
                                  style: const TextStyle(
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                                onTap: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) =>
                                          StudentDetailsScreen(student: student),
                                    ),
                                  );
                                },
                              ),
                            );
                          },
                        ),
                      ),
          ),
        ],
      ),
    );
  }
} 