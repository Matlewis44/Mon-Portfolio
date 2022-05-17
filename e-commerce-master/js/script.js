window.onload = () => {
    document.querySelector("#pwd").addEventListener('input', checkPass);
}
/**
 * Fonction pour afficher le mot de passe
 */
function Afficher(){ 
    var input = document.getElementById("pwd"); 
    if (input.type === "password")  { 
        input.type = "text"; 
    }
    else{ 
        input.type = "password"; 
    } 
} 
function Afficher2(){ 
    var input = document.getElementById("pwd2"); 
    if (input.type === "password")  { 
        input.type = "text"; 
    }
    else{ 
        input.type = "password"; 
    } 
} 
/////////////////
window.onload = () => {
    document.querySelector("#password").addEventListener('input', checkPass);
}
/**
 * Fonction pour afficher le mot de passe
 */
function Afficher3(){ 
    var input = document.getElementById("password"); 
    if (input.type === "password")  { 
        input.type = "text"; 
    }
    else{ 
        input.type = "password"; 
    } 
} 

function Afficher4(){ 
    var input = document.getElementById("password_two"); 
    if (input.type === "password")  { 
        input.type = "text"; 
    }
    else{ 
        input.type = "password"; 
    } 
} 


/*e=true;
function changer(){
	if(e){
		document.getElementById("password").setAttribute("type","text");
		document.getElementById("eye").src="img/open_eye";
		e=false;
	}
}*/


/**
 * Cette fonctions vérifie le mot de passe
 * 
 */
function checkPass() {
    // On récupère ce qui a été saisi
    let mdp = this.value;

    // On intialise un score
    let score = 0;

    // On va chercher les éléments dont on a besoin
    let minuscule = document.querySelector("#minuscule");
    let majuscule = document.querySelector("#majuscule");
    let chiffre = document.querySelector("#chiffre");
    let special = document.querySelector("#special");
    let longueur = document.querySelector("#longueur");

    let valid = document.querySelector(".valid");
    let invalid = document.querySelector(".invalid");

    //On vérifie qu'on a une minuscule
    if (/[a-z]/.test(mdp)) {
        // On passe en vert "valid"
        minuscule.classList.replace("invalid", "valid");
        valid.style.color ='green';
        score++;
    }else{
        // On passe en rouge "invalid"
        minuscule.classList.replace("valid", "invalid");
        invalid.style.color ='red';
    }

    //On vérifie qu'on a une majuscule
    if (/[A-Z]/.test(mdp)) {
        // On passe en vert "valid"
        majuscule.classList.replace("invalid", "valid");
        valid.style.color ='green';
        score++;
    }else{
        // On passe en rouge "invalid"
        majuscule.classList.replace("valid", "invalid");
        invalid.style.color ='red';

    }

    //On vérifie qu'on a un chiffre
    if (/[0-9]/.test(mdp)) {
        // On passe en vert "valid"
        chiffre.classList.replace("invalid", "valid");
        valid.style.color ='green';
        score++;
    }else{
        // On passe en rouge "invalid"
        chiffre.classList.replace("valid", "invalid");
        invalid.style.color ='red';
    }

    //On vérifie qu'on a un carctère spécial
    if (/[$@!%*#/\\&]/.test(mdp)) {
        // On passe en vert "valid"
        special.classList.replace("invalid", "valid");
        valid.style.color ='green';
        score++;
    }else{
        // On passe en rouge "invalid"
        special.classList.replace("valid", "invalid");
        invalid.style.color ='red';
    }
    
    //On vérifie que la longueur est bonne
    if (mdp.length >= 8) {
        // On passe en vert "valid"
        longueur.classList.replace("invalid", "valid");
        valid.style.color ='green';
        score++;
    }else{
        // On passe en rouge "invalid"
        longueur.classList.replace("valid", "invalid");
        invalid.style.color ='red';
    }

    if (score === 5) {
        document.querySelector("[type = submit]").style.display = "initial";
    } else {
        document.querySelector("[type = submit]").style.display = "none";
    }

}

