INSERT INTO Kayttaja (nimi, password) VALUES ('Esa', '1234');
INSERT INTO Kayttaja (nimi, password) VALUES ('Keijo', '0000');

INSERT INTO Aselaji (nimi) VALUES ('Ilmakivääri');
INSERT INTO Aselaji (nimi) VALUES ('Pienoiskivääri');
INSERT INTO Aselaji (nimi) VALUES ('300m Vakiokivääri');
INSERT INTO Aselaji (nimi) VALUES ('300m Kivääri');

INSERT INTO Kilpailumuoto (nimi) VALUES ('60 ls');
INSERT INTO Kilpailumuoto (nimi) VALUES ('3x20 ls');
INSERT INTO Kilpailumuoto (nimi) VALUES ('3x40 ls');
INSERT INTO Kilpailumuoto (nimi) VALUES ('40 ls');
INSERT INTO Kilpailumuoto (nimi) VALUES ('Makuu 60 ls');

INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (1, 1);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (1, 2);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (1, 4);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (1, 5);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (2, 2);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (2, 3);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (2, 5);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (3, 2);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (4, 2);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (4, 3);
INSERT INTO KilpailumuodonLaji (aselaji, kilpailumuoto) VALUES (4, 5);

INSERT INTO Tulos (arvo, kilpailu, paivamaara, lisatiedot, napakympit, kayttaja, aselaji, kilpailumuoto) VALUES (615.4, 'Kupittaan kisa', NOW(), 'Meni ihan ok', 38, 1, 1, 1);
INSERT INTO Tulos (arvo, kilpailu, paivamaara, lisatiedot, napakympit, kayttaja, aselaji, kilpailumuoto) VALUES (568, 'Matin kisat', NOW(), 'Meni semisti', 35, 2, 2, 2);

INSERT INTO Sarja (arvo, lisatiedot, tulos) VALUES (102.5, 'ok', 1);
INSERT INTO Sarja (arvo, lisatiedot, tulos) VALUES (103.2, 'hyvä', 1);
INSERT INTO Sarja (arvo, lisatiedot, tulos) VALUES (101.7, 'ok', 1);
INSERT INTO Sarja (arvo, lisatiedot, tulos) VALUES (102.0, 'ok', 1);
INSERT INTO Sarja (arvo, lisatiedot, tulos) VALUES (103.1, 'ok', 1);
INSERT INTO Sarja (arvo, lisatiedot, tulos) VALUES (102.9, 'ok', 1);