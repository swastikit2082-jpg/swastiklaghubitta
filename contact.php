<?php
include 'db.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message_text = $_POST['message'];
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message_text);
    
    if ($stmt->execute()) {
        $message = "🎉 सन्देश सफलतापूर्वक पठाइयो! हाम्रो टिम २४ घण्टाभित्र तपाईंसँग सम्पर्क गर्नेछ। धन्यवाद!";
        $messageType = "success";
    } else {
        $message = "❌ त्रुटि भयो। कृपया पुनः प्रयास गर्नुहोस्।";
        $messageType = "error";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>सम्पर्क | स्वस्तिक लघुवित्त बित्तीय संस्था लिमिटेड</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
</head>
<body>
<?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <!-- Floating Shapes -->
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-shape hero-shape-3"></div>
        
        <div class="container">
            <div class="glass-dark" style="padding: 2.5rem; display: inline-block; max-width: 900px;">
                <h1 class="fade-in gradient-text">हामीलाई सम्पर्क गर्नुहोस्</h1>
                <p style="font-size: 1.4rem; margin-bottom: 1.5rem;">तपाईंका जिज्ञासाहरूको उत्तर दिन हामी तयार छौं</p>
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">स्वस्तिक लघुवित्त वित्तीय संस्था लिमिटेडको टिम तपाईंको कुनै पनि प्रश्न, सुझाव वा गुनासो सुन्न готов छ। हामीलाई सम्पर्क गर्नुहोस् र छिटो प्रतिक्रिया प्राप्त गर्नुहोस्।</p>
                
                <div class="hero-buttons">
                    <a href="#contact-section" class="cta-btn">सन्देश पठाउनुहोस्</a>
                    <a href="#info-section" class="btn-link-outline">थप जान्नुहोस्</a>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section id="info-section" class="stats-section">
        <div class="gradient-orb gradient-orb-1"></div>
        <div class="gradient-orb gradient-orb-2"></div>
        <div class="container">
            <div class="stats-grid">
                <div class="stat-box fade-in">
                    <div class="stat-icon">🏢</div>
                    <div class="stat-number" data-target="15">0</div>
                    <div class="stat-label">शाखा कार्यालय</div>
                </div>
                <div class="stat-box fade-in">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number" data-target="75000">0</div>
                    <div class="stat-label">सन्तुष्ट ग्राहक</div>
                </div>
                <div class="stat-box fade-in">
                    <div class="stat-icon">💰</div>
                    <div class="stat-number" data-target="15">0</div>
                    <div class="stat-label">अर्ब+ कुल लगानी</div>
                </div>
                <div class="stat-box fade-in">
                    <div class="stat-icon">📈</div>
                    <div class="stat-number" data-target="99">0</div>
                    <div class="stat-label">% पुनर्भरण दर</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact-section" class="contact">
        <div class="gradient-orb gradient-orb-3"></div>
        <div class="container">
            <h2>हामीलाई सम्पर्क गर्नुहोस्</h2>
            
            <?php if($message): ?>
                <div class="glass" style="padding: 1.5rem; margin-bottom: 2rem; text-align: center; <?php echo $messageType == 'success' ? 'background: rgba(46, 204, 113, 0.2); border-color: #2ecc71;' : 'background: rgba(231, 76, 60, 0.2); border-color: #e74c3c;'; ?>">
                    <p style="color: white; font-size: 1.1rem; margin: 0;"><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
            
            <div class="contact-grid">
                <div class="info-grid">
                    <div class="info-item">
                        <h3>🏢 मुख्य कार्यालय</h3>
                        <p>लहान नगरपालिका<br>लहान, सिराहा, मधेश प्रदेश, नेपाल</p>
                        <p>📞 फोन: ०३३-५६३०६७<br>📧 ईमेल: info@swastikmicrofinance.com</p>
                    </div>
                    <div class="info-item">
                        <h3>🕒 कार्य समय</h3>
                        <p>सोम-शुक्र: १०:०० AM - ५:०० PM<br>शनिबार: ११:०० AM - २:०० PM</p>
                        <p>बिदा: आइतबार</p>
                    </div>
                    <div class="info-item">
                        <h3>📱 अन्य शाखाहरू</h3>
                        <p>भक्तपुर | ललितपुर | काभ्रे<br>धुलिखेल | पनौती</p>
                    </div>
                    <div class="info-item" style="margin-top: 1rem;">
                        <h3>📱 सामाजिक माध्यमहरू</h3>
                        <div class="social-links" style="justify-content: flex-start;">
                            <a href="https://facebook.com/swastiklaghubitta" target="_blank"><span>📘</span></a>
                            <a href="https://viber.com" target="_blank"><span>💜</span></a>
                            <a href="https://t.me/swastiklaghubitta" target="_blank"><span>✈️</span></a>
                        </div>
                    </div>
                </div>
                <form class="contact-form glass" method="POST" action="">
                    <div class="form-group">
                        <label>पूरा नाम *</label>
                        <input type="text" name="name" id="name" placeholder="तपाईंको नाम लेख्नुहोस्" required>
                    </div>
                    <div class="form-group">
                        <label>ईमेल *</label>
                        <input type="email" name="email" id="email" placeholder="example@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>मोबाइल नम्बर *</label>
                        <input type="tel" name="phone" id="phone" placeholder="९८XXXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label>विषय *</label>
                        <select name="subject" id="subject" required>
                            <option value="">छान्नुहोस्</option>
                            <option value="loan">ऋण सम्बन्धित</option>
                            <option value="service">सेवा सम्बन्धित</option>
                            <option value="complaint">गुनासो</option>
                            <option value="other">अन्य</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>सन्देश *</label>
                        <textarea name="message" id="message" rows="5" placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..." required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">सन्देश पठाउनुहोस्</button>
                </form>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>

<script src="common.js"></script>
</body>
</html>
