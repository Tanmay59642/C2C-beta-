import sqlite3

conn = sqlite3.connect("c2c_database.db")
cursor = conn.cursor()

print("--- VERIFYING USERS TABLE SCHEMA ---")
cursor.execute("PRAGMA table_info(users);")
columns = cursor.fetchall()
for col in columns:
    print(f"Column: {col[1]} ({col[2]})")

print("\n--- CHECKING USERS RECORDS ---")
cursor.execute("SELECT user_id, aadhaar_number, full_name, role, email, institution_name, password FROM users;")
rows = cursor.fetchall()
for r in rows:
    print(f"User: {r[2]} | Aadhaar: {r[1]} | Role: {r[3]} | Has Password: {bool(r[6])}")

conn.close()
