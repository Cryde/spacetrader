<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203140200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, creation_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, space_trader_token_api TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE user_token (id INT NOT NULL, user_reference_id INT NOT NULL, creation_datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BDF55A639689A2AB ON user_token (user_reference_id)');
        $this->addSql('ALTER TABLE user_token ADD CONSTRAINT FK_BDF55A639689A2AB FOREIGN KEY (user_reference_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_token_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_token DROP CONSTRAINT FK_BDF55A639689A2AB');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_token');
    }
}
