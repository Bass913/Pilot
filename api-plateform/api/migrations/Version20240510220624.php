<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510220624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE img_company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rating_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category_review (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE img_company (id INT NOT NULL, company_id INT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D04DA7979B1AD6 ON img_company (company_id)');
        $this->addSql('CREATE TABLE rating (id INT NOT NULL, review_id INT NOT NULL, category_id INT NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D88926223E2E969B ON rating (review_id)');
        $this->addSql('CREATE INDEX IDX_D889262212469DE2 ON rating (category_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, company_id INT DEFAULT NULL, date VARCHAR(255) NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6979B1AD6 ON review (company_id)');
        $this->addSql('ALTER TABLE img_company ADD CONSTRAINT FK_2D04DA7979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926223E2E969B FOREIGN KEY (review_id) REFERENCES review (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262212469DE2 FOREIGN KEY (category_id) REFERENCES category_review (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE company ADD review_rating DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE company_services ADD service_id INT NOT NULL');
        $this->addSql('ALTER TABLE company_services ADD CONSTRAINT FK_6BA5F404ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6BA5F404ED5CA9E6 ON company_services (service_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE img_company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rating_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('ALTER TABLE img_company DROP CONSTRAINT FK_2D04DA7979B1AD6');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D88926223E2E969B');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D889262212469DE2');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6979B1AD6');
        $this->addSql('DROP TABLE category_review');
        $this->addSql('DROP TABLE img_company');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE company DROP review_rating');
        $this->addSql('ALTER TABLE company_services DROP CONSTRAINT FK_6BA5F404ED5CA9E6');
        $this->addSql('DROP INDEX IDX_6BA5F404ED5CA9E6');
        $this->addSql('ALTER TABLE company_services DROP service_id');
    }
}
