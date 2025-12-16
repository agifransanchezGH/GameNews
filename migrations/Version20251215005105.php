<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251215005105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, contenido VARCHAR(255) DEFAULT NULL, fecha_hora DATETIME NOT NULL, estado_moderacion VARCHAR(50) NOT NULL, noticia_id INT NOT NULL, usuario_id INT DEFAULT NULL, INDEX IDX_4B91E70299926010 (noticia_id), INDEX IDX_4B91E702DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE denuncia_comentario (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(50) DEFAULT NULL, descripcion LONGTEXT NOT NULL, fecha DATETIME NOT NULL, estado VARCHAR(30) NOT NULL, denunciante_id INT NOT NULL, comentario_id INT DEFAULT NULL, INDEX IDX_EA1E97B2FAF1174A (denunciante_id), INDEX IDX_EA1E97B2F3F2D7EC (comentario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE estadistica (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(150) NOT NULL, fecha_desde DATETIME NOT NULL, fecha_hasta DATETIME NOT NULL, valor INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE noticia (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(100) NOT NULL, subtitulo VARCHAR(150) NOT NULL, cuerpo LONGTEXT NOT NULL, fecha_publicacion DATETIME NOT NULL, estado VARCHAR(100) NOT NULL, valoracion_promedio DOUBLE PRECISION DEFAULT NULL, imagen VARCHAR(255) DEFAULT NULL, categoria_id INT DEFAULT NULL, INDEX IDX_31205F963397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, correo VARCHAR(150) NOT NULL, nombre_usuario VARCHAR(100) NOT NULL, contraseña VARCHAR(150) NOT NULL, rol VARCHAR(100) NOT NULL, estado TINYINT(1) DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE voto_comentario (id INT AUTO_INCREMENT NOT NULL, valor TINYINT(1) DEFAULT NULL, usuario_id INT NOT NULL, comentario_id INT NOT NULL, INDEX IDX_4AEA759BDB38439E (usuario_id), INDEX IDX_4AEA759BF3F2D7EC (comentario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE voto_noticia (id INT AUTO_INCREMENT NOT NULL, puntuacion INT NOT NULL, fecha DATETIME NOT NULL, usuario_id INT DEFAULT NULL, noticia_id INT NOT NULL, INDEX IDX_2FBC8A90DB38439E (usuario_id), INDEX IDX_2FBC8A9099926010 (noticia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70299926010 FOREIGN KEY (noticia_id) REFERENCES noticia (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE denuncia_comentario ADD CONSTRAINT FK_EA1E97B2FAF1174A FOREIGN KEY (denunciante_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE denuncia_comentario ADD CONSTRAINT FK_EA1E97B2F3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id)');
        $this->addSql('ALTER TABLE noticia ADD CONSTRAINT FK_31205F963397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE voto_comentario ADD CONSTRAINT FK_4AEA759BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE voto_comentario ADD CONSTRAINT FK_4AEA759BF3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE voto_noticia ADD CONSTRAINT FK_2FBC8A90DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE voto_noticia ADD CONSTRAINT FK_2FBC8A9099926010 FOREIGN KEY (noticia_id) REFERENCES noticia (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E70299926010');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE denuncia_comentario DROP FOREIGN KEY FK_EA1E97B2FAF1174A');
        $this->addSql('ALTER TABLE denuncia_comentario DROP FOREIGN KEY FK_EA1E97B2F3F2D7EC');
        $this->addSql('ALTER TABLE noticia DROP FOREIGN KEY FK_31205F963397707A');
        $this->addSql('ALTER TABLE voto_comentario DROP FOREIGN KEY FK_4AEA759BDB38439E');
        $this->addSql('ALTER TABLE voto_comentario DROP FOREIGN KEY FK_4AEA759BF3F2D7EC');
        $this->addSql('ALTER TABLE voto_noticia DROP FOREIGN KEY FK_2FBC8A90DB38439E');
        $this->addSql('ALTER TABLE voto_noticia DROP FOREIGN KEY FK_2FBC8A9099926010');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE denuncia_comentario');
        $this->addSql('DROP TABLE estadistica');
        $this->addSql('DROP TABLE noticia');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE voto_comentario');
        $this->addSql('DROP TABLE voto_noticia');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
