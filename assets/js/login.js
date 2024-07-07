const telInput = document.getElementById('telefone');

telInput.addEventListener('input', function (event) {
    var input = event.target;
    var inputValue = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos
    var formattedValue = '';

    if (inputValue.length > 0) {
        formattedValue += '(';
    }
    if (inputValue.length > 2) {
        formattedValue += inputValue.substring(0, 2) + ') ';
    } else if (inputValue.length > 0) {
        formattedValue += inputValue;
    }
    if (inputValue.length > 7) {
        formattedValue += inputValue.substring(2, 7) + '-';
        formattedValue += inputValue.substring(7, 11);
    } else if (inputValue.length > 2) {
        formattedValue += inputValue.substring(2, 7);
    }
    input.value = formattedValue;

    // Define o cursor para o final do texto
    input.setSelectionRange(input.value.length, input.value.length);
});


telInput.addEventListener('keypress', function (event) {
    // Obtém o código da tecla pressionada
    var charCode = (event.which) ? event.which : event.keyCode;

    // Verifica se o código da tecla não está entre os códigos ASCII dos números (48-57)
    if (charCode < 48 || charCode > 57) {
        event.preventDefault();
    }

    var telefone = telInput.value.replace(/(\d{2})(\d{5})(\d{4})/, '($1)$2-$3');
    telInput.value = telefone;
});

// Adiciona uma verificação adicional para impedir colar texto não numérico
telInput.addEventListener('paste', function (event) {
    var pasteData = (event.clipboardData || window.clipboardData).getData('text');

    if (!/^\d+$/.test(pasteData)) {
        event.preventDefault();
    }
});

// LOGIN - CADASTRO

const isValidCadastro = () => {
    return document.getElementById('formCadastro').reportValidity()
}

const isValidLogin = () => {
    return document.getElementById('formLogin').reportValidity()
}

const constructorCliente = (nome_cliente, usuario, email, telefone, senha, imagem) => {
    const formCliente = new FormData();
    formCliente.append("nome_cliente", nome_cliente);
    formCliente.append("user", usuario);
    formCliente.append("email", email);
    formCliente.append("telefone", telefone);
    formCliente.append("key", senha);
    formCliente.append("pp", imagem); // Adiciona a imagem ao objeto FormData

    realizeCadastro(formCliente);
}

const constructorLogin = (usuario, senha) => {
    const objLogin = {
        usuario: usuario,
        senha: senha
    };
    realizeLogin(objLogin);
}

const realizeCadastro = async (formCliente) => {
    try {
        const dados = await fetch("../controller/cadastrar_cliente.php", {
            method: "POST", // Enviar os dados do JavaScript para o PHP através do método POST
            body: formCliente, // Enviar os dados do JavaScript para o PHP
        });
        const resposta = await dados.json();
        if(!resposta["status"]) {
            Swal.fire({
                text: resposta['msg'],
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
            document.getElementById('formCadastro').reset();
        } else {
            document.getElementById('formCadastro').reset();
            Swal.fire({
                text: resposta['msg'],
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Fechar'
            });
        }
    } catch(error) {
        Swal.fire({
            text: "Erro ao cadastrar o cliente: " + error,
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
    
    }
}

const realizeLogin = async (objLogin) => {
    const formData = new FormData();

    formData.append("usuario", objLogin.usuario);
    formData.append("senha", objLogin.senha);

    const dados = await fetch("../controller/validar_usuario.php", {
        method: "POST", // Enviar os dados do JavaScript para o PHP através do método POST
        body: formData, // Enviar os dados do JavaScript para o PHP
    });

    const resposta = await dados.json();

    if(!resposta["status"]) {
        Swal.fire({
            text: resposta['msg'],
            icon: 'error',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Fechar'
        });
        document.getElementById('formCadastro').reset();
    } else {
        document.getElementById('formCadastro').reset();
        window.location.href = '../index.php';
    }
}

const handleCadastro = () => {
    if (isValidCadastro()) {
        const nomeField = document.getElementById('nome_cliente').value;
        const userField = document.getElementById('user').value;
        const emailField = document.getElementById('email').value;
        const telefoneField = document.getElementById('telefone').value;
        const keyField = document.getElementById('key').value;
        const imagemField = document.getElementById('imagem').files[0]; // Obtém o arquivo de imagem selecionado

        constructorCliente(nomeField, userField, emailField, telefoneField, keyField, imagemField);
    }
}

const handleLogin= () => {
    if (isValidLogin()) {
        const usuarioField = document.getElementById('usuario').value;
        const senhaField = document.getElementById('senha').value;

        constructorLogin(usuarioField, senhaField);
    }
}

document.querySelector("#formCadastro").addEventListener("submit", (e) => {
    e.preventDefault();
    handleCadastro();
});

document.querySelector("#formLogin").addEventListener("submit", (e) => {
    e.preventDefault();
    handleLogin();
});