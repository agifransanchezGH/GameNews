<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251207211136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE voto_comentario (id INT AUTO_INCREMENT NOT NULL, valor TINYINT(1) DEFAULT NULL, usuario_id INT DEFAULT NULL, comentario_id INT DEFAULT NULL, INDEX IDX_4AEA759BDB38439E (usuario_id), INDEX IDX_4AEA759BF3F2D7EC (comentario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE voto_comentario ADD CONSTRAINT FK_4AEA759BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE voto_comentario ADD CONSTRAINT FK_4AEA759BF3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voto_comentario DROP FOREIGN KEY FK_4AEA759BDB38439E');
        $this->addSql('ALTER TABLE voto_comentario DROP FOREIGN KEY FK_4AEA759BF3F2D7EC');
        $this->addSql('DROP TABLE voto_comentario');
    }
}
