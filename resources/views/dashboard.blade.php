<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CheckIt</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            /* COLORES LANDING ROSA/MORADO */
            --purple: #7b2cff;
            --purple-dark: #3b0f72;
            --pink: #ff4b8b;
            --pink-dark: #c7337b;
            --pink-light: #ff7ac5;

            /* GENERALES */
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.75);
            --card-bg: rgba(255, 255, 255, 0.15);
            --card-border: rgba(255, 255, 255, 0.35);
            --radius-lg: 18px;
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        * {
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            /* página estática, sin scroll */
        }

        body {
            background: radial-gradient(circle at 20% 20%, var(--purple), var(--pink-dark), var(--purple-dark));
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
        }

        /* --------------------------------------------------------------
           FORMAS ORGÁNICAS DEL FONDO (BLUR + DEGRADADOS)
        -------------------------------------------------------------- */
        .blob {
            position: absolute;
            width: 650px;
            height: 450px;
            filter: blur(80px);
            opacity: 0.55;
            border-radius: 50%;
            z-index: -1;
        }

        .blob.top-right {
            background: var(--pink);
            top: -120px;
            right: -220px;
        }

        .blob.bottom-left {
            background: var(--purple-dark);
            bottom: -160px;
            left: -220px;
        }

        /* --------------------------------------------------------------
           TOPBAR (USUARIO)
        -------------------------------------------------------------- */
        .topbar {
            display: flex;
            justify-content: flex-end;
            padding: 10px 20px;
            /* antes 16px 32px */
        }

        .user-menu-wrapper {
            position: relative;
        }

        .user-menu-toggle {
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
        }


        .user-avatar {
            width: 26px;
            height: 26px;
            font-size: 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.20);
            display: flex;
            align-items: center;
            justify-content: center;
        }


        /* Dropdown topbar */
        .user-dropdown {
            position: absolute;
            right: 0;
            top: 46px;
            background: rgba(255, 255, 255, 0.22);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            backdrop-filter: blur(14px);
            padding: 8px 0;
            min-width: 150px;
            display: none;
            box-shadow: var(--shadow);
            z-index: 100;
            min-width: 120px;
            padding: 6px 0;
        }

        .user-dropdown.open {
            display: block;
        }

        .dropdown-item {
            background: none;
            border: none;
            padding: 10px 16px;
            width: 100%;
            text-align: left;
            cursor: pointer;
            color: white;
            padding: 8px 12px;
            font-size: 0.80rem;
        }


        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        /* --------------------------------------------------------------
           MINI SIDEBAR GLASS (FLOTANTE)
        -------------------------------------------------------------- */
        .side-mini {
            position: fixed;
            left: 18px;
            top: 45%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 14px;
            z-index: 50;
        }

        .side-btn {
            position: relative;
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.22);
            border-radius: 18px;
            border: 1px solid var(--card-border);
            backdrop-filter: blur(16px);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s ease;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.25);
        }

        .side-btn:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.35);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.35);
        }

        .side-btn.active {
            background: linear-gradient(135deg, var(--pink-dark), var(--purple));
            border: 1px solid rgba(255, 255, 255, 0.85);
        }

        .side-icon {
            width: 26px;
            height: 26px;
            filter: brightness(2);
        }

        /* Tooltip */
        .tooltip {
            position: absolute;
            left: 80px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.55);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            opacity: 0;
            pointer-events: none;
            transition: 0.15s ease;
            white-space: nowrap;
        }

        .side-btn:hover .tooltip {
            opacity: 1;
        }

        .side-user-wrapper {
            position: relative;
        }

        .side-user-dropdown {
            position: absolute;
            left: 80px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.22);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow);
            padding: 8px 0;
            display: none;
            width: 150px;
            z-index: 200;
        }

        .side-user-dropdown.open {
            display: block;
        }

        .side-dropdown-item {
            padding: 10px 14px;
            color: white;
            background: none;
            border: none;
            text-align: left;
            width: 100%;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .side-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        /* --------------------------------------------------------------
           CONTENIDO CENTRAL
        -------------------------------------------------------------- */
        .main {
            display: flex;
            justify-content: center;
            padding: 0 16px 24px;
        }

        .center-panel {
            width: 100%;
            max-width: 800px;
        }

        /* BUSCADOR */
        .search-form {
            display: flex;
            gap: 12px;
            align-items: center;
            background: rgba(255, 255, 255, 0.20);
            border: 1px solid var(--card-border);
            padding: 14px 20px;
            border-radius: 999px;
            backdrop-filter: blur(14px);
            margin-bottom: 18px;
            box-shadow: var(--shadow);
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1rem;
            color: white;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-create {
            padding: 8px 18px;
            background: linear-gradient(90deg, var(--pink-light), var(--pink-dark));
            border: none;
            border-radius: 999px;
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s ease;
            white-space: nowrap;
        }

        .btn-create:hover {
            filter: brightness(1.1);
        }

        /* LISTAS (CARDS GLASS) */
        .lists-container {
            background: rgba(255, 255, 255, 0.18);
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(14px);
            box-shadow: var(--shadow);
            max-height: 455px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
        }

        .lists-container::-webkit-scrollbar {
            display: none;
        }

        .lists-header {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
            font-weight: 600;
            color: white;
        }

        .lists-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .list-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.20);
            color: white;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-emoji {
            font-size: 1.4rem;
        }

        .list-main {
            flex: 1;
        }

        .list-name-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .list-name a {
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .list-name a:hover {
            color: var(--pink-light);
        }

        .list-meta {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 2px;
        }

        .list-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-icon {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon img.icon-action {
            width: 22px;
            height: 22px;
            opacity: 0.9;
            transition: 0.15s;
        }

        .btn-icon:hover img.icon-action {
            opacity: 1;
            transform: scale(1.05);
        }

        .empty-state {
            padding: 14px 20px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .go-home-btn {
            margin-right: 14px;
            padding: 8px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
            color: white;
            font-weight: 400;
            font-size: 0.78rem;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .go-home-btn:hover {
            background: rgba(255, 255, 255, 0.30);
            transform: translateY(-2px);
        }

        #datetime-box {
            position: fixed;
            bottom: 18px;
            right: 24px;
            padding: 10px 16px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow);
            color: white;
            font-size: 0.80rem;
            line-height: 1.25;
            z-index: 9999;
            text-align: right;
            pointer-events: none;
        }

        /* ========== RESPONSIVE ========== */
        
        /* Tablets */
        @media (max-width: 900px) {
            .side-mini {
                left: 12px;
            }

            .side-btn {
                width: 48px;
                height: 48px;
                border-radius: 14px;
            }

            .side-icon {
                width: 22px;
                height: 22px;
            }

            .main {
                padding: 0 12px 20px;
            }

            .search-form {
                padding: 12px 16px;
            }

            .lists-container {
                max-height: 400px;
            }
        }

        /* Móviles */
        @media (max-width: 600px) {
            /* Sidebar pasa a bottom bar */
            .side-mini {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                top: auto;
                transform: none;
                flex-direction: row;
                justify-content: center;
                gap: 12px;
                padding: 10px 16px;
                background: rgba(0, 0, 0, 0.25);
                backdrop-filter: blur(16px);
                border-top: 1px solid var(--card-border);
                z-index: 100;
            }

            .side-btn {
                width: 44px;
                height: 44px;
                border-radius: 12px;
            }

            .side-icon {
                width: 20px;
                height: 20px;
            }

            .tooltip {
                display: none;
            }

            .side-user-dropdown {
                left: auto;
                right: 0;
                top: auto;
                bottom: 60px;
                transform: none;
            }

            /* Topbar */
            .topbar {
                padding: 8px 12px;
                margin-bottom: 16px;
            }

            .go-home-btn {
                margin-right: 8px;
                padding: 6px 10px;
                font-size: 0.7rem;
            }

            .user-avatar {
                width: 24px;
                height: 24px;
                font-size: 0.75rem;
            }

            /* Contenido principal */
            .main {
                padding: 0 10px 80px; /* espacio para bottom bar */
            }

            .center-panel {
                max-width: 100%;
            }

            .search-form {
                padding: 10px 14px;
                gap: 8px;
                margin-bottom: 14px;
            }

            .search-input {
                font-size: 0.9rem;
            }

            .btn-create {
                padding: 6px 14px;
                font-size: 0.8rem;
            }

            /* Listas */
            .lists-container {
                max-height: calc(100vh - 220px);
                border-radius: 14px;
            }

            .lists-header {
                padding: 12px 16px;
                font-size: 0.9rem;
            }

            .list-item {
                padding: 12px 14px;
                gap: 10px;
            }

            .list-emoji {
                font-size: 1.2rem;
            }

            .list-name a {
                font-size: 0.9rem;
            }

            .list-meta {
                font-size: 0.75rem;
            }

            .btn-icon img.icon-action {
                width: 18px;
                height: 18px;
            }

            /* Datetime box */
            #datetime-box {
                display: none;
            }

            /* Blobs */
            .blob {
                width: 400px;
                height: 300px;
                filter: blur(60px);
                opacity: 0.4;
            }

            .blob.top-right {
                top: -80px;
                right: -150px;
            }

            .blob.bottom-left {
                bottom: -100px;
                left: -150px;
            }
        }

        /* Móviles pequeños */
        @media (max-width: 400px) {
            .side-mini {
                gap: 8px;
                padding: 8px 12px;
            }

            .side-btn {
                width: 40px;
                height: 40px;
            }

            .side-icon {
                width: 18px;
                height: 18px;
            }

            .topbar {
                padding: 6px 10px;
            }

            .go-home-btn {
                padding: 5px 8px;
                font-size: 0.65rem;
            }

            .main {
                padding: 0 8px 70px;
            }

            .search-form {
                padding: 8px 12px;
            }

            .search-input {
                font-size: 0.85rem;
            }

            .btn-create {
                padding: 5px 10px;
                font-size: 0.75rem;
            }

            .lists-container {
                max-height: calc(100vh - 200px);
            }

            .lists-header {
                padding: 10px 12px;
                font-size: 0.85rem;
            }

            .list-item {
                padding: 10px 12px;
            }

            .list-emoji {
                font-size: 1rem;
            }

            .list-name a {
                font-size: 0.85rem;
            }

            .list-meta {
                font-size: 0.7rem;
            }
        }

        /* Pantallas cortas */
        @media (max-height: 600px) {
            .lists-container {
                max-height: calc(100vh - 180px);
            }

            .search-form {
                padding: 8px 12px;
                margin-bottom: 10px;
            }

            .lists-header {
                padding: 10px 14px;
            }

            .list-item {
                padding: 10px 14px;
            }
        }
    </style>
</head>

<body>
    <!-- BLOBS DE FONDO -->
    <div class="blob top-right"></div>
    <div class="blob bottom-left"></div>

    <div class="page">
        <!-- MINI SIDEBAR GLASS -->
        <aside class="side-mini">
            <a class="side-btn active" data-filter="all">
                <img src="/img/list_all.png" class="side-icon" alt="Todas las listas">
                <span class="tooltip">Todas las listas</span>
            </a>

            <a class="side-btn" data-filter="owned">
                <img src="/img/my_list.png" class="side-icon" alt="Listas propias">
                <span class="tooltip">Listas propias</span>
            </a>

            <a class="side-btn" data-filter="shared">
                <img src="/img/list_shared.png" class="side-icon" alt="Listas compartidas">
                <span class="tooltip">Listas compartidas</span>
            </a>

            <div class="side-user-wrapper">
                <a class="side-btn side-user-btn">
                    <img src="/img/account.png" class="side-icon" alt="Cuenta">
                    <span class="tooltip">Cuenta</span>
                </a>

                <div class="side-user-dropdown" id="sidebarUserDropdown">
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="side-dropdown-item" type="submit">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- TOPBAR: USUARIO -->
        <header class="topbar">
            <a href="{{ url('/') }}" class="go-home-btn">Landing Page</a>
            <div class="user-menu-wrapper">
                <button class="user-menu-toggle" type="button">
                    <div class="user-avatar">
                        {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                </button>

                <div class="user-dropdown" id="userDropdown">
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="dropdown-item" type="submit">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- CONTENIDO CENTRAL -->
        <main class="main">
            <div class="center-panel">
                <!-- BUSCADOR -->
                <form class="search-form" id="searchForm">
                    <input id="searchInput" class="search-input" type="text"
                        placeholder="Busca una lista por nombre..." autocomplete="off">
                    <button type="submit" class="btn-create">Crear lista</button>
                </form>

                <!-- LISTAS (CARDS GLASS) -->
                <section class="lists-container">
                    <div class="lists-header">
                        Listas propias y compartidas
                    </div>

                    <ul class="lists-list" id="listsList">
                        @php
                        $allLists = collect($owned)->map(function ($l) {
                        $l->is_shared = false;
                        return $l;
                        })->merge(
                        collect($shared)->map(function ($l) {
                        $l->is_shared = true;
                        return $l;
                        })
                        );
                        @endphp

                        @forelse ($allLists as $l)
                        <li class="list-item"
                            data-name="{{ Str::lower($l->name) }}"
                            data-type="{{ $l->is_shared ? 'shared' : 'owned' }}">

                            <div class="list-emoji">
                                {{ $l->is_shared ? '🤝' : '📝' }}
                            </div>

                            <div class="list-main">
                                <div class="list-name-row">
                                    <div class="list-name">
                                        <a href="{{ route('lists.show', $l) }}">{{ $l->name }}</a>
                                    </div>

                                    <div class="list-actions">
                                        <a href="{{ route('lists.show', $l) }}" class="btn-icon" title="Abrir lista">
                                            <img src="/img/open_list.png" class="icon-action" alt="Abrir">
                                        </a>

                                        @if(!$l->is_shared)
                                        <form class="delete-list-form" action="/lists/{{ $l->id }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon" title="Eliminar lista">
                                                <img src="/img/delete_list.png" class="icon-action" alt="Eliminar">
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="list-meta">
                                    {{ $l->is_shared ? 'Compartida' : 'Propia' }}
                                    @if ($l->is_shared && isset($l->owner))
                                    • Propietario: {{ $l->owner->name }}
                                    @endif
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="empty-state">
                            No tienes listas todavía. Crea una nueva con el botón de arriba.
                        </li>
                        @endforelse
                    </ul>
                </section>
            </div>
        </main>
    </div>
    <!-- FECHA + LOCALIZACIÓN (FIJO ABAJO DERECHA) -->
    <aside id="datetime-box">
        <div id="date-line"></div>
        <div id="location-line"></div>
    </aside>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        /* -----------------------------
        BUSCADOR + FILTROS
        ----------------------------- */
        const listItems = [...document.querySelectorAll('.list-item')];
        const searchInput = document.getElementById('searchInput');
        let activeFilter = "all";

        function applyFilters() {
            const query = (searchInput.value || "").trim().toLowerCase();

            listItems.forEach(li => {
                const name = li.dataset.name || "";
                const type = li.dataset.type || "owned";

                const matchesName = name.includes(query);
                const matchesType = (activeFilter === 'all') || (type === activeFilter);

                li.style.display = matchesName && matchesType ? "flex" : "none";
            });
        }

        searchInput.addEventListener("input", applyFilters);

        document.querySelectorAll('.side-btn[data-filter]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.side-btn[data-filter]').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeFilter = btn.dataset.filter;
                applyFilters();
            });
        });

        /* -----------------------------
        CREAR LISTA
        ----------------------------- */
        document.getElementById('searchForm').addEventListener('submit', async e => {
            e.preventDefault();
            const name = (searchInput.value || "").trim();
            if (!name) return alert("Escribe un nombre para la nueva lista.");

            const res = await fetch('/lists', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name
                })
            });

            if (res.ok) {
                location.reload();
            } else {
                alert("Error creando la lista.");
            }
        });

        /* -----------------------------
        BORRAR LISTA
        ----------------------------- */
        document.querySelectorAll('.delete-list-form').forEach(form => {
            form.addEventListener('submit', async e => {
                e.preventDefault();
                if (!confirm("¿Eliminar esta lista?")) return;

                const res = await fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                    },
                    body: new URLSearchParams(new FormData(form))
                });

                if (res.ok) {
                    location.reload();
                } else {
                    alert("Error eliminando la lista.");
                }
            });
        });

        /* -----------------------------
        DROPDOWN USUARIO (TOPBAR)
        ----------------------------- */
        const userToggle = document.querySelector('.user-menu-toggle');
        const userDropdown = document.getElementById('userDropdown');

        userToggle.addEventListener('click', () => {
            userDropdown.classList.toggle('open');
        });

        document.addEventListener('click', e => {
            if (!e.target.closest('.user-menu-wrapper')) {
                userDropdown.classList.remove('open');
            }
        });

        /* -----------------------------
        DROPDOWN USUARIO (SIDEBAR)
        ----------------------------- */
        const sideUserBtn = document.querySelector('.side-user-btn');
        const sideUserDropdown = document.getElementById('sidebarUserDropdown');

        sideUserBtn.addEventListener('click', e => {
            e.preventDefault();
            sideUserDropdown.classList.toggle('open');
        });

        document.addEventListener('click', e => {
            if (!e.target.closest('.side-user-wrapper')) {
                sideUserDropdown.classList.remove('open');
            }
        });

        /* -----------------------------
        FECHA + LOCALIDAD DISPOSITIVO
        ----------------------------- */
        (function() {
            const dateLine = document.getElementById('date-line');

            const now = new Date();
            const day = String(now.getDate()).padStart(2, "0");
            const month = String(now.getMonth() + 1).padStart(2, "0");
            const year = now.getFullYear();

            const opts = Intl.DateTimeFormat().resolvedOptions();
            const timeZone = opts.timeZone;

            dateLine.textContent = `${day}/${month}/${year} · ${timeZone}`;
        })();
    </script>
</body>

</html>