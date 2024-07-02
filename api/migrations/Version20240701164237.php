<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701164237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE statistics_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (id UUID NOT NULL, company_service_id UUID DEFAULT NULL, client_id UUID DEFAULT NULL, employee_id UUID DEFAULT NULL, company_id UUID NOT NULL, start_date VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDE50D24070 ON booking (company_service_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON booking (client_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE8C03F15C ON booking (employee_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE979B1AD6 ON booking (company_id)');
        $this->addSql('COMMENT ON COLUMN booking.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN booking.company_service_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN booking.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN booking.employee_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN booking.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE category_review (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN category_review.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE company (id UUID NOT NULL, speciality_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, zipcode VARCHAR(10) NOT NULL, city VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, review_rating DOUBLE PRECISION DEFAULT NULL, review_count INT DEFAULT NULL, images TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FBF094F3B5A08D7 ON company (speciality_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FA76ED395 ON company (user_id)');
        $this->addSql('COMMENT ON COLUMN company.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN company.speciality_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN company.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN company.images IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE company_service (id UUID NOT NULL, company_id UUID DEFAULT NULL, service_id UUID NOT NULL, price NUMERIC(10, 2) NOT NULL, duration INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C1CF8005979B1AD6 ON company_service (company_id)');
        $this->addSql('CREATE INDEX IDX_C1CF8005ED5CA9E6 ON company_service (service_id)');
        $this->addSql('COMMENT ON COLUMN company_service.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN company_service.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN company_service.service_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE rating (id UUID NOT NULL, review_id UUID NOT NULL, category_id UUID NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D88926223E2E969B ON rating (review_id)');
        $this->addSql('CREATE INDEX IDX_D889262212469DE2 ON rating (category_id)');
        $this->addSql('COMMENT ON COLUMN rating.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rating.review_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rating.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(255) NOT NULL, kbis VARCHAR(255) DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_validated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN request.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE review (id UUID NOT NULL, company_id UUID DEFAULT NULL, client_id UUID DEFAULT NULL, date VARCHAR(255) NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6979B1AD6 ON review (company_id)');
        $this->addSql('CREATE INDEX IDX_794381C619EB6921 ON review (client_id)');
        $this->addSql('COMMENT ON COLUMN review.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN review.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN review.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE schedule (id UUID NOT NULL, company_id UUID DEFAULT NULL, user_id UUID DEFAULT NULL, day_of_week VARCHAR(9) NOT NULL, start_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL, end_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A3811FB979B1AD6 ON schedule (company_id)');
        $this->addSql('CREATE INDEX IDX_5A3811FBA76ED395 ON schedule (user_id)');
        $this->addSql('COMMENT ON COLUMN schedule.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN schedule.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN schedule.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE service (id UUID NOT NULL, speciality_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E19D9AD23B5A08D7 ON service (speciality_id)');
        $this->addSql('COMMENT ON COLUMN service.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN service.speciality_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE speciality (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN speciality.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE statistics (id INT NOT NULL, total_reservations INT NOT NULL, total_clients INT NOT NULL, todays_reservations INT NOT NULL, weekly_reservations INT NOT NULL, monthly_reservations INT NOT NULL, active_reservations INT NOT NULL, cancelled_reservations INT NOT NULL, distinct_clients_per_week INT NOT NULL, total_services_per_company JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE unavailability (id UUID NOT NULL, user_id UUID DEFAULT NULL, company_id UUID DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0016D1A76ED395 ON unavailability (user_id)');
        $this->addSql('CREATE INDEX IDX_F0016D1979B1AD6 ON unavailability (company_id)');
        $this->addSql('COMMENT ON COLUMN unavailability.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN unavailability.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN unavailability.company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, company_id UUID DEFAULT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(20) DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON "user" (company_id)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".company_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE50D24070 FOREIGN KEY (company_service_id) REFERENCES company_service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE8C03F15C FOREIGN KEY (employee_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F3B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_service ADD CONSTRAINT FK_C1CF8005979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_service ADD CONSTRAINT FK_C1CF8005ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926223E2E969B FOREIGN KEY (review_id) REFERENCES review (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262212469DE2 FOREIGN KEY (category_id) REFERENCES category_review (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C619EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD23B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE statistics_id_seq CASCADE');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE50D24070');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE19EB6921');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE8C03F15C');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE979B1AD6');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F3B5A08D7');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE company_service DROP CONSTRAINT FK_C1CF8005979B1AD6');
        $this->addSql('ALTER TABLE company_service DROP CONSTRAINT FK_C1CF8005ED5CA9E6');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D88926223E2E969B');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D889262212469DE2');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6979B1AD6');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C619EB6921');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB979B1AD6');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FBA76ED395');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD23B5A08D7');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D1A76ED395');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D1979B1AD6');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649979B1AD6');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE category_review');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_service');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE speciality');
        $this->addSql('DROP TABLE statistics');
        $this->addSql('DROP TABLE unavailability');
        $this->addSql('DROP TABLE "user"');
    }
}
