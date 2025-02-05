"use strict";

async function aggiornaVittorie(){
    const res = await fetch("./ottieniVittorie.php");
    const data = await res.json();
    const vittorie = data.vittorie;

    return vittorie;
}

async function aggiornaAmici(){
    const res = await fetch("./ottieniNumAmici.php");
    const data = await res.json();
    const amici = data.amici;

    return amici;
}

function aggiornaProfilo(){
    aggiornaVittorie()
        .then((vittorie) => {
            const pVittorie = document.getElementById('vittorie');
            console.log('richieste vittorie fatta');
            pVittorie.innerText = "Vittorie: "+ vittorie;
        })
        .catch((e) => {
            alert("Errore aggiornamento vittorie: " + e.message);
        });

    aggiornaAmici()
        .then((amici) => {
            const pAmici = document.getElementById('num-amici');
            console.log('richieste num amici fatta');
            pAmici.innerText = "#Amici: "+ amici;
        })
        .catch((e) => {
            alert("Errore notifiche invito: " + e.message);
        });
        
    console.log('Aggiornamento profilo utente fatta');
}

document.addEventListener("DOMContentLoaded", aggiornaProfilo);
