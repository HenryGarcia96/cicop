<table>
<thead>
<tr>
    <th>Folio</th>
    <th>Cliente</th>
    <th>Mza</th>
    <th>Lote</th>
    <th>Fecha Pago</th>
    <th>Fecha Registro</th>
    <th>Correspondiente</th>
    <th>Tipo Pago</th>
    <th>Institucion Bancaria</th>
    <th>Forma pago</th>
    <th>Ticket</th>
    <th>Cantidad</th>                            
</tr>
</thead>
<tbody>
</tbody>
<?php
$total=0;
?>
@foreach($result as $fila)
    <tr>
        <td>{{$fila["folio"]}}</td>
        <td>{{$fila["nombre"]}}</td>
        <td>{{$fila["manzana"]}}</td>
        <td>{{$fila["lote"]}}</td>
        <td>{{substr($fila["fecha_pago"],0,10)}}</td>
        <td>{{$fila["fecha_registro"]}}</td>
        <td>{{substr($fila["fecha"],0,10)}}</td>
        <td>{{$fila["descripcion"]}}</td>
        <td>{{$fila["institucion_bancaria"]}}</td>
        <td>{{$fila["forma_pago"]}}</td>
        <td>{{$fila["ticket"]}}</td>
        <td>{{number_format($fila["importe"])}}</td>
    </tr>
    <?php
    $total +=$fila["importe"];
    ?>
@endforeach
<tr>
        <td>Total</td>
        <td>{{number_format($total)}}</td>
</tr>
</table>