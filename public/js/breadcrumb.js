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

function getUrlAnt(lista, ultimo) {
    resultado = "";
    if (lista.length > 2) {
        for (i = 1; i < lista.length - 1; i++) {
            crumb = lista[i];
            if (crumb['label'] != ultimo) {
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
    breadTxt = '';
    ultimo = lista[lista.length - 1];
    ultimo_label = ultimo['label'];
    url_volver = "";
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
        if (bread_label != ultimo_label) {}
    }
    ulr_volver = getUrlAnt(lista, ultimo_label)
    document.write(url_volver);
    document.write("<br>");
    $('#breadcrumb_contenido').append(breadTxt);
}