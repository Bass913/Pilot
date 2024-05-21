<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514095348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD company_service_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE50D24070 FOREIGN KEY (company_service_id) REFERENCES company_services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E00CEDDE50D24070 ON booking (company_service_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON booking (client_id)');
        $this->addSql('ALTER TABLE img_company ADD title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE50D24070');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE19EB6921');
        $this->addSql('DROP INDEX IDX_E00CEDDE50D24070');
        $this->addSql('DROP INDEX IDX_E00CEDDE19EB6921');
        $this->addSql('ALTER TABLE booking DROP company_service_id');
        $this->addSql('ALTER TABLE booking DROP client_id');
        $this->addSql('ALTER TABLE img_company DROP title');
    }
}
