<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617075517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctors (id INT NOT NULL, speciality VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, doctors_id INT DEFAULT NULL, title VARCHAR(50) DEFAULT NULL, date_string VARCHAR(50) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5387574A67B3B43D (users_id), INDEX IDX_5387574A6425CC19 (doctors_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, address VARCHAR(50) DEFAULT NULL, zip_code VARCHAR(50) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', picture VARCHAR(255) DEFAULT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_doctors (users_id INT NOT NULL, doctors_id INT NOT NULL, INDEX IDX_4676F37467B3B43D (users_id), INDEX IDX_4676F3746425CC19 (doctors_id), PRIMARY KEY(users_id, doctors_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE doctors ADD CONSTRAINT FK_B67687BEBF396750 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A6425CC19 FOREIGN KEY (doctors_id) REFERENCES doctors (id)');
        $this->addSql('ALTER TABLE users_doctors ADD CONSTRAINT FK_4676F37467B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_doctors ADD CONSTRAINT FK_4676F3746425CC19 FOREIGN KEY (doctors_id) REFERENCES doctors (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctors DROP FOREIGN KEY FK_B67687BEBF396750');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A67B3B43D');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A6425CC19');
        $this->addSql('ALTER TABLE users_doctors DROP FOREIGN KEY FK_4676F37467B3B43D');
        $this->addSql('ALTER TABLE users_doctors DROP FOREIGN KEY FK_4676F3746425CC19');
        $this->addSql('DROP TABLE doctors');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_doctors');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
