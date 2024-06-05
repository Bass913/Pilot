<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605092734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rating_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE services_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE speciality_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE unavailability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, company_service_id INT NOT NULL, client_id INT NOT NULL, start_date VARCHAR(255) NOT NULL, end_date VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E00CEDDE50D24070 ON booking (company_service_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE19EB6921 ON booking (client_id)');
        $this->addSql('CREATE TABLE category_review (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, speciality_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, zipcode VARCHAR(10) NOT NULL, city VARCHAR(255) NOT NULL, kbis VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, review_rating DOUBLE PRECISION DEFAULT NULL, review_count INT DEFAULT NULL, images TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FBF094F3B5A08D7 ON company (speciality_id)');
        $this->addSql('COMMENT ON COLUMN company.images IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE company_services (id INT NOT NULL, company_id INT DEFAULT NULL, service_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, duration INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6BA5F404979B1AD6 ON company_services (company_id)');
        $this->addSql('CREATE INDEX IDX_6BA5F404ED5CA9E6 ON company_services (service_id)');
        $this->addSql('CREATE TABLE rating (id INT NOT NULL, review_id INT NOT NULL, category_id INT NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D88926223E2E969B ON rating (review_id)');
        $this->addSql('CREATE INDEX IDX_D889262212469DE2 ON rating (category_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, company_id INT DEFAULT NULL, date VARCHAR(255) NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6979B1AD6 ON review (company_id)');
        $this->addSql('CREATE TABLE schedule (id INT NOT NULL, company_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, day_of_week VARCHAR(9) NOT NULL, start_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL, end_time TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A3811FB979B1AD6 ON schedule (company_id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB9D86650F ON schedule (user_id_id)');
        $this->addSql('CREATE TABLE services (id INT NOT NULL, speciality_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7332E1693B5A08D7 ON services (speciality_id)');
        $this->addSql('CREATE TABLE speciality (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE unavailability (id INT NOT NULL, user_id INT DEFAULT NULL, company_id INT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0016D1A76ED395 ON unavailability (user_id)');
        $this->addSql('CREATE INDEX IDX_F0016D1979B1AD6 ON unavailability (company_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, company_id INT DEFAULT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON "user" (company_id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE50D24070 FOREIGN KEY (company_service_id) REFERENCES company_services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F3B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_services ADD CONSTRAINT FK_6BA5F404979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company_services ADD CONSTRAINT FK_6BA5F404ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926223E2E969B FOREIGN KEY (review_id) REFERENCES review (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262212469DE2 FOREIGN KEY (category_id) REFERENCES category_review (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E1693B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE unavailability ADD CONSTRAINT FK_F0016D1979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_services_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rating_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE schedule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE services_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE speciality_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE unavailability_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE50D24070');
        $this->addSql('ALTER TABLE booking DROP CONSTRAINT FK_E00CEDDE19EB6921');
        $this->addSql('ALTER TABLE company DROP CONSTRAINT FK_4FBF094F3B5A08D7');
        $this->addSql('ALTER TABLE company_services DROP CONSTRAINT FK_6BA5F404979B1AD6');
        $this->addSql('ALTER TABLE company_services DROP CONSTRAINT FK_6BA5F404ED5CA9E6');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D88926223E2E969B');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D889262212469DE2');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6979B1AD6');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB979B1AD6');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB9D86650F');
        $this->addSql('ALTER TABLE services DROP CONSTRAINT FK_7332E1693B5A08D7');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D1A76ED395');
        $this->addSql('ALTER TABLE unavailability DROP CONSTRAINT FK_F0016D1979B1AD6');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649979B1AD6');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE category_review');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE company_services');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE speciality');
        $this->addSql('DROP TABLE unavailability');
        $this->addSql('DROP TABLE "user"');
    }
}
