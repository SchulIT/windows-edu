<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219201638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE setting (id INT UNSIGNED AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, `data` JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_9F74B8984E645A7E (`key`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log CHANGE details details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user DROP kivuto_firstname, DROP kivuto_lastname, DROP kivuto_email, CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE uuid uuid VARCHAR(36) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B49202 ON user (idp_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D17F50A6 ON user (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE setting');
        $this->addSql('ALTER TABLE log CHANGE details details LONGTEXT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649B49202 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649D17F50A6 ON user');
        $this->addSql('ALTER TABLE user ADD kivuto_firstname VARCHAR(255) DEFAULT NULL, ADD kivuto_lastname VARCHAR(255) DEFAULT NULL, ADD kivuto_email VARCHAR(255) DEFAULT NULL, CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE uuid uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }
}
