<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Booking app</title> 

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <link rel="shortcut icon" href="{{ asset('storage/favicon.ico') }}" type="image/x-icon"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />

    <script src="https://kit.fontawesome.com/cb57072874.js" crossorigin="anonymous"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo config( 'services.paypal.client_id'); ?>&currency=EUR" ></script>
</head>
<body class="left-side-menu-dark">
    <div id="root"></div>
    <div id="error-root"></div>
    <script src="{{ mix('js/index.js') }}"></script>
</body>
</html>