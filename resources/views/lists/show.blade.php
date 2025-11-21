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
            overflow-y: auto;
            position: relative;
        }

        /* Fondo orgánico */
        .bg-shape {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            border-radius: 50% 40% 60% 50%;
            opacity: 0.9;
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
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 16px 48px;
        }

        /* Top bar */
        .top-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: var(--text-main);
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            font-size: 0.85rem;
        }

        .back-link:hover {
            background: rgba(0, 0, 0, 0.28);
        }

        .list-header {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .list-title {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .owner-pill {
            font-size: 0.85rem;
            color: var(--text-soft);
        }

        .owner-pill strong {
            color: var(--text-main);
        }

        /* Botón editar lista */
        .edit-list-btn {
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.5);
            background: rgba(0,0,0,0.15);
            color: var(--text-main);
            font-size: 0.9rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .edit-list-btn:hover {
            background: rgba(0,0,0,0.27);
        }

        /* Card general */
        .grid {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1.3fr);
            gap: 18px;
        }

        .card {
            background: var(--card-bg);
            border-radius: 18px;
            padding: 16px 18px;
            border: 1px solid var(--card-border);
            backdrop-filter: blur(18px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        .card + .card {
            margin-top: 12px;
        }

        .card h2 {
            font-size: 1.1rem;
            margin: 0 0 10px;
        }

        .card small {
            color: var(--text-soft);
        }

        /* Inputs & selects (estilo similar login) */
        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            background: rgba(255,255,255,0.05);
            color: var(--text-main);
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.7);
            padding: 8px 10px;
            font-size: 0.9rem;
            margin-bottom: 8px;
            backdrop-filter: blur(4px);
        }

        input::placeholder {
            color: rgba(255,255,255,0.6);
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: rgba(255,255,255,0.95);
            box-shadow: 0 0 8px rgba(255,255,255,0.5);
        }

        select {
            background-color: rgba(255,255,255,0.08);
        }

        /* Botones */
        button,
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 8px 14px;
            border-radius: 999px;
            border: none;
            font-size: 0.85rem;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(90deg, #ff7ac5, #ff4b8b);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.6);
        }

        .btn-primary:hover {
            filter: brightness(1.05);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-soft);
        }

        .btn-ghost:hover {
            background: rgba(255,255,255,0.08);
        }

        .btn-danger-link {
            background: transparent;
            color: #ffd3e6;
            font-size: 0.8rem;
            padding: 0;
        }

        .btn-danger-link:hover {
            text-decoration: underline;
        }

        /* Listas dentro de cards */
        .list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list li {
            padding: 6px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .list li:last-child {
            border-bottom: none;
        }

        .list .row-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tag {
            display: inline-flex;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(0,0,0,0.28);
            font-size: 0.7rem;
            color: var(--text-soft);
        }

        .muted {
            color: var(--text-soft);
            font-size: 0.85rem;
        }

        .product-meta {
            font-size: 0.8rem;
            color: var(--text-soft);
        }

        .completed-tag {
            color: #b8ffce;
            font-size: 0.8rem;
        }

        a {
            color: #ffe2f2;
            text-decoration: underline;
            text-decoration-thickness: 1px;
        }

        a:hover {
            text-decoration-thickness: 2px;
        }

        /* Editar nombre de lista */
        #editListForm {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        #editListInput {
            flex: 1;
            min-width: 180px;
            margin-bottom: 0;
        }

        .edit-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        /* Comentarios */
        .comment-author {
            font-weight: 500;
        }

        .comment-content {
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 600px) {
            .list-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
<div class="bg-shape one"></div>
<div class="bg-shape two"></div>

<div class="page-wrapper">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="back-link">
            <span>←</span> <span>Volver a mis listas</span>
        </a>

        <div class="list-header">
            <div class="list-title" id="listName">{{ $list->name }}</div>
            <div class="owner-pill">
                Propietario: <strong>{{ $list->owner->name ?? '—' }}</strong>
            </div>
        </div>

        @can('update', $list)
            <button id="editListBtn" class="edit-list-btn">
                <span>Editar nombre</span>
            </button>
        @endcan>
    </div>

    @can('update', $list)
        <form id="editListForm" style="display:none;">
            <input id="editListInput" type="text" value="{{ $list->name }}" required>
            <div class="edit-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" id="cancelEdit" class="btn btn-ghost">Cancelar</button>
            </div>
        </form>
    @endcan

    <div class="grid">

        {{-- COLUMNA IZQUIERDA: miembros, categorías, productos --}}
        <div>

            {{-- COMPARTIR / GESTIONAR MIEMBROS --}}
            @can('manageMembers', $list)
                <section class="card">
                    <h2>Compartir lista</h2>
                    @if($list->members->count())
                        <ul class="list">
                            @foreach($list->members as $m)
                                <li>
                                    <div class="row-main">
                                        <span>{{ $m->name }}</span>
                                        <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                            <span class="tag">{{ $m->pivot->role }}</span>
                                            @if($m->id === $list->id_user)
                                                <small class="muted">(propietario)</small>
                                            @else
                                                <form class="member-del" action="/lists/{{ $list->id }}/members/{{ $m->id }}" method="post">
                                                    @csrf @method('DELETE')
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

                    <form id="inviteForm">
                        <input id="inviteEmail" type="email" placeholder="email@ejemplo.com" required>
                        <select id="inviteRole">
                            <option value="editor">Editor</option>
                            <option value="owner">Owner</option>
                            <option value="viewer">Viewer</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="margin-top:4px;">Invitar</button>
                    </form>
                </section>
            @endcan

            {{-- CATEGORÍAS --}}
            <section class="card">
                <h2>Categorías</h2>
                <ul id="cats" class="list">
                    @forelse($list->categories as $c)
                        <li>
                            <div class="row-main">
                                <span>{{ $c->name }}</span>
                                @can('detachFromList', [\App\Models\Category::class, $list])
                                    <form class="cat-del" action="/lists/{{ $list->id }}/categories/{{ $c->id_category }}" method="post">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-link">Quitar</button>
                                    </form>
                                @endcan
                            </div>
                        </li>
                    @empty
                        <li><span class="muted">(sin categorías)</span></li>
                    @endforelse
                </ul>

                @can('createInList', [\App\Models\Category::class, $list])
                    <form id="cat-add" style="margin-top:8px;">
                        <input id="catName" type="text" placeholder="Nueva categoría" required>
                        <button type="submit" class="btn btn-primary">Añadir categoría</button>
                    </form>
                @endcan
            </section>

            {{-- PRODUCTOS --}}
            <section class="card">
                <h2>Productos</h2>
                <ul class="list">
                    @forelse($list->products as $p)
                        <li>
                            <div class="row-main">
                                <div>
                                    <strong>{{ $p->name }}</strong>
                                    @if($p->completed)
                                        <span class="completed-tag">✔ completado</span>
                                    @endif
                                </div>
                                <div style="display:flex; align-items:center; flex-wrap:wrap; gap:8px;">
                                    @can('toggle', [\App\Models\Product::class, $list])
                                        <form class="prod-toggle" action="/lists/{{ $list->id }}/products/{{ $p->id_product }}/toggle" method="post">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-ghost">Toggle</button>
                                        </form>
                                    @endcan

                                    @can('delete', [\App\Models\Product::class, $list])
                                        <form class="prod-del" action="/lists/{{ $list->id }}/products/{{ $p->id_product }}" method="post">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger-link">Quitar</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>

                            @if(!empty($p->details))
                                <div class="product-meta">
                                    <em>Detalles:</em> {{ $p->details }}
                                </div>
                            @endif
                        </li>
                    @empty
                        <li><span class="muted">(sin productos)</span></li>
                    @endforelse
                </ul>

                @can('create', [\App\Models\Product::class, $list])
                    <form id="prod-add" style="margin-top:10px;">
                        <input id="prodName" type="text" placeholder="Nuevo producto" required>
                        <select id="prodCat" required>
                            <option value="" disabled selected>Elige categoría…</option>
                            @foreach($list->categories as $c)
                                <option value="{{ $c->id_category }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <input id="prodDetails" type="text" placeholder="Detalles (opcional)">
                        <button type="submit" class="btn btn-primary" style="margin-top:4px;">Añadir producto</button>
                    </form>
                @endcan
            </section>

        </div>

        {{-- COLUMNA DERECHA: comentarios --}}
        <div>
            <section class="card">
                <h2>Comentarios</h2>
                <ul class="list">
                    @forelse($list->comments as $cm)
                        <li>
                            <div class="row-main">
                                <span class="comment-author">
                                    {{ $cm->user->name ?? '—' }}
                                </span>
                                @can('delete', $cm)
                                    <form class="c-del" action="/comments/{{ $cm->id_comment }}" method="post">
                                        @csrf @method('DELETE')
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
                    <form id="c-add" style="margin-top:10px;">
                        <input id="cText" type="text" placeholder="Escribe un comentario" required>
                        <button type="submit" class="btn btn-primary" style="margin-top:4px;">Comentar</button>
                    </form>
                @endcan
            </section>
        </div>

    </div>
</div>

<script>
const token = document.querySelector('meta[name="csrf-token"]').content;
async function postJson(url, body){
  const res = await fetch(url, {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
    body: JSON.stringify(body)
  });
  return res;
}

// Invitar miembro
const invite = document.getElementById('inviteForm');
if(invite){
  invite.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const email = document.getElementById('inviteEmail').value.trim();
    const role  = document.getElementById('inviteRole').value;
    const res = await postJson('/lists/{{ $list->id }}/members', { email, role });
    if(res.ok) location.reload();
    else { const j = await res.json().catch(()=>null); alert(j?.message || 'Error al invitar'); }
  });
}

// Quitar miembro
document.querySelectorAll('form.member-del').forEach(f=>{
  f.addEventListener('submit', async (e)=>{
    e.preventDefault();
    if(!confirm('¿Quitar este miembro de la lista?')) return;
    const res = await fetch(f.action, {
      method:'POST',
      headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'},
      body: new URLSearchParams(new FormData(f))
    });
    if(res.ok) location.reload(); else alert('Error al quitar miembro');
  });
});

// Crear categoría
const catAdd = document.getElementById('cat-add');
if(catAdd){
  catAdd.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const name = document.getElementById('catName').value.trim();
    if(!name) return;
    const res = await postJson(`/lists/{{ $list->id }}/categories`, {name});
    if(res.ok) location.reload(); else alert('Error categoría');
  });
}
document.querySelectorAll('form.cat-del').forEach(f=>{
  f.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const res = await fetch(f.action, { method:'POST', headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}, body: new URLSearchParams(new FormData(f)) });
    if(res.ok) location.reload(); else alert('Error quitando categoría');
  });
});

// Crear producto
const prodAdd = document.getElementById('prod-add');
if(prodAdd){
  prodAdd.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const name = document.getElementById('prodName').value.trim();
    const id_category = document.getElementById('prodCat').value;
    const details = document.getElementById('prodDetails').value.trim();
    if(!name || !id_category) return;
    const res = await postJson(`/lists/{{ $list->id }}/products`, {name, id_category, details});
    if(res.ok) location.reload(); else alert('Error producto');
  });
}
document.querySelectorAll('form.prod-toggle').forEach(f=>{
  f.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const res = await fetch(f.action, { method:'POST', headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}, body: new URLSearchParams(new FormData(f)) });
    if(res.ok) location.reload(); else alert('Error toggle');
  });
});
document.querySelectorAll('form.prod-del').forEach(f=>{
  f.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const res = await fetch(f.action, { method:'POST', headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}, body: new URLSearchParams(new FormData(f)) });
    if(res.ok) location.reload(); else alert('Error quitando producto');
  });
});

// Crear comentario
const cAdd = document.getElementById('c-add');
if(cAdd){
  cAdd.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const content = document.getElementById('cText').value.trim();
    if(!content) return;
    const res = await postJson(`/lists/{{ $list->id }}/comments`, {content});
    if(res.ok) location.reload(); else alert('Error comentario');
  });
}
document.querySelectorAll('form.c-del').forEach(f=>{
  f.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const res = await fetch(f.action, { method:'POST', headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}, body: new URLSearchParams(new FormData(f)) });
    if(res.ok) location.reload(); else alert('Error borrando comentario');
  });
});

// Editar nombre de la lista
const editBtn = document.getElementById('editListBtn');
if(editBtn){
  const form = document.getElementById('editListForm');
  const input = document.getElementById('editListInput');
  const cancel = document.getElementById('cancelEdit');
  const h1 = document.getElementById('listName') || document.querySelector('h1');

  editBtn.addEventListener('click', () => {
    editBtn.style.display = 'none';
    form.style.display = 'flex';
    input.focus();
  });

  if(cancel){
    cancel.addEventListener('click', () => {
      form.style.display = 'none';
      editBtn.style.display = 'inline-flex';
    });
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const name = input.value.trim();
    if(!name) return alert('El nombre no puede estar vacío');

    const res = await fetch(`/lists/{{ $list->id }}`, {
      method:'PUT',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
      body: JSON.stringify({ name })
    });

    if(res.ok){
      if(h1) h1.textContent = name; else location.reload();
      form.style.display = 'none';
      editBtn.style.display = 'inline-flex';
    }else{
      alert('Error al actualizar el nombre');
    }
  });
}
</script>
</body>
</html>
