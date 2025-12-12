<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251212001800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario ADD estado TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE voto_noticia DROP FOREIGN KEY FK_2FBC8A9099926010');
        $this->addSql('ALTER TABLE voto_noticia CHANGE noticia_id noticia_id INT NOT NULL');
        $this->addSql('ALTER TABLE voto_noticia ADD CONSTRAINT FK_2FBC8A9099926010 FOREIGN KEY (noticia_id) REFERENCES noticia (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voto_noticia DROP FOREIGN KEY FK_2FBC8A9099926010');
        $this->addSql('ALTER TABLE voto_noticia CHANGE noticia_id noticia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voto_noticia ADD CONSTRAINT FK_2FBC8A9099926010 FOREIGN KEY (noticia_id) REFERENCES noticia (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE usuario DROP estado');
    }
}
