<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240225073149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE navigation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE navigation (id INT NOT NULL, creation_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ship_symbol VARCHAR(255) NOT NULL, origin JSON NOT NULL, destination JSON NOT NULL, departure_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, arrival_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, flight_mode VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE navigation_id_seq CASCADE');
        $this->addSql('DROP TABLE navigation');
    }
}
