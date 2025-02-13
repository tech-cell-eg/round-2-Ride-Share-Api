<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
</head>
<body>
    <p>Hello!</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>

    <p>Your password reset token is: <strong>{{ $token }}</strong></p>

    <p>This token will expire in 60 minutes.</p>

    <p>If you did not request a password reset, no further action is required.</p>

    <p>Regards,<br>Laravel</p>
</body>
</html>
