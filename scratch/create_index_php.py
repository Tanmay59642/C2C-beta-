import re

with open('index.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace header section with <?php include __DIR__ . '/includes/header.php'; ?>
header_pattern = re.compile(r'<header class="sticky top-0 z-50.*?</header>', re.DOTALL)
if header_pattern.search(content):
    content = header_pattern.sub("<?php include __DIR__ . '/includes/header.php'; ?>", content)

# Replace footer section with <?php include __DIR__ . '/includes/footer.php'; ?>
footer_pattern = re.compile(r'<footer class="relative z-10.*?>.*?</footer>', re.DOTALL)
if footer_pattern.search(content):
    content = footer_pattern.sub("<?php include __DIR__ . '/includes/footer.php'; ?>", content)

# Prepend db.php inclusion at top
php_top = """<?php
require_once __DIR__ . '/db.php';
$metrics = getImpactMetrics();
$tickets = getCivicTickets();
$telemetry = getSensorTelemetry();
$user = $_SESSION['user'] ?? null;
?>
"""

content = php_top + content

with open('index.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Created index.php successfully!")
