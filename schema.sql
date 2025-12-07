-- Organization Units
CREATE TABLE organization_units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL COMMENT 'Area/Region/Branch',
    parent_id INT,
    FOREIGN KEY (parent_id) REFERENCES organization_units(id)
);

-- Departments
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    organization_unit_id INT NOT NULL,
    head_id INT,
    FOREIGN KEY (organization_unit_id) REFERENCES organization_units(id)
);

-- Positions
CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    base_salary DECIMAL(15, 2) NOT NULL,
    level VARCHAR(50) NOT NULL COMMENT 'Junior/Middle/Senior',
    department_id INT NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Employees
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nomor Induk Karyawan',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100),
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    hire_date DATE NOT NULL,
    resignation_date DATE COMMENT 'Tanggal pengajuan resign',
    termination_date DATE COMMENT 'Last working day',
    employment_status ENUM('active', 'on_probation', 'on_notice', 'resigned', 'terminated') NOT NULL,
    is_blacklisted BOOLEAN DEFAULT FALSE COMMENT 'Pernah melanggar berat?',
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    position_id INT NOT NULL,
    supervisor_id INT COMMENT 'Self-referential ke employee lain',
    FOREIGN KEY (position_id) REFERENCES positions(id),
    FOREIGN KEY (supervisor_id) REFERENCES employees(id)
);

-- Add foreign key for department head (circular dependency resolved by adding after table creation)
ALTER TABLE departments ADD CONSTRAINT fk_department_head FOREIGN KEY (head_id) REFERENCES employees(id);

-- Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login DATETIME,
    password_last_changed DATETIME,
    failed_login_attempts INT DEFAULT 0,
    lockout_until DATETIME,
    employee_id INT NOT NULL UNIQUE,
    role_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL COMMENT 'e.g., Admin, HR Staff, Manager',
    description TEXT
);

ALTER TABLE users ADD CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES roles(id);

-- Permissions
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'e.g., attendance.manage, payroll.view',
    description TEXT
);

-- Role Permissions
CREATE TABLE role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id)
);

-- Leave Types
CREATE TABLE leave_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'e.g., Annual, Sick, Maternity',
    default_days INT NOT NULL,
    requires_document BOOLEAN DEFAULT FALSE
);

-- Leave Requests
CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days FLOAT NOT NULL,
    status ENUM('draft', 'pending', 'approved', 'rejected', 'cancelled') NOT NULL,
    reason TEXT,
    rejection_note TEXT,
    employee_id INT NOT NULL,
    leave_type_id INT NOT NULL,
    approved_by INT COMMENT 'employee_id of approver',
    backup_approver_id INT COMMENT 'Jika approver utama unavailable',
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (leave_type_id) REFERENCES leave_types(id),
    FOREIGN KEY (approved_by) REFERENCES employees(id),
    FOREIGN KEY (backup_approver_id) REFERENCES employees(id)
);

-- Leave Balances
CREATE TABLE leave_balances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year INT NOT NULL,
    balance INT NOT NULL,
    initial_balance INT NOT NULL,
    employee_id INT NOT NULL,
    leave_type_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (leave_type_id) REFERENCES leave_types(id)
);

-- Attendances
CREATE TABLE attendances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    clock_in TIME,
    clock_out TIME,
    status ENUM('present', 'late', 'absent') NOT NULL,
    note TEXT,
    employee_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Payrolls
CREATE TABLE payrolls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    basic_salary DECIMAL(15, 2) NOT NULL,
    allowance_transport DECIMAL(15, 2) NOT NULL,
    allowance_meal DECIMAL(15, 2) NOT NULL,
    bpjs_kesehatan DECIMAL(15, 2) NOT NULL,
    bpjs_ketenagakerjaan DECIMAL(15, 2) NOT NULL,
    pph21 DECIMAL(15, 2) NOT NULL,
    net_salary DECIMAL(15, 2) NOT NULL,
    status ENUM('calculated', 'approved', 'paid', 'rejected') NOT NULL,
    rejection_note TEXT,
    employee_id INT NOT NULL,
    approved_by INT COMMENT 'user_id',
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Performance Reviews
CREATE TABLE performance_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_period VARCHAR(50) NOT NULL COMMENT 'e.g., Q1-2025',
    rating FLOAT NOT NULL COMMENT '1-5 scale',
    comments TEXT,
    employee_id INT NOT NULL,
    reviewer_id INT NOT NULL COMMENT 'employee_id of reviewer',
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (reviewer_id) REFERENCES employees(id)
);

-- Training Programs
CREATE TABLE training_programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    location VARCHAR(255)
);

-- Training Enrollments
CREATE TABLE training_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status ENUM('registered', 'completed', 'failed') NOT NULL,
    certificate_url TEXT,
    employee_id INT NOT NULL,
    training_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (training_id) REFERENCES training_programs(id)
);

-- Employee Families
CREATE TABLE employee_families (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    relation ENUM('spouse', 'child', 'parent') NOT NULL,
    date_of_birth DATE NOT NULL,
    employee_id INT NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Documents
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL COMMENT 'KTP, Ijazah, dll',
    file_path VARCHAR(255) NOT NULL,
    uploaded_at DATETIME NOT NULL,
    employee_id INT NOT NULL,
    uploaded_by INT NOT NULL COMMENT 'user_id',
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Employee Histories
CREATE TABLE employee_histories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    effective_date DATETIME NOT NULL,
    change_type ENUM('position', 'department', 'salary', 'status') NOT NULL,
    old_value TEXT,
    new_value TEXT,
    reason TEXT,
    changed_by INT NOT NULL COMMENT 'user_id',
    employee_id INT NOT NULL,
    FOREIGN KEY (changed_by) REFERENCES users(id),
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Audit Logs
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_time DATETIME NOT NULL,
    event_type ENUM('LOGIN', 'UPDATE', 'DELETE', 'APPROVE') NOT NULL,
    module ENUM('user', 'employee', 'payroll', 'leave') NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    user_id INT COMMENT 'Null jika sistem otomatis',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Devices
CREATE TABLE devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sn VARCHAR(50) NOT NULL UNIQUE,
    ip_address VARCHAR(50),
    last_activity DATETIME,
    options TEXT,
    push_version VARCHAR(50),
    dev_language VARCHAR(50)
);

-- Device Commands
CREATE TABLE device_commands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_sn VARCHAR(50) NOT NULL,
    content TEXT NOT NULL,
    commit_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    trans_time DATETIME,
    return_value VARCHAR(255),
    FOREIGN KEY (device_sn) REFERENCES devices(sn)
);
