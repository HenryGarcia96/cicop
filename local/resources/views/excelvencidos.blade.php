<table>
<thead>
<tr>
    <th>Folio</th>
    <th>Cliente</th>
    <th>Fecha de Pago</th>
    <th>Tipo Pago</th>
    <th>$</th>
    <th></th>
</tr>
</thead>
<tbody>
</tbody>
@foreach($result as $fila)
    <tr>
        <td>{{$fila['folio']}}</td>
        <td>{{$fila['nombre']}}</td>
        <td>{{$fila['fecha']}}</td>
        <td>{{$fila['tipo_pago']}}</td>
        @if($fila['tipo']==1)
                <td>{{(($fila['tipo_pago']=='Mensual')?$fila['mensual']:$fila['quincenal'])}}</td>
                <td></td>
        @else if($fila['tipo']==2)
                <td>{{$fila['especiales']}}</td>
                <td>Pago Especial</td>
        @endif
    </tr>
@endforeach

</table>