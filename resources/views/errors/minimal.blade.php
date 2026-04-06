<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');

        body {
            margin: 0;
            height: 100vh;
            background: #050505;
            color: #00ff9f;
            font-family: 'JetBrains Mono', monospace;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .container {
            text-align: center;
            position: relative;
        }

        .code {
            font-size: 100px;
            font-weight: bold;
            position: relative;
            animation: glitch 1s infinite;
        }

        .message {
            margin-top: 10px;
            font-size: 16px;
            opacity: 0.7;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            border: 1px solid #00ff9f;
            color: #00ff9f;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn:hover {
            background: #00ff9f;
            color: #000;
        }

        @keyframes glitch {
            0% { text-shadow: 2px 2px red, -2px -2px blue; }
            25% { text-shadow: -2px 2px red, 2px -2px blue; }
            50% { text-shadow: 2px -2px red, -2px 2px blue; }
            75% { text-shadow: -2px -2px red, 2px 2px blue; }
            100% { text-shadow: 2px 2px red, -2px -2px blue; }
        }

        .noise {
            position: absolute;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                rgba(255,255,255,0.02),
                rgba(255,255,255,0.02) 1px,
                transparent 1px,
                transparent 2px
            );
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="noise"></div>

@php
$code = isset($exception) ? $exception->getStatusCode() : 500;

$messages = match($code) {
    403 => ["admin only bro 🥀", "not for you 💀"],
    404 => ["this page ghosted you", "nothing here 😂"],
    500 => ["server cooked itself", "something exploded 💥"],
    default => ["something broke 💀"]
};
@endphp

<div class="container">
    <div class="code">{{ $code }}</div>

    <div class="message">
        {{ $messages[array_rand($messages)] }}
    </div>

    <a href="/" class="btn">return home</a>
</div>

</body>
</html>