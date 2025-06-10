<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{env("ORG_NAME")}}</title>
    <style>
        body, p{
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .generated {
            font-style: italic;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<p>
    <strong>{{ $details["school_name"] }}</strong><br>
    <strong>{{ $details["school_address"] }}</strong><br>
    <br>
    <strong>Subject:</strong> Welcome to Online SO Processing – Account Created Successfully<br>
    <br>
    Dear Sir/Madam:<br>
    <br>
    Congratulations! Your account has been successfully created. Welcome to the Online SO Processing system! <br>
    <br>
    Below are your login credentials:<br>
    <strong>Username:</strong> {{ $details["school_number"] }}<br>
    <strong>Password:</strong> {{ $details["password"] }}<br>
    <br>
    For your security, we advise that you change your password immediately. Additionally, please<br>
    ensure you update the required information by following the link below:<br>
    <a href="so.depedcalabarzon.ph">so.depedcalabarzon.ph</a><br>
    <br>
    For your convenience, a user manual is also available in your profile to guide you through the process.<br>
    If you need any assistance or encounter any issues, please don't hesitate to contact us through<br>
    <strong>tel. No: 02-8682-2114 local 450 or {{ env('MAIL_FROM_ADDRESS') }}.</strong><br>
    <br>
    Best regards,<br>
    <br>
    <strong>{{ env('MAIL_FROM_NAME') }}</strong><br>
</p>
<p class="generated">This email was generated automatically. Please do not reply to this message.</p>
</body>
</html>
