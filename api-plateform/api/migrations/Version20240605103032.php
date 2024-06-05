<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605103032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4FBF094FA76ED395 ON company (user_id)');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT fk_5a3811fb9d86650f');
        $this->addSql('DROP INDEX idx_5a3811fb9d86650f');
        $this->addSql('ALTER TABLE schedule RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A3811FBA76ED395 ON schedule (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094FA76ED395');
        $this->addSql('DROP INDEX IDX_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE company DROP user_id');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FBA76ED395');
        $this->addSql('DROP INDEX IDX_5A3811FBA76ED395');
        $this->addSql('ALTER TABLE schedule RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT fk_5a3811fb9d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5a3811fb9d86650f ON schedule (user_id_id)');
    }
}
