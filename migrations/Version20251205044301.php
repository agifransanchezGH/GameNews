<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251205044301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, contenido VARCHAR(255) DEFAULT NULL, fecha_hora VARCHAR(30) NOT NULL, estado_moderacion VARCHAR(50) NOT NULL, votos_positivos INT NOT NULL, votos_negativos INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE estadistica (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(150) NOT NULL, fecha_desde DATETIME NOT NULL, fecha_hasta DATETIME NOT NULL, valor INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE noticia (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(100) NOT NULL, subtitulo VARCHAR(150) NOT NULL, cuerpo LONGTEXT NOT NULL, fecha_publicacion DATETIME NOT NULL, estado VARCHAR(100) NOT NULL, valoracion_promedio DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, correo VARCHAR(150) NOT NULL, nombre_usuario VARCHAR(100) NOT NULL, contraseña VARCHAR(150) NOT NULL, rol VARCHAR(100) NOT NULL, estado VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE estadistica');
        $this->addSql('DROP TABLE noticia');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
