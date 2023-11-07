CREATE TABLE UTILISATEUR(
                            idUser int(10),
                            nomUser varchar(30),
                            mdp varchar(256),
                            role int(3),
                            PRIMARY KEY (idUser)
);

CREATE TABLE TOUITE(
                       idTouite INT(12),
                       idUser INT(10),
                       date DATE,
                       texteTouite VARCHAR(236),
                       idImage INT(10),
                       score INT(10),
                       PRIMARY KEY (idTouite)
);

CREATE TABLE IMAGE(
                      idImage INT(10),
                      description VARCHAR(150),
                      cheminFichier VARCHAR(200),
                      PRIMARY KEY(idImage)
);

CREATE TABLE TAG(
                    idTag INT(10),
                    nomTag VARCHAR(235),
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
                           PRIMARY KEY (idUser,idUserSuivi)
);



CREATE TABLE SUIVRETAG(
                          idUser INT(10),
                          idTag INT(10),
                          PRIMARY KEY (idUser, idTag)
);

CREATE TABLE LIKE2TOUITE(
                            idUser INT(10),
                            idTouite INT(12),
                            appreciation int(1),	-- vaut 1 ou -1
                            PRIMARY KEY (idUser, IdTouite)
);

ALTER TABLE TOUITE ADD
    FOREIGN KEY (idUser) REFERENCES UTILISATEUR(idUser);
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



-- On ajoute aux autres membres du groupe les permissions d'accéder à ma base de données
GRANT ALL PRIVILEGES ON * TO 'pinot33u';
GRANT ALL PRIVILEGES ON * TO 'pierrot67u';
GRANT ALL PRIVILEGES ON * TO 'sassiweb2u';


