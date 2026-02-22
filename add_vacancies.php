<?php
// This script inserts sample vacancy data into vacancies table
// Run this file once to add vacancy data

include 'db.php';

$message = "";
$success = true;

// Sample vacancies data
$vacancies = [
    [
        "शाखा प्रबन्धक",
        "Branch Manager",
        "लहान, सिराहा",
        "पूर्णकालिक",
        "रु. ५०,००० - ८०,०००",
        "स्नातक (बिजनेस/म्यानेजमेन्ट)",
        "न्यूनतम ३ वर्ष",
        "शाखा कार्यालयको सम्पूर्ण जिम्मेवारी, टिम व्यवस्थापन, लक्ष्य प्राप्ति।"
    ],
    [
        "कर्जा अधिकृत",
        "Loan Officer",
        "सप्तरी, सिरहा, धनुषा",
        "पूर्णकालिक",
        "रु. ३०,००० - ४५,०००",
        "+2/स्नातक",
        "१-२ वर्ष",
        "ऋण आवेदन प्रशोधन, ग्राहक मूल्यांकन, ऋण वितरण।"
    ],
    [
        "ग्राहक सेवा अधिकृत",
        "Customer Service Officer",
        "सबै शाखा कार्यालय",
        "पूर्णकालिक",
        "रु. २५,००० - ३५,०००",
        "+2 पास",
        "अनुभव आवश्यक छैन",
        "ग्राहक सेवा, खाता व्यवस्थापन, सामान्य कार्य।"
    ],
    [
        "IT सहायक",
        "IT Assistant",
        "मुख्य कार्यालय, लहान",
        "आंशिक",
        "रु. २०,००० - २८,०००",
        "+2/IT कोर्स",
        "१ वर्ष",
        "प्रणाली मर्मत, नेटवर्क व्यवस्थापन, प्राविधिक सहायता।"
    ],
    [
        "लेखा अधिकृत",
        "Accountant",
        "मुख्य कार्यालय, लहान",
        "पूर्णकालिक",
        "रु. ४०,००० - ५५,०००",
        "बि.कम. / स्नातक (लेखा)",
        "२-३ वर्ष",
        "लेखा रेकर्डिंग, वित्तीय प्रतिवेदन, कर व्यवस्थापन।"
    ],
    [
        "सुरक्षा गार्ड",
        "Security Guard",
        "सबै शाखा कार्यालय",
        "पूर्णकालिक",
        "रु. १८,००० - २२,०००",
        "SSC पास",
        "अनुभव आवश्यक छैन",
        "कार्यालय सुरक्षा, भौतिक सुरक्षा, अवलोकन।"
    ]
];

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
    $message = "✅ सफलतापूर्वक रिक्त पदहरू थपिए!<br>
    - " . count($vacancies) . " वटा रिक्त पदहरू थपिए";
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
    <title>रिक्त पदहरू थप्नुहोस् - स्वस्तिक लघुवित्त</title>
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
        <h1>💼 रिक्त पदहरू थप्नुहोस्</h1>
        <div class="message <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
        <a href="career.html" class="btn">📋 करियर पेज हेर्नुहोस्</a>
        <a href="index.html" class="btn">🏠 होम पेज</a>
    </div>
</body>
</html>
