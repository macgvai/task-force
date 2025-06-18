-- Table structure for categories
DROP TABLE IF EXISTS categories CASCADE;
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table structure for cities
DROP TABLE IF EXISTS cities CASCADE;
CREATE TABLE cities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    UNIQUE (name)
);

-- Table structure for files
DROP TABLE IF EXISTS files CASCADE;
CREATE TABLE files (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (path),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table structure for opinions
DROP TABLE IF EXISTS opinions CASCADE;
CREATE TABLE opinions (
    id SERIAL PRIMARY KEY,
    owner_id INT NOT NULL,
    performer_id INT NOT NULL,
    rate SMALLINT NOT NULL,
    description TEXT NOT NULL,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id),
    FOREIGN KEY (performer_id) REFERENCES users(id)
);

-- Table structure for replies
DROP TABLE IF EXISTS replies CASCADE;
CREATE TABLE replies (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255) NOT NULL,
    task_id INT NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

-- Table structure for statuses
DROP TABLE IF EXISTS statuses CASCADE;
CREATE TABLE statuses (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    UNIQUE (name)
);

-- Table structure for tasks
DROP TABLE IF EXISTS tasks CASCADE;
CREATE TABLE tasks (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) DEFAULT NULL,
    budget INT DEFAULT NULL,
    expire_dt TIMESTAMP DEFAULT NULL,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    client_id INT NOT NULL,
    performer_id INT DEFAULT NULL,
    status_id INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (status_id) REFERENCES statuses(id)
);

-- Table structure for users
DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    city_id INT NOT NULL,
    password CHAR(64) NOT NULL,
    dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (email),
    FOREIGN KEY (city_id) REFERENCES cities(id)
);

-- Table structure for user_categories
DROP TABLE IF EXISTS user_categories CASCADE;
CREATE TABLE user_categories (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Table structure for user_settings
DROP TABLE IF EXISTS user_settings CASCADE;
CREATE TABLE user_settings (
    id SERIAL PRIMARY KEY,
    address VARCHAR(255) DEFAULT NULL,
    bd DATE DEFAULT NULL,
    avatar_path VARCHAR(255) DEFAULT NULL,
    about TEXT,
    phone CHAR(11) DEFAULT NULL,
    skype CHAR(32) DEFAULT NULL,
    messenger CHAR(32) DEFAULT NULL,
    notify_new_msg BOOLEAN DEFAULT FALSE,
    notify_new_action BOOLEAN DEFAULT FALSE,
    notify_new_reply BOOLEAN DEFAULT FALSE,
    opt_hide_contacts BOOLEAN DEFAULT FALSE,
    opt_hide_me BOOLEAN DEFAULT FALSE,
    is_performer BOOLEAN DEFAULT FALSE,
    user_id INT NOT NULL,
    UNIQUE (user_id),
    UNIQUE (phone, skype, messenger),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

