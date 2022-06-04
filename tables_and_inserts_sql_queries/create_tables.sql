
-- create tables

CREATE TABLE Cliente (
    id_cliente int,
    nome_cliente char(50),
    morada char(100),
    PRIMARY KEY(id_cliente)
    );

CREATE TABLE Alergia (
    id_alergia int,
    nome_alergia char(50),
    PRIMARY KEY(id_alergia)
    );

CREATE TABLE ClienteAlergia (
    id_cliente int NOT NULL,
    id_alergia int NOT NULL,
    grau_intensidade int,
    PRIMARY KEY(id_cliente, id_alergia),
    FOREIGN KEY(id_cliente) REFERENCES Cliente(id_cliente),
    FOREIGN KEY(id_alergia) REFERENCES Alergia(id_alergia)
    );

CREATE TABLE Medicamento (
    id_medicamento int,
    nome_medicamento char(50),
    PRIMARY KEY(id_medicamento)
    );

CREATE TABLE ClienteMedicamento (
    id_cliente int NOT NULL,
    id_medicamento int NOT NULL,
    frequencia_dosagem char(50),
    PRIMARY KEY(id_cliente, id_medicamento),
    FOREIGN KEY(id_cliente) REFERENCES Cliente(id_cliente),
    FOREIGN KEY(id_medicamento) REFERENCES Medicamento(id_medicamento)
    );

CREATE TABLE Empregado (
    id_empregado int,
    nome_empregado char(50),
    titulo char(50),
    area_especializacao char(50),
    PRIMARY KEY(id_empregado)
    );

-- weak entity
CREATE TABLE Horario (
    id_empregado int NOT NULL,
    dia_semana char(10),
    hora_entrada time,
    hora_saida time,
    PRIMARY KEY(id_empregado, dia_semana),
    FOREIGN KEY(id_empregado) REFERENCES Empregado(id_empregado)
    );

CREATE TABLE Consulta (
    id_consulta int,
    id_cliente int NOT NULL,
    razao_consulta char(50),
    conclusao_medica char(50),
    PRIMARY KEY(id_consulta),
    FOREIGN KEY(id_cliente) REFERENCES Cliente(id_cliente)
    );

CREATE TABLE ConsultaEmpregado (
    id_consulta int NOT NULL,
    id_empregado int NOT NULL,
    titulo char(50),
    PRIMARY KEY(id_consulta, id_empregado),
    FOREIGN KEY(id_consulta) REFERENCES Consulta(id_consulta),
    FOREIGN KEY(id_empregado) REFERENCES Empregado(id_empregado)
    );


-- This table is for the created users (staff & admins)

CREATE TABLE Users (
    username char(50),
    password char(50),
    type char(10),
    PRIMARY KEY(username)
    );
