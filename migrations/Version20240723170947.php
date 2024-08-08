<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723170947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availability_time ADD day_of_week VARCHAR(30) DEFAULT NULL');
        $this->addSql('CREATE INDEX day_of_week_idx ON availability_time (day_of_week)');
        $this->addSql('ALTER TABLE garage_availability DROP day_of_week');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE garage_availability ADD day_of_week VARCHAR(30) DEFAULT NULL');
        $this->addSql('DROP INDEX day_of_week_idx');
        $this->addSql('ALTER TABLE availability_time DROP day_of_week');
    }
}
