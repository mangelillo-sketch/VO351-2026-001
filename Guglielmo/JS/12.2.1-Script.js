// ESERCIZIO 1

function isPrimo(numero) {

    if (numero < 2) {
        return false;
    }

    for (let i = 2; i < numero; i++) {
        if (numero % i === 0) {
            return false;
        }
    }

    return true;
}

// PARTE 2

let contatore = 0;
let numero = 2;

while (contatore < 10) {

    if (isPrimo(numero)) {
        console.log(numero);
        contatore++;
    }

    numero++;
}

/* COME FUNZIONA
isPrimo(numero) restituisce true se il numero è primo, altrimenti false.
contatore tiene traccia di quanti numeri primi sono stati trovati.
numero viene incrementato uno alla volta.
Quando un numero è primo viene stampato e il contatore aumenta.
Il ciclo termina quando sono stati trovati 10 numeri primi.*/

// ESERCIZIO 2

function miaRandom(x, y) {
    return Math.floor(Math.random() * (y - x + 1)) + x;
}

for (let i = 0; i < 10; i++) {
    console.log(miaRandom(1, 10));

}


/* COME FUNZIONA
Math.random() genera un numero casuale tra 0 e 1 (escluso 1).
Moltiplicandolo per (y - x + 1) otteniamo un valore nell'intervallo desiderato.
Math.floor() elimina la parte decimale.
+ x sposta l'intervallo da 0... a x...y.*/

// ESERCIZIO 3

function primaCifra(numero) {

    while (numero >= 10) {
        numero = Math.floor(numero / 10);
    }

    return numero;
}

/* COME FUNZIONA
Ad ogni iterazione il numero viene diviso per 10 e viene eliminata la parte decimale con Math.floor().
Quando il numero diventa minore di 10, significa che è rimasta solo la prima cifra, che viene restituita.*/

// ESERCIZIO 4

function isPalindromo(numero) {

    let originale = numero;
    let contrario = 0;

    while (numero > 0) {

        let cifra = numero % 10; // prende l'ultima cifra

        contrario = contrario * 10 + cifra;

        numero = Math.floor(numero / 10);
    }

    return originale === contrario;
}