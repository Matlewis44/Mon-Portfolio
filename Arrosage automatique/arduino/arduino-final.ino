int pinRelais = 8;
int  pinModule=A0;
boolean statutPompe = 0;
String configuration = "Aucune";
int frequence; //en heures
int seuil; //en % d'humidité
int duree; //en secondes
int mode = 0; //val: 0 = inactif, 1 = frequence, 2 = seuil
int switchPhase = 0;
unsigned long previousMillis = 0;
unsigned long tempoDepart = 0;
unsigned long tempoDepart2 = 0;
String fromPi;

void setup() {
  Serial.begin(9600);
  pinMode(LED_BUILTIN, OUTPUT);
  digitalWrite(LED_BUILTIN, LOW);
  pinMode(pinRelais, OUTPUT);
  digitalWrite(pinRelais, HIGH); //à l'arrêt
}

void loop() {
  unsigned long currentMillis = millis();
  if (currentMillis - previousMillis >= 500) {
    fromPi = "";
    if(Serial.available() > 0){
      fromPi = Serial.readString();
      
      while(fromPi.charAt(0) == ' '){ //retire les caractères espace du recu
        fromPi = fromPi.substring(1, fromPi.length());
      }
      
      if(fromPi == "A"){
        configuration = "Aucune";
        mode = 0;
        switchPhase = 0;
        digitalWrite(pinRelais, LOW); //en fonctionnement
        Serial.println("OK - A");
      }
    
      else if(fromPi == "E"){
        configuration = "Aucune";
        mode = 0;
        switchPhase = 0;
        digitalWrite(pinRelais, HIGH); //à l'arrêt
        Serial.println("OK - E");
      }
  
      else if(fromPi == "AE"){
        digitalWrite(pinRelais, LOW); //en fonctionnement
        delay(5000);
        digitalWrite(pinRelais, HIGH); //à l'arrêt
        Serial.println("OK - AE");
      }

      else if(fromPi == "getHumidity"){
        int humidity = (100-((75UL*analogRead(pinModule))/1023)-20);
        Serial.println(humidity);
      }
      
      else if(fromPi == "getConfig"){
        Serial.println(configuration);
      }
  
      else if(fromPi == "resetConfig"){
        digitalWrite(pinRelais, HIGH); //pompe à l'arrêt
        configuration = "Aucune";
        mode = 0;
        switchPhase = 0;
        Serial.println(configuration);
      }
  
      else if(fromPi.substring(0,9) == "setConfig"){
        String trame = fromPi;
        int trame_len = trame.length() + 1;
        char trame_array[trame_len];
        trame.toCharArray(trame_array, trame_len);
        
        int cnt = 0;
        char* tab[5] = {0};
   
        char *pch = strtok(trame_array, "-");
   
        while (pch != NULL) {
          if (cnt < 5) {
            tab[cnt++] = pch;
          } else {
            break;
          }
          pch = strtok (NULL, "-");
        }
  
        //tab[0] == "setConfig"
        configuration = String(tab[1]);
        frequence = atoi(tab[2]);
        seuil = atoi(tab[3]);
        duree = atoi(tab[4]);
        //Définition du mode
        if(seuil == 0)
          mode = 1; //mode fréquence
        else //fréquence == 0
          mode = 2; //mode seuil
        switchPhase = 0;
        Serial.println(configuration);
        tempoDepart = millis();
      } // end setConfig
  
    } // end Serial.available
    
    //mode 1 = config en fonction de la fréquence
    if(mode == 1){ //UL = eviter l'overflow
      //phase passive (fréquence)
      if (switchPhase == 0 && ((millis() - tempoDepart) >= (1000UL*frequence)*3600)){ //en heures //en secondes (uniquement pour les tests sinon en heures *3600)
        switchPhase = 1;
        digitalWrite(pinRelais, LOW); //pompe en fonctionnement
        tempoDepart2 = millis();
      }
      //phase active (durée)
      if (switchPhase == 1 && ((millis() - tempoDepart2) >= (1000UL*duree))) { //en secondes
          switchPhase = 0;
          digitalWrite(pinRelais, HIGH); //pompe à l'arrêt
          tempoDepart = millis();
      }
    } // end if(mode == 1)

    else if(mode == 2){
      //phase passive (vérif toutes les 10secs du seuil)
      if(switchPhase == 0 && (millis() - tempoDepart >= 10000) && (100-((75UL*analogRead(pinModule))/1023)-20) < seuil) {
        switchPhase = 1;
        digitalWrite(pinRelais, LOW); //pompe en fonctionnement
        tempoDepart2 = millis();
      }
      //phase active (durée)
      if (switchPhase == 1 && ((millis() - tempoDepart2) >= (1000UL*duree))) { //en secondes
          switchPhase = 0;
          digitalWrite(pinRelais, HIGH); //pompe à l'arrêt
          tempoDepart = millis();
      }
    } // end if(mode == 2)
    
    previousMillis = currentMillis;
    Serial.print(" ");
  } // end delai 500ms
} // end loop()
