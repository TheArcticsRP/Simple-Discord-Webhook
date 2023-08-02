<?php
// Include the necessary PHP code for session and database connection
session_start();


// Initialize variables for alert message
$alertClass = '';
$alertMessage = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["subject"]) && isset($_POST["message"])) {
    // Get the subject and message from the POST data
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Discord webhook URL - Replace 'YOUR_DISCORD_WEBHOOK_URL' with the actual webhook URL
    $discordWebhookUrl = 'YOUR_DISCORD_WEBHOOK_URL';

    // Modify your Embed settings here
    $payload = array(
        "username" => "YOUR_BOT_NAME", // Replace with your bot's name
        "avatar_url" => "BOT_AVATAR_URL", // Replace with the URL to your bot's avatar
        "embeds" => array(
            array(
                "content" => "<@&ROLE_ID>", // Add role ID if you want to mention a role
                "title" => $subject,
                "description" => $message,
                "color" => hexdec("00FF00") // Green color for the embed message (you can change it)
            )
        )
    );

    // Convert the payload to JSON
    $jsonPayload = json_encode($payload);

    // Prepare and send the HTTP POST request to the Discord webhook
    $ch = curl_init($discordWebhookUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Check if the message was sent successfully
    if ($response !== false) {
        $responseData = json_decode($response, true);
        if (isset($responseData["message"]) && $responseData["message"] === "Unknown Webhook") {
            // If the webhook URL is incorrect or invalid
            $alertClass = 'alert-danger';
            $alertMessage = 'Invalid Discord webhook URL. Please check the webhook URL.';
        } else {
            // If the message was sent successfully
            $alertClass = 'alert-success';
            $alertMessage = 'Message sent successfully to Discord webhook.';
        }
    } else {
        // If an error occurred while sending the request
        $alertClass = 'alert-danger';
        $alertMessage = 'An error occurred while sending the message to Discord.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Message to Discord Webhook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
pre code {
  background-color: #474747;
  border: none; /* Remove borders */
  display: block;
  padding: 20px;
  border-radius: 10px; /* Add rounded corners */
}

</style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php if (!empty($alertMessage)) { ?>
                    <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                        <?php echo $alertMessage; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" class="form-control" name="subject" id="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" name="message" id="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
                                <div class="mt-3 text-muted">
                    <p>The Message will ping the <code>@ROLE_ID</code> role by default.</p>
                    <p><strong>This uses standard Discrd Text formatting.</strong></p>
                    <p><code>**Bold Text**</code> for <strong>Bold Text</strong></p>
                    <p><code>*Italic Text*</code> for <i>Italic Text</i></p>
                    <p><code>***Bold Italic Text***</code> for <i><strong>Bold Italic Text</stong></i>
                    <p><code>__Underline Text__</code> for <u>Underline Text</u></p>
                    <p><code>[Hyperlink](YOUR_URL)</code> for <a href='#'>Hyperlink</a></p>
                    <p><code>`Short Code Block`</code> for <code>Short Code Block</code>
                    <p><code>```Long Code Block```</code> for <pre><code>Long Code Block</code></pre></p>
                    <p><code>&lt;@USER_ID&gt;</code> to tag a member by their username.</p>
                    <p><code>&lt;@&ROLE_ID&gt;</code> to tag a specific role in the server.</p>
                    <p><code>NOTE:</code> None of these will actually ping the role/member. This is a limitation of this feature in itself.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
