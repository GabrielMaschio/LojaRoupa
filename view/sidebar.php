<?php
    if (isset($_POST["logout"])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
        echo '
        <div class="sidebar">
            <div class="user-container">
                <div class="user-img">
                    <img src="./upload/cliente/'.  $_SESSION["img_path"]  .'" alt="">
                </div>
        
                <div class="user-info">
                    <p>Olá,</p>
                    <p><span>'.  $_SESSION["usuario"]  .'</span></p>
                </div>
            </div>
                
            <div class="sidebar-nav">
                <ul>
                    <li>
                        <a href="./view/cadastrar_tipo.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                            <span class="title">Cadastrar Tipo</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="./view/cadastrar_categoria.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                            <span class="title">Cadastrar Categoria</span>
                        </a>
                    </li>

                    <li>
                        <a href="./view/cadastrar_produto.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                            </svg>
                            <span class="title">Cadastrar Produto</span>
                        </a>
                    </li>

                    <li>
                        <a href="#camisetas">
                            <span>Camiseta</span>
                        </a>
                    </li>

                    <li>
                        <a href="#shorts">
                            <span>Shorts</span>
                        </a>
                    </li>
                
                    <li>
                        <a href="#jaquetas">
                            <span>Jaquetas e Moletons</span>
                        </a>
                    </li>
                </ul>
                <div class="log-out">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                    </svg>
                    <form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
                        <input type="submit" id="logout" name="logout" class="red" value="Log out"/>
                    </form>
                </div>
            </div>
        </div>
        ';
        
        if (isset($_POST["logout"])) {
            logout();
        }
    } else {
        echo '
        <div class="sidebar">
            <div class="user-container">
                <div class="user-img">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                </div>
        
                <div class="user-info">
                    <p>Faça <a href="view/login.php">LOGIN</a> ou</p>
                    <p>crie seu <a href="view/login.php">CADASTRO</a></p>
                </div>
            </div>
                
            <div class="sidebar-nav">
                <ul>
                    <li>
                        <a href="#camisetas">
                            <span>Camiseta</span>
                        </a>
                    </li>
                    <li>
                        <a href="#shorts">
                            <span>Shorts</span>
                        </a>
                    </li>
                
                    <li>
                        <a href="#jaquetas">
                            <span>Jaquetas e Moletons</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        ';
    }

