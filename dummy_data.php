<?php
// This script inserts dummy data into contacts and loans tables
// Run this file once to add sample data

include 'db.php';

$message = "";
$success = true;

// Insert dummy contacts data
$contacts = [
    ["राम बहादुर थापा", "ram@example.com", "9841234567", "loan", "मलाई व्यापारिक ऋण चाहिएको छ।"],
    ["सीता देवी शर्मा", "sita@example.com", "9842345678", "service", "कृषि ऋणको बारेमा जान्न चाहन्छु।"],
    ["गीता राई", "geeta@example.com", "9843456789", "complaint", "मेरो किस्ता भुक्तानीको बारेमा समस्या छ।"],
    ["जय प्रसाद गुरागाईं", "jay@example.com", "9844567890", "loan", "घर निर्माणका लागि ऋण चाहन्छु।"],
    ["निर्मला कुमारी", "nirmala@example.com", "9845678901", "other", "तपाईंहरूको सेवाको बारेमा जान्न चाहन्छु।"]
];

// Insert dummy loans data
$loans = [
    ["रमेश कुमार यादव", "9855012345", "business", 500000],
    ["कमला देवी", "9855123456", "agri", 150000],
    ["विष्णु प्रसाद", "9855234567", "home", 1000000],
    ["मीना कुमारी", "9855345678", "women", 250000],
    ["दिपक राज", "9855456789", "education", 300000],
    ["सुरेश महतो", "9855567890", "business", 750000],
    ["काजल श्रेष्ठ", "9855678901", "vehicle", 100000]
];

// Insert dummy vacancies data
$vacancies = [
    ["शाखा प्रबन्धक", "Branch Manager", "लहान, सिराहा", "पूर्णकालिक", "रु. ५०,००० - ८०,०००", "स्नातक (बिजनेस/म्यानेजमेन्ट)", "न्यूनतम ३ वर्ष", "शाखा कार्यालयको सम्पूर्ण जिम्मेवारी, टिम व्यवस्थापन, लक्ष्य प्राप्ति।"],
    ["कर्जा अधिकृत", "Loan Officer", "सप्तरी, सिरहा, धनुषा", "पूर्णकालिक", "रु. ३०,००० - ४५,०००", "+2/स्नातक", "१-२ वर्ष", "ऋण आवेदन प्रशोधन, ग्राहक मूल्यांकन, ऋण वितरण।"],
    ["ग्राहक सेवा अधिकृत", "Customer Service Officer", "सबै शाखा कार्यालय", "पूर्णकालिक", "रु. २५,००० - ३५,०००", "+2 पास", "अनुभव आवश्यक छैन", "ग्राहक सेवा, खाता व्यवस्थापन, सामान्य कार्य।"],
    ["IT सहायक", "IT Assistant", "मुख्य कार्यालय, लहान", "आंशिक", "रु. २०,००० - २८,०००", "+2/IT कोर्स", "१ वर्ष", "प्रणाली मर्मत, नेटवर्क व्यवस्थापन, प्राविधिक सहायता।"],
    ["लेखा अधिकृत", "Accountant", "मुख्य कार्यालय, लहान", "पूर्णकालिक", "रु. ४०,००० - ५५,०००", "बि.कम. / स्नातक (लेखा)", "२-३ वर्ष", "लेखा रेकर्डिंग, वित्तीय प्रतिवेदन, कर व्यवस्थापन।"],
    ["सुरक्षा गार्ड", "Security Guard", "सबै शाखा कार्यालय", "पूर्णकालिक", "रु. १८,००० - २२,०००", "SSC पास", "अनुभव आवश्यक छैन", "कार्यालय सुरक्षा, भौतिक सुरक्षा, अवलोकन।"]
];

// Insert contacts
foreach ($contacts as $contact) {
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $contact[0], $contact[1], $contact[2], $contact[3], $contact[4]);
    if (!$stmt->execute()) {
        $message .= "Error inserting contact: " . $contact[0] . "<br>";
        $success = false;
    }
    $stmt->close();
}

// Insert loans
foreach ($loans as $loan) {
    $stmt = $conn->prepare("INSERT INTO loans (name, phone, loan_type, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $loan[0], $loan[1], $loan[2], $loan[3]);
    if (!$stmt->execute()) {
        $message .= "Error inserting loan: " . $loan[0] . "<br>";
        $success = false;
    }
    $stmt->close();
}

// Insert vacancies
foreach ($vacancies as $vacancy) {
    $stmt = $conn->prepare("INSERT INTO vacancies (title, title_en, location, job_type, salary_range, qualification, experience, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("ssssssss", $vacancy[0], $vacancy[1], $vacancy[2], $vacancy[3], $vacancy[4], $vacancy[5], $vacancy[6], $vacancy[7]);
    if (!$stmt->execute()) {
        $message .= "Error inserting vacancy: " . $vacancy[0] . "<br>";
        $success = false;
    }
    $stmt->close();
}

if ($success) {
    $message = "✅ सफलतापूर्वक डमी डाटा थपियो!<br>
    - ५ वटा सम्पर्क रेकर्ड थपिए<br>
    - ७ वटा ऋण आवेदन रेकर्ड थपिए<br>
    - ६ वटा रिक्त पद रेकर्ड थपिए";
} else {
    $message = "❌ केही त्रुटिहरू भए: " . $message;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ne" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>डमी डाटा - स्वस्तिक लघुवित्त</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 500px;
            text-align: center;
        }
        h1 {
            color: #1e3c72;
            margin-bottom: 1rem;
        }
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            font-size: 1.1rem;
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
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #1e3c72;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 1rem;
            font-weight: 500;
        }
        .btn:hover {
            background: #2a5298;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ डमी डाटा थप्नुहोस्</h1>
        <div class="message <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
        <a href="index.html" class="btn">🏠 होम पेज</a>
        <a href="http://localhost/phpmyadmin/index.php?route=/database/structure&db=swastiklbs" target="_blank" class="btn" style="background: #f39c12;">📊 डेटाबेस हेर्नुहोस्</a>
    </div>
</body>
</html>
