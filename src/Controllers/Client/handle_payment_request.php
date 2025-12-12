<?php

use Utilities\Mailer;

function getDbConnection(): \PDO {
    try {
        $db = new \PDO(
            'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
            getenv('DB_USER'),
            getenv('DB_PASS')
        );
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (\PDOException $e) {
        error_log("Error de conexión a DB: " . $e->getMessage());
        throw $e; 
    }
}



function getPedidoDetailsFromDB(\PDO $db, string $pedidoId, string $payerEmail): ?array {
    
    return [
        'pedidoId' => $pedidoId,
        'fecha' => date('Y-m-d H:i:s'),
        'nombre' => 'Cliente Confirmado',
        'correo' => $payerEmail,
        'total' => 55.50, 
        'productos' => [
            ['productName' => 'Café Espresso', 'cantidad' => 2, 'precio_unitario' => 2.50, 'subtotal' => 5.00],
            ['productName' => 'Pastel de Chocolate', 'cantidad' => 1, 'precio_unitario' => 5.00, 'subtotal' => 5.00],
        ]
    ];
}



function renderReciboHtml(array $datosPedido): string {

    $template = file_get_contents('../templates/recibo_confirmacion.html'); 
    

    $template = str_replace('{{pedidoId}}', htmlspecialchars($datosPedido['pedidoId']), $template);

    $productosHtml = '';
    foreach ($datosPedido['productos'] as $producto) {
        $productosHtml .= '<tr>';
        $productosHtml .= '<td>' . htmlspecialchars($producto['productName']) . '</td>';
        $productosHtml .= '<td>' . htmlspecialchars($producto['cantidad']) . '</td>';
        $productosHtml .= '<td>$' . number_format($producto['precio_unitario'], 2) . '</td>';
        $productosHtml .= '<td>$' . number_format($producto['subtotal'], 2) . '</td>';
        $productosHtml .= '</tr>';
    }
    
    return str_replace('{{productos_table_rows}}', $productosHtml, $template);
}


$db = getDbConnection(); 

$payload = @file_get_contents('php://input');
$eventData = json_decode($payload, true); 


if (empty($eventData) || $eventData['event_type'] !== 'CHECKOUT.ORDER.COMPLETED') {
    http_response_code(200); exit();
}



$purchaseUnit = $eventData['resource']['purchase_units'][0] ?? null;
$payerEmail = $eventData['resource']['payer']['email_address'] ?? null;
$pedidoId = $purchaseUnit['reference_id'] ?? null; 

if (empty($pedidoId) || empty($payerEmail)) {
    http_response_code(400); exit();
}


try {
    $stmt = $db->prepare("UPDATE pedidos SET estado = 'Pagado', transaccion_id = :tid WHERE id = :id");
    $stmt->execute([':id' => $pedidoId, ':tid' => $eventData['resource']['id']]);
    
} catch (\PDOException $e) {
    http_response_code(500); 
    error_log("Error crítico de DB en Controller: " . $e->getMessage());
    exit();
}


$datosPedido = getPedidoDetailsFromDB($db, $pedidoId, $payerEmail); 

if ($datosPedido) {
    $cuerpoHTML = renderReciboHtml($datosPedido); 
    Mailer::sendHtmlEmail(
        $datosPedido['correo'],
        '✅ ¡Tu Recibo de COFFEESHOP! Pedido #' . $datosPedido['pedidoId'],
        $cuerpoHTML
    );
}