// -- Categoria Option --
const constructorCategoria = (obj) => {
    const newTipoOption = document.createElement('option');
    newTipoOption.value = obj.id;
    newTipoOption.innerHTML = obj.nome_categoria;
    document.getElementById("categoria").appendChild(newTipoOption);
}

const constructorEditCategoria = (nome_categoria, obj) => {
    const newTipoOption = document.createElement('option');
    newTipoOption.value = obj.id;
    newTipoOption.innerHTML = obj.nome_categoria;

    if(obj.nome_categoria == nome_categoria) {
        newTipoOption.selected = true;
    }
    document.getElementById("categoria").appendChild(newTipoOption);
}

const _getCategoria = async (nome_categoria, isEdit) => {
    try {
        const response = await fetch('../controller/crud_categoria/listar_categoria.php');
        const data = await response.json();
        data.forEach(obj => {
            if(!isEdit) {
                constructorCategoria(obj)
            } else {
                constructorEditCategoria(nome_categoria, obj)
            }
        });
    } catch (error) {
        console.error('Erro ao carregar o arquivo JSON:', error);
    }
}



// -- Tipo Option --
const constructorTipo = (obj) => {
    const newTipoOption = document.createElement('option');
    newTipoOption.value = obj.id;
    newTipoOption.innerHTML = obj.nome_tipo;
    document.getElementById("tipo").appendChild(newTipoOption);
}

const constructorEditTipo = (nome_tipo, obj) => {
    const newTipoOption = document.createElement('option');
    newTipoOption.value = obj.id;
    newTipoOption.innerHTML = obj.nome_tipo;

    if(obj.nome_tipo == nome_tipo) {
        newTipoOption.selected = true;
    }

    document.getElementById("tipo").appendChild(newTipoOption);
}

const _getTipo = async (nome_tipo, isEdit) => {
    try {
        const response = await fetch('../controller/crud_tipo/listar_tipo.php');
        const data = await response.json();
        data.forEach(obj => {
            if(!isEdit) {
                constructorTipo(obj)
            } else {
                constructorEditTipo(nome_tipo, obj)
            }
        });
    } catch (error) {
        console.error('Erro ao carregar o arquivo JSON:', error);
    }
}



// -- PRODUTO -- 
const imgInput = document.querySelector(".img");
const file = document.getElementById("imgInput");

const dbProduto = [];

let isEdit = false, modalState = false, id = 0;

file.onchange = () => {
    if(file.files[0].size < 1000000){  // 1MB = 1000000
        var fileReader = new FileReader();

        fileReader.onload = (e) => {
            imgUrl = e.target.result
            imgInput.src = imgUrl
        }

        fileReader.readAsDataURL(file.files[0])
    }
    else{
        Swal.fire({
            text: "This file is too large!",
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    }
}

const clearTable = () => { 
    document.getElementById("data").innerHTML = "";
}
const clearFields = () => {
    document.querySelectorAll('.modal-field').forEach(field => field.value = "");
    document.getElementById('tipo').innerHTML = "";
    document.getElementById('categoria').innerHTML = "";
    file.value = "";
}

const openModal = () => {
    document.querySelector(".modal").style.display = "block";
    modalState = true;
}

const closeModal = () => {
    document.querySelector(".modal").style.display = "none";
    modalState = false;
    clearFields();
}

const fillFields = (obj) => {
    id = obj.id;
    document.getElementById('nome_produto').value = obj.nome_produto;
    document.getElementById('quantidade').value = obj.quantidade;
    document.getElementById('valor').value = obj.valor;
    document.getElementById('desconto').value = obj.desconto;
    imgInput.src = `../upload/produto/${obj.pp}`;

    _getTipo(obj.nome_tipo, true);
    _getCategoria(obj.nome_categoria, true);
}

const constructorProduto = (index, obj) => {
    const objProduto = {
        index: index,
        id: obj.id,
        nome_produto: obj.nome_produto,
        quantidade: obj.quantidade,
        valor: obj.valor,
        desconto: obj.desconto,
        nome_categoria: obj.nome_categoria,
        id_categoria: obj.id_categoria,
        nome_tipo: obj.nome_tipo,
        id_tipo: obj.id_tipo,
        pp: obj.pp,
    }

    dbProduto.push(objProduto);
}

const showInfo = (index, obj) => {
    let createElement = 
    `<tr class="employeeDetails">
        <td>${obj.id}</td>
        <td>${obj.nome_produto}</td>
        <td>${obj.nome_categoria}</td>
        <td>${obj.nome_tipo}</td>
        <td>${obj.quantidade}</td>
        <td>${obj.valor}</td>
        <td>${obj.desconto}</td>
        <td><img src="../upload/produto/${obj.pp}" alt="Imagem do produto" width="100" height="100"></td>

        <td>
            <button class="btn btnEdit" onclick="editInfo(${index})" data-bs-toggle="modal" data-bs-target="#userForm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
            </button>

            <button class="btn btnDelete" onclick="deleteInfo(${index})">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>
            </button>
        </td>
    </tr>`

    document.getElementById("data").innerHTML += createElement

    constructorProduto(index, obj);
}

const _getProduto = async () => {
    let index = 0;
    clearTable();
    try {
        const response = await fetch('../controller/crud_produto/listar_produto.php');

        const data = await response.json();

        dbProduto.splice(0, data.length);
        
        clearTable();
        
        data.forEach(obj => {
            showInfo(index, obj)
            index++;
        });
    } catch (error) {
        Swal.fire({
            text: "Erro ao carregar o arquivo JSON: " + error,
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    }
}

const createProduto = async (nomeField, quantidadeField, valorField, descontoField, idCategoriaField, idTipoField, imagemField) => {
    try {
        const formData = new FormData();
        formData.append("nome_produto", nomeField);
        formData.append("quantidade", quantidadeField);
        formData.append("valor", valorField);
        formData.append("desconto", descontoField);
        formData.append("id_categoria", idCategoriaField);
        formData.append("id_tipo", idTipoField);
        formData.append("pp", imagemField);
        console.log(nomeField, quantidadeField, valorField, descontoField, idCategoriaField, idTipoField, imagemField)

        const dados = await fetch("../controller/crud_produto/gravar_produto.php", {
            method: "POST", // Enviar os dados do JavaScript para o PHP através do método POST
            body: formData, // Enviar os dados do JavaScript para o PHP
        });

        const resposta = await dados.json();
                
        if(resposta['status']) {
            closeModal();
            _getProduto();
        } else {
            Swal.fire({
                title: "ERRO!",
                text: resposta['msg'],
                icon: "error"
            });
        }
    } catch(error) {
        console.log(error)
        Swal.fire({
            title: "ERRO!",
            text: `Não foi possivel cadastrar o produto "${document.getElementById("nome_produto").value}"!`,
            icon: "error"
        });
    }
}

const updateProduto = async (id, nomeField, quantidadeField, valorField, descontoField, idCategoriaField, idTipoField, imagemField) => {
    const formEdit = new FormData();
    
    // Criar o formulário
    formEdit.append("id", id);
    formEdit.append("nome_produto", nomeField);
    formEdit.append("quantidade", quantidadeField);
    formEdit.append("valor", valorField);
    formEdit.append("desconto", descontoField);
    formEdit.append("id_categoria", idCategoriaField);
    formEdit.append("id_tipo", idTipoField);

    if (imagemField) {
        formEdit.append("pp", imagemField);
    } else {
        formEdit.append("pp", "");
    }



    try {
        const dados = await fetch("../controller/crud_produto/update_produto.php", {
            method: "POST", 
            body: formEdit, 
        });

        const resposta = await dados.json();

        if(resposta["status"]) {
            closeModal();

            Swal.fire({
                text: resposta['msg'],
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });

            _getProduto();
        } else {
            closeModal();

            Swal.fire({
                text: resposta['msg'],
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
        }
    } catch(error) {
            closeModal();

            Swal.fire({
                text: `Não foi possivel atualizar o produto "${document.getElementById("nome_produto").value}"! Erro: ${error}`,
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
    }
}

const editInfo = (index) => {
    openModal();
    fillFields(dbProduto[index]);
    isEdit = true;
}

_getProduto();

// -- Eventos --

document.querySelector(".newProduto").addEventListener('click', () => {
    openModal();
    _getTipo("", false);
    _getCategoria("", false);
    isEdit = false
    imgInput.src = "../upload/produto/default-pp.png";
})

document.querySelector(".btn-close").addEventListener('click', () => {
    closeModal();
})

document.querySelector(".btnClose").addEventListener('click', () => {
    closeModal();
})

document.getElementById('myForm').addEventListener('submit', (e) => {
    e.preventDefault();

    const nomeField = document.getElementById('nome_produto').value;
    const quantidadeField = document.getElementById('quantidade').value;
    const valorField = document.getElementById('valor').value;
    const descontoField = document.getElementById('desconto').value;
    const imagemField = document.getElementById('imgInput').files[0];
    


    const selectCategoriaElement = document.getElementById("categoria");
    const selectedCategoriaIndex = selectCategoriaElement.selectedIndex;
    const selectedCategoriaOption = selectCategoriaElement.options[selectedCategoriaIndex];
    const idCategoriaField = selectedCategoriaOption.value;

    const selectTipoElement = document.getElementById("tipo");
    const selectedTipoIndex = selectTipoElement.selectedIndex;
    const selectedTipoOption = selectTipoElement.options[selectedTipoIndex];
    const idTipoField = selectedTipoOption.value;


    if(isEdit) {
        updateProduto(id, nomeField, quantidadeField, valorField, descontoField, idCategoriaField, idTipoField, imagemField)
    } else {
        createProduto(nomeField, quantidadeField, valorField, descontoField, idCategoriaField, idTipoField, imagemField);
    }
});

document.onkeydown = function(e) {
    if(e.key === 'Escape') {
        if(modalState) {
            closeModal();
        }
    }
}