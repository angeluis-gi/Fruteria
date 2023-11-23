<?php
session_start();

$compraRealizada = "";

if (isset($_GET['cliente'])) {
    $_SESSION['cliente'] = $_GET['cliente'];
    $_SESSION['pedidos'] = [];
}

if (!isset($_SESSION['cliente'])) {
    require_once('bienvenida.php');
    exit();
}

if (isset($_POST["accion"])) {
    switch ($_POST["accion"]) {
        case " Anotar ":
            $cantidad = $_POST['cantidad'];
            $fruta = $_POST['fruta'];
            if ($cantidad > 0) {
                // si existe un pedido anterior de la misma fruta:
                if (isset($_SESSION['pedidos'][$fruta])) {
                    $_SESSION['pedidos'][$fruta] += $cantidad;
                } else {
                    $_SESSION['pedidos'][$fruta] = $cantidad;
                }
            }
            break;

        case " Anular ":
            unset($_SESSION['pedidos'][$_POST['fruta']]);
            break;

        case " Terminar ":
            $compraRealizada = htmlTablaPedidos();
            require_once("despedida.php");
            session_destroy();
            exit;
    }
}

$compraRealizada = htmlTablaPedidos();
require_once('compra.php');

function htmlTablaPedidos(): string {
    $msg = "";
    if (count($_SESSION['pedidos']) > 0) {
        $msg .= "Este es su pedido :";
        $msg .= "<table style='border: 1px solid black'>";
        foreach ($_SESSION['pedidos'] as $fruta => $cantidad) {
            $msg .= "<tr><td><b>" . $fruta . "</b><td></td><td>" . $cantidad . "</td></tr>";
        }
        $msg .= "</table>";
    }
    return $msg;
}
