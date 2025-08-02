-- usuarios.sql
-- Script para crear la base de datos y la tabla de usuarios

CREATE DATABASE IF NOT EXISTS apidb;
USE apidb;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
);
