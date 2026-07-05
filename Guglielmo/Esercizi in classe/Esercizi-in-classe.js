/*function isPrimo(numero) {
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

let contatore = 0;
let numero = 2;

while (contatore < 10) {
  if (isPrimo(numero)) {
    console.log(numero);
    contatore++;
  }

  numero++;
}*/

// Trasformare questo esercizio utilizzando oggetti

var calcolatoreNumeriPrimi = {
  memoria: [],
  isPrimo: function (numero) {
    if (numero < 2) {
      return false;
    }

    for (let i = 2; i < numero; i++) {
      if (numero % i === 0) {
        return false;
      }
    }

    return true;
  },

  primiDieciPrimi: function (numero) {
    let contatore = 0;
    while (contatore < 10) {
      if (this.isPrimo(numero)) {
        this.memoria.push(numero);
        contatore++;
      }

      numero++;
    }
  },
};

calcolatoreNumeriPrimi.primiDieciPrimi(121);
console.log(calcolatoreNumeriPrimi.memoria);
