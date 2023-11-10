# SAE-3.02--
SAE S3.02  Développer une application web sécurisée. PIERROT-PINOT-TROHA

PIERROT Nathan -- Ratz123323  
PINOT Gaëtan -- gaetanpinot  
TROHA Stanislas -- Stantrh  




le config.ini doit être configuré comme suit:  

hostWeb doit être l'url du serveur web où vous déployez le projet  

Attention les images uploadées avec les touites sont uploadées dans le dossier images de hostWeb dans le fichier de config  
Donc toutes les images uploadées avec l'appli locale ne seront pas disponibles sur les applis des autres utilisateurs  
Par contre les images uploadées avec l'application sur le webetu sont stockées sur le webetu et peuvent être consultées de partout.


```
driver=mysql
username=usernameXu
password=yourPassword
host=webetu.iutnc.univ-lorraine.fr
database=troha2u
hostWeb=http://localhost:63342/SAE-3.02-Touiteur.app
```
il doit être placé dans le src  

https://webetu.iutnc.univ-lorraine.fr/www/pinot33u/SaeTouiteurPinotPierrotTroha  

le site sur le webetu ne differe que par quelques détails de compatibilité, la structure reste absolument la même, seuls des chemins de fichiers sont changés pour la compatibilité
