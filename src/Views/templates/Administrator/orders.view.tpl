<div class="container">
<h1 class="title">Pedidos</h1>
<div class="tabs">
  <span class="tab active" data-status="PEND">Pendiente</span>
  <span class="tab" data-status="PAG">Aceptado</span>
  <span class="tab" data-status="ENV">Enviado</span>
</div>
<div class="table-container">
  <table class="order-table">
  <thead>
    <tr>
      <th>ID Pedido</th>
      <th>Cliente</th>
      <th>Fecha</th>
      <th>Total</th>
      <th>Estado</th>
      <th colspan="2"></th>
    </tr>
  </thead>
  <tbody id="orderTable">
    {{foreach pedidos}}
    <tr data-status="{{estado}}">
      <td>{{pedidoId}}</td>
      <td>{{username}}</td>
      <td>{{fchpedido}}</td>
      <td>{{total}}</td>
       <td><span class="badge status-{{estado}}">{{estado}}</span></td>
        <td class="actions">
          <a class="btn view" href="index.php?page=Administrator-Order&mode=DSP&id={{pedidoId}}">
            <i class="fas fa-eye"></i> Ver detalles
          </a>
          {{if ~isUpdateEnabled}}
          <a class="btn edit" href="index.php?page=Administrator-Order&mode=UPD&id={{pedidoId}}">
            <i class="fas fa-edit"></i> Cambiar estado
          </a>
          {{endif ~isUpdateEnabled}}
    </tr>
    {{endfor pedidos}}
  </tbody>
</table>
</div>
</div>
<script>
  const tabs = document.querySelectorAll('.tab'); const rows =
  document.querySelectorAll('#orderTable tr'); tabs.forEach(tab => {
  tab.addEventListener('click', () => { tabs.forEach(t =>
  t.classList.remove('active')); tab.classList.add('active'); const status =
  tab.dataset.status; rows.forEach(row => { row.style.display =
  (row.dataset.status === status) ? '' : 'none'; }); }); }); tabs[0].click();
</script>