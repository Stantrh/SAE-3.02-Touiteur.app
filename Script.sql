DROP TABLE LIKE2TOUITE;
DROP TABLE SUIVRETAG;
DROP TABLE SUIVREUSER;
DROP TABLE TAG2TOUITE;
DROP TABLE TAG;
DROP TABLE IMAGE;
DROP TABLE TOUITE;
DROP TABLE UTILISATEUR;














CREATE TABLE UTILISATEUR(
                            idUser int(10) AUTO_INCREMENT,
                            nom varchar(256),
                            prenom varchar(256),
                            email varchar(256),
                            nomUser varchar(25),
                            mdp varchar(256),
                            role int(3),
                            PRIMARY KEY (idUser)
);

CREATE TABLE TOUITE(
                       idTouite INT(12) AUTO_INCREMENT,
                       idUser INT(10),
                       date DATE,
                       texteTouite VARCHAR(256),
                       idImage INT(10),
                       score INT(10),
                       PRIMARY KEY (idTouite)
);

CREATE TABLE IMAGE(
                      idImage INT(10) AUTO_INCREMENT,
                      description VARCHAR(256),
                      cheminFichier VARCHAR(256),
                      PRIMARY KEY(idImage)
);

CREATE TABLE TAG(
                    idTag INT(10) AUTO_INCREMENT,
                    libelle VARCHAR(256),
                    description varchar(256),
                    PRIMARY KEY (idTag)
);

CREATE TABLE TAG2TOUITE(
                           idTouite INT(12),
                           idTag INT(10),
                           PRIMARY KEY (idTouite, idTag)
);

CREATE TABLE SUIVREUSER(
                           idUser INT(10),
                           idUserSuivi INT(10),
                           PRIMARY KEY (idUser, idUserSuivi)
);

CREATE TABLE SUIVRETAG(
                          idUser INT(10),
                          idTag INT(10),
                          PRIMARY KEY (idUser, IdTag)
);

CREATE TABLE LIKE2TOUITE(
                            idUser INT(10),
                            idTouite INT(12),
                            appreciation int(1),
                            PRIMARY KEY (idUser, IdTouite)
);

ALTER TABLE TOUITE ADD
    FOREIGN KEY (idUser) REFERENCES UTILISATEUR(idUser);
ALTER TABLE TOUITE ADD
    FOREIGN KEY (idImage) REFERENCES IMAGE(idImage);
ALTER TABLE TAG2TOUITE ADD
    FOREIGN KEY(idTouite) REFERENCES TOUITE(idTouite);
ALTER TABLE TAG2TOUITE ADD
    FOREIGN KEY (idTag) REFERENCES TAG(idTag);
ALTER TABLE SUIVREUSER ADD
    FOREIGN KEY (idUser) REFERENCES UTILISATEUR(idUser);
ALTER TABLE SUIVREUSER ADD
    FOREIGN KEY (idUserSuivi) REFERENCES UTILISATEUR(idUser);
ALTER TABLE SUIVRETAG ADD
    FOREIGN KEY (idUser) REFERENCES UTILISATEUR(idUser);
ALTER TABLE SUIVRETAG ADD
    FOREIGN KEY (idTag) REFERENCES TAG(idTag);
ALTER TABLE LIKE2TOUITE ADD
    FOREIGN KEY (idUser) REFERENCES UTILISATEUR(idUser);
ALTER TABLE LIKE2TOUITE ADD
    FOREIGN KEY (idTouite) REFERENCES TOUITE(idTouite);








-- Insertions
-- Pour la table Utilisateur
INSERT INTO UTILISATEUR(nom, prenom, email, nomUser, mdp, role) VALUES ('PIERROT', 'Nathan', 'user1@mail.com', 'user1', '$2y$10$bXR7ERBTsXzbpJjdXbxPMee477c87MR5M9YduJlNxaUhImEkdM4ve', 1);
INSERT INTO UTILISATEUR(nom, prenom, email, nomUser, mdp, role) VALUES ('PINOT', 'Gaetan', 'user2@mail.com', 'user2', '$2y$10$QwtB76gJcZEqSJ/vEHxdIe2fwhrXN9YYG71fKEv/mm3aOI9zXCZMi', 1);
INSERT INTO UTILISATEUR(nom, prenom, email, nomUser, mdp, role) VALUES ('SASSI-WEBER', 'Joel', 'user3@mail.com', 'user3', '$2y$10$GYZn8tmhLvORWmNBg/GgKutSghinLXgj.ZAqekzhZJBhTMdEoPHOG', 1);
INSERT INTO UTILISATEUR(nom, prenom, email, nomUser, mdp, role) VALUES ('TROHA', 'Stanislas', 'user4@mail.com', 'user100', '$2y$10$FiQ4SawRg9sdwdXyKhu3wuUYLu46EJxors7kHAbJKuBcDP1SjNrrO', 100);
INSERT INTO UTILISATEUR(nom, prenom, email, nomUser, mdp, role) VALUES ('CANALS', 'Gérome', 'user5@mail.com', 'user101', '$2y$10$rJzcrXUAXmgEdv6thS6pqeRu7P.Ka0bWKzhDq2lXqYoPZQfszWbvi', 100);


-- Pour la table Image
INSERT INTO IMAGE(description, cheminFichier) VALUES ('ekip ekip so le flem', '../ekip/667tah/669.jpeg');
INSERT INTO IMAGE(description, cheminFichier) VALUES ('ekip ekip so le flem', '../ekip/667tah/lyonzon.jpeg');
INSERT INTO IMAGE(description, cheminFichier) VALUES ('ekip ekip so le flem', '../ekip/667tah/667ekipekip.jpeg');

-- Pour la table Touite
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (1,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), 'J aime bien les chats, c est cool, surtout les chats français #chat #france' , -1, 0);
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (1,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), 'il fait super beau aujourdhui ' , -1, 0);
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (2,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), 'j ai fait un site en php pour voir des images de chat #php #chat' , -1, 0);
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (3,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), 'nancy c est la plus belle ville du monde' , -1, 0);
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (4,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), ' j ai bien mangé ce midi ' , -1, 0);


-- Pour la table Tag
INSERT INTO TAG (libelle, description) VALUES ('chat', 'animal mignon');
INSERT INTO TAG (libelle, description) VALUES ('france', 'meilleur pays');
INSERT INTO TAG (libelle, description) VALUES ('NancyCentreDuMonde', 'reel selon Annick Thimon');
INSERT INTO TAG (libelle, description) VALUES ('php', 'langage adoré');
INSERT INTO TAG (libelle, description) VALUES ('X','réseau social ça a changé');
INSERT INTO TAG (libelle, description) VALUES ('Légende', 'Zinedine Zidane');
INSERT INTO TAG (libelle, description) VALUES ('IUTCharlemagne', 'IUT de fou');
INSERT INTO TAG (libelle, description) VALUES ('gâteaux','pâtisseries appréciées de tous');

-- Pour la table Tag2Touite
INSERT INTO TAG2TOUITE (idTouite, idTag) VALUES (1, 1);
INSERT INTO TAG2TOUITE (idTouite, idTag) VALUES (1, 2);
INSERT INTO TAG2TOUITE (idTouite, idTag) VALUES (3, 1);
INSERT INTO TAG2TOUITE (idTouite, idTag) VALUES (3, 4);

-- Pour la table SuivreUser
INSERT INTO SUIVREUSER (idUser, idUserSuivi) VALUES (1, 3);
INSERT INTO SUIVREUSER (idUser, idUserSuivi) VALUES (2, 3);
INSERT INTO SUIVREUSER (idUser, idUserSuivi) VALUES (3, 1);

-- Pour la table SuivreTag
INSERT INTO SUIVRETAG (idUser, idTag) VALUES (1, 1);
INSERT INTO SUIVRETAG (idUser, idTag) VALUES (1, 8);
INSERT INTO SUIVRETAG (idUser, idTag) VALUES (1, 5);
INSERT INTO SUIVRETAG (idUser, idTag) VALUES (2, 6);
INSERT INTO SUIVRETAG (idUser, idTag) VALUES (3, 7);
INSERT INTO SUIVRETAG (idUser, idTag) VALUES (4, 4);
INSERT INTO SUIVRETAG (idUser, idTag) VALUES (2, 3);

-- Pour la table Like2Touite
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (1, 3, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (1, 4, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (1, 5, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (2, 1, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (4, 2, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (5, 3, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (5, 4, 1);






-- On ajoute aux autres membres du groupe les permissions d'accéder à ma base de données
GRANT ALL PRIVILEGES ON * TO 'pinot33u';
GRANT ALL PRIVILEGES ON * TO 'pierrot67u';
GRANT ALL PRIVILEGES ON * TO 'sassiweb2u';







-- On ajoute aux autres membres du groupe les permissions d'accéder à ma base de données
GRANT ALL PRIVILEGES ON * TO 'pinot33u';
GRANT ALL PRIVILEGES ON * TO 'pierrot67u';
GRANT ALL PRIVILEGES ON * TO 'sassiweb2u';


