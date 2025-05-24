CREATE TABLE IF NOT EXISTS users (

    id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,

    username varchar(40) NOT NULL,
    password varchar(120) NOT NULL,

    token varchar(200) NOT NULL

)
ENGINE = InnoDB
COMMENT = 'Table "users" under "task_app" database'
;