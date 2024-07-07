const menuButton = document.getElementById('hamburger');
const menuIcon = document.querySelector('#hamburger path');
const sidebar =  document.querySelector(".sidebar");

const activeMenu = () => {
    menuIcon.setAttribute("d", "M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8");
    menuButton.setAttribute("action", "active");
    menuButton.style.left = "250px";
}

const desactiveMenu = () => {
    menuIcon.setAttribute("d", "M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5");
    menuButton.setAttribute("action", "");
    menuButton.style.left = "120px";
}

menuButton.addEventListener("click", () => {
    const menuStatus = menuButton.getAttribute('action');
    if(menuStatus === "active") {
        desactiveMenu();
        sidebar.style.left = "-303px";
    } else  {
        activeMenu();
        sidebar.style.left = "0";
    }
}); 

document.querySelectorAll('.sidebar a').forEach(item => {
    item.addEventListener('click', () => {
        desactiveMenu();
        sidebar.style.left = "-303px";
    });
});



// -- Função para mostrar os produtos na tela -- //
const showCamiseta = (objProduto) => {
    const camisetaCarousel = document.querySelector('#camisetas .carousel');

    const desconto = (objProduto.valor - (objProduto.valor * objProduto.desconto / 100)).toFixed(2);

    let createElement = 
    `
    <div class="produto-slide">
        <div class="img-container">
            <img src="./upload/produto/${objProduto.pp}" alt="">
        </div>

        <div class="desc-container">
            <h1>${objProduto.nome_produto}</h1>
            <h2>${objProduto.nome_tipo}</h2>
            <p>R$${objProduto.valor.replace('.', ',')} no Pix <span class="preco">R$${desconto.replace('.', ',')}</span> <span class="desconto">${objProduto.desconto}% off</span></p>
        </div>

        <button onclick="addToCarrinho(${objProduto.id}, ${objProduto.valor})" class="buy-butt">Comprar</button>
    </div>
    `;

    camisetaCarousel.innerHTML += createElement;
}

const showShort = (objProduto) => {
    const shortCarousel = document.querySelector('#shorts .carousel');

    const desconto = (objProduto.valor - (objProduto.valor * objProduto.desconto / 100)).toFixed(2);

    let createElement = 
    `
    <div class="produto-slide">
        <div class="img-container">
            <img src="./upload/produto/${objProduto.pp}" alt="">
        </div>

        <div class="desc-container">
            <h1>${objProduto.nome_produto}</h1>
            <h2>${objProduto.nome_tipo}</h2>
            <p>R$${objProduto.valor.replace('.', ',')} no Pix <span class="preco">R$${desconto.replace('.', ',')}</span> <span class="desconto">${objProduto.desconto}% off</span></p>
        </div>

        <button onclick="addToCarrinho(${objProduto.id}, ${objProduto.valor})" class="buy-butt">Comprar</button>
    </div>
    `;

    shortCarousel.innerHTML += createElement;
}

const showJaquetas = (objProduto) => {
    const jaquetaCarousel = document.querySelector('#jaquetas .carousel');

    const desconto = (objProduto.valor - (objProduto.valor * objProduto.desconto / 100)).toFixed(2);

    let createElement = 
    `
    <div class="produto-slide">
        <div class="img-container">
            <img src="./upload/produto/${objProduto.pp}" alt="">
        </div>

        <div class="desc-container">
            <h1>${objProduto.nome_produto}</h1>
            <h2>${objProduto.nome_tipo}</h2>
            <p>R$${objProduto.valor.replace('.', ',')} no Pix <span class="preco">R$${desconto.replace('.', ',')}</span> <span class="desconto">${objProduto.desconto}% off</span></p>
        </div>

        <button onclick="addToCarrinho(${objProduto.id}, ${objProduto.valor})" class="buy-butt">Comprar</button>
    </div>
    `;

    jaquetaCarousel.innerHTML += createElement;
}

const _getCamiseta = async () => {
    try {
        const response = await fetch('controller/listar_camisetas.php');

        const data = await response.json();
        data.forEach(obj => {
            showCamiseta(obj)
        });
    } catch (error) {
        Swal.fire({
            text: "Erro ao carregar as camisetas: " + error,
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    }
};

const _getShort = async () => {
    try {
        const response = await fetch('controller/listar_shorts.php');

        const data = await response.json();
        data.forEach(obj => {
            showShort(obj)
        });
    } catch (error) {
        Swal.fire({
            text: "Erro ao carregar os shorts: " + error,
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    }
};

const _getJaqueta = async () => {
    try {
        const response = await fetch('controller/listar_jaquetas.php');

        const data = await response.json();
        data.forEach(obj => {
            showJaquetas(obj)
        });
    } catch (error) {
        Swal.fire({
            text: "Erro ao carregar os shorts: " + error,
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    }
};

_getCamiseta();
_getShort();
_getJaqueta();

// -- Função para adicionar produtos ao carrinho -- //
const addToCarrinho = async (id, valor) => {
    try {
        const formData = new FormData();
        formData.append("id_produto", id);
        formData.append("valor", valor);

        const response = await fetch('controller/adicionar_carrinho.php', {
            method: 'POST',
            body: formData
        });

        const resposta = await response.json();

        if(resposta['status']) {
            Swal.fire({
                text: "Produto adicionado ao carrinho!",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
        } else {
            if(resposta['msg'] === "Usuário não logado") {
                window.location.href = "./view/login.php";
            } else {
                Swal.fire({
                    text: resposta['msg'],
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Fechar'
                });
            }
        }
    } catch (error) {
        Swal.fire({
            text: "Erro ao adicionar produto ao carrinho: " + error,
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    }
}