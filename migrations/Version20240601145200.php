<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601145200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX description_idx ON garage (description)');
        $this->addSql('CREATE INDEX price_per_day_idx ON garage (price_per_day)');
        $this->addSql('CREATE INDEX price_per_hour_idx ON garage (price_per_hour)');
        $this->addSql('CREATE INDEX start_at_idx ON garage_availability (start_at)');
        $this->addSql('CREATE INDEX end_at_idx ON garage_availability (end_at)');
        $this->addSql('CREATE INDEX start_time_idx ON garage_availability (start_time)');
        $this->addSql('CREATE INDEX end_time_idx ON garage_availability (end_time)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX description_idx');
        $this->addSql('DROP INDEX price_per_day_idx');
        $this->addSql('DROP INDEX price_per_hour_idx');
        $this->addSql('DROP INDEX start_at_idx');
        $this->addSql('DROP INDEX end_at_idx');
        $this->addSql('DROP INDEX start_time_idx');
        $this->addSql('DROP INDEX end_time_idx');
    }
}
