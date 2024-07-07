<?php
    session_start();
    require_once "../config/config.php";

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("Location: ../view/login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/carrinho.css">
    <title>Maschio Wear - Carrinho de Compras</title>
</head>
<body>
    <nav>
        <button onclick="window.location.href = '../index.php'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5"/>
            </svg>
        </button>
        <h1>Carrinho de Compras</h1>
    </nav>

    <section>
        <div class="container-alert">
            <p>Pague no pix <span>e ganhe 5% de desconto.</span> <span>Ver regras.</span></p>
        </div>

        <div class="container-carrinho">
            <div class="container_data">
                <div class="row">
                    <div>
                        <table class="table">
                            <thead>
                                <tr class="infoData">
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Valor</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="data">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="container-resumo">
                <h1>Resumo</h1>

                <div class="body-info">
                    <p>Valor dos produto</p>
                    <p id="valor-produto">R$ 000,00</p>
                </div>

                <div class="body-info">
                    <p>Frete</p>
                    <p>A calcular</p>
                </div>

                <div class="body-info">
                    <p>Desconto</p>
                    <span id="valor-desconto">-R$ 00,00</span>
                </div>

                <div class="body-info">
                    <p>Total da compra</p>
                    <p id="valor-compra">R$ 000,00</p>
                </div>

                <button onclick="finalizarCompra()">Finalizar Compra</button>
            </div>
        </div>
    </section>

    <script src="../assets/js/sweetalert2.js"></script>
    
    <script src="../assets/js/carrinho.js"></script>
</body>
</html>