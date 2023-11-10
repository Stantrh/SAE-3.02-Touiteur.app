# SAE-3.02--
SAE S3.02  Développer une application web sécurisée. PIERROT-PINOT-TROHA

PIERROT Nathan -- Ratz123323  
PINOT Gaëtan -- gaetanpinot  
TROHA Stanislas -- Stantrh  




le config.ini doit être configuré comme suit:  

hostWeb doit être l'url du serveur web où vous déployez le projet  

Attention les images uploadé avec les touites sont uploadés sur dans le dossier images de hostWeb dans le fichier de config  
Donc toutes les images uploadé avec l'applie local ne seront pas disponible sur les applie des autres utilisateurs  
Par contre les images uploadé avec l'application sur le webetu sont stocké sur le webetu et peuvent être consulté de partout.


```
driver=mysql
username=usernameXu
password=yourPassword
host=webetu.iutnc.univ-lorraine.fr
database=troha2u
hostWeb=http://localhost:63342/SAE-3.02-Touiteur.app
```
il doit être placé dans le src
