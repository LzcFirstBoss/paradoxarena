create table usuario(
id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
nome_completo varchar(255) NOT NULL,
data_nascimento varchar(10),
genero ENUM('F', 'M') NOT NULL,
email varchar(255) NOT NULL,
email_verificado BOOLEAN DEFAULT 0,
nickname varchar(255) NOT NULL,
cpf varchar(11),
senha varchar(255),
tag varchar(255)
);

create table chaves_de_pagamento(
id  int NOT NULL PRIMARY KEY AUTO_INCREMENT,
chave varchar(100) NOT NULL,
metodo varchar(3) DEFAULT 'PIX' NOT NULL,
tipode_de_chave varchar(20) NOT NULL,
user_id int NOT NULL,
FOREIGN KEY (user_id) REFERENCES usuario(id)
);

CREATE TABLE usuario_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(255) NOT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  expira_em DATETIME,
  FOREIGN KEY (user_id) REFERENCES usuario(id) ON DELETE CASCADE
);
