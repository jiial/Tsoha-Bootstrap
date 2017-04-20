CREATE TABLE Kayttaja(
  id SERIAL PRIMARY KEY,
  nimi varchar(12) NOT NULL,
  password varchar(12) NOT NULL
);

CREATE TABLE Aselaji(
  id SERIAL PRIMARY KEY,
  nimi varchar(20)
);

CREATE TABLE Kilpailumuoto(
  id SERIAL PRIMARY KEY,
  nimi varchar(20)
);

CREATE TABLE KilpailumuodonLaji(
  aselaji INTEGER REFERENCES Aselaji(id),
  kilpailumuoto INTEGER REFERENCES Kilpailumuoto(id)
);

CREATE TABLE Tulos(
  id SERIAL PRIMARY KEY,
  arvo DECIMAL(4,1),
  kilpailu varchar(30),
  paivamaara DATE,
  lisatiedot varchar(500),
  napakympit INTEGER,
  kayttaja INTEGER REFERENCES Kayttaja(id),
  aselaji INTEGER REFERENCES Aselaji(id),
  kilpailumuoto INTEGER REFERENCES Kilpailumuoto(id)
);

CREATE TABLE Sarja(
  id SERIAL PRIMARY KEY,
  arvo DECIMAL(4,1),
  lisatiedot varchar(200),
  tulos INTEGER REFERENCES Tulos(id)
);