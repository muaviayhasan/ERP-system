# Education ERP System Documentation

## 1. Project Overview

This is a complete **Education ERP System** designed for:

- Schools
- Colleges
- Universities

The system manages all academic, financial, HR, transport, hostel, library, and administrative operations in a single unified platform.

This document is created as a **handoff specification for programming agents and UI design agents**.

---

## 2. Main Goal

Build a scalable ERP system where institutions can:

- Manage students, teachers, staff
- Handle admissions and enrollment
- Track attendance in real time
- Conduct exams and publish results
- Manage fee collection and finance
- Handle HR and payroll
- Manage transport and hostel operations
- Generate reports and analytics
- Control system access via roles and permissions

---

## 3. System Name

Education ERP (Multi Institution System)

---

## 4. Design Theme

| Purpose | Color |
|--------|------|
| Primary | Indigo |
| Secondary | Emerald |
| Background | Light Gray |
| Card Background | White |
| Text Primary | Dark Gray |
| Success | Green |
| Warning | Orange |
| Danger | Red |

UI Style Guidelines:

- Modern SaaS admin dashboard
- Card based layout
- Sidebar navigation
- Top header with search and filters
- Modal and drawer based forms
- Fully responsive design

---

## 5. User Roles

### Super Admin
Full system access across all institutions

### Admin
Manages campus-level operations

### HOD (Head of Department)
Manages academic department operations

### Teacher
Manages classes, attendance, marks

### Student
Views academic progress, attendance, fees

### Parent
Monitors student performance and fees

### Accountant
Handles fees, income, expenses, ledger

### Librarian
Manages books and library operations

### Transport Manager
Manages routes and vehicles

### Hostel Warden
Manages hostel rooms and students

---

## 6. Core System Rules

### 6.1 Multi Campus System

Every record must include:

- campus_id

Data must be isolated per campus.

---

### 6.2 Academic System Rules

- Students belong to classes and programs
- Subjects are mapped to classes
- Exams determine final results
- GPA / CGPA is auto calculated

---

### 6.3 Attendance Rules

- Attendance is daily or lecture-based
- Minimum attendance required for exams
- Low attendance triggers alerts

---

### 6.4 Exam Rules

- Exams have multiple types:
  - Midterm
  - Final
  - Quiz
  - Assignments

- Marks contribute to GPA

---

### 6.5 Fee System Rules

- Fees are structured per student
- Supports:
  - Monthly
  - Semester
  - Annual plans
- Supports:
  - Discounts
  - Scholarships
  - Fines
  - Installments

---

### 6.6 Ledger System (Financial Core)

All financial transactions must pass through ledger:

Includes:
- Fee payments
- Expenses
- Salary
- Refunds
- Scholarships

Ledger is the **source of truth**

---

## 7. Main Modules

## 7.1 Academic Module

Handles:
- Students
- Teachers
- Classes
- Subjects
- Programs
- Semesters

---

## 7.2 Admission Module

- Student applications
- Enrollment process
- Admission approval workflow

---

## 7.3 Attendance Module

- Student attendance
- Teacher attendance
- Staff attendance
- Attendance reports

---

## 7.4 Exam Module

- Exam scheduling
- Marks entry
- Result generation
- GPA / CGPA calculation

---

## 7.5 Finance Module

- Fee management
- Income
- Expenses
- Ledger system
- Scholarships
- Refunds

---

## 7.6 HR Module

- Staff management
- Salary management
- Payroll system
- Leave system

---

## 7.7 Transport Module

- Vehicles
- Routes
- Driver assignment
- Student transport allocation

---

## 7.8 Hostel Module

- Hostel management
- Rooms and beds
- Student allocation
- Hostel fees

---

## 7.9 Library Module

- Books
- Issue/return system
- Fines
- Inventory

---

## 7.10 Reports Module

- Academic reports
- Financial reports
- Attendance reports
- Admission reports
- HR reports

---

## 7.11 Notification Module

- Email notifications
- SMS notifications
- Push notifications
- System alerts

---

## 7.12 Settings Module

- Academic rules
- Fee rules
- Exam rules
- Security settings
- Integration settings
- Localization settings

---

## 8. Core Workflows

## 8.1 Admission Workflow

1. Student applies
2. Admin reviews application
3. Approval or rejection
4. Student enrolled in class
5. Fee structure assigned

---

## 8.2 Attendance Workflow

1. Teacher marks attendance
2. System updates records
3. Alerts sent if low attendance
4. Attendance affects exam eligibility

---

## 8.3 Exam Workflow

1. Exam created
2. Marks entered
3. GPA calculated
4. Results published
5. Notifications sent

---

## 8.4 Fee Workflow

1. Fee assigned to student
2. Student pays fee
3. Ledger updated
4. Receipt generated
5. Pending fees tracked

---

## 8.5 Salary Workflow

1. Salary generated
2. Attendance adjustment applied
3. Deductions added
4. Payment processed
5. Ledger updated

---

## 9. Database Structure (Core Entities)

- users
- students
- teachers
- staff
- classes
- subjects
- attendance
- exams
- results
- fees
- payments
- income
- expenses
- ledger_entries
- campuses
- roles
- permissions

---

## 10. System Requirements

### Performance
- Must support large scale institutions
- Optimized queries
- Pagination required

### Security
- Role based access control
- Encrypted sensitive data
- Audit logging enabled

### Reliability
- Transaction based financial operations
- Rollback on failure
- Consistent ledger system

---

## 11. Screen List

- Dashboard
- Admission Screen
- Student Management
- Teacher Management
- Attendance Screen
- Exam Screen
- Result Screen
- Fee Management
- Income Screen
- Expense Screen
- Ledger Screen
- Reports Screen
- Settings Screen
- Notifications Screen
- Hostel Screen
- Transport Screen
- Library Screen
- User Management
- Role Management
- Profile Screen

---

## 12. Development Rules

- No business logic in controllers
- Use service layer for logic
- Use event-driven architecture
- Use modular structure
- Keep modules independent
- Use API-first approach

---

## 13. Integration Rules

System should support future:

- Mobile apps
- Parent portal
- Student portal
- Teacher portal
- AI analytics module
- SMS / WhatsApp integration

---

## 14. Final Notes

This is a **full enterprise-level Education ERP system**.

The system must be:

- Modular
- Scalable
- Secure
- Multi-campus ready
- API driven
- Production ready

All modules must work together through shared services like:

- Ledger system
- Notification system
- Role system
- Activity logs
- Settings engine

---

## 15. Implementation Notes — Security Model (Backend)

This appendix documents how the §10 security requirements are enforced in the
Laravel backend. See `docs/SCHEMA.md` for the data model and `README.md` for the
full API reference.

### 15.1 Authentication
- Laravel **Sanctum** bearer tokens. All endpoints except `POST /api/v1/auth/login`
  and `/register` require `Authorization: Bearer <token>`.
- Unauthenticated API requests return `401` with a JSON envelope.
- Login/register are rate-limited (`throttle:10,1`); authenticated traffic is
  capped at `throttle:120,1` to blunt brute-force and abuse.

### 15.2 Authorization (Role-Based Access Control)
- **Spatie Laravel Permission**. Permissions follow `{resource}.{action}` where
  action ∈ `view|create|edit|delete` (280 permissions across 70 resources).
- 10 roles: `super-admin`, `admin`, `hod`, `teacher`, `accountant`, `librarian`,
  `transport-manager`, `hostel-warden`, `student`, `parent`.
- The `EnsureApiPermission` middleware derives the required ability from the
  route name and is **fail-closed**: if the user lacks the ability (or it can't
  be determined) the request is denied `403`.
- Authorization runs **before** route-model binding (middleware priority), so a
  denied request never reveals whether a record exists (403, not 404).
- `super-admin` is granted everything via a `Gate::before` bypass.

### 15.3 Encrypted Sensitive Data
- Integration credentials (API keys/secrets) are stored **encrypted at rest**
  (`encrypted:array` cast) — never plaintext in the database.
- User `two_factor_secret` is encrypted; `password` is hashed; both are hidden
  from serialization.
- API responses **mask** secrets: integration credentials are returned as
  `********`, and settings whose key contains `password/secret/token/api_key/…`
  are masked in `SettingResource`.

### 15.4 Audit Logging
- The `AuditApiActions` middleware records every successful mutating request
  (POST/PUT/PATCH/DELETE → 2xx) to `activity_logs`: actor, role, module, action,
  IP, device, and a **sanitized** payload (passwords/secrets/tokens redacted).

### 15.5 Input & Mass-Assignment Safety
- Every write goes through a FormRequest; controllers persist only
  `$request->validated()` data (no `$request->all()` mass assignment).
- Foreign keys are validated with `exists:` rules; enums with `in:` rules.

### 15.6 Transport / CORS
- CORS is scoped to `api/*`; token auth means no cookie credentials are shared
  (`supports_credentials = false`). Restrict `allowed_origins` per environment
  before production.

> Validation: the RBAC matrix, secret masking, encryption-at-rest, audit
> logging, and 401/403 behavior are exercised by isolated-process integration
> checks against the booted HTTP kernel.

---
## 1\. System Core Screens

1.  Dashboard Screen
2.  Institute Profile Screen
3.  Campus Management Screen
4.  Academic Year Management Screen

* * *

## 2\. Academic Structure Management

5.  Class Management Screen
6.  Section Management Screen
7.  Department Management Screen
8.  Program Management Screen
9.  Semester Management Screen
10.  Subject Management Screen
11.  Course Management Screen
12.  Batch Management Screen

* * *

## 3\. Admission & Student Management

13.  Student Admission Screen
14.  Student Management Screen
15.  Student Profile Screen
16.  Student Promotion Screen
17.  Student Documents Screen

* * *

## 4\. Guardian & Relationship Management

18.  Guardian Management Screen

* * *

## 5\. Teacher & Staff Management

19.  Teacher Management Screen
20.  Teacher Profile Screen
21.  Teacher Assignment Screen
22.  Staff Management Screen

* * *

## 6\. Academic Operations

23.  Timetable Management Screen
24.  Attendance Management Screen
25.  Exam Management Screen _(optional future extension if needed)_
26.  Result Management Screen _(optional future extension if needed)_

* * *

## 7\. Fee & Financial System (Core ERP Engine)

27.  Fee Category Management Screen
28.  Fee Structure Management Screen
29.  Fee Plan Management Screen
30.  Student Fee Assignment Screen
31.  Fee Collection Screen
32.  Fee Receipt Screen
33.  Pending Fee Management Screen
34.  Installment Management Screen
35.  Student Fee Ledger Screen

* * *

## 8\. Scholarship & Financial Aid

36.  Scholarship Management Screen
37.  Scholarship Approval Screen

* * *

## 9\. Fine & Penalty System

38.  Fine Management Screen

* * *

## 10\. Refund & Adjustment System

39.  Refund Management Screen

* * *

## 11\. Reports & Financial Intelligence

40.  Reports Dashboard Screen
41.  Fee Collection Report Screen
42.  Attendance Report Screen
43.  Academic Performance Report Screen

* * *

## 12\. HR System (Teachers + Staff)

44.  Teacher Profile Screen (already included above)
45.  Staff Payroll Screen _(can be added later if needed)_
46.  Salary Management Screen _(optional extension)_

* * *

## 13\. Communication & Notifications

47.  Notification Management Screen
48.  Announcement / Notice Board Screen
49.  Messaging Screen (Parent, Student, Teacher communication)

* * *

## 14\. Library System (Optional Module)

50.  Book Management Screen
51.  Book Issue / Return Screen
52.  Library Fine Screen

* * *

## 15\. Transport System (Optional Module)

53.  Transport Management Screen
54.  Route Management Screen
55.  Vehicle Assignment Screen

* * *

## 16\. Hostel Management (Optional Module)

56.  Hostel Management Screen
57.  Room Management Screen
58.  Hostel Fee Screen

* * *

## 17\. System Administration

59.  User Management Screen
60.  Role & Permission Management Screen
61.  Activity Logs Screen
62.  Settings Screen
63.  Login Screen
64.  Forgot Password Screen