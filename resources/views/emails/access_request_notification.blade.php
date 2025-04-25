<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Access Request</title>
</head>

<body>
    <h1>New Access Request Submitted</h1>

    <p>A new access request has been submitted. Here are the details:</p>

    <ul>
        <li>Kingdom: {{ $data['kingdom'] }}</li>
        <li>Alliance: {{ $data['alliance'] }}</li>
        <li>Player Name: {{ $data['player_name'] }}</li>
        <li>Email: {{ $data['email'] }}</li>
    </ul>

    <p>Please review the request and take appropriate action.</p>
</body>

</html>