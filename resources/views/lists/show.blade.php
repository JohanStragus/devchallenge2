<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Lista: {{ $list->name }}</title>
</head>
<body>
  <p><a href="{{ route('lists.page') }}">← Volver</a></p>

  <h1 id="listName">{{ $list->name }}</h1>

  @can('update', $list)
    <form id="editListForm" style="display:none;">
      <input id="editListInput" type="text" value="{{ $list->name }}" required>
      <button type="submit">Guardar</button>
      <button type="button" id="cancelEdit">Cancelar</button>
    </form>
    <button id="editListBtn">✏️ Editar lista</button>
  @endcan

  <p>Propietario: <strong>{{ $list->owner->name ?? '—' }}</strong></p>

  {{-- COMPARTIR / GESTIONAR MIEMBROS --}}
  @can('manageMembers', $list)
  <section>
    <h2>Compartir lista</h2>

    @if($list->members->count())
      <ul>
        @foreach($list->members as $m)
          <li>
            {{ $m->name }} — <em>{{ $m->pivot->role }}</em>
            @if($m->id === $list->id_user)
              <small>(propietario)</small>
            @else
              <form class="member-del" action="/lists/{{ $list->id }}/members/{{ $m->id }}" method="post" style="display:inline">
                @csrf @method('DELETE')
                <button>Quitar</button>
              </form>
            @endif
          </li>
        @endforeach
      </ul>
    @else
      <p>(sin miembros todavía)</p>
    @endif

    <form id="inviteForm">
      <input id="inviteEmail" type="email" placeholder="email@ejemplo.com" required>
      <select id="inviteRole">
        <option value="editor">Editor</option>
        <option value="owner">Owner</option>
        <option value="viewer">Viewer</option>
      </select>
      <button type="submit">Invitar</button>
    </form>
  </section>
  @endcan

  {{-- CATEGORÍAS --}}
  <section>
    <h2>Categorías</h2>
    <ul id="cats">
      @forelse($list->categories as $c)
        <li>
          {{ $c->name }}
          @can('detachFromList', [\App\Models\Category::class, $list])
            <form class="cat-del" action="/lists/{{ $list->id }}/categories/{{ $c->id_category }}" method="post" style="display:inline">
              @csrf @method('DELETE')
              <button>Quitar</button>
            </form>
          @endcan
        </li>
      @empty
        <li>(sin categorías)</li>
      @endforelse
    </ul>

    @can('createInList', [\App\Models\Category::class, $list])
      <form id="cat-add">
        <input id="catName" type="text" placeholder="Nueva categoría" required>
        <button type="submit">Añadir</button>
      </form>
    @endcan
  </section>

  {{-- PRODUCTOS --}}
  <section>
    <h2>Productos</h2>
    <ul>
      @forelse($list->products as $p)
        <li>
          <div>
            <strong>{{ $p->name }}</strong>
            <small>@if($p->completed) (✔ completado) @endif</small>
          </div>
          @if(!empty($p->details))
            <div><em>Detalles:</em> {{ $p->details }}</div>
          @endif

          @can('toggle', [\App\Models\Product::class, $list])
            <form class="prod-toggle" action="/lists/{{ $list->id }}/products/{{ $p->id_product }}/toggle" method="post" style="display:inline">
              @csrf @method('PATCH')
              <button type="submit">Toggle</button>
            </form>
          @endcan

          @can('delete', [\App\Models\Product::class, $list])
            <form class="prod-del" action="/lists/{{ $list->id }}/products/{{ $p->id_product }}" method="post" style="display:inline">
              @csrf @method('DELETE')
              <button>Quitar</button>
            </form>
          @endcan
        </li>
      @empty
        <li>(sin productos)</li>
      @endforelse
    </ul>

    @can('create', [\App\Models\Product::class, $list])
      <form id="prod-add">
        <input id="prodName" type="text" placeholder="Nuevo producto" required>
        <select id="prodCat" required>
          <option value="" disabled selected>Elige categoría…</option>
          @foreach($list->categories as $c)
            <option value="{{ $c->id_category }}">{{ $c->name }}</option>
          @endforeach
        </select>
        <input id="prodDetails" type="text" placeholder="Detalles (opcional)">
        <button type="submit">Añadir</button>
      </form>
    @endcan
  </section>

  {{-- COMENTARIOS --}}
  <section>
    <h2>Comentarios</h2>
    <ul>
      @forelse($list->comments as $cm)
        <li>
          <strong>{{ $cm->user->name ?? '—' }}:</strong> {{ $cm->content }}
          @can('delete', $cm)
            <form class="c-del" action="/comments/{{ $cm->id_comment }}" method="post" style="display:inline">
              @csrf @method('DELETE')
              <button>Eliminar</button>
            </form>
          @endcan
        </li>
      @empty
        <li>(sin comentarios)</li>
      @endforelse
    </ul>

    @can('create', [\App\Models\Comment::class, $list])
      <form id="c-add">
        <input id="cText" type="text" placeholder="Escribe un comentario" required>
        <button type="submit">Comentar</button>
      </form>
    @endcan
  </section>

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
    form.style.display = 'block';
    input.focus();
  });

  if(cancel){
    cancel.addEventListener('click', () => {
      form.style.display = 'none';
      editBtn.style.display = 'inline';
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
      editBtn.style.display = 'inline';
    }else{
      alert('Error al actualizar el nombre');
    }
  });
}
</script>
</body>
</html>
