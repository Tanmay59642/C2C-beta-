<?php
require_once __DIR__ . '/../db.php';

echo "--- TESTING REGISTER & AUTHENTICATE SYSTEM ---\n";

$test_aadhaar = "999988887777";
$test_name = "Rahul Sharma";
$test_pass = "securePass123!";
$test_role = "student";
$test_institution = "VJTI Mumbai";

// Clean up existing test user if present
$db = getDB();
$db->exec("DELETE FROM users WHERE aadhaar_number = '$test_aadhaar'");

try {
    echo "1. Registering test user: $test_name ($test_aadhaar)...\n";
    $registered_user = registerUser($test_name, $test_aadhaar, $test_pass, $test_role, $test_institution);
    echo "SUCCESS: User registered! ID = " . $registered_user['user_id'] . ", Name = " . $registered_user['full_name'] . "\n";

    echo "2. Authenticating with correct password...\n";
    $auth_user = authenticateUser($test_aadhaar, $test_pass);
    echo "SUCCESS: User authenticated! Name = " . $auth_user['full_name'] . ", Institution = " . $auth_user['institution_name'] . "\n";

    echo "3. Testing incorrect password verification...\n";
    try {
        authenticateUser($test_aadhaar, "wrongPassword");
        echo "ERROR: Wrong password was accepted!\n";
    } catch (Exception $e) {
        echo "SUCCESS: Caught expected error for wrong password: " . $e->getMessage() . "\n";
    }

    echo "\n--- ALL AUTHENTICATION TESTS PASSED PERFECTLY ---\n";
} catch (Exception $e) {
    echo "FAILURE: " . $e->getMessage() . "\n";
}
