<?php
/**
 * Database Connection Test Script for Swastik Laghubitta
 * This script tests the database connection and displays status
 * 
 * Usage: Run this file in browser after starting XAMPP
 */

// Include database connection
include 'db.php';

$connection_status = "✅ Connected successfully!";
$connection_class = "success";

if ($conn->connect_error) {
    $connection_status = "❌ Connection failed: " . $conn->connect_error;
    $connection_class = "error";
}

// Get database info
$db_info = [
    'Server' => $conn->server_info,
    'Database' => $conn->host_info,
    'Character Set' => $conn->character_set_name(),
    'Protocol Version' => $conn->protocol_version,
];

// Count records in tables
$contacts_count = 0;
$loans_count = 0;

$result = $conn->query("SELECT COUNT(*) as count FROM contacts");
if ($result) {
    $contacts_count = $result->fetch_assoc()['count'];
}

$result = $conn->query("SELECT COUNT(*) as count FROM loans");
if ($result) {
    $loans_count = $result->fetch_assoc()['count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>कनेक्सन परीक्षण - स्वस्तिक लघुवित्त</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #1e3c72;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .status-icon {
            text-align: center;
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .connection-status {
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        .info-table th, .info-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .info-table th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 500;
        }
        .info-table tr:hover {
            background: #f8f9fa;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1.5rem 0;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .stat-card .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 1.5rem;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #1e3c72;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2a5298;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-setup {
            background: #28a745;
        }
        .btn-setup:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-icon">🔗</div>
        <h1>कनेक्सन परीक्षण</h1>
        
        <div class="connection-status <?php echo $connection_class; ?>">
            <?php echo $connection_status; ?>
        </div>
        
        <?php if ($connection_class === 'success'): ?>
        
        <table class="info-table">
            <tr>
                <th>सेटिङ</th>
                <th>मान</th>
            </tr>
            <tr>
                <td>MySQL संस्करण</td>
                <td><?php echo htmlspecialchars($db_info['Server']); ?></td>
            </tr>
            <tr>
                <td>क्यारेक्टर सेट</td>
                <td><?php echo htmlspecialchars($db_info['Character Set']); ?></td>
            </tr>
            <tr>
                <td>प्रोटोकल संस्करण</td>
                <td><?php echo htmlspecialchars($db_info['Protocol Version']); ?></td>
            </tr>
        </table>
        
        <div class="stats">
            <div class="stat-card">
                <div class="number"><?php echo $contacts_count; ?></div>
                <div class="label">कुल सम्पर्क रेकर्ड</div>
            </div>
            <div class="stat-card">
                <div class="number"><?php echo $loans_count; ?></div>
                <div class="label">कुल ऋण आवेदन</div>
            </div>
        </div>
        
        <div class="btn-group">
            <a href="index.html" class="btn">🏠 होम पेज</a>
            <a href="db_setup.php" class="btn btn-setup">🗄️ डेटाबेस सेटअप</a>
            <a href="dummy_data.php" class="btn btn-secondary">➕ डमी डाटा</a>
            <a href="http://localhost/phpmyadmin/index.php?route=/database/structure&db=swastiklbs" target="_blank" class="btn" style="background: #f39c12;">📊 phpMyAdmin</a>
        </div>
        
        <?php else: ?>
        
        <div class="btn-group">
            <a href="index.html" class="btn">🏠 होम पेज</a>
            <a href="db_setup.php" class="btn btn-setup">🗄️ डेटाबेस सेटअप गर्नुहोस्</a>
        </div>
        
        <?php endif; ?>
    </div>
</body>
</html>
