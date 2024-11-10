<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924215210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation ADD reservation_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN conversation.reservation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A8E26E9B83297E7 ON conversation (reservation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9B83297E7');
        $this->addSql('DROP INDEX UNIQ_8A8E26E9B83297E7');
        $this->addSql('ALTER TABLE conversation DROP reservation_id');
    }
}
