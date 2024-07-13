<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240713160958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD users_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN payment.users_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D67B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6D28840D67B3B43D ON payment (users_id)');
        $this->addSql('CREATE INDEX amount_idx ON payment (amount)');
        $this->addSql('CREATE INDEX commission_idx ON payment (commission)');
        $this->addSql('CREATE INDEX status_idx ON payment (status)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D67B3B43D');
        $this->addSql('DROP INDEX IDX_6D28840D67B3B43D');
        $this->addSql('DROP INDEX amount_idx');
        $this->addSql('DROP INDEX commission_idx');
        $this->addSql('DROP INDEX status_idx');
        $this->addSql('ALTER TABLE payment DROP users_id');
    }
}
