<div>
    <p>Hola,</p>
    @if($iusers)
      <p>Esta es la lista de usuarios a quienes se les vence el Sarlaft en los proximos 30 diás.</p>
      <table border="1" width="100%" cellspacing="1" cellpadding="1">
          <tr border="1" width="100%" cellspacing="1" cellpadding="1">
            <th>Nombre</th>
            <th>Identificación</th>
            <th>Vencimiento</th>
          </tr>
        @foreach ( $iusers as $u )
          <tr border="1" width="100%" cellspacing="1" cellpadding="1">
            <td>{{ $u->name }}</td>
            <td>{{ $u->document }}</td>
            <td>{{ $u->sarlaf_duedate }}</td>
          </tr>
        @endforeach
      </table>
    @else
      <p>No existe usuarios a quienes se les vence el Sarlaft en los próximos 30 días.</p>
    @endif
    
    
    
</div>