<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CheckIt</title>

    <!-- Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ---------- Inputs transparentes ---------- */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            border: 1px solid rgba(254, 254, 254, 0.87) !important;
            padding: 12px 14px !important;
            font-size: 0.95rem !important;
            backdrop-filter: blur(6px) !important;
            transition: 0.25s ease;
        }

        /* Placeholder */
        input::placeholder {
            color: rgba(255, 255, 255, 0.55) !important;
        }

        /* Focus state */
        input:focus {
            outline: none !important;
            border: 1px solid rgba(230, 198, 209, 1) !important;
            box-shadow: 0 0 12px rgba(253, 251, 252, 0.55) !important;
        }

        /* ---------- Autofill Fix ---------- */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.05) inset !important;
            -webkit-text-fill-color: #ffffff !important;
            caret-color: #ffffff !important;
            border: 1px solid rgba(254, 254, 254, 0.87) !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }

        /* ---------- Labels ---------- */
        label {
            color: white !important;
            font-size: 0.9rem;
        }

        /* ---------- Checkbox: estilo premium ✓ rosa ---------- */
        input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            background-color: rgba(255, 255, 255, 0.18) !important;
            border: 2px solid rgba(255, 255, 255, 0.7) !important;
            width: 18px;
            height: 18px;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            transition: 0.2s;
        }

        input[type="checkbox"]:checked {
            background-color: transparent !important;
            border-color: rgba(255, 255, 255, 0.9) !important;
        }

        /* Texto "Remember me" */
        .text-gray-600,
        .dark\:text-gray-400 {
            color: white !important;
        }

        /* Forgot password link */
        .underline {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .underline:hover {
            color: white !important;
        }
    </style>
</head>

<body style="
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: radial-gradient(circle at 0% 0%, #7b2cff, #ff4b8b);
    min-height: 100vh;
    overflow: hidden;
    position: relative;
">

    <!-- ---------- Formas orgánicas ---------- -->
    <div style="
        position:absolute;
        width:70vw;
        height:70vh;
        right:-15vw;
        top:-10vh;
        border-radius:50% 40% 60% 50%;
        background: radial-gradient(circle at 30% 30%, #7b2cff, #ff4b8b, #3b0f72);
        opacity:0.85;
        z-index:0;
    "></div>

    <div style="
        position:absolute;
        width:60vw;
        height:50vh;
        left:-20vw;
        bottom:-15vh;
        border-radius:50% 40% 60% 50%;
        background: radial-gradient(circle at 60% 20%, #3b0f72, #ff4b8b);
        opacity:0.85;
        z-index:0;
    "></div>

    <!-- ---------- Contenedor del formulario ---------- -->
    <div class="min-h-screen flex flex-col justify-center items-center"
        style="position: relative; z-index: 2; padding-top: 20px;">

        <div class="w-full sm:max-w-md px-6 py-8 shadow-xl"
            style="
                background: rgba(255,255,255,0.16);
                backdrop-filter: blur(22px);
                border-radius: 22px;
                color: white;
                box-shadow: 0 8px 40px rgba(0,0,0,0.25);
            ">
            {{ $slot }}
        </div>

    </div>
</body>

</html>