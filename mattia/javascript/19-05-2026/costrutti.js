//Selezione => if

/*
if(condizione){
    //codice IF
}else{
    //codice altrimenti
}

if(condizione){
    //codice
}

//If nidificicati
if(condizione){
    if(condizione){
        if(condizione){
            
        }
    }
}else{
    if(condizione){
        
    }    
}

//If a cascata
if(condizione){
    
}else if(condizione){

}else{

}
*/
console.log("COSTRUTTI");

/*var n = 10;
if(!(n%2==0)){
    console.log("DISPARI");
}else{
    console.log("PARI");
}*/

/*var voto = 5;
if(voto<6){
    console.log("Ins");
}else if(voto===6){
    console.log("Suff");
}else if(voto===7){
    console.log("Discreto");
}else if(voto===8){
    console.log("Buono");
}else if(voto===9){
    console.log("Ottimo");
}else if(voto===10){
    console.log("Distinto");
}else{
    console.log("Voto non valido");
}*/

/*
switch(espressione){
    case ...:
        //codice
        break;
    .
    .
    .
    default:
        //codice;
}

switch(espressione){
    case ...:
    case ...:
    case ...:
    case ...:
        //codice
        break;
    .
    .
    .
    default:
        //codice;
}
*/

/*var semaforo = "yeru";
switch(semaforo){
    case "rosso":
        console.log("accendi rosso");
        break;
    case "arancio":
        console.log("accendi arancio");
        break;
    case "verde":
        console.log("accendi verde");
        break;
    default:
        console.log("errore di sistema: arancio lampeggiante");
}*/

/*ITERAZIONI

ciclo pre condizionale
while(condizione){
    codice da ripetere
}

ciclo post-condizionale
do{
    condizione da ripetere
}while(condizione);
*/

/*console.log("1) Inserisci dati")
console.log("2) cerca")
console.log("0) Esci")
/*var response = prompt("Fai la tua scelta");
while(response!=0){
    console.log("sono nel ciclo ed eseguo questo codice fino a quando non digito 0");

    response = prompt("Fai la tua scelta");
}*/
/*do{
    var response = prompt("Fai la tua scelta");
    console.log("sono nel ciclo ed eseguo questo codice fino a quando non digito 0");
}while(response!=0);
console.log("Sono uscito dal ciclo");**


/*
Ciclo For
for(inizializzazione del contatore; condizione; operazione sul contatore){
    codice da ciclare
}

contatore = 0;
for(; condizione; ){
    codice da ciclare

    contatore ++;
}
for(iniz cont1, ...., in contN; condizione; op cont1, ...., op contN){
    codice da ciclare

    contatore ++;
}
*/

var arr = ["lun", "mar", "mer", "gio", "ven", "sab", "dom"];
/*for(let i = 0; i<arr.length; i ++){
    console.log( arr[i] );
}
for(let i = 0; i<arr.length; ){
    console.log( arr[i ++] );
}
*/

//cicli per array
for(let indice in arr){
    console.log(indice);
}
for(let valore of arr){
    console.log(valore);
}