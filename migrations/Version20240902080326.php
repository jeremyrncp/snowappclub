<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240902080326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE weather_data (id INT AUTO_INCREMENT NOT NULL, station_id INT NOT NULL, tint DOUBLE PRECISION DEFAULT NULL, tout DOUBLE PRECISION DEFAULT NULL, rhint DOUBLE PRECISION DEFAULT NULL, rhout DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_3370691A21BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE weather_station (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, elevation INT DEFAULT NULL, serial VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE weather_data ADD CONSTRAINT FK_3370691A21BDB235 FOREIGN KEY (station_id) REFERENCES weather_station (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE weather_data DROP FOREIGN KEY FK_3370691A21BDB235');
        $this->addSql('DROP TABLE weather_data');
        $this->addSql('DROP TABLE weather_station');
    }
}
