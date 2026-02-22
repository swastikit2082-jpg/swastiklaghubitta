<?php
include 'db.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $loan_type = $_POST['loan_type'];
    $amount = $_POST['amount'];
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO loans (name, phone, loan_type, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $name, $phone, $loan_type, $amount);
    
    if ($stmt->execute()) {
        $message = "🎉 ऋण आवेदन सफलतापूर्वक पेश भयो! हाम्रो टिम २४ घण्टाभित्र तपाईंसँग सम्पर्क गर्नेछ। धन्यवाद!";
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
    <title>ऋण आवेदन - स्वस्तिक लघुवित्त बित्तीय संस्था लिमिटेड</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="logo.png">
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
                <div class="search-bar">
                    <input type="text" placeholder="खोज्नुहोस्..." id="searchInput">
                    <button onclick="searchFunction()">🔍</button>
                </div>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <h1 class="fade-in">ऋण आवेदन</h1>
            <p>तपाईंको आर्थिक आवश्यकताका लागि हामी तयार छौं</p>
        </div>
    </section>

    <!-- Loan Application Section -->
    <section class="contact">
        <div class="container">
            <?php if($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="contact-grid">
                <div class="info-grid">
                    <div class="info-item">
                        <h3>🏢 मुख्य कार्यालय</h3>
                        <p>लहान नगरपालिका<br>लहान, सिराहा, मधेश प्रदेश, नेपाल</p>
                        <p>फोन: ०३३-५६३०६७<br>ईमेल: info@swastikmicrofinance.com</p>
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
                    <div class="info-item">
                        <h3>🕒 कार्य समय</h3>
                        <p>सोम-शुक्र: १०:०० AM - ५:०० PM<br>शनिबार: ११:०० AM - २:०० PM</p>
                        <p>बिदा: आइतबार</p>
                    </div>
                    <div class="info-item">
                        <h3>📱 अन्य शाखाहरू</h3>
                        <p>भक्तपुर | ललितपुर | काभ्रे<br>धुलिखेल | पनौती</p>
                    </div>
                </div>
                <form class="contact-form" method="POST" action="">
                    <div class="form-group">
                        <label>पूरा नाम *</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label>मोबाइल नम्बर *</label>
                        <input type="tel" name="phone" id="phone" required>
                    </div>
                    <div class="form-group">
                        <label>ऋण प्रकार *</label>
                        <select name="loan_type" id="loan_type" required>
                            <option value="">छान्नुहोस्</option>
                            <option value="business">व्यापारिक ऋण</option>
                            <option value="agri">कृषि ऋण</option>
                            <option value="home">घरायसी ऋण</option>
                            <option value="women">महिला समूह ऋण</option>
                            <option value="education">शिक्षा ऋण</option>
                            <option value="vehicle">दुई पाङ्ग्रे ऋण</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ऋण रकम (रु.) *</label>
                        <input type="number" name="amount" id="amount" placeholder="50000" min="50000" max="5000000" required>
                    </div>
                    <button type="submit" class="submit-btn">आवेदन पेश गर्नुहोस्</button>
                </form>
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
                    </ul>
                </div>
                <div>
                    <h4>हामीलाई सम्पर्क गर्नुहोस्</h4>
                    <p>📍 लहान, सिराहा, नेपाल<br>📞 ०३३-५६३०६७<br>📧 info@swastikmicrofinance.com</p>
                    <div style="margin-top: 1rem;">
                        <h5>अनुसरण गर्नुहोस्</h5>
                        <p>Facebook | Viber | Telegram</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; २०८२ स्वस्तिक लघुवित्त बित्तीय संस्था लिमिटेड। सबै अधिकार सुरक्षित।</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        // Search function
        function searchFunction() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const sections = document.querySelectorAll('section');
            let found = false;
            sections.forEach(section => {
                const text = section.textContent.toLowerCase();
                if (text.includes(query)) {
                    section.scrollIntoView({ behavior: 'smooth' });
                    found = true;
                }
            });
            if (!found) {
                alert('कुनै परिणाम फेला परेन।');
            }
        }

        // Fade in animation on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        });

        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    </script>
</body>
</html>
