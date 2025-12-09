<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckIt | Landing</title>
    <!-- Logo del navegador -->

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            /* Colores base estilo diseño de ejemplo */
            --pink: #ff4b8b;
            --pink-dark: #c7337b;
            --purple: #7b2cff;
            --purple-dark: #3b0f72;

            --text-light: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--text-light);
            background: radial-gradient(circle at 0% 0%, var(--purple), var(--pink));
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ========== NAVBAR ========== */
        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 24px 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.5rem;
        }

        nav {
            display: flex;
            gap: 25px;
            font-size: 0.95rem;
        }

        nav a {
            text-decoration: none;
            color: var(--text-light);
            position: relative;
        }

        nav a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0;
            height: 2px;
            background: var(--text-light);
            transition: width 0.25s ease;
        }

        nav a:hover::after {
            width: 100%;
        }


        .auth-links {
            margin-left: auto;
            padding-right: 0px;
        }

        .auth-links a {
            text-decoration: none;
            color: var(--text-light);
            margin-left: 14px;
            font-size: 0.9rem;
            padding: 7px 16px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(4px);
            transition: 0.25s;
        }

        .auth-links a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* ========== HERO ========== */
        .hero {
            position: relative;
            min-height: 100vh;
            padding: 120px 70px 60px;
            display: flex;
            align-items: center;
        }

        /* Formas orgánicas */
        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            border-radius: 50% 40% 60% 50%;
            filter: blur(0);
            opacity: 0.9;
            pointer-events: none;
        }

        .hero::before {
            width: 70vw;
            height: 70vh;
            right: -15vw;
            top: -10vh;
            background: radial-gradient(circle at 30% 30%, var(--purple), var(--pink-dark), var(--purple-dark));
        }

        .hero::after {
            width: 60vw;
            height: 50vh;
            left: -20vw;
            bottom: -15vh;
            background: radial-gradient(circle at 60% 20%, var(--purple-dark), var(--pink));
        }

        .hero-inner {
            position: relative;
            max-width: 550px;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.2rem;
            margin-bottom: 16px;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .hero-btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 30px;
            border: none;
            text-decoration: none;
            color: var(--text-light);
            font-weight: 600;
            background: linear-gradient(90deg, #ff7ac5, #ff4b8b);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.35);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 900px) {
            header {
                padding: 20px 24px;
            }

            .hero {
                padding: 120px 24px 50px;
            }

            .hero-title {
                font-size: 2.6rem;
            }

            nav {
                display: none;
                /* simplificamos en móvil */
            }

            .auth-links a {
                margin-left: 8px;
                padding: 6px 12px;
            }
        }
    </style>
</head>

<body style="overflow: hidden;"> <!-- Desactiva scroll -->

    <header>
        <div class="brand">
            <span>CheckIt</span>
        </div>


        <div class="auth-links">
            @auth
            <a href="/dashboard">Dashboard</a>
            @else
            <a href="{{ route('login') }}">Iniciar sesión</a>
            <a href="{{ route('register') }}">Registrarse</a>
            @endauth
        </div>
    </header>

    <section class="hero" style="height: 100vh; display: flex; align-items: center;">
        <div class="hero-inner">
            <h1 class="hero-title">Organiza tus listas con amigos</h1>
            <p class="hero-subtitle">
                Crea listas compartidas, colabora en tiempo real y organiza cualquier cosa con tus amigos.
                La forma más fácil de coordinarte con quien tú quieras.
            </p>

            <a href="{{ route('login') }}" class="hero-btn">
                Iniciar Sesión
            </a>
        </div>
    </section>

</body>

</html>