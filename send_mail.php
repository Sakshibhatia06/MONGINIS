<?php
// Set header to return JSON response
header('Content-Type: application/json');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitize and trim input values
    $name    = strip_tags(trim($_POST['name'] ?? ''));
    $mobile  = strip_tags(trim($_POST['mobile'] ?? ''));
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $city    = strip_tags(trim($_POST['city'] ?? ''));
    $message = strip_tags(trim($_POST['message'] ?? ''));
    $updates = isset($_POST['updates']) ? 'Yes' : 'No';

    // Validate required fields
    if (empty($name) || empty($mobile) || empty($email) || empty($city)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill in all required fields (Name, Mobile, Email, and City).'
        ]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please provide a valid email address.'
        ]);
        exit;
    }

    // Email configuration
    $to = 'bhatiasakshi60@gmail.com'; // <--- CHANGE THIS to your actual receiving email address
    $subject = 'New Franchise Enquiry: ' . $name . ' (' . $city . ')';

    // Build the email body content
    $email_body = "You have received a new franchise enquiry:\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Mobile: " . $mobile . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "City: " . $city . "\n";
    $email_body .= "Wants Updates: " . $updates . "\n\n";
    $email_body .= "Message:\n" . (!empty($message) ? $message : 'No message provided.') . "\n";

    // Set email headers
    $headers = "From: noreply@" . $_SERVER['SERVER_NAME'] . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send the email using PHP's mail() function
    if (mail($to, $subject, $email_body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Thank you! Your enquiry has been sent successfully. We will get back to you soon.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Server error: Failed to send email. Please try again later.'
        ]);
    }

} else {
    // If someone tries to access send_mail.php directly via URL instead of form submit
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
