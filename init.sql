CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE merane_data (
    id SERIAL PRIMARY KEY,
    nazov_zariadenia VARCHAR(255) NOT NULL,
    vlhkost_pody FLOAT,
    tlak_vzduchu FLOAT,
    teplota_vzduchu FLOAT,
    vlhkost_vzduchu FLOAT,
    datum_cas TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE zariadenia (
    id SERIAL PRIMARY KEY,
    author_id INT,
    token VARCHAR(255) NOT NULL UNIQUE,
    nazov_zariadenia VARCHAR(255) NOT NULL UNIQUE,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

INSERT INTO users (username, password, email) VALUES ('admin', 'admin', 'admin@admin.com');
INSERT INTO users (username, password, email) VALUES ('admin2', 'admin2', 'admin2@admin.com');
INSERT INTO zariadenia (author_id, nazov_zariadenia) VALUES (1, '5d5f82b5-17d9-485c-bc93-623647ce9863', 'Zariadenie 1');
INSERT INTO zariadenia (author_id, nazov_zariadenia) VALUES (1, '9c1e51a6-7d53-4466-a61f-eac2723565ef', 'Zariadenie 2');
INSERT INTO zariadenia (author_id, nazov_zariadenia) VALUES (2, '0358f61e-20bd-4562-9a8c-4e3f6a789aba', 'XYZ');
INSERT INTO merane_data (nazov_zariadenia, vlhkost_pody, tlak_vzduchu, teplota_vzduchu, vlhkost_vzduchu) VALUES ('Zariadenie 1', 50, 1000, 20, 50);
INSERT INTO merane_data (nazov_zariadenia, vlhkost_pody, tlak_vzduchu, teplota_vzduchu, vlhkost_vzduchu) VALUES ('Zariadenie 2', 50, 1000, 20, 50);
INSERT INTO merane_data (nazov_zariadenia, vlhkost_pody, tlak_vzduchu, teplota_vzduchu, vlhkost_vzduchu) VALUES ('XYZ', 50, 1000, 20, 50);