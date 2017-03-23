INSERT INTO Kayttaja (nimi, password) VALUES ('Esa', '1234');
INSERT INTO Kayttaja (nimi, password) VALUES ('Keijo', '0000');

INSERT INTO Aselaji (nimi) VALUES ('Ilmakivääri');
INSERT INTO Aselaji (nimi) VALUES ('Pienoiskivääri');

INSERT INTO Kilpailumuoto (nimi) VALUES ('60 ls');
INSERT INTO Kilpailumuoto (nimi) VALUES ('3x20 ls');

INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (1, 1);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (2, 2);

INSERT INTO Tulos (arvo, kilpailu, paivamaara, lisatiedot, napakympit, kayttaja, aselaji, kilpailumuoto) VALUES (615.4, 'Kupittaan kisa', NOW(), 'Meni ihan ok', 38, 1, 1, 1);
INSERT INTO Tulos (arvo, kilpailu, paivamaara, lisatiedot, napakympit, kayttaja, aselaji, kilpailumuoto) VALUES (568, 'Matin kisat', NOW(), 'Meni semisti', 35, 2, 2, 2);

INSERT INTO Sarja (arvo, tulos) VALUES (102.5, 0, 1);
INSERT INTO Sarja (arvo, tulos) VALUES (103.2, 0, 1);
INSERT INTO Sarja (arvo, tulos) VALUES (101.7, 0, 1);
INSERT INTO Sarja (arvo, tulos) VALUES (102.0, 0, 1);
INSERT INTO Sarja (arvo, tulos) VALUES (103.1, 0, 1);
INSERT INTO Sarja (arvo, tulos) VALUES (102.9, 0, 1);