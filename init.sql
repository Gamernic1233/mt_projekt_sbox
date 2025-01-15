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
    nazov_zariadenia VARCHAR(255) NOT NULL UNIQUE,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

INSERT INTO users (username, password, email) VALUES ('admin', 'admin', 'admin@admin.com');
INSERT INTO users (username, password, email) VALUES ('admin2', 'admin2', 'admin2@admin.com');
INSERT INTO zariadenia (author_id, nazov_zariadenia) VALUES (1, 'Zariadenie 1');
INSERT INTO zariadenia (author_id, nazov_zariadenia) VALUES (2, 'XYZ');
INSERT INTO merane_data (nazov_zariadenia, vlhkost_pody, tlak_vzduchu, teplota_vzduchu, vlhkost_vzduchu) VALUES ('Zariadenie 1', 50, 1000, 20, 50);
