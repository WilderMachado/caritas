/**
 * Created by Wilder on 28/08/2017.
 */
window.onload = function () {
    indiceTelefone = adicionarFuncaoRemover(document.getElementsByClassName("btn-remover-telefone"));
    indiceEmail = adicionarFuncaoRemover(document.getElementsByClassName("btn-remover-email"));
    btnAdicionarTelefone = document.getElementById("btn-adicionar-telefone");
    if (btnAdicionarTelefone) {
        btnAdicionarTelefone.addEventListener("click", adicionarTelefone);
    }
    btnAdicionarEmail = document.getElementById("btn-adicionar-email");
    if (btnAdicionarEmail) {
        btnAdicionarEmail.addEventListener("click", adicionarEmail);
    }
    adicionarFuncaoExcluir();
    adicionarFuncaoBuscar();
    tipoTelefone = criarTipoTelefone();
};

function adicionarFuncaoRemover(lista) {
    for (var i = 0; i < lista.length; i++) {
        lista.item(i).addEventListener("click", function () {
            remover(this);
        });
    }
    return i;
}

function adicionarFuncaoExcluir() {
    var excluir = document.getElementsByClassName("btn-excluir");
    for (var i = 0; i < excluir.length; i++) {
        excluir.item(i).onclick = function () {
            return confirm("Tem certeza que deseja excluir?");
        }
    }
}

function adicionarTelefone() {
    var divForm = criarElemento("div", {"class": "form-group"});
    var indice = "telefones[".concat(indiceTelefone).concat("][ddd]");
    var lblTelefone = criarElemento("label", {"class": "control-label col-xs-2 col-md-3", "for": indice});
    lblTelefone.appendChild(document.createTextNode("Telefone: "));
    divForm.appendChild(lblTelefone);
    var divColDDD = criarElemento("div", {"class": "col-xs-1 col-md-1"});
    var txtDDD = criarElemento("input", {
        "class": "form-control",
        "name": indice,
        "id": indice,
        "placeholder": "DDD",
        "maxlength": 2
    });
    divColDDD.appendChild(txtDDD);
    indice = indice.replace("ddd", "numero");
    var divColNumero = criarElemento("div", {"class": "col-xs-2 col-md-3"});
    var txtNumero = criarElemento("input", {
        "class": "form-control",
        "name": indice,
        "id": indice,
        "placeholder": "Número",
        "maxlength": 9
    });
    divColNumero.appendChild(txtNumero);
    indice = indice.replace("numero", "tipo");
    var divColTipo = criarElemento("div", {"class": "col-xs-2 col-md-2"});
    var selTipo = criarElemento("select", {"class": "form-control", "name": indice, "id": indice});
    var opcao = criarOpcao("Tipo", {"value": "", "disabled": true, "selected": true, "hidden": true});
    selTipo.appendChild(opcao);
    for (var chave in tipoTelefone) {
        selTipo.appendChild(criarOpcao(tipoTelefone[chave], {"value": chave}));
    }
    divColTipo.appendChild(selTipo);
    divForm.appendChild(divColDDD);
    divForm.appendChild(divColNumero);
    divForm.appendChild(divColTipo);
    indiceTelefone++;
    var btnExcluir = criarElemento("button", {"class": "btn btn-danger  btn-remover-telefone glyphicon glyphicon-remove"});
    //btnExcluir.appendChild(document.createTextNode("-"));
    btnExcluir.addEventListener("click", function () {
        remover(this);
    });
    divForm.appendChild(btnExcluir);
    var divTelefones = document.getElementById("telefones");
    divTelefones.insertBefore(divForm, divTelefones.lastElementChild);
}

function remover(elemento) {
    elemento.parentNode.parentNode.removeChild(elemento.parentNode);
}

function adicionarEmail() {
    var divForm = criarElemento("div", {"class": "form-group"});
    var lblEmail = criarElemento("label", {"class": "control-label col-xs-2 col-md-3"});
    lblEmail.appendChild(document.createTextNode("E-mail: "));
    var indice = "emails[".concat(indiceEmail).concat("][email]");
    lblEmail.setAttribute("for", indice);
    divForm.appendChild(lblEmail);
    var divCol = criarElemento("div", {"class": "col-xs-5 col-md-6"});
    var txtEmail = criarElemento("input", {"class": "form-control", "name": indice, "id": indice});
    divCol.appendChild(txtEmail);
    indiceEmail++;
    divForm.appendChild(divCol);
    var btnExcluir = criarElemento("button", {"class": "btn btn-danger  btn-remover-email glyphicon glyphicon-remove"});
//    btnExcluir.appendChild(document.createTextNode("-"));
    btnExcluir.addEventListener("click", function () {
        remover(this);
    });
    divForm.appendChild(btnExcluir);
    var divEmails = document.getElementById("emails");
    divEmails.insertBefore(divForm, divEmails.lastElementChild);
}

function criarElemento(tipo, atributos) {
    var elemento = document.createElement(tipo);
    for (var atributo in atributos) {
        elemento.setAttribute(atributo, atributos[atributo]);
    }
    return elemento;
}

function criarOpcao(texto, atributos) {
    var opcao = criarElemento("option", atributos);
    opcao.appendChild(document.createTextNode(texto));
    return opcao;
}

function criarTipoTelefone() {
    var tipos = document.getElementsByClassName("tipo-telefone");
    var tiposTelefone = {};
    for (var i = 0; i < tipos.length; i++) {
        tiposTelefone[tipos.item(i).value] = tipos.item(i).name;
    }
    return tiposTelefone;
}

function adicionarFuncaoBuscar() {
    var listaBuscar = document.getElementsByClassName("btn-buscar");

    for (var i = 0; i < listaBuscar.length; i++) {
        listaBuscar.item(i).addEventListener("click", function () {
            buscar(this.value);
        });
    }
}
function buscar(caminho) {
    var xhttp = criarXHTTP();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            var texto = xhttp.responseText;
            alert(texto);
        }
    };
    xhttp.open("GET", caminho, true);
    xhttp.send();
}
function criarXHTTP() {
    var xhttp = null;
    if (window.XMLHttpRequest) {
        xhttp = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        try {
            xhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xhttp;
}
//,'onkeypress'=>'return event.charCode >= 48 && event.charCode <= 57'
//onkeypress="if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;"