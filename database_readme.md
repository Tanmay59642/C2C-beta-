# Campus2Community (C2C) - Database Documentation & Schema Reference

This repository contains the complete database setup for the **Campus2Community (C2C)** Sovereign Civic Innovation Platform.

## Quick Start

### 1. Initialize SQLite Database
Run the automated Python database setup script:
```bash
python setup_db.py
```
This will:
- Read and execute [`schema.sql`](file:///c:/Users/Shado/Desktop/campus2community/schema.sql)
- Initialize `c2c_database.db`
- Insert realistic seed records matching platform metrics (32,000+ solvers, Bhiwandi municipal tickets, Section 135 escrows, IoT telemetry, AICTE credit records).

---

## Database ER Diagram & Relationships

```
[ users ] (user_id)
    │
    ├── (reported_by_user_id) ──> [ civic_tickets ] (ticket_id)
    │                                  │
    │                                  ├──> [ ai_matches ] (ticket_id)
    │                                  ├──> [ escrow_contracts ] (ticket_id)
    │                                  └──> [ sensor_telemetry ] (ticket_id)
    │                                          │
    └── (student_user_id) ─────────> [ academic_credits ] (ticket_id)
```

---

## Data Models Summary

### 1. `users` Table
Stores registered platform users across Student, Govt/ULB, and CSR Personas.
- `user_id`: Primary Key (UUID v4)
- `aadhaar_number`: Unique 12-digit Aadhaar Number (e.g. `'9022 6343 3612'`)
- `role`: Enum (`'student'`, `'govt'`, `'csr'`)
- `institution_name`: College/ULB/Corporate Name
- `digilocker_verified`: DigiLocker SSO verification status (Boolean)

### 2. `civic_tickets` Table
Stores ground distress tickets reported by local wards, panchayats, and municipal commissioners.
- `ticket_id`: Primary Key (UUID v4)
- `ticket_code`: Sovereign Ticket Code (e.g. `'C2C-2026-W482'`)
- `category`: Ticket domain (`'water'`, `'roads'`, `'solar'`, `'health'`, `'infrastructure'`, `'agritech'`)
- `priority_level`: Urgency tier (`'critical'`, `'high'`, `'medium'`, `'low'`)
- `sla_hours`: Municipal SLA target (e.g., 24h, 48h, 72h)
- `affected_population`: Population density index

### 3. `ai_matches` Table
Links citizen distress vectors to university research labs and faculty mentors using NLP semantic scoring.
- `match_id`: Primary Key (UUID v4)
- `assigned_university`: Academic host (e.g. `'Veermata Jijabai Technological Institute (VJTI)'`)
- `match_confidence_score`: Semantic vector score (e.g., `98.40%`)
- `solution_architecture`: Recommended engineering spec

### 4. `escrow_contracts` Table
Manages Companies Act Section 135 Corporate CSR escrow funding & milestone payouts.
- `escrow_id`: Primary Key (UUID v4)
- `committed_amount_inr`: Committed capital (e.g., `₹4,50,000.00`)
- `section_135_ref`: Regulatory compliance ref code
- `escrow_status`: State (`'locked'`, `'partially_released'`, `'completed'`)

### 5. `sensor_telemetry` Table
Tracks real-time IoT sensor telemetry streaming from deployed community pilots.
- `telemetry_id`: Primary Key (UUID v4)
- `node_identifier`: IoT Node Code (e.g., `'NODE-BHIV-W482-01'`)
- `last_reading_value`: Water purity/pH/solar reading (e.g., `'0.012 mg/L (Safe)'`)

### 6. `academic_credits` Table
Stores AICTE Activity Points and provisional patent filings for student engineers.
- `credit_id`: Primary Key (UUID v4)
- `aicte_points_awarded`: Academic credit points (e.g., `45 Points`)
- `faculty_signoff_hash`: Cryptographic signature from faculty mentor

---

## Sample SQL Queries

### Query 1: Join Tickets with AI Matches & Escrow Balances
```sql
SELECT 
    t.ticket_code,
    t.title,
    t.priority_level,
    m.assigned_university,
    m.faculty_mentor_name,
    e.committed_amount_inr,
    e.disbursed_amount_inr
FROM civic_tickets t
JOIN ai_matches m ON t.ticket_id = m.ticket_id
JOIN escrow_contracts e ON t.ticket_id = e.ticket_id
WHERE t.status = 'pilot_active';
```

### Query 2: Fetch Student Academic Credit Transcripts
```sql
SELECT 
    u.full_name AS student_name,
    u.aadhaar_number,
    u.institution_name,
    t.ticket_code,
    c.aicte_points_awarded,
    c.provisional_patent_id
FROM academic_credits c
JOIN users u ON c.student_user_id = u.user_id
JOIN civic_tickets t ON c.ticket_id = t.ticket_id;
```
