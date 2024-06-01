<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601224050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversation_user (conversation_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(conversation_id, user_id))');
        $this->addSql('CREATE INDEX IDX_5AECB5559AC0396 ON conversation_user (conversation_id)');
        $this->addSql('CREATE INDEX IDX_5AECB555A76ED395 ON conversation_user (user_id)');
        $this->addSql('COMMENT ON COLUMN conversation_user.conversation_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN conversation_user.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT FK_5AECB5559AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT FK_5AECB555A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT fk_8a8e26e956ae248b');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT fk_8a8e26e9441b8b65');
        $this->addSql('DROP INDEX idx_8a8e26e9441b8b65');
        $this->addSql('DROP INDEX idx_8a8e26e956ae248b');
        $this->addSql('ALTER TABLE conversation DROP user1_id');
        $this->addSql('ALTER TABLE conversation DROP user2_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conversation_user DROP CONSTRAINT FK_5AECB5559AC0396');
        $this->addSql('ALTER TABLE conversation_user DROP CONSTRAINT FK_5AECB555A76ED395');
        $this->addSql('DROP TABLE conversation_user');
        $this->addSql('ALTER TABLE conversation ADD user1_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation ADD user2_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN conversation.user1_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN conversation.user2_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT fk_8a8e26e956ae248b FOREIGN KEY (user1_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT fk_8a8e26e9441b8b65 FOREIGN KEY (user2_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8a8e26e9441b8b65 ON conversation (user2_id)');
        $this->addSql('CREATE INDEX idx_8a8e26e956ae248b ON conversation (user1_id)');
    }
}
