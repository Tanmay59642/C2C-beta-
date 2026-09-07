import sqlite3

conn = sqlite3.connect('c2c_database.db')
cursor = conn.cursor()
cursor.execute("DELETE FROM users WHERE aadhaar_number = '111122223333';")
conn.commit()
print(f"Deleted {cursor.rowcount} rows.")
conn.close()
