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
            --bg: #f5f7fb;
            --card-bg: #ffffff;
            --border-soft: #e2e5f0;
            --border-strong: #cbd0e2;
            --text-main: #111827;
            --text-muted: #6b7280;
            --accent: #4f46e5;
            --accent-soft: rgba(79, 70, 229, 0.08);
            --danger: #e11d48;
            --radius-lg: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: var(--bg);
            color: var(--text-main);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
        }

        /* Layout general */
        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top bar */
        .topbar {
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 24px;
        }

        .user-btn {
            border-radius: 999px;
            border: 1px solid var(--border-soft);
            background: var(--card-bg);
            padding: 6px 12px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
        }

        .user-avatar {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            background: var(--accent-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: var(--accent);
            font-weight: 600;
        }

        /* Contenedor central */
        .main {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 10px 16px 32px;
        }

        .center-panel {
            width: 100%;
            max-width: 800px;
        }

        /* Buscador estilo Google */
        .search-wrapper {
            margin-top: 18px;
            margin-bottom: 16px;
        }

        .search-form {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--card-bg);
            border-radius: 999px;
            border: 1px solid var(--border-soft);
            padding: 8px 14px;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
        }

        .search-icon {
            font-size: 1.1rem;
            color: var(--text-muted);
        }

        .search-input {
            border: none;
            flex: 1;
            font-size: 0.95rem;
            padding: 4px 0;
            outline: none;
            background: transparent;
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        .btn-create {
            border-radius: 999px;
            border: none;
            background: linear-gradient(90deg, #4f46e5, #6366f1);
            color: #ffffff;
            font-size: 0.85rem;
            padding: 8px 12px;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-create:hover {
            filter: brightness(1.05);
        }

        /* Lista de listas */
        .lists-container {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-soft);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
            padding: 10px 0;
        }

        .lists-header {
            padding: 4px 18px 10px;
            border-bottom: 1px solid var(--border-soft);
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .lists-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .list-item {
            padding: 10px 18px;
            border-bottom: 1px solid var(--border-soft);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-emoji {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .list-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .list-name-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .list-name {
            font-size: 0.96rem;
            font-weight: 500;
        }

        .list-name a {
            color: var(--text-main);
        }

        .list-name a:hover {
            color: var(--accent);
        }

        .list-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .list-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-link {
            border: none;
            background: none;
            padding: 0;
            font-size: 0.8rem;
            color: var(--accent);
            cursor: pointer;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .btn-danger {
            border: none;
            background: none;
            padding: 0;
            font-size: 0.78rem;
            color: var(--danger);
            cursor: pointer;
        }

        .btn-danger:hover {
            text-decoration: underline;
        }

        .empty-state {
            padding: 14px 18px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* --- SIDEBAR MINI ESTILO BRAND APART --- */
        .side-mini {
            position: fixed;
            left: 18px;
            top: 46%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 14px;
            z-index: 50;
        }

        .side-btn {
            width: 54px;
            height: 54px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            cursor: pointer;
            transition: 0.15s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .side-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(219, 250, 20, 0.15);
        }

        .side-btn.active {
            background: #f9ffa9ff;
            border-color: #111827;
            color: white;
            box-shadow: 0 5px 14px rgba(0, 0, 0, 0.25);
        }

        .user-btn-mini {
            font-size: 1.1rem;
            font-weight: bold;
        }

        /* Iconos */
        .side-icon {
            width: 26px;
            height: 26px;
            object-fit: contain;
            opacity: 0.75;
            transition: opacity .15s ease;
        }

        .side-btn:hover .side-icon {
            opacity: 1;
        }

        /* Tooltip */
        .side-btn {
            position: relative;
        }

        .tooltip {
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: #272611ff;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.78rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity .18s ease;
        }

        .side-btn:hover .tooltip {
            opacity: 1;
        }

        .tooltip::after {
            content: "";
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
            border-right: 6px solid #111827;
        }

        /* Menú usuario minimal */
        .user-menu-wrapper {
            position: relative;
        }

        .user-menu-toggle {
            background: white;
            border: 1px solid var(--border-soft);
            border-radius: 999px;
            padding: 6px 8px;
            cursor: pointer;
        }

        .user-dropdown {
            position: absolute;
            right: 0;
            top: 46px;
            background: white;
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            display: none;
            width: 150px;
            z-index: 20;
        }

        .user-dropdown.open {
            display: block;
        }

        .dropdown-item.logout {
            width: 100%;
            background: none;
            border: none;
            padding: 12px 16px;
            text-align: left;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .dropdown-item.logout:hover {
            background: #f3f4f6;
        }

        /* Wrapper del usuario en el sidebar */
        .side-user-wrapper {
            position: relative;
        }

        /* Dropdown lateral */
        .side-user-dropdown {
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            padding: 6px 0;
            width: 150px;
            display: none;
            z-index: 40;
        }

        .side-user-dropdown.open {
            display: block;
        }

        /* Botón de logout */
        .side-dropdown-item {
            width: 100%;
            padding: 10px 14px;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
            font-size: 0.9rem;
        }

        .side-dropdown-item:hover {
            background: #f3f4f6;
        }
    </style>
</head>

<body>
    <div class="page">

        <!-- SIDEBAR MINI -->
        <aside class="side-mini">
            <a class="side-btn active" data-filter="all">
                <img src="/img/list_all.png" class="side-icon">
                <span class="tooltip">Todas las listas</span>
            </a>

            <a class="side-btn" data-filter="owned">
                <img src="/img/my_list.png" class="side-icon">
                <span class="tooltip">Listas propias</span>
            </a>

            <a class="side-btn" data-filter="shared">
                <img src="/img/list_shared.png" class="side-icon">
                <span class="tooltip">Listas compartidas</span>
            </a>

            <div class="side-user-wrapper">
                <a class="side-btn side-user-btn">
                    <img src="/img/account.png" class="side-icon">
                    <span class="tooltip">Cuenta</span>
                </a>

                <div class="side-user-dropdown" id="sidebarUserDropdown">
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="side-dropdown-item">Cerrar sesión</button>
                    </form>
                </div>
            </div>

        </aside>


        <!-- Barra superior -->
        <header class="topbar">
            <div class="user-menu-wrapper">
                <button class="user-menu-toggle" type="button">
                    <div class="user-avatar">
                        {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                </button>

                <div class="user-dropdown" id="userDropdown">
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="dropdown-item logout">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Contenido central -->
        <main class="main">
            <div class="center-panel">

                <!-- Buscador + crear -->
                <section class="search-wrapper">
                    <form class="search-form" id="searchForm">
                        <span class="search-icon">🔍</span>
                        <input id="searchInput" type="text" class="search-input" placeholder="Busca una lista por nombre..." autocomplete="off">
                        <button type="submit" class="btn-create">Crear lista</button>
                    </form>
                </section>

                <!-- Listas -->
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
                        <li class="list-item" data-name="{{ Str::lower($l->name) }}" data-type="{{ $l->is_shared ? 'shared' : 'owned' }}">
                            <div class="list-emoji">
                                {{ $l->is_shared ? '🤝' : '📝' }}
                            </div>
                            <div class="list-main">
                                <div class="list-name-row">
                                    <div class="list-name">
                                        <a href="{{ route('lists.show', $l) }}">{{ $l->name }}</a>
                                    </div>
                                    <div class="list-actions">
                                        <a href="{{ route('lists.show', $l) }}" class="btn-link">Abrir</a>

                                        @if(!$l->is_shared)
                                        <form class="delete-list-form" action="/lists/{{ $l->id }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">Eliminar</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="list-meta">
                                    {{ $l->is_shared ? 'Compartida' : 'Propia' }}
                                    @if ($l->is_shared && isset($l->owner))
                                    • Propietario: {{ $l->owner->name ?? '—' }}
                                    @endif
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="empty-state">No tienes listas todavía. Crea una nueva con el botón de arriba.</li>
                        @endforelse
                    </ul>
                </section>

            </div>
        </main>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // ---- Filtro + búsqueda ----
        const listItems = Array.from(document.querySelectorAll('.list-item'));
        const searchInput = document.getElementById('searchInput');
        let activeFilter = "all";

        function applyFilters() {
            const query = searchInput.value.trim().toLowerCase();

            listItems.forEach(li => {
                const name = li.dataset.name || '';
                const type = li.dataset.type || 'owned';

                const matchesName = name.includes(query);
                const matchesType = (activeFilter === 'all') || (type === activeFilter);

                li.style.display = (matchesName && matchesType) ? 'flex' : 'none';
            });
        }

        searchInput.addEventListener('input', applyFilters);

        // Botones del sidebar
        const sideBtns = document.querySelectorAll('.side-btn[data-filter]');
        sideBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                sideBtns.forEach(b => b.classList.remove('active'));

                btn.classList.add('active');
                activeFilter = btn.dataset.filter;

                applyFilters();
            });
        });

        // Crear lista
        const searchForm = document.getElementById('searchForm');
        searchForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = searchInput.value.trim();
            if (!name) {
                alert('Escribe un nombre para la nueva lista.');
                return;
            }

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
                alert('Error creando la lista');
            }
        });

        // Eliminar listas propias
        document.querySelectorAll('.delete-list-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!confirm('¿Eliminar esta lista?')) return;

                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: new URLSearchParams(new FormData(form))
                });

                if (res.ok) {
                    location.reload();
                } else {
                    alert('Error eliminando la lista');
                }
            });
        });

        // Dropdown del usuario (solo logout)
        const userToggle = document.querySelector('.user-menu-toggle');
        const userDropdown = document.getElementById('userDropdown');

        userToggle.addEventListener('click', () => {
            userDropdown.classList.toggle('open');
        });

        // cerrar al click fuera
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.user-menu-wrapper')) {
                userDropdown.classList.remove('open');
            }
        });
        // Dropdown del sidebar (logout)
        const sideUserBtn = document.querySelector('.side-user-btn');
        const sideUserDropdown = document.getElementById('sidebarUserDropdown');

        sideUserBtn.addEventListener('click', (e) => {
            e.preventDefault();
            sideUserDropdown.classList.toggle('open');
        });

        // cerrar al click fuera
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.side-user-wrapper')) {
                sideUserDropdown.classList.remove('open');
            }
        });
    </script>

</body>

</html>