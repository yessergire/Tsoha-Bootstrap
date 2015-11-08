INSERT INTO Meklari (sähköposti, nimi, salasana)
VALUES ('admin', 'administrator', 'salasana');

INSERT INTO Asiakas (nimi, sähköposti, osoite, puhelin, salasana)
VALUES ('Asiakas', 'asiakas', 'asiakkaan osoite', '020202', 'salasana');

INSERT INTO Tuote (nimi, kuvaus, hinta)
VALUES ('TV', '42 tuuman plasma tv', 299.99);

INSERT INTO TuoteLuokka(nimi, kuvaus)
VALUES ('elektroniikka', 'Kodin elektroniikka');

INSERT INTO TuotteenLuokat(tuote_id, tuoteluokka_id)
VALUES (1, 1);

INSERT INTO Kauppa (tuote_id, alkaa, päättyy)
VALUES (1, now(), now() + interval '1 month');


INSERT INTO Tarjous (asiakas_id, kauppa_id, hinta, ajankohta)
VALUES (1, 1, 350, now());
