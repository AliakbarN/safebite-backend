<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    @vite(['resources/css/app.css'])
</head>
<body class="">
<div id="chat-app" class="">
    <!-- Chat app content goes here -->
</div>
<script type="module" src="{{ asset('assets/chat.js') }}"></script>
</body>
@vite(['resources/js/chat.js'])
</html>
