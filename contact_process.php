<?php
// Include database and contact class
include_once 'config/database.php';
include_once 'models/Contact.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check if form is submitted via POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Get database connection
    $database = new Database();
    $db = $database->getConnection();

    // Initialize contact object
    $contact = new Contact($db);

    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate inputs
    $errors = [];
    
    if(empty($name)) {
        $errors[] = "Name is required.";
    }
    
    if(empty($email)) {
        $errors[] = "Email is required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if(empty($message)) {
        $errors[] = "Message is required.";
    }
    
    // If no errors, process the form
    if(empty($errors)) {
        // Set contact properties
        $contact->name = $name;
        $contact->email = $email;
        $contact->message = $message;
        
        // Create contact message
        if($contact->create()){
            // Send email notification (optional)
            $to = "princyramani09@gmail.com";
            $subject = "New Contact Message from D'LUMINE Website";
            $email_message = "
                New contact form submission:\n\n
                Name: $name\n
                Email: $email\n
                Message:\n$message\n
            ";
            $headers = "From: $email";
            
            // Uncomment to send email
            // mail($to, $subject, $email_message, $headers);
            
            echo json_encode([
                'success' => true,
                'message' => 'Thank you for your message! We will get back to you soon.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Unable to send message. Please try again.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => implode(' ', $errors)
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>