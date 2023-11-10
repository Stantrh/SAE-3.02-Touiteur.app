DROP TABLE LIKE2TOUITE;
DROP TABLE SUIVRETAG;
DROP TABLE SUIVREUSER;
DROP TABLE TAG2TOUITE;
DROP TABLE TAG;
DROP TABLE TOUITE;
DROP TABLE IMAGE;
DROP TABLE UTILISATEUR;

-- Création des tables
CREATE TABLE UTILISATEUR (
                             idUser int(10) AUTO_INCREMENT NOT NULL,
                             nom varchar(100) NOT NULL,
                             prenom varchar(100) NOT NULL,
                             email varchar(100) NOT NULL UNIQUE,
                             nomUser varchar(20) NOT NULL UNIQUE,
                             mdp varchar(256) NOT NULL,
                             role int(3) NOT NULL,
                             PRIMARY KEY (idUser)
);

CREATE TABLE TOUITE(
                       idTouite INT(12) AUTO_INCREMENT NOT NULL,
                       idUser INT(10) NOT NULL,
                       date DATETIME NOT NULL,
                       texteTouite VARCHAR(256) NOT NULL,
                       idImage INT(10),
                       score INT(10) NOT NULL DEFAULT 0,
                       PRIMARY KEY (idTouite)
);

CREATE TABLE IMAGE(
                      idImage INT(10) AUTO_INCREMENT NOT NULL,
                      description VARCHAR(256) NOT NULL,
                      cheminFichier VARCHAR(256) NOT NULL,
                      PRIMARY KEY(idImage)
);

CREATE TABLE TAG(
                    idTag INT(10) AUTO_INCREMENT NOT NULL,
                    libelle VARCHAR(128) NOT NULL UNIQUE,
                    description varchar(256) NOT NULL,
                    PRIMARY KEY (idTag)
);

CREATE TABLE TAG2TOUITE(
                           idTouite INT(12) NOT NULL,
                           idTag INT(10) NOT NULL,
                           PRIMARY KEY (idTouite, idTag)
);

CREATE TABLE SUIVREUSER(
                           idUser INT(10) NOT NULL,
                           idUserSuivi INT(10) NOT NULL,
                           PRIMARY KEY (idUser, idUserSuivi)
);

CREATE TABLE SUIVRETAG(
                          idUser INT(10) NOT NULL,
                          idTag INT(10) NOT NULL,
                          PRIMARY KEY (idUser, IdTag)
);

CREATE TABLE LIKE2TOUITE(
                            idUser INT(10) NOT NULL,
                            idTouite INT(12) NOT NULL,
                            appreciation int(1) NOT NULL,
                            PRIMARY KEY (idUser, IdTouite)
);



-- Ajout des relations entre tables (clés étrangères)
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
INSERT INTO UTILISATEUR(nom, prenom, email, nomUser, mdp, role) VALUES ('HOWARD', 'Victoria', 'user3@mail.com', 'user3', '$2y$10$KlIiCDwpbk8QLAR4LfIaKOa1t3uMYlmYB/i9tPQGopGqgJgcDYyBa', 1);
INSERT INTO UTILISATEUR(nom, prenom, email, nomUser, mdp, role) VALUES ('TROHA', 'Stanislas', 'user4@mail.com', 'user100', '$2y$10$FiQ4SawRg9sdwdXyKhu3wuUYLu46EJxors7kHAbJKuBcDP1SjNrrO', 100);
INSERT INTO UTILISATEUR(nom, prenom, email, nomUser, mdp, role) VALUES ('CANALS', 'Gérome', 'user5@mail.com', 'user101', '$2y$10$rJzcrXUAXmgEdv6thS6pqeRu7P.Ka0bWKzhDq2lXqYoPZQfszWbvi', 100);


-- Pour la table Image
INSERT INTO IMAGE(description, cheminFichier) VALUES ('ekip ekip so le flem', '../ekip/667tah/669.jpeg');
INSERT INTO IMAGE(description, cheminFichier) VALUES ('ekip ekip so le flem', '../ekip/667tah/lyonzon.jpeg');
INSERT INTO IMAGE(description, cheminFichier) VALUES ('ekip ekip so le flem', '../ekip/667tah/667ekipekip.jpeg');

-- Pour la table Touite
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (1,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), 'J aime bien les chats, c est cool, surtout les chats français #chat #france' , NULL, -3);
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (1,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), 'il fait super beau aujourdhui ' , NULL, -1);
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (2,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), 'j ai fait un site en php pour voir des images de chat #php #chat' , NULL, 3);
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (3,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), 'nancy c est la plus belle ville du monde' , NULL, -3);
INSERT INTO TOUITE (idUser, date, texteTouite, idImage, score) VALUES (4,  STR_TO_DATE(NOW(), '%Y-%m-%d %H:%i:%s'), ' j ai bien mangé ce midi ' , NULL, 4);


-- Pour la table Tag
INSERT INTO TAG (libelle, description) VALUES ('#chat', 'animal mignon');
INSERT INTO TAG (libelle, description) VALUES ('#france', 'meilleur pays');
INSERT INTO TAG (libelle, description) VALUES ('#NancyCentreDuMonde', 'reel selon la communication');
INSERT INTO TAG (libelle, description) VALUES ('#php', 'langage adoré');
INSERT INTO TAG (libelle, description) VALUES ('#X','réseau social ça a changé');
INSERT INTO TAG (libelle, description) VALUES ('#Légende', 'Zinedine Zidane');
INSERT INTO TAG (libelle, description) VALUES ('#IUTCharlemagne', 'super IUT');
INSERT INTO TAG (libelle, description) VALUES ('#gâteaux','pâtisseries appréciées de tous');

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

/**
 delete from LIKE2TOUITE where idUser > 0;
 UPDATE TOUITE
    SET score = 0
    WHERE score <> 0;

 */

-- Pour la table Like2Touite
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (1, 1, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (2, 1, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (3, 1, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (4, 1, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (5, 1, -1); -- touite 1 score -3 FINAL


INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (1, 2, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (2, 2, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (3, 2, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (4, 2, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (5, 2, 1); -- touite 2 score -1 FINAL

INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (1, 3, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (2, 3, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (3, 3, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (4, 3, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (5, 3, -1); -- touite 3 score 3 FINAL

INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (1, 4, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (2, 4, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (3, 4, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (4, 4, -1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (5, 4, -1); -- touite 4 score -3 FINAL

INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (1, 5, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (2, 5, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (3, 5, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (4, 5, 1);
INSERT INTO LIKE2TOUITE (idUser, idTouite, appreciation) VALUES (5, 5, -1); -- touite 5 score 4 FINAL MAIS ON OBTIENT QUAND MEME 5

-- On ajoute aux autres membres du groupe les permissions d'accéder à ma base de données
GRANT ALL PRIVILEGES ON * TO 'pinot33u';
GRANT ALL PRIVILEGES ON * TO 'pierrot67u';

commit;