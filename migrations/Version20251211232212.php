<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251211232212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E70299926010');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70299926010 FOREIGN KEY (noticia_id) REFERENCES noticia (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE voto_comentario DROP FOREIGN KEY FK_4AEA759BF3F2D7EC');
        $this->addSql('ALTER TABLE voto_comentario ADD CONSTRAINT FK_4AEA759BF3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voto_comentario DROP FOREIGN KEY FK_4AEA759BF3F2D7EC');
        $this->addSql('ALTER TABLE voto_comentario ADD CONSTRAINT FK_4AEA759BF3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E70299926010');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70299926010 FOREIGN KEY (noticia_id) REFERENCES noticia (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
