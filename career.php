<?php
// Career Page Handler for Swastik Laghubitta
// Handles both vacancy display and career application submissions

// Include database connection
require_once 'db.php';

// Set response header for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/plain; charset=utf-8');
    
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $position = isset($_POST['position']) ? trim($_POST['position']) : '';
    $education = isset($_POST['education']) ? trim($_POST['education']) : '';
    $experience = isset($_POST['experience']) ? trim($_POST['experience']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($position)) {
        echo "Error: Please fill in all required fields.";
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Please enter a valid email address.";
        exit;
    }
    
    // Handle CV file upload
    $cv_filename = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/careers/';
        
        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Get file extension
        $file_ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed_ext = ['pdf', 'doc', 'docx'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            echo "Error: Only PDF, DOC, and DOCX files are allowed.";
            exit;
        }
        
        // Generate unique filename
        $new_filename = time() . '_' . sanitize_filename($name) . '.' . $file_ext;
        $target_path = $upload_dir . $new_filename;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $target_path)) {
            $cv_filename = $new_filename;
        }
    }
    
    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO careers (name, email, phone, position, education, experience, message, cv_filename) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $email, $phone, $position, $education, $experience, $message, $cv_filename);
    
    if ($stmt->execute()) {
        echo "Success: Your application has been submitted successfully! We will contact you within 7 days.";
    } else {
        echo "Error: Something went wrong. Please try again.";
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

// For GET request - Display the career page with vacancies
?>
<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>करियर | स्वस्तिक लघुवित्त बित्तीय संस्था लिमिटेड</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        .career-hero {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 50%, #0d1b2a 100%);
            padding: 150px 0 80px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .career-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 193, 7, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 152, 0, 0.1) 0%, transparent 50%);
        }
        
        .career-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.5);
            position: relative;
            color: #ffffff;
            border-bottom: 4px solid #ffc107;
            display: inline-block;
            padding-bottom: 0.5rem;
        }
        
        .career-hero p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            color: #e0e0e0;
        }
        
        .benefits {
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            padding: 80px 0;
            position: relative;
        }
        
        .benefits::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(ellipse, rgba(255, 193, 7, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .benefit-card {
            background: #ffffff;
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(13, 27, 42, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .benefit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffc107, #ff9800);
        }
        
        .benefit-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(13, 27, 42, 0.2);
            border-color: #ffc107;
        }
        
        .benefit-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .benefit-card:hover .benefit-icon {
            transform: scale(1.2) rotate(10deg);
        }
        
        .benefit-card h3 {
            color: #0d1b2a;
            margin-bottom: 0.5rem;
        }
        
        .vacancies {
            background: linear-gradient(180deg, #0d1b2a 0%, #1b263b 100%);
            padding: 80px 0;
            color: white;
            position: relative;
        }
        
        .vacancies::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 10% 90%, rgba(255, 193, 7, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 10%, rgba(255, 152, 0, 0.08) 0%, transparent 40%);
            pointer-events: none;
        }
        
        .vacancy-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
            position: relative;
            z-index: 1;
        }
        
        .vacancy-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border-left: 5px solid #ffc107;
            transition: all 0.4s ease;
        }
        
        .vacancy-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 193, 7, 0.2);
            background: rgba(255, 193, 7, 0.1);
        }
        
        .vacancy-card h3 {
            color: #ffc107;
            margin-bottom: 0.5rem;
        }
        
        .vacancy-card p {
            color: #cbd5e1;
        }
        
        .vacancy-type {
            display: inline-block;
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #0d1b2a;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .vacancy-details {
            margin: 1rem 0;
        }
        
        .vacancy-details p {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #cbd5e1;
        }
        
        .apply-btn {
            display: inline-block;
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #0d1b2a;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }
        
        .apply-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.5);
            background: linear-gradient(135deg, #ff9800, #ffc107);
        }
        
        .application-section {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            padding: 80px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .application-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(ellipse, rgba(255, 193, 7, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .application-form {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            padding: 2.5rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 193, 7, 0.3);
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .application-form h2 {
            color: #ffffff;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .application-form h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #ffc107, #ff9800);
            margin: 1rem auto 0;
            border-radius: 2px;
        }
        
        .application-form .form-group label {
            color: #ffc107;
            font-weight: 600;
        }
        
        .application-form .form-group input,
        .application-form .form-group select,
        .application-form .form-group textarea {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            color: white;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .application-form .form-group input::placeholder,
        .application-form .form-group textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .application-form .form-group input:focus,
        .application-form .form-group select:focus,
        .application-form .form-group textarea:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.2);
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .application-form .submit-btn {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #0d1b2a;
            font-weight: 700;
            width: 100%;
            padding: 16px 35px;
            font-size: 1.1rem;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(255, 193, 7, 0.4);
            border: 2px solid #ffc107;
        }
        
        .application-form .submit-btn:hover {
            background: linear-gradient(135deg, #ff9800, #ffc107);
            transform: translateY(-3px);
            box-shadow: 0 10px 35px rgba(255, 193, 7, 0.6);
        }
        
        .file-input-wrapper {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .file-input-wrapper input[type="file"] {
            display: none;
        }
        
        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px dashed rgba(255, 193, 7, 0.3);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .file-input-label:hover {
            border-color: #ffc107;
            background: rgba(255, 193, 7, 0.1);
        }
        
        .success-message {
            display: none;
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #0d1b2a;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }
        
        .success-message.show {
            display: block;
            animation: fadeInUp 0.5s ease;
        }
        
        .no-vacancies {
            text-align: center;
            padding: 2rem;
            color: #a0aec0;
            font-size: 1.2rem;
        }
        
        /* Company Culture Section */
        .culture-section {
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
            padding: 80px 0;
            position: relative;
        }
        
        .culture-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .culture-card {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(13, 27, 42, 0.08);
            text-align: center;
            transition: all 0.4s ease;
            border: 2px solid transparent;
        }
        
        .culture-card:hover {
            transform: translateY(-10px);
            border-color: #ffc107;
            box-shadow: 0 20px 50px rgba(13, 27, 42, 0.15);
        }
        
        .culture-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        
        .culture-card h3 {
            color: #0d1b2a;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .culture-card p {
            color: #5a6c7d;
            line-height: 1.8;
        }
        
        /* Testimonials Section */
        .testimonials-section {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            padding: 80px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .testimonials-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 70%, rgba(255, 193, 7, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 193, 7, 0.2);
            transition: all 0.4s ease;
        }
        
        .testimonial-card:hover {
            background: rgba(255, 193, 7, 0.1);
            transform: translateY(-5px);
        }
        
        .testimonial-card .quote-icon {
            font-size: 3rem;
            color: #ffc107;
            opacity: 0.5;
        }
        
        .testimonial-card p {
            color: #e0e0e0;
            font-style: italic;
            line-height: 1.8;
            margin: 1rem 0;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffc107, #ff9800);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d1b2a;
        }
        
        .testimonial-info h4 {
            color: #ffc107;
            margin: 0;
            font-size: 1.1rem;
        }
        
        .testimonial-info p {
            color: #a0aec0;
            margin: 0;
            font-size: 0.9rem;
            font-style: normal;
        }
        
        /* FAQ Section */
        .faq-section {
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            padding: 80px 0;
        }
        
        .faq-container {
            max-width: 800px;
            margin: 3rem auto 0;
        }
        
        .faq-item {
            background: #ffffff;
            border-radius: 15px;
            margin-bottom: 1rem;
            box-shadow: 0 5px 20px rgba(13, 27, 42, 0.08);
            overflow: hidden;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            border-color: #ffc107;
        }
        
        .faq-question {
            padding: 1.5rem 2rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #0d1b2a;
            font-size: 1.1rem;
        }
        
        .faq-question:hover {
            color: #ffc107;
        }
        
        .faq-icon {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
            color: #ffc107;
        }
        
        .faq-item.active .faq-icon {
            transform: rotate(45deg);
        }
        
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            padding: 0 2rem;
        }
        
        .faq-item.active .faq-answer {
            max-height: 300px;
            padding: 0 2rem 1.5rem;
        }
        
        .faq-answer p {
            color: #5a6c7d;
            line-height: 1.8;
        }
        
        @media (max-width: 768px) {
            .career-hero h1 {
                font-size: 2rem;
            }
            
            .career-hero p {
                font-size: 1rem;
            }
            
            .vacancy-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <a href="index.html">
                    <img src="logo.png" alt="स्वस्तिक लघुवित्त बित्तीय संस्था लिमिटेड">
                </a>
            </div>
            <div class="nav-container">
                <ul class="nav-links">
                    <li><a href="index.html">🏠 मुख्य पृष्ठ</a></li>
                    <li><a href="Service.html">💼 सेवाहरू</a></li>
                    <li><a href="about.html">ℹ️ हाम्रो बारेमा</a></li>
                    <li><a href="news.html">📰 समाचार</a></li>
                    <li><a href="contact.php">📞 सम्पर्क</a></li>
                    <li><a href="photos.html">📸 फोटोहरू</a></li>
                </ul>
               
                <div class="auth-buttons">
                    <a href="login.html" class="btn-login">🔑Login</a>
                    <a href="career.php" class="btn-career">💼 Career</a>
                </div>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Career Hero -->
    <section class="career-hero">
        <div class="container">
            <h1 class="fade-in">हाम्रो टिममा सामेल हुनुहोस्</h1>
            <p class="fade-in">स्वस्तिक लघुवित्तमा आफ्नो करियर बनाउनुहोस्। हामी तपाईंको व्यावसायिक विकास र भविष्यलाई महत्व दिन्छौं।</p>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits">
        <div class="container">
            <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 1rem; color: #0d1b2a; position: relative; display: inline-block; left: 50%; transform: translateX(-50%);">किन हामीसँग काम गर्नुहुन्छ?</h2>
            <p style="text-align: center; font-size: 1.2rem; color: #5a6c7d; margin-bottom: 3rem;">हाम्रो संगठनमा काम गर्ने फाइदाहरू</p>
            
            <div class="benefits-grid">
                <div class="benefit-card fade-in">
                    <div class="benefit-icon">💼</div>
                    <h3>व्यावसायिक विकास</h3>
                    <p>नियमित तालिम र क्षमता विकास कार्यक्रमहरू</p>
                </div>
                <div class="benefit-card fade-in">
                    <div class="benefit-icon">🏥</div>
                    <h3>स्वास्थ्य बीमा</h3>
                    <p>तपाईं र तपाईंको परिवारको लागि व्यापक स्वास्थ्य बीमा</p>
                </div>
                <div class="benefit-card fade-in">
                    <div class="benefit-icon">🎉</div>
                    <h3>उत्सव बिदा</h3>
                    <p>वर्षिक उत्सव बिदा र बोनस सुविधा</p>
                </div>
                <div class="benefit-card fade-in">
                    <div class="benefit-icon">⚖️</div>
                    <h3>कानूनी सुविधा</h3>
                    <p>सामाजिक सुरक्षा र अन्य कानूनी सुविधा</p>
                </div>
                <div class="benefit-card fade-in">
                    <div class="benefit-icon">🤝</div>
                    <h3>मैत्री वातावरण</h3>
                    <p>सहयोगी र मैत्री कार्य वातावरण</p>
                </div>
                <div class="benefit-card fade-in">
                    <div class="benefit-icon">📈</div>
                    <h3>करियर अवसर</h3>
                    <p>उत्कृष्ट करियर प्रगति र अवसरहरू</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vacancies Section - Dynamically Loaded from Database -->
    <section class="vacancies">
        <div class="container">
            <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 1rem; color: #ffffff; position: relative; display: inline-block; left: 50%; transform: translateX(-50%);">हालका रिक्त पदहरू</h2>
            <p style="text-align: center; font-size: 1.2rem; color: #a0aec0; margin-bottom: 3rem;">आफ्नो सीप र योग्यता अनुसार पद छान्नुहोस्</p>
            
            <?php
            // Fetch vacancies from database
            $vacancies = [];
            $db_error = false;
            
            try {
                $stmt = $conn->prepare("SELECT * FROM vacancies WHERE status = 'active' ORDER BY created_at DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    $vacancies[] = $row;
                }
                $stmt->close();
            } catch (Exception $e) {
                $db_error = true;
            }
            
            if (count($vacancies) > 0) {
                echo '<div class="vacancy-grid">';
                
                foreach ($vacancies as $vacancy) {
                    $job_type = htmlspecialchars($vacancy['job_type']);
                    $title = htmlspecialchars($vacancy['title']);
                    $location = htmlspecialchars($vacancy['location']);
                    $salary = htmlspecialchars($vacancy['salary_range']);
                    $qualification = htmlspecialchars($vacancy['qualification']);
                    $experience = htmlspecialchars($vacancy['experience']);
                    $position_value = strtolower(str_replace(' ', '-', $vacancy['title_en']));
                    
                    echo '<div class="vacancy-card fade-in">';
                    echo '<span class="vacancy-type">' . $job_type . '</span>';
                    echo '<h3>' . $title . '</h3>';
                    echo '<p style="color: #666; font-size: 1rem;">' . $location . '</p>';
                    echo '<div class="vacancy-details">';
                    echo '<p>📋 योग्यता: ' . $qualification . '</p>';
                    echo '<p>💰 तलब: ' . $salary . '</p>';
                    echo '<p>⏰ अनुभव: ' . $experience . '</p>';
                    echo '</div>';
                    echo '<button class="apply-btn" onclick="scrollToForm(\'' . $position_value . '\')">आवेदन गर्नुहोस्</button>';
                    echo '</div>';
                }
                
                echo '</div>';
            } else {
                // Fallback to default vacancies if database is empty or error
                echo '<div class="vacancy-grid">';
                
                $default_vacancies = [
                    ['title' => 'शाखा प्रबन्धक', 'location' => 'लहान, सिराहा', 'job_type' => 'पूर्णकालिक', 'salary' => 'रु. ५०,००० - ८०,०००', 'qualification' => 'स्नातक (बिजनेस/म्यानेजमेन्ट)', 'experience' => 'न्यूनतम ३ वर्ष', 'position' => 'branch-manager'],
                    ['title' => 'कर्जा अधिकृत', 'location' => 'सप्तरी, सिरहा, धनुषा', 'job_type' => 'पूर्णकालिक', 'salary' => 'रु. ३०,००० - ४५,०००', 'qualification' => '+2/स्नातक', 'experience' => '१-२ वर्ष', 'position' => 'loan-officer'],
                    ['title' => 'ग्राहक सेवा अधिकृत', 'location' => 'सबै शाखा कार्यालय', 'job_type' => 'पूर्णकालिक', 'salary' => 'रु. २५,००० - ३५,०००', 'qualification' => '+2 पास', 'experience' => 'अनुभव आवश्यक छैन', 'position' => 'customer-service'],
                    ['title' => 'IT सहायक', 'location' => 'मुख्य कार्यालय, लहान', 'job_type' => 'आंशिक', 'salary' => 'रु. २०,००० - २८,०००', 'qualification' => '+2/IT कोर्स', 'experience' => '१ वर्ष', 'position' => 'it-assistant'],
                    ['title' => 'लेखा अधिकृत', 'location' => 'मुख्य कार्यालय, लहान', 'job_type' => 'पूर्णकालिक', 'salary' => 'रु. ४०,००० - ५५,०००', 'qualification' => 'बि.कम. / स्नातक (लेखा)', 'experience' => '२-३ वर्ष', 'position' => 'accountant'],
                    ['title' => 'सुरक्षा गार्ड', 'location' => 'सबै शाखा कार्यालय', 'job_type' => 'पूर्णकालिक', 'salary' => 'रु. १८,००० - २२,०००', 'qualification' => 'SSC पास', 'experience' => 'अनुभव आवश्यक छैन', 'position' => 'security']
                ];
                
                foreach ($default_vacancies as $vacancy) {
                    echo '<div class="vacancy-card fade-in">';
                    echo '<span class="vacancy-type">' . $vacancy['job_type'] . '</span>';
                    echo '<h3>' . $vacancy['title'] . '</h3>';
                    echo '<p style="color: #666; font-size: 1rem;">' . $vacancy['location'] . '</p>';
                    echo '<div class="vacancy-details">';
                    echo '<p>📋 योग्यता: ' . $vacancy['qualification'] . '</p>';
                    echo '<p>💰 तलब: ' . $vacancy['salary'] . '</p>';
                    echo '<p>⏰ अनुभव: ' . $vacancy['experience'] . '</p>';
                    echo '</div>';
                    echo '<button class="apply-btn" onclick="scrollToForm(\'' . $vacancy['position'] . '\')">आवेदन गर्नुहोस्</button>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <!-- Application Form Section -->
    <section id="application-form" class="application-section">
        <div class="container">
            <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 1rem; color: white;">आवेदन पेश गर्नुहोस्</h2>
            <p style="text-align: center; font-size: 1.2rem; color: rgba(255,255,255,0.9); margin-bottom: 3rem;">आफ्नो CV र व्यक्तिगत विवरण पठाउनुहोस्</p>
            
            <form class="application-form" onsubmit="submitCareerApplication(event)" enctype="multipart/form-data">
                <div class="form-group">
                    <label>पूरा नाम *</label>
                    <input type="text" id="career_name" required placeholder="तपाईंको पूरा नाम">
                </div>
                
                <div class="form-group">
                    <label>इमेल ठेगाना *</label>
                    <input type="email" id="career_email" required placeholder="example@email.com">
                </div>
                
                <div class="form-group">
                    <label>मोबाइल नम्बर *</label>
                    <input type="tel" id="career_phone" required placeholder="9800000000">
                </div>
                
                <div class="form-group">
                    <label>आवेदन गर्ने पद *</label>
                    <select id="career_position" required>
                        <option value="">छान्नुहोस्</option>
                        <option value="branch-manager">शाखा प्रबन्धक</option>
                        <option value="loan-officer">कर्जा अधिकृत</option>
                        <option value="customer-service">ग्राहक सेवा अधिकृत</option>
                        <option value="it-assistant">IT सहायक</option>
                        <option value="accountant">लेखा अधिकृत</option>
                        <option value="security">सुरक्षा गार्ड</option>
                        <option value="other">अन्य</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>शैक्षिक योग्यता *</label>
                    <input type="text" id="career_education" required placeholder="जस्तै: +2, स्नातक, master's">
                </div>
                
                <div class="form-group">
                    <label>अनुभव (वर्षमा)</label>
                    <input type="text" id="career_experience" placeholder="जस्तै: 3 वर्ष">
                </div>
                
                <div class="form-group">
                    <label>मेसेज</label>
                    <textarea id="career_message" rows="4" placeholder="तपाईंको बारेमा केही लेख्नुहोस्..."></textarea>
                </div>
                
                <div class="form-group">
                    <label>CV/Resume अपलोड गर्नुहोस् *</label>
                    <div class="file-input-wrapper">
                        <label for="career_cv" class="file-input-label">
                            <span>📁 फाइल चयन गर्नुहोस् (PDF/DOC/DOCX)</span>
                        </label>
                        <input type="file" id="career_cv" accept=".pdf,.doc,.docx" required>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">आवेदन पेश गर्नुहोस्</button>
            </form>
            
            <div class="success-message" id="careerSuccessMessage">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
                <h3>धन्यवाद!</h3>
                <p>तपाईंको आवेदन सफलतापूर्वक पेश भयो।</p>
                <p>हाम्रो टिमले ७ दिन भित्रमा तपाईंसँग सम्पर्क गर्नेछ।</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div>
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <img src="logo.png" alt="स्वस्तिक" style="height: 50px; width: auto;">
                        <div style="margin-left: 1rem;">
                            <h3 style="margin: 0; color: white;">स्वस्तिक लघुवित्त</h3>
                            <p style="margin: 0; color: #ccc; font-size: 0.9rem;">बित्तीय संस्था लिमिटेड</p>
                        </div>
                    </div>
                    <p>नेपाल राष्ट्र बैंक दर्ता नं: क/०३६/०२७<br>कम्पनी दर्ता नं: ६०६५६२७०६</p>
                </div>
                <div>
                    <h4>त्वरित लिङ्कहरू</h4>
                    <ul style="list-style: none;">
                        <li><a href="index.html" style="color: #ccc; text-decoration: none;">मुख्य पृष्ठ</a></li>
                        <li><a href="Service.html" style="color: #ccc; text-decoration: none;">सेवाहरू</a></li>
                        <li><a href="about.html" style="color: #ccc; text-decoration: none;">हाम्रो बारेमा</a></li>
                        <li><a href="news.html" style="color: #ccc; text-decoration: none;">समाचार</a></li>
                        <li><a href="contact.php" style="color: #ccc; text-decoration: none;">सम्पर्क</a></li>
                        <li><a href="photos.html" style="color: #ccc; text-decoration: none;">फोटोहरू</a></li>
                        <li><a href="career.php" style="color: #ccc; text-decoration: none;">करियर</a></li>
                    </ul>
                </div>
                <div>
                    <h4>अनुसरण गर्नुहोस्</h4>
                    <p>
                        <a href="https://facebook.com/swastiklaghubitta" target="_blank" style="color: #1877F2; text-decoration: none; margin-right: 10px;">Facebook</a> | 
                        <a href="https://viber.com" target="_blank" style="color: #665CAC; text-decoration: none; margin: 0 10px;">Viber</a> | 
                        <a href="https://t.me/swastiklaghubitta" target="_blank" style="color: #0088CC; text-decoration
