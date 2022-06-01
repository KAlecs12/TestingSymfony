<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531232110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact ADD id_user_id INT NOT NULL, ADD id_pro_id INT NOT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E63879F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638E5805157 FOREIGN KEY (id_pro_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4C62E63879F37AE5 ON contact (id_user_id)');
        $this->addSql('CREATE INDEX IDX_4C62E638E5805157 ON contact (id_pro_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63879F37AE5');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638E5805157');
        $this->addSql('DROP INDEX IDX_4C62E63879F37AE5 ON contact');
        $this->addSql('DROP INDEX IDX_4C62E638E5805157 ON contact');
        $this->addSql('ALTER TABLE contact DROP id_user_id, DROP id_pro_id');
    }
}
