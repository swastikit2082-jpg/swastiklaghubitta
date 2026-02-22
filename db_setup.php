<?php
/**
 * Database Setup Script for Swastik Laghubitta
 * This script creates the database, tables, and optionally inserts dummy data
 * 
 * Usage: Run this file in browser after starting XAMPP
 */

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swastiklbs";

// Create connection without database
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

$message = "";
$success = true;

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    $message .= "✅ Database '$dbname' created successfully or already exists<br>";
} else {
    $message .= "❌ Error creating database: " . $conn->error . "<br>";
    $success = false;
}

// Select the database
$conn->select_db($dbname);

// Set charset
$conn->set_charset("utf8mb4");

// Create contacts table
$sql_contacts = "CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(50) NOT NULL,
    subject VARCHAR(100) DEFAULT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_contacts) === TRUE) {
    $message .= "✅ Contacts table created successfully<br>";
} else {
    $message .= "❌ Error creating contacts table: " . $conn->error . "<br>";
    $success = false;
}

// Create loans table
$sql_loans = "CREATE TABLE IF NOT EXISTS loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    loan_type VARCHAR(100) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_loans) === TRUE) {
    $message .= "✅ Loans table created successfully<br>";
} else {
    $message .= "❌ Error creating loans table: " . $conn->error . "<br>";
    $success = false;
}

// Create vacancies table
$sql_vacancies = "CREATE TABLE IF NOT EXISTS vacancies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_en VARCHAR(255),
    location VARCHAR(255),
    job_type VARCHAR(50),
    salary_range VARCHAR(100),
    qualification VARCHAR(255),
    experience VARCHAR(100),
    description TEXT,
    status ENUM('active', 'inactive', 'closed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_job_type (job_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql_vacancies) === TRUE) {
    $message .= "✅ Vacancies table created successfully<br>";
} else {
    $message .= "❌ Error creating vacancies table: " . $conn->error . "<br>";
    $success = false;
}

// Check if tables already have data
$result_contacts = $conn->query("SELECT COUNT(*) as count FROM contacts");
$contacts_count = $result_contacts->fetch_assoc()['count'];

$result_loans = $conn->query("SELECT COUNT(*) as count FROM loans");
$loans_count = $result_loans->fetch_assoc()['count'];

$message .= "<br>📊 Current data: $contacts_count contacts, $loans_count loans<br>";

$conn->close();
?>

<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>डेटाबेस सेटअप - स्वस्तिक लघुवित्त</title>
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
            margin-bottom: 1rem;
            text-align: center;
        }
        .status-icon {
            text-align: center;
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            font-size: 1rem;
            line-height: 1.6;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 1rem;
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
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d7ff;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .info-box h3 {
            color: #004085;
            margin-bottom: 0.5rem;
        }
        .info-box ul {
            margin-left: 1.5rem;
            color: #004085;
        }
        .info-box li {
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-icon">🗄️</div>
        <h1>डेटाबेस सेटअप</h1>
        
        <?php if ($success): ?>
        <div class="message success">
            <?php echo $message; ?>
        </div>
        
        <div class="info-box">
            <h3>📋 अर्को चरण:</h3>
            <ul>
                <li>तपाईंको डेटाबेस तयार छ!</li>
                <li>तपाईं अब फारमहरू प्रयोग गर्न सक्नुहुन्छ</li>
            </ul>
        </div>
        
        <div class="btn-group">
            <a href="index.html" class="btn">🏠 होम पेज</a>
            <a href="dummy_data.php" class="btn btn-success">➕ डमी डाटा थप्नुहोस्</a>
            <a href="test_connection.php" class="btn btn-secondary">🔗 कनेक्सन परीक्षण</a>
            <a href="http://localhost/phpmyadmin/index.php?route=/database/structure&db=swastiklbs" target="_blank" class="btn" style="background: #f39c12;">📊 phpMyAdmin</a>
        </div>
        <?php else: ?>
        <div class="message error">
            <?php echo $message; ?>
        </div>
        <div class="btn-group">
            <a href="index.html" class="btn">🏠 होम पेज</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
