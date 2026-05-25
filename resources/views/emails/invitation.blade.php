<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <p>Hi Delivery Ahoy user,</p>

        <p>Join us! on tribe app, Click on Registration it will auto login your account</p>

        <a href="{{ $details['link'] ?? '#' }}" target="_blank" class="btn btn-primary">Registration Link</a>

        <p> Your login credentials will be after click on link</p>
        <p> Email: {{ $details['email']}} </p>
        <p> Password: 12345678 </p>

        <p>thanks and regards,</p>
        <p>{{ config('name') }}</p>
    </div>


</body>

</html>