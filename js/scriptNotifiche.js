"use strict";

const TIME = 1000;

let timeridNotifiche;
let time;

async function aggiornaNotificheInvito(){
    const res = await fetch("./ottieniInfoInviti.php");
    const data = await res.json();
    const inviti = data.inviti;

    return inviti;
}

async function aggiornaNotificheRichieste(){
    const res = await fetch("./ottieniInfoRichieste.php");
    const data = await res.json();
    const richieste = data.richieste;

    return richieste;
}

function aggiornaNotifiche(){
    
    aggiornaNotificheInvito()
        .then((inviti) => {
            const linkPartita = document.getElementById('link-partita');
            const linkInviti = document.getElementById('link-inviti');
            
            console.log('richieste aggiornamento inviti fatta');
            if(inviti > 0){
                linkPartita.classList.add('newMsg');
                linkPartita.innerText = "Inizia Partita ("+ inviti + ")";
                if(linkInviti){
                    linkInviti.classList.add('newMsg');
                    linkInviti.innerText = "Vedi inviti ("+ inviti + ")";
                }
            }else{
                linkPartita.classList.remove('newMsg');
                linkPartita.innerText = "Inizia Partita";
                if(linkInviti){
                    linkInviti.classList.remove('newMsg');
                    linkInviti.innerText = "Vedi inviti";
                }
            }
        })
        .catch((e) => {
            if(timeridNotifiche) clearInterval(timeridNotifiche);
            alert("Errore notifiche invito: " + e.message);
        });

    aggiornaNotificheRichieste()
        .then((richieste) => {
            const linkAmici = document.getElementById('link-amici');
            const linkRichieste = document.getElementById('link-richieste');

            console.log('aggiornamento richieste fatta');
            if(richieste > 0){
                linkAmici.classList.add('newMsg');
                linkAmici.innerText = "Amici ("+ richieste + ")";
                if(linkRichieste){
                    linkRichieste.classList.add('newMsg');
                    linkRichieste.innerText = "Vedi richieste ("+ richieste + ")";
                }
            }else{
                linkAmici.classList.remove('newMsg');
                linkAmici.innerText = "Amici";
                if(linkRichieste){
                    linkRichieste.classList.remove('newMsg');
                    linkRichieste.innerText = "Vedi richieste";
                }
            }
        })
        .catch((e) => {
            if(timeridNotifiche) clearInterval(timeridNotifiche);
            alert("Errore notifiche richieste: " + e.message);
        });

}

function init(){
    time = 0;
    aggiornaNotifiche();
    timeridNotifiche = setInterval(aggiornaNotifiche, TIME);
    console.log('inizializzazione aggiornamento notifiche fatta');
}

document.addEventListener("DOMContentLoaded", init);
