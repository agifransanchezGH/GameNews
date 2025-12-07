<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251205161139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario ADD noticia_id INT NOT NULL');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70299926010 FOREIGN KEY (noticia_id) REFERENCES noticia (id)');
        $this->addSql('CREATE INDEX IDX_4B91E70299926010 ON comentario (noticia_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E70299926010');
        $this->addSql('DROP INDEX IDX_4B91E70299926010 ON comentario');
        $this->addSql('ALTER TABLE comentario DROP noticia_id');
    }
}
