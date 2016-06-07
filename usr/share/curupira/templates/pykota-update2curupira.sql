--
-- Conjunto de altera�es no banco de dados do pykota para o CURUPIRA,
-- Sistema de Gerenciamento de Impressao da CEF - Caixa Econ�ica Federal
	--
-- $Id: pykota-postgresql.sql 1764 2005-09-17 21:43:47Z jerome $
--
--

-- Infelizmente existem modificacoes "tristes" feitas nesse banco.
-- confesso que sempre fui contra algumas, mas por capricho de funcionarios
-- publicos sempre fui voto vencido... que Deus vos abencoe.

--
-- PyKota Database creation script for PostgreSQL
--
-- Launch this as PostgreSQL administrator with \i
--

-- 
-- Conectando com o Banco (PostgreSQL)
-- 
\connect pykota

--
-- Criando tabela ilhas
--
CREATE TABLE ilhas
(
	codilha SERIAL,
	nomeilha TEXT,
	CONSTRAINT "ilhas_pkey" PRIMARY KEY(codilha)
) WITH OIDS;

-- Alterando sequencia da tabela ilhas
ALTER SEQUENCE "ilhas_codilha_seq"
	INCREMENT 1 MINVALUE 1
	MAXVALUE 9223372036854775807 START 1
	CACHE 1;

INSERT INTO ilhas(codilha,nomeilha) VALUES(0,'Ilha Inicial');

--
-- Criando tabela tb_link
--
CREATE TABLE tb_link
(
	id SERIAL,
	ds_link CHARACTER VARYING(30),
	co_filial CHARACTER(32),
	end_predio CHARACTER VARYING(300),
	no_servidor CHARACTER(256),
	ip_servidor CHARACTER VARYING(16),
	link CHARACTER VARYING(50)
) WITH OIDS;

-- Alterando sequencia da tabela tb_link
ALTER SEQUENCE "tb_link_id_seq"
	INCREMENT 1 MINVALUE 1
	MAXVALUE 9223372036854775807 START 1
	CACHE 1;

--
-- Criando tabela unidades
--
CREATE TABLE unidades
(
	codunidade INTEGER NOT NULL,
        nomeunidade TEXT UNIQUE NOT NULL,
	endereco TEXT,
	codilha INTEGER,
	CONSTRAINT "unidades_nomeunidade_key" UNIQUE(nomeunidade),
	CONSTRAINT "unidades_pkey" PRIMARY KEY(codunidade),
	FOREIGN KEY (codilha) REFERENCES ilhas(codilha)
	ON DELETE NO ACTION
	ON UPDATE NO ACTION
	NOT DEFERRABLE
) WITH OIDS;

--
-- Atualizando tabela users
--
ALTER TABLE users ADD COLUMN codunidade INTEGER;
ALTER TABLE users ADD COLUMN nome TEXT;
ALTER TABLE users ADD FOREIGN KEY (codunidade) REFERENCES unidades(codunidade);

--
-- Atualizando tabela printers
--
ALTER TABLE printers ADD COLUMN codunidade INTEGER;
ALTER TABLE printers ADD COLUMN recurso INTEGER;
ALTER TABLE printers ADD COLUMN cor INTEGER;
ALTER TABLE printers ADD COLUMN nserie TEXT;
ALTER TABLE printers ADD COLUMN ppm INTEGER;
ALTER TABLE printers ADD COLUMN nomeservidor TEXT;
ALTER TABLE printers ADD COLUMN localizacao TEXT;
ALTER TABLE printers ADD FOREIGN KEY (codunidade) REFERENCES unidades(codunidade);

--
-- Atualizando tabela userpquota
--
ALTER TABLE userpquota ADD COLUMN temporarydenied BOOLEAN;
ALTER TABLE userpquota ALTER COLUMN temporarydenied SET DEFAULT false;

--
-- Atualizando (correcao) tabela printergroupsmembers
--
ALTER TABLE printergroupsmembers DROP COLUMN groupid;
ALTER TABLE printergroupsmembers ADD COLUMN groupid INT4 REFERENCES groups(id);

--
-- Set some ACLs -> Permissoes
--
REVOKE ALL ON unidades FROM public;
REVOKE ALL ON ilhas FROM public;
REVOKE ALL ON tb_link FROM public;

GRANT SELECT, INSERT, UPDATE, DELETE, REFERENCES ON  unidades, ilhas, tb_link, ilhas_codilha_seq, tb_link_id_seq TO pykotaadmin;
GRANT SELECT ON unidades, ilhas TO pykotaadmin;

GRANT SELECT ON users, groups, printers, userpquota, grouppquota, groupsmembers, printergroupsmembers, jobhistory, payments, coefficients, billingcodes TO pykotaadmin;

--
-- Inse�es Iniciais necessarias para o funcionamento do Sistema
--


-- Grupos de Usuarios
-- IDs atualmente em uso: 1, 2, 3, 4, 6, 100

INSERT INTO groups (id,groupname,description,limitby) values (1,'Nivel2', 'Administradores do sistema, permite alterar perfil do usuario e visualizar as impressoes da unidade','quota');
INSERT INTO groups (id,groupname,description,limitby) values (2,'Nivel4','Acesso ilimitado','quota');
INSERT INTO groups (id,groupname,description,limitby) values (3,'Nivel3','Visualiza relatorios de todas as unidades, e nao gerencia cadastro.','quota');
INSERT INTO groups (id,groupname,description,limitby) values (4,'NivelE','Administradores da unidade. Permite gerenciar usuarios e visualizar impressoes especificas de uma unidade.','quota');
INSERT INTO groups (id,groupname,description,limitby) values (6,'Nivel1','Usuarios comuns do dominio (na primeira impressao do usuario lhe e atribuido esse grupo)','quota');
INSERT INTO groups (id,groupname,description,limitby) values (100,'Impressoras Comuns','Impressoras destinadas a todos os usuarios','quota');


-- Se ja existir os bancos acima, apenas dar um update
UPDATE groups SET groupname='Nivel2', description='Administradores do sistema. Permite alterar perfil do usuario e visualizar as impressoes da unidade.' WHERE id=1;
UPDATE groups SET groupname='Nivel4', description='Super usuario. Possui acesso ilimitado.' WHERE id=2;
UPDATE groups SET groupname='Nivel3', description='Visualiza relatorios de todas as unidades, e nao gerencia cadastro' WHERE id=3;
UPDATE groups SET groupname='Nivel1', description='Usuarios comuns do dominio (grupo padrao atribuido na primeira impressao do usuario )' WHERE id=6;
UPDATE groups SET groupname='Impressoras Comuns', description='Impressoras destinadas a todos os usuarios' WHERE id=100;


-- Problemas
UPDATE printergroupsmembers SET groupid=100;


-- Cria Unidade Inicial
INSERT INTO unidades (codunidade,nomeunidade,endereco) values ('0','Sem unidade','Unidade Inicial');

--
-- Cria as funcoes
--
CREATE FUNCTION getgrupo (text) RETURNS INTEGER
AS 'SELECT groupid FROM groupsmembers 
INNER JOIN users ON groupsmembers.userid=users.id
WHERE upper(username)=upper($1) ORDER BY groupid;'
LANGUAGE SQL
STABLE
RETURNS NULL ON NULL INPUT
SECURITY INVOKER;

CREATE VIEW qryrelUnidades AS SELECT jobhistory.userid, jobhistory.printerid, count(DISTINCT jobhistory.userid) AS numerousuarios, sum(jobhistory.jobsize) AS numeropaginas, sum(jobhistory.jobprice) AS custo FROM jobhistory GROUP BY jobhistory.printerid, jobhistory.userid;

GRANT SELECT, INSERT, UPDATE, DELETE ON qryrelUnidades TO pykotaadmin;
