<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista: {{ $list->name }}</title>

    <!-- Fuente Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg1: #7b2cff;
            --bg2: #ff4b8b;
            --card-bg: rgba(255, 255, 255, 0.14);
            --card-border: rgba(255, 255, 255, 0.35);
            --text-main: #ffffff;
            --text-soft: rgba(255, 255, 255, 0.8);
            --accent: #ff4b8b;
            --danger: #ffd3e6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            color: var(--text-main);
            background: radial-gradient(circle at 0% 0%, var(--bg1), var(--bg2));
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
        }

        /* FONDO ORGÁNICO */
        .bg-shape {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            border-radius: 50% 40% 60% 50%;
            opacity: 0.75;
        }

        .bg-shape.one {
            width: 55vw;
            height: 55vh;
            right: -12vw;
            top: -8vh;
            background: radial-gradient(circle at 30% 30%, #7b2cff, #ff4b8b, #3b0f72);
        }

        .bg-shape.two {
            width: 65vw;
            height: 55vh;
            left: -18vw;
            bottom: -18vh;
            background: radial-gradient(circle at 60% 20%, #3b0f72, #ff4b8b);
        }

        .page-wrapper {
            position: relative;
            z-index: 1;
            max-width: 1300px;
            margin: 0 auto;
            padding: 20px 16px 40px;
        }

        /* TOP BAR */
        .top-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .top-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: var(--text-main);
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 0.8rem;
        }

        .back-link:hover {
            background: rgba(0, 0, 0, 0.38);
        }

        .list-header {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .list-title {
            font-size: 1.7rem;
            font-weight: 600;
        }

        .owner-pill {
            font-size: 0.8rem;
            color: var(--text-soft);
        }

        .owner-pill strong {
            color: var(--text-main);
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .member-avatars {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .avatar-circle {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.35);
        }

        .edit-list-btn {
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: rgba(0, 0, 0, 0.22);
            color: var(--text-main);
            font-size: 0.8rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .edit-list-btn:hover {
            background: rgba(0, 0, 0, 0.32);
        }

        /* FORM EDITAR NOMBRE LISTA */
        #editListForm {
            margin: 4px 0 14px;
            display: none;
            flex-wrap: wrap;
            gap: 6px;
        }

        #editListInput {
            flex: 1;
            min-width: 180px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            padding: 6px 10px;
            background: rgba(0, 0, 0, 0.25);
            color: #fff;
            font-size: 0.9rem;
        }

        #editListInput::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        #editListInput:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
        }

        .edit-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        button,
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 999px;
            border: none;
            font-size: 0.78rem;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(90deg, #ff7ac5, #ff4b8b);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .btn-primary:hover {
            filter: brightness(1.05);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-soft);
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-danger-link {
            background: transparent;
            color: var(--danger);
            font-size: 0.75rem;
            padding: 0;
        }

        .btn-danger-link:hover {
            text-decoration: underline;
        }

        /* LAYOUT PRINCIPAL: BOARD + SIDEBAR */
        .layout {
            display: grid;
            grid-template-columns: minmax(0, 4fr) minmax(260px, 1.2fr);
            gap: 14px;
            align-items: flex-start;
        }

        @media (max-width: 900px) {
            .layout {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        /* BOARD TRELLO */
        .board-wrapper {
            background: rgba(0, 0, 0, 0.18);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(18px);
            padding: 10px 8px;
            overflow-x: auto;
        }

        .board {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            min-height: 260px;
        }

        .board-column {
            min-width: 220px;
            max-width: 260px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            padding: 8px 8px 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* ===== Estilo scroll horizontal del board ===== */
        .board-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .board-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }

        .board-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.35);
            border-radius: 999px;
            backdrop-filter: blur(4px);
        }

        .board-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.55);
        }


        .column-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 2px;
        }

        .column-title {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .column-meta {
            font-size: 0.7rem;
            color: var(--text-soft);
        }

        .column-body {
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-height: 360px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .column-body::-webkit-scrollbar,
        .sidebar-list::-webkit-scrollbar {
            width: 0;
            height: 0;
        }


        .trello-card {
            background: rgba(0, 0, 0, 0.28);
            border-radius: 10px;
            padding: 6px 8px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            font-size: 0.82rem;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .card-title-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 6px;
        }

        .card-title {
            font-weight: 500;
            word-break: break-word;
            flex-grow: 1;
        }

        .card-badges {
            display: flex;
            align-items: flex-start;
            flex-shrink: 0;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 0.7rem;
            color: var(--text-soft);
        }

        .badge-completed {
            background: rgba(116, 255, 173, 0.16);
            color: #b9ffd0;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            margin-top: 2px;
        }

        .card-footer button {
            font-size: 0.72rem;
        }

        .card-details {
            font-size: 0.75rem;
            color: var(--text-soft);
            word-break: break-word;
            white-space: normal;
        }

        .column-add-card {
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px dashed rgba(255, 255, 255, 0.25);
        }

        .column-add-card input {
            width: 100%;
            margin-bottom: 4px;
        }

        .column-add-card input,
        .column-add-card textarea {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            padding: 5px 7px;
            font-size: 0.78rem;
            resize: none;
        }

        .column-add-card textarea {
            min-height: 40px;
        }

        .column-add-card input::placeholder,
        .column-add-card textarea::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .column-add-card input:focus,
        .column-add-card textarea:focus {
            outline: none;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.4);
        }

        /* COLUMNA "NUEVA CATEGORÍA" */
        .board-column.new-category-col {
            background: rgba(255, 255, 255, 0.08);
            border-style: dashed;
            align-items: stretch;
        }

        .new-category-col h3 {
            font-size: 0.9rem;
            margin: 0 0 6px;
        }

        .new-category-col form input {
            width: 100%;
            background: rgba(0, 0, 0, 0.28);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            padding: 5px 7px;
            font-size: 0.78rem;
            color: #fff;
            margin-bottom: 6px;
        }

        .new-category-col form input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .new-category-col form input:focus {
            outline: none;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.4);
        }

        /* SIDEBAR DERECHA: miembros + comentarios */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-card {
            background: rgba(0, 0, 0, 0.22);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 10px 12px;
            backdrop-filter: blur(18px);
        }

        .sidebar-card h2 {
            margin: 0 0 6px;
            font-size: 0.98rem;
        }

        .muted {
            color: var(--text-soft);
            font-size: 0.8rem;
        }

        .sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.8rem;
            max-height: 260px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .sidebar-list li {
            padding: 4px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-list li:last-child {
            border-bottom: none;
        }

        .member-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .comment-author {
            font-weight: 500;
        }

        .comment-content {
            font-size: 0.8rem;
            color: var(--text-main);
            word-break: break-word;
            white-space: normal;
        }

        /* Inputs genéricos */
        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            padding: 6px 8px;
            font-size: 0.8rem;
            margin-bottom: 6px;
        }

        select {
            background: rgba(255, 255, 255, 0.05) !important;
        }

        select option {
            background: rgba(24, 21, 21, 0.84);
            color: #f2e6e6ff;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
        }

        a {
            color: #ffe2f2;
            text-decoration: underline;
            text-decoration-thickness: 1px;
        }

        a:hover {
            text-decoration-thickness: 2px;
        }
    </style>
</head>

<body>
    <div class="bg-shape one"></div>
    <div class="bg-shape two"></div>

    <!--Agrupamos productos por categoría para el estilo Trello-->
    @php
    $productsByCategory = $list->products->groupBy('id_category');
    $uncategorized = $productsByCategory->get(null, collect());
    @endphp

    <div class="page-wrapper">

        {{-- TOP BAR --}}
        <div class="top-bar">
            <div class="top-left">
                <a href="{{ route('dashboard') }}" class="back-link">
                    <span>←</span>
                    <span>Back</span>
                </a>

                <div class="list-header">
                    <div class="list-title" id="listName">{{ $list->name }}</div>
                    <div class="owner-pill">
                        Propietario: <strong>{{ $list->owner->name ?? '—' }}</strong>
                    </div>
                </div>
            </div>

            <div class="top-right">
                {{-- Avatares rápidos (propietario + algunos miembros) --}}
                <div class="member-avatars">
                    @php
                    $allAvatars = collect([$list->owner])->filter();
                    if (isset($list->members)) {
                    $allAvatars = $allAvatars->merge($list->members)->unique('id');
                    }
                    $shown = 0;
                    @endphp

                    @foreach($allAvatars as $u)
                    @if($shown < 4)
                        <div class="avatar-circle">
                        {{ strtoupper(mb_substr($u->name ?? 'U', 0, 2)) }}
                </div>
                @php $shown++; @endphp
                @endif
                @endforeach
                @if(($allAvatars->count() ?? 0) > $shown)
                <div class="avatar-circle">+{{ $allAvatars->count() - $shown }}</div>
                @endif
            </div>

            @can('update', $list)
            <button id="editListBtn" class="edit-list-btn">
                <span>Editar nombre</span>
            </button>
            @endcan
        </div>
    </div>

    {{-- FORM EDITAR NOMBRE --}}
    @can('update', $list)
    <form id="editListForm">
        <input id="editListInput" type="text" value="{{ $list->name }}" required>
        <div class="edit-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" id="cancelEdit" class="btn btn-ghost">Cancelar</button>
        </div>
    </form>
    @endcan

    <div class="layout">

        {{-- BOARD TRELLO (CATEGORÍAS COMO COLUMNAS) --}}
        <div class="board-wrapper">
            <div class="board">

                {{-- Columnas por categoría --}}
                @foreach($list->categories as $c)
                @php
                $catProducts = $productsByCategory->get($c->id_category, collect());
                @endphp

                <section class="board-column" data-cat-id="{{ $c->id_category }}">
                    <div class="column-header">
                        <div>
                            <div class="column-title">{{ $c->name }}</div>
                            <div class="column-meta">{{ $catProducts->count() }} productos</div>
                        </div>

                        @can('detachFromList', [\App\Models\Category::class, $list])
                        <form class="cat-del" action="/lists/{{ $list->id }}/categories/{{ $c->id_category }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger-link">Quitar</button>
                        </form>
                        @endcan
                    </div>

                    <div class="column-body">
                        @forelse($catProducts as $p)
                        <article class="trello-card">
                            <div class="card-title-row">
                                <div class="card-title">{{ $p->name }}</div>
                                <div class="card-badges">
                                    @if($p->completed)
                                    <span class="badge badge-completed">✔ Completado</span>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($p->details))
                            <div class="card-details">
                                <em>Detalles:</em> {{ $p->details }}
                            </div>
                            @endif

                            <div class="card-footer">
                                @can('toggle', [\App\Models\Product::class, $list])
                                <form class="prod-toggle" action="/lists/{{ $list->id }}/products/{{ $p->id_product }}/toggle" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-ghost">
                                        {{ $p->completed ? 'Desmarcar' : 'Completar' }}
                                    </button>
                                </form>
                                @endcan

                                @can('delete', [\App\Models\Product::class, $list])
                                <form class="prod-del" action="/lists/{{ $list->id }}/products/{{ $p->id_product }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-link">Quitar</button>
                                </form>
                                @endcan
                            </div>
                        </article>
                        @empty
                        <div class="muted">(sin productos)</div>
                        @endforelse
                    </div>

                    {{-- Añadir producto en esta categoría --}}
                    @can('create', [\App\Models\Product::class, $list])
                    <form class="column-add-card prod-add">
                        @csrf
                        <input type="hidden" name="id_category" value="{{ $c->id_category }}">
                        <input type="text" name="name" placeholder="Nuevo producto..." required>
                        <input type="text" name="details" placeholder="Detalles (opcional)">
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </form>
                    @endcan
                </section>
                @endforeach

                {{-- Columna SIN CATEGORÍA (productos sin id_category) --}}
                @if($uncategorized->count() > 0)
                <section class="board-column" data-cat-id="">
                    <div class="column-header">
                        <div>
                            <div class="column-title">Sin categoría</div>
                            <div class="column-meta">{{ $uncategorized->count() }} productos</div>
                        </div>
                    </div>

                    <div class="column-body">
                        @foreach($uncategorized as $p)
                        <article class="trello-card">
                            <div class="card-title-row">
                                <div class="card-title">{{ $p->name }}</div>
                                <div class="card-badges">
                                    @if($p->completed)
                                    <span class="badge badge-completed">✔ Completado</span>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($p->details))
                            <div class="card-details">
                                <em>Detalles:</em> {{ $p->details }}
                            </div>
                            @endif

                            <div class="card-footer">
                                @can('toggle', [\App\Models\Product::class, $list])
                                <form class="prod-toggle" action="/lists/{{ $list->id }}/products/{{ $p->id_product }}/toggle" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-ghost">
                                        {{ $p->completed ? 'Desmarcar' : 'Completar' }}
                                    </button>
                                </form>
                                @endcan

                                @can('delete', [\App\Models\Product::class, $list])
                                <form class="prod-del" action="/lists/{{ $list->id }}/products/{{ $p->id_product }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-link">Quitar</button>
                                </form>
                                @endcan
                            </div>
                        </article>
                        @endforeach
                    </div>

                    @can('create', [\App\Models\Product::class, $list])
                    <form class="column-add-card prod-add">
                        @csrf
                        <input type="hidden" name="id_category" value="">
                        <input type="text" name="name" placeholder="Nuevo producto..." required>
                        <input type="text" name="details" placeholder="Detalles (opcional)">
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </form>
                    @endcan
                </section>
                @endif

                {{-- Columna para crear nueva categoría --}}
                @can('createInList', [\App\Models\Category::class, $list])
                <section class="board-column new-category-col">
                    <h3>Nueva columna</h3>
                    <p class="muted" style="margin:0 0 4px;">Crea una nueva categoría para esta lista.</p>
                    <form id="cat-add">
                        @csrf
                        <input id="catName" type="text" placeholder="Nombre de la categoría" required>
                        <button type="submit" class="btn btn-primary">Añadir columna</button>
                    </form>
                </section>
                @endcan
            </div>
        </div>

        {{-- SIDEBAR DERECHA --}}
        <aside class="sidebar">

            {{-- MIEMBROS / COMPARTIR --}}
            <section class="sidebar-card">
                <h2>Miembros</h2>

                @can('manageMembers', $list)
                @if($list->members->count())
                <ul class="sidebar-list">
                    @foreach($list->members as $m)
                    <li>
                        <div class="member-row">
                            <span>{{ $m->name }}</span>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <span class="badge">{{ $m->pivot->role }}</span>
                                @if($m->id === $list->id_user)
                                <span class="muted">(propietario)</span>
                                @else
                                <form class="member-del" action="/lists/{{ $list->id }}/members/{{ $m->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-link">Quitar</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="muted">(sin miembros todavía)</p>
                @endif

                <form id="inviteForm" style="margin-top:8px;">
                    @csrf
                    <input id="inviteEmail" type="email" placeholder="email@ejemplo.com" required>
                    <select id="inviteRole">
                        <option value="editor">Editor</option>
                        <option value="owner">Owner</option>
                        <option value="viewer">Viewer</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="margin-top:4px;">Invitar</button>
                </form>
                @else
                <p class="muted">Solo el propietario puede gestionar miembros.</p>
                @endcan
            </section>

            {{-- COMENTARIOS --}}
            <section class="sidebar-card">
                <h2>Comentarios</h2>
                <ul class="sidebar-list">
                    @forelse($list->comments as $cm)
                    <li>
                        <div class="member-row" style="margin-bottom:2px;">
                            <span class="comment-author">
                                {{ $cm->user->name ?? '—' }}
                            </span>
                            @can('delete', $cm)
                            <form class="c-del" action="/comments/{{ $cm->id_comment }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger-link">Eliminar</button>
                            </form>
                            @endcan
                        </div>
                        <div class="comment-content">
                            {{ $cm->content }}
                        </div>
                    </li>
                    @empty
                    <li><span class="muted">(sin comentarios)</span></li>
                    @endforelse
                </ul>

                @can('create', [\App\Models\Comment::class, $list])
                <form id="c-add" style="margin-top:8px;">
                    @csrf
                    <input id="cText" type="text" placeholder="Escribe un comentario" required>
                    <button type="submit" class="btn btn-primary" style="margin-top:4px;">Comentar</button>
                </form>
                @endcan
            </section>
        </aside>

    </div>
    </div>

    <script>
        const token = document.querySelector('meta[name="csrf-token"]').content;

        async function postJson(url, body) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(body)
            });
            return res;
        }

        // ---------------------------
        //   EDITAR NOMBRE LISTA
        // ---------------------------
        const editBtn = document.getElementById('editListBtn');
        if (editBtn) {
            const form = document.getElementById('editListForm');
            const input = document.getElementById('editListInput');
            const cancel = document.getElementById('cancelEdit');
            const title = document.getElementById('listName');

            editBtn.addEventListener('click', () => {
                editBtn.style.display = 'none';
                form.style.display = 'flex';
                input.focus();
            });

            cancel.addEventListener('click', () => {
                form.style.display = 'none';
                editBtn.style.display = 'inline-flex';
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const name = input.value.trim();
                if (!name) return alert('El nombre no puede estar vacío');

                const res = await fetch(`/lists/{{ $list->id }}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name
                    })
                });

                if (res.ok) {
                    if (title) title.textContent = name;
                    form.style.display = 'none';
                    editBtn.style.display = 'inline-flex';
                } else {
                    alert('Error al actualizar el nombre');
                }
            });
        }

        // ---------------------------
        //   INVITAR MIEMBRO
        // ---------------------------
        const invite = document.getElementById('inviteForm');
        if (invite) {
            invite.addEventListener('submit', async (e) => {
                e.preventDefault();
                const email = document.getElementById('inviteEmail').value.trim();
                const role = document.getElementById('inviteRole').value;
                if (!email || !role) return;

                const res = await postJson('/lists/{{ $list->id }}/members', {
                    email,
                    role
                });
                if (res.ok) {
                    location.reload();
                } else {
                    const j = await res.json().catch(() => null);
                    alert(j?.message || 'Error al invitar');
                }
            });
        }

        // Quitar miembro
        document.querySelectorAll('form.member-del').forEach(f => {
            f.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!confirm('¿Quitar este miembro de la lista?')) return;
                const res = await fetch(f.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams(new FormData(f))
                });
                if (res.ok) location.reload();
                else alert('Error al quitar miembro');
            });
        });

        // ---------------------------
        //   CREAR CATEGORÍA
        // ---------------------------
        const catAdd = document.getElementById('cat-add');
        if (catAdd) {
            catAdd.addEventListener('submit', async (e) => {
                e.preventDefault();
                const name = document.getElementById('catName').value.trim();
                if (!name) return;
                const res = await postJson(`/lists/{{ $list->id }}/categories`, {
                    name
                });
                if (res.ok) location.reload();
                else alert('Error creando categoría');
            });
        }

        // Quitar categoría
        document.querySelectorAll('form.cat-del').forEach(f => {
            f.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!confirm('¿Quitar esta categoría de la lista?')) return;
                const res = await fetch(f.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams(new FormData(f))
                });
                if (res.ok) location.reload();
                else alert('Error quitando categoría');
            });
        });

        // ---------------------------
        //   CREAR PRODUCTO (POR COLUMNA)
        // ---------------------------
        document.querySelectorAll('form.prod-add').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const name = (formData.get('name') || '').toString().trim();
                const id_category = (formData.get('id_category') || '').toString();
                const details = (formData.get('details') || '').toString().trim();

                if (!name) return;

                const body = {
                    name,
                    details
                };
                if (id_category !== '') {
                    body.id_category = id_category;
                }

                const res = await postJson(`/lists/{{ $list->id }}/products`, body);
                if (res.ok) location.reload();
                else alert('Error creando producto');
            });
        });

        // Toggle producto
        document.querySelectorAll('form.prod-toggle').forEach(f => {
            f.addEventListener('submit', async (e) => {
                e.preventDefault();
                const res = await fetch(f.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams(new FormData(f))
                });
                if (res.ok) location.reload();
                else alert('Error haciendo toggle');
            });
        });

        // Quitar producto
        document.querySelectorAll('form.prod-del').forEach(f => {
            f.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!confirm('¿Quitar este producto?')) return;
                const res = await fetch(f.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams(new FormData(f))
                });
                if (res.ok) location.reload();
                else alert('Error quitando producto');
            });
        });

        // ---------------------------
        //   COMENTARIOS
        // ---------------------------
        const cAdd = document.getElementById('c-add');
        if (cAdd) {
            cAdd.addEventListener('submit', async (e) => {
                e.preventDefault();
                const content = document.getElementById('cText').value.trim();
                if (!content) return;
                const res = await postJson(`/lists/{{ $list->id }}/comments`, {
                    content
                });
                if (res.ok) location.reload();
                else alert('Error creando comentario');
            });
        }

        document.querySelectorAll('form.c-del').forEach(f => {
            f.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!confirm('¿Eliminar este comentario?')) return;
                const res = await fetch(f.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams(new FormData(f))
                });
                if (res.ok) location.reload();
                else alert('Error borrando comentario');
            });
        });
    </script>
</body>

</html>