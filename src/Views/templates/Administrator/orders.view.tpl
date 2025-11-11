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
      <td data-label="ID Pedido">{{pedidoId}}</td>
      <td data-label="Cliente">{{username}}</td>
      <td data-label="Fecha">{{fchpedido}}</td>
      <td data-label="Total">{{total}}</td>
      <td data-label="Estado"><span class="badge status-{{estado}}">{{estado}}</span></td>
      <td class="actions" data-label="Acciones">
        <a class="btn view" href="index.php?page=Administrator-Order&mode=DSP&id={{pedidoId}}">
          <i class="fas fa-eye"></i> <span class="btn-text">Ver detalles</span>
        </a>
        {{if ~isUpdateEnabled}}
        <a class="btn edit" href="index.php?page=Administrator-Order&mode=UPD&id={{pedidoId}}">
          <i class="fas fa-edit"></i> <span class="btn-text">Cambiar estado</span>
        </a>
        {{endif ~isUpdateEnabled}}
      </td>
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