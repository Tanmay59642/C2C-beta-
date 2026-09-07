import sqlite3
import re
import os

DB_FILENAME = "c2c_database.db"
SCHEMA_FILENAME = "schema.sql"

def setup_database():
    if os.path.exists(DB_FILENAME):
        os.remove(DB_FILENAME)

    print(f"Connecting to SQLite database: {DB_FILENAME}...")
    conn = sqlite3.connect(DB_FILENAME)
    cursor = conn.cursor()

    print(f"Reading schema from {SCHEMA_FILENAME}...")
    with open(SCHEMA_FILENAME, "r", encoding="utf-8") as f:
        sql = f.read()

    # Convert MySQL-specific statements into SQLite-compatible SQL
    sql = re.sub(r'CREATE DATABASE IF NOT EXISTS.*?;', '', sql, flags=re.IGNORECASE)
    sql = re.sub(r'USE `.*?`;', '', sql, flags=re.IGNORECASE)

    # Convert INSERT IGNORE or ON DUPLICATE KEY to INSERT OR IGNORE for SQLite
    sql = sql.replace('INSERT IGNORE INTO', 'INSERT OR IGNORE INTO')
    sql = sql.replace('INSERT INTO', 'INSERT OR IGNORE INTO')

    cursor.executescript(sql)
    conn.commit()
    print("SQLite database schema created & seeded successfully!")

    # Summary Report
    print("\n--- DATABASE SCHEMA SUMMARY ---")
    tables = ['users', 'civic_tickets', 'ai_matches', 'escrow_contracts', 'sensor_telemetry', 'academic_credits']
    for t in tables:
        cursor.execute(f"SELECT COUNT(*) FROM {t};")
        cnt = cursor.fetchone()[0]
        print(f"Table '{t}': {cnt} records")

    conn.close()

if __name__ == "__main__":
    setup_database()
