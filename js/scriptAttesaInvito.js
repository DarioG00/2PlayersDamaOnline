"use strict";

const TIME = 1000;
const WAIT = 60;

let timerid;
let time;

async function ottieniRispostaInvito(){
    const res = await fetch("./ottieniRispostaInvito.php");
    const data = await res.json();
    const rispostaInvito = data.invitoPartita;

    return rispostaInvito;
}

function controllaInvito(){
    if(time < WAIT){
        const p = document.getElementById('tempo-attesa');
        p.innerText = "Tempo di attesa: "+ ++time + " sec ";
        ottieniRispostaInvito()
            .then((rispostaInvito) => {
                if(rispostaInvito != "vuoto"){
                    if(rispostaInvito.stato == "accettato"){
                        clearInterval(timerid);
                        window.location.href = "./entraPartita.php";
                    }
                }else{
                    clearInterval(timerid);
                    alert("Invito alla partita rifiutato");
                    window.location.href = "./menu.php";
                }
            })
            .catch((e) => {
                clearInterval(timerid);
                alert("Errore nell\'attesa invito: " + e.message);
                window.location.href = "./annullaInvito.php";
            });
    }else{
        clearInterval(timerid);
        alert("Tempo di attesa accettazione invito scaduta.");
        window.location.href = "./annullaInvito.php";
    }
}

function init(){
    time = 0;
    timerid = setInterval(controllaInvito, TIME);
}

document.addEventListener("DOMContentLoaded", init);
