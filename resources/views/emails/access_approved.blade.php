<!DOCTYPE html>
<html>

<head>
    <title>Access Approved</title>
</head>

<body>
    <h1>Hello {{ $playerName }},</h1>

    <p>Your request to join the kingdom <strong>{{ $kingdom }}</strong> has been approved!</p>

    <p>Please click the button below to complete your registration. This link will expire in 24 hours.</p>

    <p>
        <a href="{{ $registrationUrl }}" style="padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">
            Complete Registration
        </a>
    </p>

    <p>If the button doesn't work, copy and paste the link below into your browser:</p>
    <p>{{ $registrationUrl }}</p>

    <p>Good luck and glory to your alliance!</p>
</body>

</html>