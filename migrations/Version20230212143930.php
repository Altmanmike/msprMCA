<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212143930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customers CHANGE address address LONGTEXT DEFAULT NULL, CHANGE profile profile LONGTEXT DEFAULT NULL, CHANGE company company LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE products CHANGE details details LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE test');
        $this->addSql('ALTER TABLE customers CHANGE address address LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE profile profile LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE company company LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE products CHANGE details details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
