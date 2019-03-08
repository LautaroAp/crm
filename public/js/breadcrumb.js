function esUltimo(lista, label) {
    bread = lista[lista.length - 1];
    document.write(bread['label']);
    return i == lista.length;
}

function getUrl(lista, i) {
    url = "";
    for (j = 1; j <= i; j++) {
        bread = lista[j];
        url = url + bread['url'];
    }
    return "<a  class=\"white-text\" href=\"" + url + "\">";
}

function getUrlAnt(lista) {
    var ultimo = lista[lista.length - 1];
    var ultimo_label = ultimo['label'];
    var resultado = "";
    if (lista.length > 2) {
        for (i = 1; i < lista.length - 1; i++) {
            crumb = lista[i];
            if (crumb['label'] != ultimo_label) {
                url = crumb['url'];
                resultado = resultado + url;
            }
        }
    } else {
        resultado = "/";
    }
    return resultado;

}

function armar_breadcrumb(lista) {
    if (lista.length == null) {
        var url = "/";
        breadTxt = '<li class="breadcrumb-item active" >' + "<a href=\"" + url + "\">" + "Home" + "</a>";+ '</li>';
        $('#breadcrumb_contenido').append(breadTxt);
    } else {
        breadTxt = '';
        ultimo = lista[lista.length - 1];
        ultimo_label = ultimo['label'];
        for (i = 0; i < lista.length; i++) {
            bread = lista[i];
            bread_label = bread['label'];
            if (bread_label == "Home") {
                resultado = "<a href=\"" + bread['url'] + "\">" + bread_label + "</a>";
            } else {
                resultado = getUrl(lista, i) + bread_label + "</a>";
            }
            if (bread_label == ultimo_label) {
                breadTxt = breadTxt + '<li class="breadcrumb-item active" >' + bread_label + '</li>';
            } else {
                breadTxt = breadTxt + '<li class="breadcrumb-item active" >' + resultado + '</li>';
            }
        }
        $('#breadcrumb_contenido').append(breadTxt);
    }
}