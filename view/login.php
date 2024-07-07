<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>Maschio Wear - Login</title>
</head>
<body>
    <header>
        <a href="../index.php"><img src="../assets/images/logo.png" alt="Logo Maschio WEAR"></a>
    </header>

    <main>
        <div class="login">
            <h1>Já sou cliente</h1>

            <form id="formLogin">
                <input type="text" id="usuario" class="lowercase" name="usuario" placeholder="Usuário" required>

                <input type="text" id="senha" name="senha" placeholder="Senha" required>

                <input class="blue" type="submit" value="Entrar">
            </form>
        </div>

        <div class="line-vertical"></div>

        <div class="cadastro">
            <h1>Criar conta</h1>

            <form id="formCadastro">
                <input type="text" id="nome_cliente" name="nome_cliente" placeholder="Nome" required>

                <input type="text" id="user" class="lowercase" name="user" placeholder="Usuário" required>
                
                <input type="email" id="email" name="email" placeholder="E-mail" required>
                
                <input type="tel" id="telefone" name="telefone" placeholder="(  )_____-____" maxlength="15" required>

                <input type="text" id="key" name="key" placeholder="Senha" required>

                <input type="file" id="imagem" name="pp">

                <input class="green" type="submit" value="Cadastrar">
            </form>
        </div>
    </main>

    <footer>
        <p>Copyright © 2024 Gabriel Maschio. All Rights Reserved.</p>
    </footer>

    <script src="../assets/js/sweetalert2.js"></script>
    <script src="../assets/js/login.js"></script>
</body>
</html>