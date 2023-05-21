CREATE TABLE IF NOT EXISTS `users` (
    id INTEGER PRIMARY KEY,
    login TEXT,
    password TEXT
);

CREATE TABLE IF NOT EXISTS `feedbacks` (
    id INTEGER PRIMARY KEY,
    name TEXT,
    phone TEXT,
    question TEXT
);

CREATE TABLE IF NOT EXISTS `orders` (
    id INTEGER PRIMARY KEY,
    name TEXT,
    phone TEXT,
    service TEXT
);