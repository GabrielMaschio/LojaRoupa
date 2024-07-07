const dbTipo = [];
let isEdit = false, modalState = false, id = 0;

const clearTable = () => { 
    document.getElementById("data").innerHTML = "";
}

const clearFields = () => {
    document.getElementById("name").value = "";
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
    document.getElementById('name').value = obj.name;
}

const constructorTipo = (index, obj) => {
    const objTipo = {
        index: index,
        id: obj.id,
        name: obj.nome_tipo
    }

    dbTipo.push(objTipo);
}

const _getTipo = async () => {
    let index = 0;
    clearTable();
    try {
        const response = await fetch('../controller/crud_tipo/listar_tipo.php');
        const data = await response.json();
        dbTipo.splice(0, data.length);
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

function showInfo(index, obj){
    let createElement = `<tr class="employeeDetails">
        <td>${obj.id}</td>
        <td>${obj.nome_tipo}</td>

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

    constructorTipo(index, obj);
}

const createTipo = async (nomeField) => {
    try {
        const formData = new FormData();
        formData.append("nome_tipo", nomeField);
        
        console.log("Entrou")

        const dados = await fetch("../controller/crud_tipo/gravar_tipo.php", {
            method: "POST", // Enviar os dados do JavaScript para o PHP através do método POST
            body: formData, // Enviar os dados do JavaScript para o PHP
        });

        const resposta = await dados.json();
                
        if(resposta['status']) {
            closeModal();
            _getTipo();
        } else {
            Swal.fire({
                title: "ERRO!",
                text: resposta['msg'],
                icon: "error"
            });
        }
    } catch {
        Swal.fire({
            title: "ERRO!",
            text: `Não foi possivel cadastrar o tipo "${document.getElementById("name").value}"!`,
            icon: "error"
        });
    }
}

const updateTipo = async (id, nomeField) => {
    const formEdit = new FormData();
    
    // Criar o formulário
    formEdit.append("id", id);
    formEdit.append("nome_tipo", nomeField);

    try {
        const dados = await fetch("../controller/crud_tipo/update_tipo.php", {
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

            _getTipo();
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
    } catch {
            closeModal();

            Swal.fire({
                text: "Não foi possivel fazer a edição!",
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
    }
}

function editInfo(index){
    isEdit = true;
    openModal();
    id = dbTipo[index].id;
    fillFields(dbTipo[index]);
}

function deleteInfo(index){
    const tipo = dbTipo[index];

    Swal.fire({
        title: `Deseja realmente deletar?`,
        text: "Você não vai poder reverter isso!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: `Sim, delete "${tipo.name}"!`
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const formDel = new FormData();
                formDel.append("id", tipo.id);
                console.log(tipo.id)
                
                const dados = await fetch("../controller/crud_tipo/deletar_tipo.php", {
                    method: "POST", // Enviar os dados do JavaScript para o PHP através do método POST
                    body: formDel, // Enviar os dados do JavaScript para o PHP
                });
    
                const resposta = await dados.json();     
                console.log(resposta)    
                
                if(resposta['status']) {
                    Swal.fire({
                        title: "Deletado!",
                        text: `Tipo "${tipo.name}" deletada com sucesso!`,
                        icon: "success"
                    });
    
                    _getTipo();
                } else {
                    Swal.fire({
                        title: "ERRO!",
                        text: resposta['msg'],
                        icon: "error"
                    });
                }
            } catch(error) {
                Swal.fire({
                    title: "ERRO!",
                    text: `Não foi possivel deletar a tipo "${tipo.name}", ${error}!`,
                    icon: "error"
                });
            }
        }
    });
}

_getTipo();

document.querySelector(".newTipo").addEventListener('click', ()=> {
    openModal();
    isEdit = false
})

document.querySelector(".btn-close").addEventListener('click', ()=> {
    closeModal();
})

document.querySelector(".btnClose").addEventListener('click', ()=> {
    closeModal();
})

document.getElementById('myForm').addEventListener('submit', (e) => {
    e.preventDefault();

    const nomeField = document.getElementById('name').value;


    if(isEdit) {
        updateTipo(id, nomeField)
    } else {
        createTipo(nomeField)
    }
});

document.onkeydown = function(e) {
    if(e.key === 'Escape') {
        if(modalState) {
            closeModal();
        }
    }
}