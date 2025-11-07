<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Lista: {{ $list->name }}</title>
</head>
<body>
  <p><a href="{{ route('lists.page') }}">← Volver</a></p>
  <h1>{{ $list->name }}</h1>
  <p>Propietario: <strong>{{ $list->owner->name ?? '—' }}</strong></p>

  {{-- COMPARTIR (solo owner) --}}
  @can('manageMembers', $list)
  <section>
    <h2>Compartir lista</h2>

    <ul>
      @foreach($list->members as $m)
        <li>{{ $m->name }} — <em>{{ $m->pivot->role }}</em></li>
      @endforeach
    </ul>

    <form id="inviteForm">
      <input id="inviteEmail" type="email" placeholder="email@ejemplo.com" required>
      <select id="inviteRole">
        <option value="editor">Editor</option>
        <option value="owner">Owner</option>
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
          {{ $p->name }}
          <small>@if($p->completed) (✔ completado) @endif</small>

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

// Compartir por email
const invite = document.getElementById('inviteForm');
if(invite){
  invite.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const email = document.getElementById('inviteEmail').value.trim();
    const role  = document.getElementById('inviteRole').value;
    const res = await fetch('/lists/{{ $list->id }}/members', {
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
      body: JSON.stringify({ email, role })
    });
    if(res.ok) location.reload();
    else {
      const j = await res.json().catch(()=>null);
      alert(j?.message || 'Error al invitar');
    }
  });
}

// Crear categoría
const catAdd = document.getElementById('cat-add');
if(catAdd){
  catAdd.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const name = document.getElementById('catName').value.trim();
    if(!name) return;
    const res = await fetch(`/lists/{{ $list->id }}/categories`, {
      method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
      body: JSON.stringify({name})
    });
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
    if(!name || !id_category) return;
    const res = await fetch(`/lists/{{ $list->id }}/products`, {
      method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
      body: JSON.stringify({name, id_category})
    });
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
    const res = await fetch(`/lists/{{ $list->id }}/comments`, {
      method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
      body: JSON.stringify({content})
    });
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
</script>
</body>
</html>
