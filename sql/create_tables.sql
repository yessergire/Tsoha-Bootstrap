CREATE TABLE Asiakas (
  id SERIAL  PRIMARY KEY,
  sähköposti VARCHAR(60)  NOT NULL UNIQUE,  -- Sähköposti
  nimi       VARCHAR(60)  NOT NULL,         -- Nimi
  osoite     VARCHAR(120) NOT NULL,         -- Osoite
  puhelin    VARCHAR(120) NOT NULL,         -- Puhelin
  salasana   VARCHAR(30)  NOT NULL          -- Salasana
);

CREATE TABLE Meklari (
  id SERIAL  PRIMARY KEY,
  sähköposti VARCHAR(60)  NOT NULL UNIQUE,  -- Sähköposti
  nimi       VARCHAR(60)  NOT NULL,         -- Nimi
  salasana   VARCHAR(30)  NOT NULL          -- Salasana
);

CREATE TABLE Tuote (
  id SERIAL  PRIMARY KEY,
  nimi       VARCHAR(60)  NOT NULL,         -- Nimi
  kuvaus     VARCHAR(120) NOT NULL,         -- Kuvaus
  kuva       VARCHAR(2000),                 -- Kuvan url osoite
  hinta      FLOAT        NOT NULL          -- Hinta
);

CREATE TABLE TuoteLuokka (
  id SERIAL  PRIMARY KEY,
  nimi       VARCHAR(60)  NOT NULL,        -- Nimi
  kuvaus     VARCHAR(120) NOT NULL         -- Kuvaus
);

CREATE TABLE TuotteenLuokat (
  tuote_id INTEGER NOT NULL        -- Tuotetunnus
  REFERENCES Tuote (id),
  tuoteluokka_id INTEGER NOT NULL  -- TuoteLuokan tunnus
  REFERENCES TuoteLuokka (id)
);

CREATE TABLE Kauppa (
  id SERIAL  PRIMARY KEY,
  tuote_id INTEGER NOT NULL        -- Tuotetunnus
  REFERENCES Tuote (id),
  alkaa      TIMESTAMP NOT NULL,   -- milloin kauppa alkaa
  päättyy    TIMESTAMP NOT NULL    -- milloin kauppa alkaa
);

CREATE TABLE Tarjous (
  asiakas_id INTEGER NOT NULL       -- Asiakastunnus
  REFERENCES Asiakas (id),
  meklari_id INTEGER                -- Meklarin tunnus
  REFERENCES Meklari (id),
  kauppa_id INTEGER NOT NULL        -- Kaupan tunnus
  REFERENCES Kauppa (id),
  hinta      FLOAT NOT NULL,        -- Tarjottu hinta
  ajankohta  TIMESTAMP NOT NULL     -- Tarjouksen ajankohta
);
