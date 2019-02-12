// function loadJSON(file, callback) {   

//     var xobj = new XMLHttpRequest();
//     xobj.overrideMimeType("application/json");
//     xobj.open('GET', file, true); // Replace 'my_data' with the path to your file
//     xobj.onreadystatechange = function () {
//           if (xobj.readyState == 4 && xobj.status == "200") {
//             // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
//             callback(xobj.responseText);
//           }
//     };
//     xobj.send(null);  
//  }
 

// function load() {
    
//     loadJSON("crm/public/json/breadcrumbs.json", function(response) {
  
//         var actual_JSON = JSON.parse(response);
//         console.log(actual_JSON);
//     });
    
function esUltimo(lista, label){
    // document.write(i);
    // document.write(lista.lenght);
    bread = lista[lista.length-1];
    document.write(bread['label']);
    return i == lista.length;
}    
function getUrl(lista, i){
    url ="";
    for (j=1; j<=i; j++){
        bread = lista[j];
        url = url + bread['url'];
    }
    return "<a  class=\"white-text\" href=\""+ url+ "\">";
}

function armar_breadcrumb(lista){
    breadTxt = '';
    ultimo= lista[lista.length-1];
    ultimo_label = ultimo['label'];
    for (i = 0; i < lista.length; i++)
    {
        bread = lista[i];
        bread_label = bread['label'];  
        if (bread_label=="Home"){
            resultado = "<a href=\""+ bread['url']+ "\">"  + bread_label + "</a>";
        }
        else {
            resultado = getUrl(lista, i) + bread_label + "</a>" ;
        }
        if (bread_label == ultimo_label){
            breadTxt= breadTxt + '<li class="breadcrumb-item active" >' + bread_label + '</li>';
        }
        else{
            breadTxt= breadTxt + '<li class="breadcrumb-item active" >'+ resultado+'</li>';
        }
   } 
    $('#breadcrumb_contenido').append(breadTxt);
}
