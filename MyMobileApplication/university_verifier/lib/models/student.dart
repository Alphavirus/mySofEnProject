class Student {
  final String id;
  final String name;
  final String department;
  final String year;
  final String email;
  final String photoUrl;
  final bool isActive;
  final String phone;
  final double gpa;

  Student({
    required this.id,
    required this.name,
    required this.department,
    this.year = '',
    required this.email,
    required this.photoUrl,
    this.isActive = true,
    this.phone = '',
    this.gpa = 0.0,
  });

  factory Student.fromJson(Map<String, dynamic> json) {
    return Student(
      id: json['student_id'].toString(),
      name: json['name'] ?? '',
      department: json['course'] ?? '',
      email: json['email'] ?? '',
      photoUrl: 'https://ui-avatars.com/api/?name=${json['name'] ?? ''}',
      phone: json['phone'] ?? '',
      gpa: double.tryParse(json['gpa'].toString()) ?? 0.0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'student_id': id,
      'name': name,
      'course': department,
      'email': email,
      'phone': phone,
      'gpa': gpa,
    };
  }
} 