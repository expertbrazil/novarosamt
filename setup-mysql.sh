#!/bin/bash
# Script para instalar e configurar MySQL

echo "Instalando MySQL Server..."
sudo apt update
sudo apt install -y mysql-server mysql-client

echo "Iniciando MySQL..."
sudo systemctl start mysql
sudo systemctl enable mysql

echo "Criando banco de dados e usuário..."
sudo mysql <<EOF
CREATE DATABASE IF NOT EXISTS novarosamt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON novarosamt.* TO 'root'@'localhost';
FLUSH PRIVILEGES;
EOF

echo "MySQL configurado com sucesso!"
echo "Banco de dados: novarosamt"
echo "Usuário: root"
echo "Senha: root"

