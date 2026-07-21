// CREARE E GESTIRE UNA RUBRICA TELEFONICA CON ANNESSE FUNZIONI

let rubrica = {
  contatti: [],
  aggiungiContatto: function (nome, cognome, telefono) {
    let contatto = {
      nome: nome,
      cognome: cognome,
      telefono: telefono,
    };
    this.contatti.push(contatto);
  },
  mostraContatti: function () {
    for (let i = 0; i < this.contatti.length; i++) {
      console.log(this.contatti[i]);
    }
  },
  cercaContatto: function (nome) {
    for (let i = 0; i < this.contatti.length; i++) {
      if (this.contatti[i].nome === nome) {
        console.log(this.contatti[i]);
      }
    }
  },
};

rubrica.aggiungiContatto("Mario", "Rossi", "3394638741");
rubrica.aggiungiContatto("Luca", "Bianchi", "3478923008");
rubrica.mostraContatti();
rubrica.cercaContatto("Mario");
