<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127140540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE taches (id INT AUTO_INCREMENT NOT NULL, projets_id INT DEFAULT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, contenu VARCHAR(255) NOT NULL, INDEX IDX_3BF2CD98597A6CB7 (projets_id), INDEX IDX_3BF2CD98A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD98597A6CB7 FOREIGN KEY (projets_id) REFERENCES projets (id)');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD98A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches DROP FOREIGN KEY FK_3BF2CD98597A6CB7');
        $this->addSql('ALTER TABLE taches DROP FOREIGN KEY FK_3BF2CD98A76ED395');
        $this->addSql('DROP TABLE taches');
    }
}
