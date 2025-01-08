<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .code {
            font-size: 30px;
            font-weight: bold;
            color: #4CAF50;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Welcome to Our Platform, {{ $user->name }}!</h2>
    </div>

    <p>Thank you for registering with us. To complete your registration, please verify your email address by using the code below:</p>

    <div class="code">
        {{ $code }}
    </div>

    <p>If you did not request this email, please ignore it. Your code will expire in 10 minutes.</p>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Our Platform. All rights reserved.</p>
    </div>
</div>
</body>
</html>
