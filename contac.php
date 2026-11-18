<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

// =====================================================
// CONFIGURATION
// =====================================================

$recipient = "AtozsolutionscallCenter@gmail.com";

$subject = "New Contact Request - Atoz Solutions";

// =====================================================
// RECEIVE FORM DATA
// =====================================================

$first_name = trim($_POST["first_name"] ?? "");
$last_name  = trim($_POST["last_name"] ?? "");
$email      = trim($_POST["email"] ?? "");
$company    = trim($_POST["company"] ?? "");
$message    = trim($_POST["message"] ?? "");

// =====================================================
// VALIDATION
// =====================================================

if (
    empty($first_name) ||
    empty($last_name) ||
    empty($email) ||
    empty($message)
) {
    exit("Please complete all required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Please enter a valid email address.");
}

// Prevent header injection.
if (
    preg_match('/[\r\n]/', $email) ||
    preg_match('/[\r\n]/', $first_name) ||
    preg_match('/[\r\n]/', $last_name)
) {
    exit("Invalid form information.");
}

// =====================================================
// EMAIL CONTENT
// =====================================================

$body = "
NEW CONTACT REQUEST

Atoz Solutions Contact Center
----------------------------------------

First Name: $first_name

Last Name: $last_name

Email Address: $email

Company Name: $company

Message:

$message

----------------------------------------
This message was sent through the Atoz Solutions
website contact form.
";

// =====================================================
// EMAIL HEADERS
// =====================================================

$headers = [];

$headers[] = "From: Atoz Solutions Website <no-reply@" . $_SERVER["HTTP_HOST"] . ">";

$headers[] = "Reply-To: " . $email;

$headers[] = "Content-Type: text/plain; charset=UTF-8";

$headers[] = "MIME-Version: 1.0";

// =====================================================
// SEND EMAIL
// =====================================================

if (mail($recipient, $subject, $body, implode("\r\n", $headers))) {

    echo "
    <script>
        alert('Your message has been sent successfully!');
        window.location.href = 'index.html#contact-section';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Sorry, your message could not be sent. Please try again later.');
        window.location.href = 'index.html#contact-section';
    </script>
    ";

}

?>
