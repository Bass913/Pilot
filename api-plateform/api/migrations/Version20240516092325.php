<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516092325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE opening_hours_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE schedule (id INT NOT NULL, company_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, day_of_week VARCHAR(9) NOT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A3811FB979B1AD6 ON schedule (company_id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB9D86650F ON schedule (user_id_id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE opening_hours');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT fk_f0016d19d86650f');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT fk_f0016d138b53c32');
        $this->addSql('DROP INDEX idx_f0016d138b53c32');
        $this->addSql('DROP INDEX idx_f0016d19d86650f');
        $this->addSql('ALTER TABLE unavailability ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unavailability ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unavailability DROP user_id_id');
        $this->addSql('ALTER TABLE unavailability DROP company_id_id');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F0016D1A76ED395 ON unavailability (user_id)');
        $this->addSql('CREATE INDEX IDX_F0016D1979B1AD6 ON unavailability (company_id)');
        $this->addSql('ALTER TABLE "user" ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON "user" (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE schedule_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE opening_hours_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE opening_hours (id INT NOT NULL, monday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, monday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, tuesday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, tuesday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, wednesday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, wednesday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, thursday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, thursday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, friday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, friday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, saturday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, saturday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, sunday_opening_hour TIME(0) WITHOUT TIME ZONE NOT NULL, sunday_closing_hour TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB979B1AD6');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB9D86650F');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649979B1AD6');
        $this->addSql('DROP INDEX IDX_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE "user" DROP company_id');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D1A76ED395');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D1979B1AD6');
        $this->addSql('DROP INDEX IDX_F0016D1A76ED395');
        $this->addSql('DROP INDEX IDX_F0016D1979B1AD6');
        $this->addSql('ALTER TABLE unavailability ADD user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unavailability ADD company_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unavailability DROP user_id');
        $this->addSql('ALTER TABLE unavailability DROP company_id');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT fk_f0016d19d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT fk_f0016d138b53c32 FOREIGN KEY (company_id_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f0016d138b53c32 ON unavailability (company_id_id)');
        $this->addSql('CREATE INDEX idx_f0016d19d86650f ON unavailability (user_id_id)');
    }
}
