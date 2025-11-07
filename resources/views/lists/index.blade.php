<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mis listas</title>
</head>
<body>
  <h1>Mis listas</h1>

  <h3>Crear nueva</h3>
  <form id="create">
    <input id="name" type="text" placeholder="Nombre" required>
    <button type="submit">Crear</button>
  </form>

  <h2>Propias</h2>
  <ul>
    @forelse($owned as $l)
      <li>
        <a href="{{ route('lists.show', $l) }}">{{ $l->name }}</a>
        <form class="del" action="/lists/{{ $l->id }}" method="post" style="display:inline">
          @csrf @method('DELETE')
          <button>Eliminar</button>
        </form>
      </li>
    @empty
      <li>(sin listas)</li>
    @endforelse
  </ul>

  <h2>Compartidas</h2>
  <ul>
    @forelse($shared as $l)
      <li>
        <a href="{{ route('lists.show', $l) }}">{{ $l->name }}</a>
        <small>(compartida)</small>
      </li>
    @empty
      <li>(ninguna)</li>
    @endforelse
  </ul>

<script>
const token = document.querySelector('meta[name="csrf-token"]').content;

document.getElementById('create').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const name = document.getElementById('name').value.trim();
  if(!name) return;
  const res = await fetch('/lists', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':token,'Accept':'application/json'},
    body: JSON.stringify({name})
  });
  if(res.ok) location.reload(); else alert('Error creando');
});

document.querySelectorAll('form.del').forEach(f=>{
  f.addEventListener('submit', async (e)=>{
    e.preventDefault();
    if(!confirm('¿Eliminar lista?')) return;
    const res = await fetch(f.action, { method:'POST', headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}, body: new URLSearchParams(new FormData(f)) });
    if(res.ok) location.reload(); else alert('Error eliminando');
  });
});
</script>
</body>
</html>
