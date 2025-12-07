erDiagram
    organization_units ||--o{ departments : "contains"
    organization_units {
        int id PK
        string name "Contoh: Jawa Barat, Sumatera Utara"
        string type "Area/Region/Branch"
        int parent_id FK "Null untuk level tertinggi"
    }

    departments ||--o{ positions : "has"
    departments {
        int id PK
        string name
        text description
        int organization_unit_id FK "Menggantikan konsep area"
        int head_id FK "employee_id dari Department Head"
    }

    positions ||--o{ employees : "filled_by"
    positions {
        int id PK
        string title
        text description
        decimal base_salary
        string level "Junior/Middle/Senior"
        int department_id FK "Relasi ke departemen induk"
    }

    employees ||--o{ employee_families : "has"
    employees ||--o{ documents : "owns"
    employees ||--o{ attendances : "records"
    employees ||--o{ leave_requests : "submits"
    employees ||--o{ payrolls : "receives"
    employees ||--o{ performance_reviews : "undergoes"
    employees ||--o{ employee_histories : "has" 
    employees {
        int id PK
        string nik "Nomor Induk Karyawan"
        string first_name
        string last_name
        date date_of_birth
        enum gender
        date hire_date
        date resignation_date "Tanggal pengajuan resign"
        date termination_date "Last working day"
        string employment_status "active|on_probation|on_notice|resigned|terminated"
        bool is_blacklisted "Pernah melanggar berat?"
        string email
        string phone
        text address
        int position_id FK
        int supervisor_id FK "Self-referential ke employee lain"
    }

    employees ||--|| users : "may_have" 
    users {
        int id PK
        string username "Unique"
        string password_hash
        bool is_active
        datetime last_login
        datetime password_last_changed
        int failed_login_attempts
        datetime lockout_until
        int employee_id FK "Unique (1:1 dengan employee)"
        int role_id FK
    }

    roles ||--o{ users : "assigned_to"
    roles ||--o{ role_permissions : "has"
    roles {
        int id PK
        string name "e.g., Admin, HR Staff, Manager"
        text description
    }

    permissions ||--o{ role_permissions : "granted_to"
    permissions {
        int id PK
        string name "e.g., attendance.manage, payroll.view"
        text description
    }

    role_permissions {
        int role_id PK,FK
        int permission_id PK,FK
    }

    leave_types ||--o{ leave_requests : "type_of"
    leave_types ||--o{ leave_balances : "allocated_to"
    leave_types {
        int id PK
        string name "e.g., Annual, Sick, Maternity"
        int default_days
        bool requires_document
    }

    leave_requests {
        int id PK
        date start_date
        date end_date
        float total_days
        enum status "draft|pending|approved|rejected|cancelled"
        text reason
        text rejection_note
        int employee_id FK
        int leave_type_id FK
        int approved_by FK "employee_id of approver"
        int backup_approver_id FK "Jika approver utama unavailable"
    }

    leave_balances {
        int id PK
        int year
        int balance
        int initial_balance
        int employee_id FK
        int leave_type_id FK
    }

    attendances {
        int id PK
        date date
        time clock_in
        time clock_out
        enum status "present/late/absent"
        text note
        int employee_id FK
    }

    payrolls {
        int id PK
        date period_start
        date period_end
        decimal basic_salary
        decimal allowance_transport
        decimal allowance_meal
        decimal bpjs_kesehatan
        decimal bpjs_ketenagakerjaan
        decimal pph21
        decimal net_salary
        enum status "calculated|approved|paid|rejected"
        text rejection_note
        int employee_id FK
        int approved_by FK "user_id"
    }

    performance_reviews {
        int id PK
        string review_period "e.g., Q1-2025"
        float rating "1-5 scale"
        text comments
        int employee_id FK
        int reviewer_id FK "employee_id of reviewer"
    }

    training_programs ||--o{ training_enrollments : "has"
    training_programs {
        int id PK
        string title
        text description
        date start_date
        date end_date
        string location
    }

    training_enrollments {
        int id PK
        enum status "registered/completed/failed"
        text certificate_url
        int employee_id FK
        int training_id FK
    }

    employee_families {
        int id PK
        string name
        enum relation "spouse/child/parent"
        date date_of_birth
        int employee_id FK
    }

    documents {
        int id PK
        string type "KTP, Ijazah, dll"
        string file_path
        datetime uploaded_at
        int employee_id FK
        int uploaded_by FK "user_id"
    }

    employee_histories {
        int id PK
        datetime effective_date
        string change_type "position|department|salary|status"
        text old_value
        text new_value
        text reason
        int changed_by FK "user_id"
        int employee_id FK
    }

    audit_logs {
        int id PK
        datetime event_time
        string event_type "LOGIN|UPDATE|DELETE|APPROVE"
        string module "user|employee|payroll|leave"
        text description
        string ip_address
        string user_agent
        int user_id FK "Null jika sistem otomatis"
    }