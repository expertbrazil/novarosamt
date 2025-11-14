-- Script para corrigir acesso ao MySQL
-- Execute: sudo mysql < fix-mysql-access.sql

-- Criar banco de dados se não existir
CREATE DATABASE IF NOT EXISTS novarosamt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Opção 1: Criar usuário específico para a aplicação (RECOMENDADO)
DROP USER IF EXISTS 'novarosamt'@'localhost';
CREATE USER 'novarosamt'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON novarosamt.* TO 'novarosamt'@'localhost';

-- Opção 2: Alterar root para usar senha (alternativa)
-- ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
-- GRANT ALL PRIVILEGES ON novarosamt.* TO 'root'@'localhost';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Verificar usuários criados
SELECT user, host, plugin FROM mysql.user WHERE user IN ('root', 'novarosamt');

