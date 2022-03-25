<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220325082227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement CHANGE titre titre VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD description VARCHAR(255) DEFAULT NULL, ADD cru TINYINT(1) NOT NULL, ADD cuit TINYINT(1) NOT NULL, ADD bio TINYINT(1) NOT NULL, ADD debut_disponibilitã© DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement CHANGE titre titre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP description, DROP cru, DROP cuit, DROP bio, DROP debut_disponibilitã©');
    }
}
