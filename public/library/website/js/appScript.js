
function chargementLieu( lieuparam = null){
    let idVille = document.getElementById("ville").value;
    let select = document.getElementById("lieu");
    fetch("http://127.0.0.1:8000/listeLieu/" + idVille)
        .then(response => response.json())
        .then((lieux)=> {
            select.innerHTML = "";
            let defaut = document.createElement("option");
            defaut.innerText = "-- Sélectionner un lieu --";
            select.appendChild(defaut);
            for (const chaqueLieu of lieux){
                if(lieuparam === chaqueLieu.nom){
                    let lieu = document.createElement("option");
                    lieu.setAttribute("selected", "");
                    lieu.innerText = chaqueLieu.nom;
                    select.appendChild(lieu);
                }
                else{
                    let lieu = document.createElement("option");
                    lieu.innerText = chaqueLieu.nom;
                    select.appendChild(lieu);
                }
            }
        })
}
// function chargementRue(){
//     let nomLieu = document.getElementById("lieu").value;
//     let input = document.getElementById("rue");
//     fetch("http://127.0.0.1:8000/listeRue/" + nomLieu)
//         .then(response => response.json())
//         .then((lieuSelect)=> {
//             for (const lieu of lieuSelect) {
//                 input.value = lieu.rue;
//             }
//         })
// }