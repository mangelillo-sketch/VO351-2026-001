// ESERICIZO 2

var a = prompt();
var contatore = 1;
var cifra = 0;
do{
    contatore = contatore *10;
    cifra ++
}
while(a/contatore >= 1);

console.log(cifra)

// ESERCIZIO 8

var votoOrale = 8;
var votoScritto = 20;

var votoFinale = votoOrale + votoScritto;

if((votoOrale <= 0 && votoFinale > 18)||(votoOrale <= 0 && votoScritto < 18)||(votoOrale > 0 && votoFinale <18))
    console.log("Sei bocciato!");
 else if(votoFinale == 31 || votoFinale == 32)
    console.log("Congratulazioni: 30 e lode!");
else{
    console.log("Il voto finale è:" + votoFinale);
}

