<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722225510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE availability_time (id UUID NOT NULL, garage_availability_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_actif BOOLEAN NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DF8B64BCBB09B3AA ON availability_time (garage_availability_id)');
        $this->addSql('COMMENT ON COLUMN availability_time.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN availability_time.garage_availability_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN availability_time.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN availability_time.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN availability_time.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN availability_time.start_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN availability_time.end_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE availability_time ADD CONSTRAINT FK_DF8B64BCBB09B3AA FOREIGN KEY (garage_availability_id) REFERENCES garage_availability (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX end_time_idx');
        $this->addSql('DROP INDEX start_time_idx');
        $this->addSql('ALTER TABLE garage_availability DROP start_time');
        $this->addSql('ALTER TABLE garage_availability DROP end_time');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE availability_time DROP CONSTRAINT FK_DF8B64BCBB09B3AA');
        $this->addSql('DROP TABLE availability_time');
        $this->addSql('ALTER TABLE garage_availability ADD start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE garage_availability ADD end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN garage_availability.start_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN garage_availability.end_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX end_time_idx ON garage_availability (end_time)');
        $this->addSql('CREATE INDEX start_time_idx ON garage_availability (start_time)');
    }
}
