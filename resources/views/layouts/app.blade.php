<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AKM Aluminium - Quotation System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('AKM.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/quotation.css') }}">
    
    @livewireStyles
</head>
<body>
    <div class="container">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
