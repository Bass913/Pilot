<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513121950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE opening_hours_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE unavailability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE opening_hours (id INT NOT NULL, monday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, monday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, tuesday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, tuesday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, wednesday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, wednesday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, thursday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, thursday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, friday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, friday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, saturday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, saturday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, sunday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, sunday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE unavailability (id INT NOT NULL, user_id_id INT DEFAULT NULL, company_id_id INT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0016D19D86650F ON unavailability (user_id_id)');
        $this->addSql('CREATE INDEX IDX_F0016D138B53C32 ON unavailability (company_id_id)');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D19D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D138B53C32 FOREIGN KEY (company_id_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE opening_hours_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE unavailability_id_seq CASCADE');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D19D86650F');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D138B53C32');
        $this->addSql('DROP TABLE opening_hours');
        $this->addSql('DROP TABLE unavailability');
    }
}
