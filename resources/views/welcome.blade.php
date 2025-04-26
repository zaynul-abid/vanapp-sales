<!DOCTYPE html>
<html>
<head>
    <!-- ... -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])  <!-- For Vite -->
    <!-- OR -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet"> <!-- For Mix -->
</head>
<body>
<div id="app"></div>  <!-- Vue mounts here -->
</body>
</html>
