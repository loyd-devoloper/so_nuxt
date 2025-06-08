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
        i{
            font-style: italic;
            color: #555;
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
        <strong>Email OTP Verification</strong><br><br>
        Code: <strong>{{ $otp }}</strong><br><br>
        Note: This OTP is valid for 10 minutes only.<br><br>
        <strong>{{ env('APP_NAME') }}</strong><br>
    </p>
    	<p class="generated">This email was generated automatically. Please do not reply to this message.</p>
</body>
</html>
