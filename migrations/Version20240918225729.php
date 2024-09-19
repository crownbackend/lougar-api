<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918225729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wallet (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_actif BOOLEAN NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, balance NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN wallet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN wallet.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN wallet.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN wallet.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "user" ADD wallet_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN "user".wallet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649712520F3 ON "user" (wallet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649712520F3');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP INDEX UNIQ_8D93D649712520F3');
        $this->addSql('ALTER TABLE "user" DROP wallet_id');
    }
}
