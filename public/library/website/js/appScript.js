
function chargementLieu( lieuparam = null){
    let idVille = document.getElementById("ville").value;
    let select = document.getElementById("lieu");
    fetch("http://127.0.0.1:8000/listeLieu/" + idVille)
        .then(response => response.json())
        .then((lieux)=> {
            select.innerHTML = "";
            let defaut = document.createElement("option");
            defaut.innerText = "-- Sélectionner un lieu --";
            defaut.value = "0";
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

function chargementVille(){

    // let idVille = document.getElementById("ville").value;
    let selectVille = document.getElementById("ville");

    fetch("http://127.0.0.1:8000/listeville")
        .then(response => response.json())
        .then((villes)=> {
            console.log(villes)
            selectVille.innerHTML = "";
            let defaut = document.createElement("option");
            defaut.innerText = "-- Sélectionner un ville --";
            selectVille.appendChild(defaut);
            for (const chaqueVille of villes){
                let ville = document.createElement("option");
                ville.innerText = chaqueVille.nom;
                ville.value = chaqueVille.id
                selectVille.appendChild(ville);
            }
        })
}

function konami() {
    var allowedKeys = {
        37: 'left',
        38: 'up',
        39: 'right',
        40: 'down',
        65: 'a',
        66: 'b'
    };

// the 'official' Konami Code sequence
    var konamiCode = ['up', 'up', 'down', 'down', 'left', 'right', 'left', 'right', 'b', 'a'];

// a variable to remember the 'position' the user has reached so far.
    var konamiCodePosition = 0;

// add keydown event listener
    document.addEventListener('keydown', function (e) {
        // get the value of the key code from the key map
        var key = allowedKeys[e.keyCode];
        // get the value of the required key from the konami code
        var requiredKey = konamiCode[konamiCodePosition];

        // compare the key with the required key
        if (key == requiredKey) {

            // move to the next key in the konami code sequence
            konamiCodePosition++;

            // if the last key is reached, activate cheats
            if (konamiCodePosition == konamiCode.length) {
                activateCheats();
                konamiCodePosition = 0;
            }
        } else {
            konamiCodePosition = 0;
        }
    });

    function activateCheats() {
        document.body.style.backgroundImage = "url('https://c.tenor.com/L_V9C0gaSwkAAAAd/konami.gif')";
        alert("Bah bravo il est joli maintenant le site");
    }
}

