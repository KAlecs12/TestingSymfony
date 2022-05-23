<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220523132626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F86FF254B3C');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F8669C39953');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, description LONGTEXT NOT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, INDEX IDX_6EA9A14679F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A14679F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE horaire');
        $this->addSql('DROP TABLE installation');
        $this->addSql('DROP TABLE professionnel');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP TABLE soin');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id_contact INT AUTO_INCREMENT NOT NULL, sujet VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, message VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id_contact)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE horaire (id INT AUTO_INCREMENT NOT NULL, horaire VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:dateinterval)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE installation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nb_max_personnes INT NOT NULL, duree_utilisation_max VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:dateinterval)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE professionnel (id INT AUTO_INCREMENT NOT NULL, identite VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, duree_moyenne VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:dateinterval)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, id_installation_id INT DEFAULT NULL, id_professionnel_id INT DEFAULT NULL, date DATETIME NOT NULL, type VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, tarification DOUBLE PRECISION NOT NULL, INDEX IDX_10C31F86FF254B3C (id_installation_id), INDEX IDX_10C31F8669C39953 (id_professionnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE soin (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F86FF254B3C FOREIGN KEY (id_installation_id) REFERENCES installation (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F8669C39953 FOREIGN KEY (id_professionnel_id) REFERENCES professionnel (id)');
        $this->addSql('DROP TABLE calendar');
    }
}
