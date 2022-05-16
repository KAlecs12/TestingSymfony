<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516140847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, sujet VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_4C62E63879F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, titre VARCHAR(45) NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_FE86641079F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, time INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recapitulatif (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(45) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, totalprice VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_rdv (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_rdv_id INT DEFAULT NULL, INDEX IDX_45C1325979F37AE5 (id_user_id), UNIQUE INDEX UNIQ_45C132596AF98A6B (id_rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(45) NOT NULL, duration VARCHAR(45) NOT NULL, coach VARCHAR(45) NOT NULL, price VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, id_recapitulatif_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', first_name VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, image_filename VARCHAR(255) DEFAULT NULL, facturation VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649668B4C3D (id_recapitulatif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_stage (user_id INT NOT NULL, stage_id INT NOT NULL, INDEX IDX_20BE6831A76ED395 (user_id), INDEX IDX_20BE68312298D193 (stage_id), PRIMARY KEY(user_id, stage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E63879F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641079F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_rdv ADD CONSTRAINT FK_45C1325979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_rdv ADD CONSTRAINT FK_45C132596AF98A6B FOREIGN KEY (id_rdv_id) REFERENCES rdv (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649668B4C3D FOREIGN KEY (id_recapitulatif_id) REFERENCES recapitulatif (id)');
        $this->addSql('ALTER TABLE user_stage ADD CONSTRAINT FK_20BE6831A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_stage ADD CONSTRAINT FK_20BE68312298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_rdv DROP FOREIGN KEY FK_45C132596AF98A6B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649668B4C3D');
        $this->addSql('ALTER TABLE user_stage DROP FOREIGN KEY FK_20BE68312298D193');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63879F37AE5');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641079F37AE5');
        $this->addSql('ALTER TABLE reservation_rdv DROP FOREIGN KEY FK_45C1325979F37AE5');
        $this->addSql('ALTER TABLE user_stage DROP FOREIGN KEY FK_20BE6831A76ED395');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP TABLE recapitulatif');
        $this->addSql('DROP TABLE reservation_rdv');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_stage');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
