"use strict";

const TIME = 1000;

const DIM_SCACCHIERA = 8;
const DIM_BLOCCO = 60;

const OFFSET_PEDINA = 10;
const DIM_PEDINA = DIM_BLOCCO - 20;

const pedinaBianca = new Image();
const pedinaNera = new Image();
const damaBianca = new Image();
const damaNera = new Image();

class Pedina {
    constructor(x, y, ctx, colore, dimensione, dama = false) {
        this.x = x;
        this.y = y;		
        this.ctx = ctx;
        this.colore = colore;
        this.dama = dama;
        this.dimensione = dimensione;
    }

    getX(){
        return this.x;
    }

    getY(){
        return this.y;
    }

    getColor(){
        return this.colore;
    }
    
    isDama(){
        return this.dama;
    }

    impostaPosizione(x, y){
        this.x = x;
        this.y = y;	
    }

    disegnaPedina(){
        if(this.colore == 'bianca'){
            if(this.dama){
                this.ctx.drawImage(damaBianca, this.x, this.y, this.dimensione, this.dimensione);
            }else{
                this.ctx.drawImage(pedinaBianca, this.x, this.y, this.dimensione, this.dimensione);
            }
        }else{
            if(this.dama){
                this.ctx.drawImage(damaNera, this.x, this.y, this.dimensione, this.dimensione);
            }else{
                this.ctx.drawImage(pedinaNera, this.x, this.y, this.dimensione, this.dimensione);
            }
        }
    }

    impostaDama(){
        this.dama = true;
    }
}

let scacchiera = [];
let pedinaSelezionata = null;
let pedinaMangiatrice = null;
let canvas;

let timerid;
let time;

let turno;

let numNere;
let numBianche;

let numSpostamenti;
let numMosse;

let colorePedina;
let colorePedinaAvversario;

function inizializza() {
    let ctx = canvas.getContext('2d');
    for (let row = 0; row < DIM_SCACCHIERA; row++) {
        scacchiera[row] = [];
        for (let col = 0; col < DIM_SCACCHIERA; col++) {
            scacchiera[row][col] = null;
            if ((row + col) % 2 === 1) {
                let x = col * DIM_BLOCCO + OFFSET_PEDINA;
                let y = row * DIM_BLOCCO + OFFSET_PEDINA;
                if (row < 3) {
                    scacchiera[row][col] = new Pedina(x, y, ctx, colorePedinaAvversario, DIM_PEDINA);
                } else if (row > 4) {
                    scacchiera[row][col] = new Pedina(x, y, ctx, colorePedina, DIM_PEDINA);
                }
            }
        }
    }

    pedinaNera.src = '../images/pedina_nera.png';
    pedinaBianca.src = '../images/pedina_bianca.png';
    damaBianca.src = '../images/pedina_dama_bianca.png';
    damaNera.src = '../images/pedina_dama_nera.png';
}

function disegnaScacchiera() {
    let ctx = canvas.getContext('2d');
    for (let row = 0; row < DIM_SCACCHIERA; row++) {
        for (let col = 0; col < DIM_SCACCHIERA; col++) {
            if ((row + col) % 2 === 0) {
                ctx.fillStyle = '#FFE45E';
            } else {
                ctx.fillStyle = '#3D1F00';
            }
            ctx.fillRect(col * DIM_BLOCCO, row * DIM_BLOCCO, DIM_BLOCCO, DIM_BLOCCO);
        }
    }

}

function disegnaPedine(colore) {
    for (let row = 0; row < DIM_SCACCHIERA; row++) {
        for (let col = 0; col < DIM_SCACCHIERA; col++) {
            let p = scacchiera[row][col];
            if(p !== null && p.colore == colore){
                p.disegnaPedina();
            }
        }
    }
}


function spostaPedina(fromRow, fromCol, toRow, toCol) {
    const startX = fromCol * DIM_BLOCCO + OFFSET_PEDINA;
    const startY = fromRow * DIM_BLOCCO + OFFSET_PEDINA;
    const endX = toCol * DIM_BLOCCO + OFFSET_PEDINA;
    const endY = toRow * DIM_BLOCCO + OFFSET_PEDINA;

    let ctx = canvas.getContext('2d');
    ctx.clearRect(startX, startY, DIM_BLOCCO, DIM_BLOCCO);
    ctx.clearRect(endX, endY, DIM_BLOCCO, DIM_BLOCCO);

    scacchiera[fromRow][fromCol].impostaPosizione(endX, endY);

    scacchiera[toRow][toCol] = scacchiera[fromRow][fromCol];
    scacchiera[fromRow][fromCol] = null;
    pedinaSelezionata = null;

    if((((toRow == 0) && (scacchiera[toRow][toCol].colore == colorePedina))) || 
      (((toRow == (DIM_SCACCHIERA-1))) && (scacchiera[toRow][toCol].colore == colorePedinaAvversario))){
        scacchiera[toRow][toCol].impostaDama();
    }
    
    if((scacchiera[toRow][toCol].colore == colorePedina)){
        if((numSpostamenti == 0)){
            numSpostamenti++;
        }
        numMosse++;
    }
    
    disegnaScacchiera();
    disegnaPedine('bianca');
    disegnaPedine('nera');
}

function mangiaPedina(fromRow, fromCol, toRow, toCol, eatRow, eatCol) {
    const startX = fromCol * DIM_BLOCCO + OFFSET_PEDINA;
    const startY = fromRow * DIM_BLOCCO + OFFSET_PEDINA;
    const endX = toCol * DIM_BLOCCO + OFFSET_PEDINA;
    const endY = toRow * DIM_BLOCCO + OFFSET_PEDINA;

    let ctx = canvas.getContext('2d');
    ctx.clearRect(startX, startY, DIM_BLOCCO, DIM_BLOCCO);
    ctx.clearRect(endX, endY, DIM_BLOCCO, DIM_BLOCCO);

    scacchiera[fromRow][fromCol].impostaPosizione(endX, endY);
    scacchiera[eatRow][eatCol] = null;

    scacchiera[toRow][toCol] = scacchiera[fromRow][fromCol];
    scacchiera[fromRow][fromCol] = null;
    pedinaSelezionata = null;

    if((((toRow == 0) && (scacchiera[toRow][toCol].colore == colorePedina))) || 
      (((toRow == (DIM_SCACCHIERA-1))) && (scacchiera[toRow][toCol].colore == colorePedinaAvversario))){
        scacchiera[toRow][toCol].impostaDama();
    }

    if((scacchiera[toRow][toCol].colore == colorePedina)){
        numMosse++;
    }

    disegnaScacchiera();
    disegnaPedine('bianca');
    disegnaPedine('nera');
}

function alzaPedina(fromRow, fromCol) {
    const startX = fromCol * DIM_BLOCCO + OFFSET_PEDINA;
    const startY = fromRow * DIM_BLOCCO + OFFSET_PEDINA;
    const endX = startX;
    const endY = startY - 15;

    let ctx = canvas.getContext('2d');
    ctx.clearRect(startX, startY, DIM_BLOCCO, DIM_BLOCCO);
    ctx.clearRect(endX, endY, DIM_BLOCCO, DIM_BLOCCO);
    disegnaScacchiera();

    scacchiera[fromRow][fromCol].impostaPosizione(endX, endY);

    disegnaPedine('bianca');
    disegnaPedine('nera');
}

function abbassaPedina(fromRow, fromCol) {
    const endX = fromCol * DIM_BLOCCO + OFFSET_PEDINA;
    const endY = fromRow * DIM_BLOCCO + OFFSET_PEDINA;

    let ctx = canvas.getContext('2d'); 
    ctx.clearRect(endX, endY, DIM_BLOCCO, DIM_BLOCCO);
    disegnaScacchiera();

    scacchiera[fromRow][fromCol].impostaPosizione(endX, endY);
    disegnaPedine('bianca');
    disegnaPedine('nera');
    
}

function controllaPosizioneValida(isDama, lastX, lastY, newX, newY){

    let condizione1 =   (newX + newY) % 2 === 1 && 
                        (newY != lastY) && (newX != lastX) && 
                        (Math.abs(lastX - newX) < 2) && 
                        (Math.abs(lastY - newY) < 2);
    
    if(isDama){
        return condizione1;
    }else{
        return condizione1 && (lastX > newX);
    }
}

function inviaMangiaPedina(lastX, lastY, newX, newY, eatX, eatY){
    const data = new FormData();
    data.append('turno', turno);
    data.append('lastX', lastX);
    data.append('lastY', lastY);
    data.append('newX', newX);
    data.append('newY', newY);
    data.append('eatX', eatX);
    data.append('eatY', eatY);

    fetch('./inviaMossa.php', {
        method: 'post',
        body: data
    }).catch(e => console.log("Errore invio mossa mangia pedina" + e.message));
}

function controllaVincitore(){
    if(!numBianche || !numNere){
        clearInterval(timerid);
        if(numBianche > numNere){
            alert("Hai vinto!");
        }else{
            alert("Hai perso...");
        }
        window.location.href= "./finePartita.php";
    }

    
}

function controlloAzione(event) {

    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    const col = Math.floor(x / DIM_BLOCCO)
    const row = Math.floor(y / DIM_BLOCCO);

    const username = document.getElementById('username').innerText;
    const turnoPlayer = document.getElementById('turno-player').innerText;

    if(numSpostamenti == 0 && (username == turnoPlayer)){
        if (pedinaSelezionata !== null) {

            const p = scacchiera[pedinaSelezionata.row][pedinaSelezionata.col];
    
            // se è la stessa pedina già selezionata
            if (pedinaSelezionata.row == row && pedinaSelezionata.col == col) {
                abbassaPedina(pedinaSelezionata.row, pedinaSelezionata.col);
                pedinaSelezionata = null;
                console.log("pedina deselezionata [%d, %d]", row, col);
                return;
            }
    
            // se è un'altra pedina dello stesso colore
            if (scacchiera[row][col] !== null && scacchiera[row][col].getColor() == p.getColor()){

                // se è diversa dalla pedina mangiatrice
                if (pedinaMangiatrice !== null){
                    if((pedinaMangiatrice.row != row) || (pedinaMangiatrice.col != col)){
                        return;
                    }
                }

                abbassaPedina(pedinaSelezionata.row, pedinaSelezionata.col);
                alzaPedina(row, col);
                pedinaSelezionata = { row: row, col: col };
                console.log("pedina selezionata [%d, %d]", row, col);
                return;
            }
            
            // se è una casella valida
            if(controllaPosizioneValida(p.isDama(), pedinaSelezionata.row, pedinaSelezionata.col, row, col)){
                
                if (scacchiera[row][col] === null){

                    if(numMosse != 0){
                        abbassaPedina(pedinaSelezionata.row, pedinaSelezionata.col);
                        pedinaSelezionata = null;
                        return;
                    }

                    // salvtaggio nel db della mossa
                    const data = new FormData();
                    data.append('turno', turno);
                    data.append('lastX', pedinaSelezionata.row);
                    data.append('lastY', pedinaSelezionata.col);
                    data.append('newX', row);
                    data.append('newY', col);
    
                    fetch('./inviaMossa.php', {
                        method: 'post',
                        body: data
                    }).catch(e => console.log("Errore invio mossa spostamento pedina" + e.message));
                    
                    console.log("pedina spostata da [%d, %d] a [%d, %d]",pedinaSelezionata.row, pedinaSelezionata.col, row, col);
                    
                    spostaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row, col);
    
                }else{
                    if(pedinaSelezionata.row < row){
                        if(pedinaSelezionata.col > col){
                            if(scacchiera[row+1][col-1] === null){
                                if(!p.isDama() && scacchiera[row][col].isDama()){
                                    abbassaPedina(pedinaSelezionata.row, pedinaSelezionata.col);
                                    pedinaSelezionata = null;
                                    return;
                                }
                                inviaMangiaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row+1, col-1, row, col);
                                mangiaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row+1, col-1, row, col);
                                numNere--;

                                pedinaMangiatrice = {row: row+1, col: col-1};
                            }
                        }else{
                            if(scacchiera[row+1][col+1] === null){
                                if(!p.isDama() && scacchiera[row][col].isDama()){
                                    abbassaPedina(pedinaSelezionata.row, pedinaSelezionata.col);
                                    pedinaSelezionata = null;
                                    return;
                                }
                                inviaMangiaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row+1, col+1, row, col);
                                mangiaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row+1, col+1, row, col);
                                numNere--;

                                pedinaMangiatrice = {row: row+1, col: col+1};
                            }
                        }
                    }else{
                        // pedinaSelezionata.row > row
                        if(pedinaSelezionata.col > col){
                            if(scacchiera[row-1][col-1] === null){
                                if(!p.isDama() && scacchiera[row][col].isDama()){
                                    abbassaPedina(pedinaSelezionata.row, pedinaSelezionata.col);
                                    return;
                                }
                                inviaMangiaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row-1, col-1, row, col);
                                mangiaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row-1, col-1, row, col);
                                numNere--;

                                pedinaMangiatrice = {row: row-1, col: col-1};
                            }
                        }else{
                            if(scacchiera[row-1][col+1] === null){
                                if(!p.isDama() && scacchiera[row][col].isDama()){
                                    abbassaPedina(pedinaSelezionata.row, pedinaSelezionata.col);
                                    return;
                                }
                                inviaMangiaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row-1, col+1, row, col);
                                mangiaPedina(pedinaSelezionata.row, pedinaSelezionata.col, row-1, col+1, row, col);
                                numNere--;

                                pedinaMangiatrice = {row: row-1, col: col+1};
                            }
                        }
                    }                
                }
            }
        } else if ((scacchiera[row][col] !== null) && (scacchiera[row][col].colore == colorePedina)) {

            if(pedinaMangiatrice !== null){
                if((row != pedinaMangiatrice.row) || (col != pedinaMangiatrice.col)){
                    return;
                }
            }

            alzaPedina(row, col);
            pedinaSelezionata = { row: row, col: col };
            console.log("pedina selezionata [%d, %d]", row, col);
        }
    }

    controllaVincitore();
    
}

function aggiornaTurnoPlayer(){
    const turnoPlayer = document.getElementById('turno-player');
    const username = document.getElementById('username').innerText;
    const avversario = document.getElementById('avversario').innerText;

    if(turnoPlayer.innerText == username){
        turnoPlayer.innerText = avversario;
    }else{
        turnoPlayer.innerText = username;
    }
}

function aggiornaStatoBottone(){
    const btn = document.getElementById("confirm-button");
    btn.disabled = !btn.disabled;
}

// Cambio turno
function confermaMossa(e){
    if(numMosse == 0){
        alert("Devi fare una mossa!");
        return;
    }

    const data = new FormData();
    data.append('turno', turno);
    fetch('./inviaCambioTurno.php', {
        method: 'post',
        body: data
    }).catch(e => console.log("Errore invio cambio turno" + e.message));

    // aggiornaStatoBottone();
    e.target.disabled = !e.target.disabled;

    turno++;

    aggiornaTurnoPlayer();

    numSpostamenti = 0;
    numMosse = 0;

    pedinaMangiatrice = null;
}

// Chat
function inviaMessaggio(){
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-msg');
    const message = chatInput.value;

    let regExpr = /^.{0,200}$/;
    if(!regExpr.test(message)){
        alert("messaggio troppo lungo (max 200 caratteri)");
        return;
    }

    if (message.trim() !== "") {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.classList.add('tu');
        chatMessages.appendChild(messageElement);
        const msgText = document.createElement('p');
        msgText.textContent = message;
        messageElement.appendChild(msgText);
        chatInput.value = "";
        chatMessages.scrollTop = chatMessages.scrollHeight;

        const data = new FormData();
        data.append('messaggio', message);
        fetch('./inviaMessaggio.php', {
            method: 'post',
            body: data
        }).catch(e => console.log("Errore invio messaggio" + e.message));
    }
}

async function ottieniMessaggiChat(){
    const res = await fetch("./ottieniMessaggi.php");
    const data = await res.json();
    const msgs = data.messaggi;

    return msgs;
}

async function ottieniRispostaInvito(){
    const res = await fetch("./ottieniRispostaInvito.php");
    const data = await res.json();
    const rispostaInvito = data.invitoPartita;

    return rispostaInvito;
}

async function ottieniMossa(){
    const dataInput = new FormData();
    dataInput.append('turno', turno);
    const res = await fetch("./ottieniMossa.php", {method: 'post', body: dataInput});
    const dataOutput = await res.json();
    const mossa = dataOutput.mossa;

    return mossa;
}

async function ottieniCambioTurno(){
    const dataInput = new FormData();
    dataInput.append('turno', turno);
    const res = await fetch("./ottieniCambioTurno.php", {method: 'post', body: dataInput});
    const dataOutput = await res.json();
    const cambio = dataOutput.cambio;

    return cambio;
}

function specchia(coordinata){
    return DIM_SCACCHIERA - coordinata - 1;
}

function aggiornaPartita(){

    ottieniRispostaInvito()
        .then((rispostaInvito) => {
            if(rispostaInvito == "vuoto"){
                clearInterval(timerid);
                alert("L\'avversario ha abbandonato la partita \r\n Hai vinto!");
                window.location.href = "./finePartita.php";
            }
        })
        .catch((e) => {
            clearInterval(timerid);
            alert("Errore: " + e.message);
        });
    
    ottieniMessaggiChat()
        .then((msgs) => {
            if(msgs != "vuoto"){
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.innerHTML = "";
                for(const key in msgs){
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');

                    const username = document.getElementById('username').innerText;
                    const avversario = document.getElementById('avversario').innerText;

                    if(msgs[key].mittente == username){
                        messageElement.classList.add('tu');
                    }else if(msgs[key].mittente == avversario){
                        messageElement.classList.add('avversario');
                    }

                    chatMessages.appendChild(messageElement);
                    const msgText = document.createElement('p');
                    msgText.textContent = msgs[key].contenuto;
                    messageElement.appendChild(msgText);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            }
        })
        .catch((e) => {
            clearInterval(timerid);
            alert("Errore ricezione messaggio: " + e.message);
        });

    ottieniMossa()
        .then((mossa) => {
            if(mossa != "vuoto"){
                if((mossa.eatX != "vuoto") && (mossa.eatY != "vuoto")){
                    mangiaPedina(specchia(mossa.lastX),
                                specchia(mossa.lastY),
                                specchia(mossa.newX),
                                specchia(mossa.newY),
                                specchia(mossa.eatX),
                                specchia(mossa.eatY));
                    numBianche--;
                }else{
                    spostaPedina(specchia(mossa.lastX),
                                specchia(mossa.lastY),
                                specchia(mossa.newX),
                                specchia(mossa.newY));
                }
                disegnaScacchiera();
                disegnaPedine('nera');
                disegnaPedine('bianca');
                controllaVincitore();
            }
        })
        .catch((e) => {
            clearInterval(timerid);
            alert("Errore ricezione mossa: " + e.message);
        });

    ottieniCambioTurno()
        .then((cambio) => {
            if(cambio != "vuoto"){
                turno++;
                aggiornaTurnoPlayer();
                aggiornaStatoBottone();
            }
        })
        .catch((e) => {
            clearInterval(timerid);
            alert("Errore ricezione cambio turno: " + e.message);
        });
}


// inizializzazione partita
function inizio() {

    turno = 0;
    numBianche = 12;
    numNere = 12;
    numMosse = 0;
    numSpostamenti = 0;

    const username = document.getElementById('username').innerText;
    console.log(username);
    const avversario = document.getElementById('avversario').innerText;
    console.log(avversario);

    const turnoPlayer = document.getElementById('turno-player').innerText;

    if(username == turnoPlayer){
        colorePedina = 'bianca';
        console.log("TU: "+colorePedina);
        colorePedinaAvversario = 'nera';
        console.log("AVVERSARIO: "+colorePedinaAvversario);
    }else if(avversario == turnoPlayer){
        colorePedina = 'nera';
        console.log("TU: "+colorePedina);
        colorePedinaAvversario = 'bianca';
        console.log("AVVERSARIO: "+colorePedinaAvversario);
    }

    canvas = document.getElementById('scacchiera');

    inizializza();

    disegnaScacchiera();

    pedinaNera.onload = () => {
        disegnaPedine('nera');
    };

    pedinaBianca.onload = () => {
        disegnaPedine('bianca');
    };

    damaNera.onload = () => {
        disegnaPedine('nera');
    };

    damaBianca.onload = () => {
        disegnaPedine('bianca');
    };

    canvas.addEventListener('click', controlloAzione);

    const chatButton = document.getElementById('chat-button');
    chatButton.addEventListener('click', inviaMessaggio);

    const confirmButton = document.getElementById('confirm-button');
    confirmButton.addEventListener('click', confermaMossa);
    
    if(turnoPlayer == avversario){
        confirmButton.disabled = true;
    }

    time = 0;
    timerid = setInterval(aggiornaPartita, TIME);
}

document.addEventListener("DOMContentLoaded", inizio);