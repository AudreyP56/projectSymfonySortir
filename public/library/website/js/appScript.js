function chargementLieu(){
    let idVille = document.getElementById("ville").value;
    let select = document.getElementById("lieu");
    fetch("http://127.0.0.1:8000/listeLieu/" + idVille)
        .then(response => response.json())
        .then((lieux)=> {
            select.innerHTML = "";
            for (const chaqueLieu of lieux){
                let lieu = document.createElement("option");
                lieu.innerText = chaqueLieu.nom;
                select.appendChild(lieu);
            }
        })
}