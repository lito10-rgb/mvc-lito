<?php

class OrderController {

    // =====================
    //  MERCADOPAGO SUCCESS
    // =====================
    public function success() {
        echo "Pago aprobado. Gracias por tu compra.";
    }

    public function failure() {
        echo "El pago falló o fue cancelado.";
    }

    public function pending() {
        echo "Tu pago quedó pendiente.";
    }

    // ===========================
    //  WEBHOOK MERCADOPAGO
    // ===========================
    public function webhookMP() {

        $input = file_get_contents("php://input");
        $event = json_decode($input, true);

        // Guardar log (opcional)
        file_put_contents(__DIR__ . "/../../logs/mp_log.txt", $input . "\n\n", FILE_APPEND);

        if (!isset($event['data']['id'])) {
            http_response_code(400);
            return;
        }

        // ID real del pago
        $paymentId = $event['data']['id'];

        // Obtener detalles desde MercadoPago
        $payment = MercadoPago\Payment::find_by_id($paymentId);

        // Crear registro en orders
        Order::create([
            'user_id'       => $_SESSION['user_id'] ?? null,
            'preference_id' => $payment->preference_id,
            'mp_payment_id' => $payment->id,
            'amount'        => $payment->transaction_amount,
            'status'        => $payment->status,
            'payload'       => json_encode($event)
        ]);

        http_response_code(200);
        echo json_encode(['status' => 'ok']);
    }

    // =====================
    //  PAYPAL SUCCESS
    // =====================
    public function paypalSuccess() {
        echo "Pago PayPal completado correctamente.";
    }

    public function paypalCancel() {
        echo "Cancelaste el pago de PayPal.";
    }

    // ======================
    //  WEBHOOK PAYPAL
    // ======================
    public function webhookPaypal() {

        $data = json_decode(file_get_contents('php://input'), true);

        // Guardar log
        file_put_contents(__DIR__ . "/../../logs/paypal_log.txt", json_encode($data) . "\n\n", FILE_APPEND);

        if (!isset($data['resource']['id'])) {
            http_response_code(400);
            return;
        }

        $paymentId = $data['resource']['id'];
        $amount    = $data['resource']['amount']['value'] ?? 0;
        $status    = $data['event_type'] ?? 'unknown';

        Order::create([
            'user_id'       => $_SESSION['user_id'] ?? null,
            'preference_id' => null, // PayPal no usa preference_id
            'mp_payment_id' => $paymentId,
            'amount'        => $amount,
            'status'        => $status,
            'payload'       => json_encode($data)
        ]);

        http_response_code(200);
        echo json_encode(['status' => 'ok']);
    }
}
