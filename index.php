<?php
    session_start();
    require_once "config/config.php";

    function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <title>Maschio Wear</title>
</head>
<body>
    <header>
        <?php 
            include "view/navbar.php";
            include "view/sidebar.php";
        ?>
    </header>

    <main>
        <section id="camisetas" class="produto-container">
            <h1>Camisetas</h1>

            <div class="wrapper">
                <div class="carousel">
                    
                </div>
            </div>
        </section>

        <section id="shorts" class="produto-container">
            <h1>Shorts</h1>

            <div class="wrapper">
                <div class="carousel">
                    
                </div>
            </div>
        </section>

        <section id="jaquetas" class="produto-container">
            <h1>Jaquetas e Moletons</h1>

            <div class="wrapper">
                <div class="carousel">
                
                </div>
            </div>
        </section>
    </main>

    <footer>
    
    </footer>

    <script src="./assets/js/sweetalert2.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>