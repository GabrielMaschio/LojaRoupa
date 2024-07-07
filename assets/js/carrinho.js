let valorDesconto = 0;
let valorProduto = 0;
let valorCompra = 0;

const showProdutos = (obj) => {
    const dataProdutos = document.getElementById("data");

    const desconto = (obj.valor - (obj.valor * obj.desconto / 100)).toFixed(2);
    const total = (desconto * obj.quantidade).toFixed(2);
    valorDesconto += Number(((obj.valor * obj.quantidade) - total));
    valorProduto += Number(obj.valor * obj.quantidade);
    valorCompra += Number(total);
    document.getElementById("valor-produto").innerHTML = `R$${valorProduto.toFixed(2).replace('.', ',')}`;
    document.getElementById("valor-desconto").innerHTML = `-R$ ${valorDesconto.toFixed(2).replace('.', ',')}`;
    document.getElementById("valor-compra").innerHTML = `R$${valorCompra.toFixed(2).replace('.', ',')}`;


    let createElement = 
    `
    <tr class="produtoDetails">
        <td>
            <div>
                <img src="../upload/produto/${obj.pp}" alt="Imagem do produto" width="120" height="120">
                <div>
                    ${obj.nome_produto}
                    <span>${obj.nome_categoria}</span>
                </div>
            </div>
        </td>
        <td>${obj.quantidade}</td>
        <td>
            <div class="valor-unit">
                <span>R$${obj.valor}</span>
                R$${desconto.replace('.', ',')}
            </div>
        </td>
        <td>
            <div class="valor-total">
                <strong>R$${total.replace('.', ',')}</strong>
                <span>${obj.desconto}% off</span>
            </div>
        </td>
        <td>
            <button onclick="removerProduto(${obj.id_cliente}, ${obj.id})">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
            </svg>
            </button>
        </td>
    </tr>
    `;

    dataProdutos.innerHTML += createElement;
}

const _getCarrinho = async () => {
    try {
        const response = await fetch('../controller/listar_carrinho.php');

        const data = await response.json();
        if(data["msg"]) {
            Swal.fire({
                text: "O seu carrinho está vazio",
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
        } else {
            data.forEach(obj => {
                showProdutos(obj)
            });
        }
    } catch (error) {
        Swal.fire({
            text: "Erro ao carregar os produtos: " + error,
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    }
}

const removerProduto = async (id_cliente, id_produto) => {
    Swal.fire({
        title: `Deseja realmente remover o produto?`,
        text: "Você não vai poder reverter isso!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: `Sim, remova!`
    }).then(async (result) => {
        if(result.isConfirmed) {
        const formData = new FormData();    
        formData.append('id_cliente', id_cliente);
        formData.append('id_produto', id_produto);

        const response = await fetch('../controller/remover_produto.php', {
            method: 'POST',
            body: formData
        });

        const resposta = await response.json();

        if(resposta["status"]) {
            document.getElementById('data').innerHTML = '';
            valorDesconto = 0;
            valorProduto = 0;
            valorCompra = 0;
            _getCarrinho();
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
    });
}

const finalizarCompra = async () => {
    try {
        const response = await fetch('../controller/finalizar_compra.php', {
            method: 'POST'
        });

        const resposta = await response.json();

        if(resposta["status"]) {
            Swal.fire({
                text: resposta["msg"],
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
            document.getElementById('data').innerHTML = '';
            valorDesconto = 0;
            valorProduto = 0;
            valorCompra = 0;
            document.getElementById("valor-produto").innerHTML = `R$ 000,00`;
            document.getElementById("valor-desconto").innerHTML = `-R$ 00,00`;
            document.getElementById("valor-compra").innerHTML = `R$ 000,00`;
        } else {
            Swal.fire({
                text: resposta["msg"],
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
        }
    } catch(error) {
        Swal.fire({
            text: "Erro ao finalizar a compra: " + error,
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    }
}

_getCarrinho();