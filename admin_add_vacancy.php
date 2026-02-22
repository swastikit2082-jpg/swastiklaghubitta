<?php
// Admin page to add new vacancies to the career page
// Include database connection
require_once 'db.php';

$message = "";
$message_type = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $title_en = isset($_POST['title_en']) ? trim($_POST['title_en']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $job_type = isset($_POST['job_type']) ? trim($_POST['job_type']) : '';
    $salary_range = isset($_POST['salary_range']) ? trim($_POST['salary_range']) : '';
    $qualification = isset($_POST['qualification']) ? trim($_POST['qualification']) : '';
    $experience = isset($_POST['experience']) ? trim($_POST['experience']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'active';
    
    // Validate required fields
    if (empty($title) || empty($title_en) || empty($location) || empty($job_type)) {
        $message = "कृपया आवश्यक फिल्डहरू भर्नुहोस्। (Please fill in required fields)";
        $message_type = "error";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO vacancies (title, title_en, location, job_type, salary_range, qualification, experience, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $title, $title_en, $location, $job_type, $salary_range, $qualification, $experience, $description, $status);
        
        if ($stmt->execute()) {
            $message = "✅ रिक्त पद सफलतापूर्वक थपिए! (Vacancy added successfully!)";
            $message_type = "success";
            // Clear form fields on success
            $title = $title_en = $location = $job_type = $salary_range = $qualification = $experience = $description = '';
        } else {
            $message = "❌ त्रुटि भयो: " . $conn->error;
            $message_type = "error";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>रिक्त पद थप्नुहोस् - स्वस्तिक लघुवित्त प्रशासन</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 50%, #0d1b2a 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .admin-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .admin-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #ffc107;
        }
        
        .admin-header h1 {
            color: #0d1b2a;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .admin-header p {
            color: #5a6c7d;
        }
        
        .back-links {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 10px 20px;
            background: #0d1b2a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            background: #ffc107;
            color: #0d1b2a;
        }
        
        .message {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #0d1b2a;
            font-weight: 600;
        }
        
        .form-group label .required {
            color: #dc3545;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.2);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-group select {
            cursor: pointer;
        }
        
        .submit-btn {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #0d1b2a;
            padding: 15px 35px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
            margin-top: 1rem;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.5);
            background: linear-gradient(135deg, #ff9800, #ffc107);
        }
        
        .current-vacancies {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e0e0e0;
        }
        
        .current-vacancies h3 {
            color: #0d1b2a;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-container {
                padding: 1.5rem;
            }
            
            .admin-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>💼 रिक्त पद थप्नुहोस्</h1>
            <p>स्वस्तिक लघुवित्त बित्तीय संस्था लिमिटेड</p>
        </div>
        
        <div class="back-links">
            <a href="index.html" class="back-link">🏠 होम पेज</a>
            <a href="career.php" class="back-link">📋 करियर पेज</a>
        </div>
        
        <?php if (!empty($message)): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-grid">
                <div class="form-group">
                    <label>पदको नाम (नेपाली) <span class="required">*</span></label>
                    <input type="text" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" placeholder="जस्तै: शाखा प्रबन्धक" required>
                </div>
                
                <div class="form-group">
                    <label>पदको नाम (English) <span class="required">*</span></label>
                    <input type="text" name="title_en" value="<?php echo isset($title_en) ? htmlspecialchars($title_en) : ''; ?>" placeholder="e.g. Branch Manager" required>
                </div>
                
                <div class="form-group">
                    <label>स्थान <span class="required">*</span></label>
                    <input type="text" name="location" value="<?php echo isset($location) ? htmlspecialchars($location) : ''; ?>" placeholder="जस्तै: लहान, सिराहा" required>
                </div>
                
                <div class="form-group">
                    <label>कामको प्रकार <span class="required">*</span></label>
                    <select name="job_type" required>
                        <option value="">छान्नुहोस्</option>
                        <option value="पूर्णकालिक" <?php echo (isset($job_type) && $job_type === 'पूर्णकालिक') ? 'selected' : ''; ?>>पूर्णकालिक (Full Time)</option>
                        <option value="आंशिक" <?php echo (isset($job_type) && $job_type === 'आंशिक') ? 'selected' : ''; ?>>आंशिक (Part Time)</option>
                        <option value="अनुबंध" <?php echo (isset($job_type) && $job_type === 'अनुबंध') ? 'selected' : ''; ?>>अनुबंध (Contract)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>तलब रेन्ज</label>
                    <input type="text" name="salary_range" value="<?php echo isset($salary_range) ? htmlspecialchars($salary_range) : ''; ?>" placeholder="जस्तै: रु. ३०,००० - ४५,०००">
                </div>
                
                <div class="form-group">
                    <label>शैक्षिक योग्यता</label>
                    <input type="text" name="qualification" value="<?php echo isset($qualification) ? htmlspecialchars($qualification) : ''; ?>" placeholder="जस्तै: +2/स्नातक">
                </div>
                
                <div class="form-group">
                    <label>अनुभव</label>
                    <input type="text" name="experience" value="<?php echo isset($experience) ? htmlspecialchars($experience) : ''; ?>" placeholder="जस्तै: १-२ वर्ष">
                </div>
                
                <div class="form-group">
                    <label>स्थिति</label>
                    <select name="status">
                        <option value="active" <?php echo (isset($status) && $status === 'active') ? 'selected' : ''; ?>>सक्रिय (Active)</option>
                        <option value="inactive" <?php echo (isset($status) && $status === 'inactive') ? 'selected' : ''; ?>>निस्क्रिय (Inactive)</option>
                        <option value="closed" <?php echo (isset($status) && $status === 'closed') ? 'selected' : ''; ?>>बन्द (Closed)</option>
                    </select>
                </div>
                
                <div class="form-group full-width">
                    <label>विवरण</label>
                    <textarea name="description" placeholder="पदको विस्तृत विवरण लेख्नुहोस्..."><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="submit-btn">✅ रिक्त पद थप्नुहोस्</button>
            </div>
        </form>
        
        <div class="current-vacancies">
            <h3>📋 हालका रिक्त पदहरू (Current Vacancies)</h3>
            <p style="color: #5a6c7d; margin-bottom: 1rem;">हालका सक्रिय रिक्त पदहरू हेर्नको लागि <a href="career.php" style="color: #ffc107;">करियर पेज</a> हेर्नुहोस्।</p>
        </div>
    </div>
</body>
</html>
