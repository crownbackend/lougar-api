<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240822231112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD tenant_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN reservation.tenant_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559033212A FOREIGN KEY (tenant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_42C849559033212A ON reservation (tenant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C849559033212A');
        $this->addSql('DROP INDEX IDX_42C849559033212A');
        $this->addSql('ALTER TABLE reservation DROP tenant_id');
    }
}
