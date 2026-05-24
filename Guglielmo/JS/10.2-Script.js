var matrice = [
    [2,4,9],
    [8,3,1]
]

var sommaPari = 0;
var sommaDispari = 0; 

for(let i=0; i<matrice.length; i++) {
    for(let j=0; j<matrice[i].length; j++) {

        if(matrice[i][j]%2 == 0) {
            sommaPari += matrice[i][j];
        } else {
            sommaDispari += matrice[i][j];
        }
    }
}

console.log("Somma pari:" + sommaPari);
console.log("Somma dispari:" + sommaDispari);


