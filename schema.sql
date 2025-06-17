DROP DATABASE IF EXISTS task_force;

CREATE DATABASE task_force
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE task_force;

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       user_name VARCHAR(128),
                       user_email VARCHAR(255) not null unique
);


CREATE TABLE tasks (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       task VARCHAR(128),
                       task_descr VARCHAR(255),
                       user_id INT,
                       FOREIGN KEY (user_id) REFERENCES  users(id)
)


-- CREATE TABLE categories (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     category_name VARCHAR(128),
--     category_code VARCHAR(10)
-- );

-- CREATE TABLE users (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
--     email VARCHAR(255) not null unique,
--     password INT,
--     name VARCHAR(15),
--     contacts VARCHAR(255)
-- );

-- CREATE TABLE lots (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     time_create DATETIME DEFAULT CURRENT_TIMESTAMP,
--     title VARCHAR(255),
--     description TEXT,
--     image VARCHAR(255),
--     cost INT,
--     time_expired DATE,
--     step INT,
--     category_id INT,
--     user_id INT,
--     winner_id INT,
--     FOREIGN KEY (user_id) REFERENCES  users(id),
--     FOREIGN KEY (winner_id) REFERENCES  users(id),
--     FOREIGN KEY (category_id) REFERENCES categories(id)
-- );

-- CREATE TABLE bets (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     date_bet DATETIME DEFAULT CURRENT_TIMESTAMP,
--     price_bet INT,
--     user_id INT,
--     lots_id INT,
--     FOREIGN KEY (user_id) REFERENCES  users(id),
--     FOREIGN KEY (lots_id) REFERENCES  lots(id)
-- );

-- CREATE INDEX user_email ON users(email);

CREATE TABLE contact (
                         id SERIAL PRIMARY KEY,  -- SERIAL = автоинкремент в PostgreSQL
                         name VARCHAR(255),
                         phone VARCHAR(20),
                         email VARCHAR(255),
                         position VARCHAR(100)
);