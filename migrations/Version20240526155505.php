<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526155505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX name_idx ON city (name)');
        $this->addSql('CREATE INDEX postal_code_idx ON city (postal_code)');
        $this->addSql('CREATE INDEX slug_idx ON city (slug)');
        $this->addSql('ALTER TABLE garage ADD name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE INDEX address_idx ON garage (address)');
        $this->addSql('CREATE INDEX content_idx ON message (content)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX content_idx');
        $this->addSql('DROP INDEX address_idx');
        $this->addSql('ALTER TABLE garage DROP name');
        $this->addSql('DROP INDEX name_idx');
        $this->addSql('DROP INDEX postal_code_idx');
        $this->addSql('DROP INDEX slug_idx');
    }
}
