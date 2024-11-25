<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'php/conexion_be.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$pedido_id = isset($_GET['pedido_id']) ? $_GET['pedido_id'] : null;

$sql = "SELECT SUM(total) AS total, SUM(cantidad) AS total_cantidad FROM DetallePedido WHERE id_pedido = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();

// Usamos bind_result en lugar de get_result
$stmt->bind_result($total, $total_cantidad);

if ($stmt->fetch()) {
    // Agregar costo de envío si la cantidad de productos es menor a 7
    $costo_envio = ($total_cantidad < 7) ? 30000 : 0;
    $total_final = $total + $costo_envio;

    $_SESSION['total'] = $total_final;
} else {
    $_SESSION['total'] = 0;
}

$stmt->close();
$conexion->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Método de Pago - Carnicería Premium</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f3f3;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCI+CiAgPHBhdGggZD0iTTAgMGg2MHY2MEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zMCAzMG0tMjggMGEyOCAyOCAwIDEgMCA1NiAwYTI4IDI4IDAgMSAwIC01NiAwIiBmaWxsPSJyZ2JhKDIwMCwgMTAwLCAxMDAsIDAuMSkiLz4KPC9zdmc+');
            padding: 20px;
            color: #2c1810;
        }

        .payment-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(44, 24, 16, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        h2 {
            color: #8b0000;
            font-size: 2.2em;
            margin-bottom: 10px;
            font-family: 'Georgia', serif;
        }

        .subtitle {
            color: #666;
            font-size: 1.1em;
        }

        .payment-methods {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }

        .payment-method {
            border: 2px solid #e6d5d5;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            background-color: #fff;
        }

        .payment-method:hover {
            border-color: #8b0000;
            transform: translateY(-2px);
        }

        .payment-method.selected {
            border-color: #8b0000;
            background-color: #fff9f9;
        }

        .payment-method img {
            width: 40px;
            height: 40px;
            margin-right: 15px;
            object-fit: contain;
        }

        .payment-method-info {
            flex: 1;
        }

        .payment-method-title {
            font-weight: bold;
            color: #2c1810;
            margin-bottom: 5px;
            font-size: 1.1em;
        }

        .payment-method-description {
            color: #666;
            font-size: 0.9em;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c1810;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e6d5d5;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #8b0000;
            outline: none;
        }

        .button {
            background-color: #8b0000;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .button:hover {
            background-color: #6d0000;
            transform: translateY(-2px);
        }

        .payment-details {
            display: none;
            background-color: #fff9f9;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid #e6d5d5;
        }

        .payment-details.active {
            display: block;
        }

        .delivery-address {
            display: none;
        }

        .delivery-address.active {
            display: block;
        }

        .total-amount {
            background-color: #fff9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 2px solid #8b0000;
            text-align: center;
        }

        .total-amount h3 {
            color: #8b0000;
            margin-bottom: 10px;
        }

        .amount {
            font-size: 2em;
            color: #2c1810;
            font-weight: bold;
        }

        .payment-details {
    display: none;
}

.payment-details.active {
    display: block;
}

#paypal-details {
    background-color: #fff9f9;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #e6d5d5;
    margin-top: 20px;
}

#paypal-details label {
    display: block;
    margin-bottom: 10px;
    font-size: 1.1em;
    color: #2c1810;
    font-weight: bold;
}

#paypal-details input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e6d5d5;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    margin-bottom: 20px;
}

#paypal-details input:focus {
    border-color: #8b0000;
    outline: none;
}

#paypal-details input[type="email"] {
    background-color: #fdfdfd;
}

#paypal-details input[type="tel"] {
    background-color: #fdfdfd;
}

#paypal-details .paypal-input-container {
    display: flex;
    gap: 20px;
    flex-direction: column;
}


    </style>
</head>
<body>
    <div class="payment-container">
        <div class="header">
            <h2>🥩 Finalizar Pedido</h2>
            <p class="subtitle">Seleccione su método de pago preferido</p>
        </div>

        <div class="total-amount">
            <h3>Total a Pagar</h3>
    $<?php echo number_format($_SESSION['total'] ?? 0, 2); ?>
       </div>

        
      <form action="procesar_pago.php?pedido_id=<?php echo $pedido_id; ?>" method="POST" id="payment-form" onsubmit="return validatePaymentForm()">
            <div class="payment-methods">
                <div class="payment-method" onclick="selectPayment('card')">
                    <input type="radio" name="payment_method" value="card" id="card">
                    <div class="payment-method-info">
                        <div class="payment-method-title">Tarjeta de Crédito/Débito</div>
                        <div class="payment-method-description">Pago seguro con tarjeta bancaria</div>
                    </div>
                </div>

                <div class="payment-method" onclick="selectPayment('paypal')">
                    <input type="radio" name="payment_method" value="paypal" id="paypal">
                    <div class="payment-method-info">
                        <div class="payment-method-title">🌐 PayPal</div>
                        <div class="payment-method-description">Pago rápido y seguro con PayPal</div>
                    </div>
                </div>

                <div class="payment-method" onclick="selectPayment('transfer')">
                    <input type="radio" name="payment_method" value="transfer" id="transfer">
                    <div class="payment-method-info">
                        <div class="payment-method-title">🏦 Transferencia Bancaria</div>
                        <div class="payment-method-description">Transferencia directa a nuestra cuenta</div>
                    </div>
                </div>

                <div class="payment-method" onclick="selectPayment('cash')">
                    <input type="radio" name="payment_method" value="cash" id="cash">
                    <div class="payment-method-info">
                        <div class="payment-method-title">💵 Pago Contra Entrega</div>
                        <div class="payment-method-description">Pague en efectivo al recibir su pedido</div>
                    </div>
                </div>

               <div class="form-group">
                    <label for="delivery_time">Horario preferido de entrega</label>
                    <select id="delivery_time" name="delivery_time">
                        <option value="">Seleccione un horario</option>
                        <option value="morning">Mañana (9:00 - 12:00)</option>
                        <option value="afternoon">Tarde (12:00 - 16:00)</option>
                    </select>
                </div>




            </div>

            <div class="payment-details" id="card-details">
                <div class="form-group">
                    <label for="card_number">Número de tarjeta</label>
                    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                </div>

                <div class="form-group">
                    <label for="card_holder">Titular de la tarjeta</label>
                    <input type="text" id="card_holder" name="card_holder" placeholder="Nombre como aparece en la tarjeta">
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group">
                        <label for="expiry">Fecha de expiración</label>
                        <input type="text" id="expiry" name="expiry" placeholder="MM/AA">
                    </div>

                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="number" id="cvv" name="cvv" placeholder="123">
                    </div>
                </div>
            </div>

            <div class="payment-details" id="transfer-details">
                <div class="form-group">
                    <h3>Datos Bancarios</h3>
                    <p><strong>Banco:</strong> Bancolombia</p>
                    <p><strong>Titular:</strong> Carnes B&R.</p>
                    <p><strong>Cuenta:</strong> 1234-5678-9012-3456</p>
                     <div class="form-group">
            <label for="transfer_reference">Código de referencia</label>
            <input type="text" id="transfer_reference" name="transfer_reference" placeholder="Ingrese su código de referencia">
                </div>
                </div>
            </div>


    <div id="paypal-details" class="payment-details">
    <label for="paypal_email">Correo de PayPal:</label>
    <input type="email" id="paypal_email" name="paypal_email" placeholder="Correo electrónico asociado a PayPal">
    <label for="paypal_phone">Teléfono:</label>
    <input type="tel" id="paypal_phone" name="paypal_phone" placeholder="Número de teléfono">
    </div>
            <div class="delivery-address" id="delivery-info">
                <div class="form-group">
                    <label for="address">Dirección de entrega</label>
                    <textarea id="address" name="address" rows="3" placeholder="Ingrese su dirección completa"></textarea>
                </div>

                <div class="form-group">
                    <label for="phone">Teléfono de contacto</label>
                    <input type="text" id="phone" name="phone" placeholder="Teléfono para coordinar la entrega">
                </div>
            </div>

            <button type="submit" class="button">Confirmar Pedido</button>
        </form>
    </div>

    <script>

  function selectPayment(method) {
    // Remover clase 'selected' de todos los métodos
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Añadir clase 'selected' al método seleccionado
    document.querySelector(`#${method}`).closest('.payment-method').classList.add('selected');
    
    // Marcar el radio correspondiente
    document.getElementById(method).checked = true;
    
    // Ocultar todos los detalles de pago
    document.querySelectorAll('.payment-details').forEach(el => {
        el.classList.remove('active');
    });
    
    // Mostrar los detalles correspondientes según el método seleccionado
    if (method === 'card') {
        document.getElementById('card-details').classList.add('active');
    } else if (method === 'transfer') {
        document.getElementById('transfer-details').classList.add('active');
    } else if (method === 'paypal') {  // Asegúrate de que esta parte esté correcta
        document.getElementById('paypal-details').classList.add('active');
    }
    
    // Mostrar información de entrega para pagos contra entrega
    const deliveryInfo = document.getElementById('delivery-info');
    if (method === 'cash') {
        deliveryInfo.classList.add('active');
    } else {
        deliveryInfo.classList.remove('active');
    }
}








function validatePaymentForm() {
    const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

    if (selectedPaymentMethod === 'card') {
        const cardNumber = document.getElementById('card_number').value.trim();
        const cardHolder = document.getElementById('card_holder').value.trim();
        const expiry = document.getElementById('expiry').value.trim();
        const cvv = document.getElementById('cvv').value.trim();

        if (!cardNumber || !cardHolder || !expiry || !cvv) {
            alert("Por favor, complete todos los campos de la tarjeta para continuar.");
            return false;
        }
    } else if (selectedPaymentMethod === 'transfer') {
        const transferReference = document.getElementById('transfer_reference').value.trim();

        if (!transferReference) {
            alert("Por favor, ingrese el código de referencia de la transferencia para continuar.");
            return false;
        }
    }

     else if (selectedPaymentMethod === 'paypal') {
        const paypalEmail = document.getElementById('paypal_email').value.trim();
        const paypalPhone = document.getElementById('paypal_phone').value.trim();

        if (!paypalEmail || !paypalPhone) {
            alert("Por favor, ingrese su correo electrónico y teléfono para PayPal.");
            return false;
        }
    }else{
    const address = document.getElementById('address').value.trim();
    const phone = document.getElementById('phone').value.trim();
        if (!address || !phone) {
        alert("Por favor, ingrese su dirección, teléfono");
        return false;
    }
    }

    
    const selectedTime = document.getElementById('delivery_time').value;

    if (!selectedTime) {
        alert("Por favor elija un horario de entrega.");
        return false;
    }

    return true;
}

    </script>

</body>
</html>