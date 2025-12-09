<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251209015031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE denuncia_comentario (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(50) DEFAULT NULL, descripcion LONGTEXT NOT NULL, fecha DATETIME NOT NULL, estado VARCHAR(30) NOT NULL, denunciante_id INT NOT NULL, comentario_id INT DEFAULT NULL, INDEX IDX_EA1E97B2FAF1174A (denunciante_id), INDEX IDX_EA1E97B2F3F2D7EC (comentario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE voto_noticia (id INT AUTO_INCREMENT NOT NULL, puntuacion_total INT NOT NULL, fecha DATETIME NOT NULL, usuario_id INT DEFAULT NULL, noticia_id INT DEFAULT NULL, INDEX IDX_2FBC8A90DB38439E (usuario_id), INDEX IDX_2FBC8A9099926010 (noticia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE denuncia_comentario ADD CONSTRAINT FK_EA1E97B2FAF1174A FOREIGN KEY (denunciante_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE denuncia_comentario ADD CONSTRAINT FK_EA1E97B2F3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id)');
        $this->addSql('ALTER TABLE voto_noticia ADD CONSTRAINT FK_2FBC8A90DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE voto_noticia ADD CONSTRAINT FK_2FBC8A9099926010 FOREIGN KEY (noticia_id) REFERENCES noticia (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE denuncia_comentario DROP FOREIGN KEY FK_EA1E97B2FAF1174A');
        $this->addSql('ALTER TABLE denuncia_comentario DROP FOREIGN KEY FK_EA1E97B2F3F2D7EC');
        $this->addSql('ALTER TABLE voto_noticia DROP FOREIGN KEY FK_2FBC8A90DB38439E');
        $this->addSql('ALTER TABLE voto_noticia DROP FOREIGN KEY FK_2FBC8A9099926010');
        $this->addSql('DROP TABLE denuncia_comentario');
        $this->addSql('DROP TABLE voto_noticia');
    }
}
