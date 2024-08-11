<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811073932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE localisation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, elevation INT NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, INDEX IDX_BFD3CE8FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE snow_data (id INT AUTO_INCREMENT NOT NULL, localisation_id INT DEFAULT NULL, date DATETIME NOT NULL, snow_fresh INT DEFAULT NULL, snow_total INT DEFAULT NULL, snow TINYINT(1) DEFAULT NULL, INDEX IDX_B76B2C27C68BE09C (localisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE localisation ADD CONSTRAINT FK_BFD3CE8FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE snow_data ADD CONSTRAINT FK_B76B2C27C68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE localisation DROP FOREIGN KEY FK_BFD3CE8FA76ED395');
        $this->addSql('ALTER TABLE snow_data DROP FOREIGN KEY FK_B76B2C27C68BE09C');
        $this->addSql('DROP TABLE localisation');
        $this->addSql('DROP TABLE snow_data');
        $this->addSql('DROP TABLE user');
    }
}
