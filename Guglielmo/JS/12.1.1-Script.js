// ESERCIZIO 1

// Seleziono il paragrafo con classe p2
let paragrafo2 = document.querySelector(".p2");

// Cambio il testo del paragrafo 2
paragrafo2.textContent = "Questo è il nuovo paragrafo 2";

// Seleziono il div
let contenitore = document.querySelector("div");

// Seleziono l'ultimo paragrafo dentro il div
let ultimoParagrafo = contenitore.lastElementChild;

// Aggiungo il bordo rosso all'ultimo paragrafo
ultimoParagrafo.style.border = "1px solid red";

// ESERCIZIO 2

// Creo l'immagine
let immagine = document.createElement("img");

immagine.src = "/Guglielmo/Img/Immagine-esercizio.webp";
console.log("Immagine esercizio:", immagine);

contenitore.insertBefore(immagine, contenitore.childNodes[3]);

/*
contenitore.appendChild(immagine)*/
// ESERCIZIO 3

// Creo il div
let nuovoDiv = document.createElement("div");

// Aggiungo la classe
nuovoDiv.className = "miodiv";

// Imposto il bordo rosso
nuovoDiv.style.border = "1px solid red";

// Inserisco il testo
nuovoDiv.textContent = "Questo è il testo del nuovo div";

// Lo aggiungo alla pagina
document.body.appendChild(nuovoDiv);