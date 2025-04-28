class User {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String role;
  final int? branchId;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    required this.role,
    this.branchId,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      role: json['role'],
      branchId: json['branch_id'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'role': role,
      'branch_id': branchId,
    };
  }
} 