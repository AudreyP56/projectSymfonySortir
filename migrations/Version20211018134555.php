<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211018134555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE lieu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ville_id_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, rue VARCHAR(255) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_2F577D59F0C17188 ON lieu (ville_id_id)');
        $this->addSql('CREATE TABLE site (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ville_site_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_694309E4C0A8BFFA ON site (ville_site_id)');
        $this->addSql('CREATE TABLE sortie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, organisateur_id INTEGER NOT NULL, etat_id INTEGER NOT NULL, lieu_id_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, date_heure_sortie DATETIME NOT NULL, date_limite DATETIME NOT NULL, nb_place INTEGER NOT NULL, duree INTEGER NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D936B2FA ON sortie (organisateur_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D5E86FF ON sortie (etat_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2BA74394C ON sortie (lieu_id_id)');
        $this->addSql('CREATE TABLE sortie_user (sortie_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(sortie_id, user_id))');
        $this->addSql('CREATE INDEX IDX_8A67684ACC72D953 ON sortie_user (sortie_id)');
        $this->addSql('CREATE INDEX IDX_8A67684AA76ED395 ON sortie_user (user_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_id_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, telephone VARCHAR(10) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, role CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE INDEX IDX_8D93D649BB1E4E52 ON user (site_id_id)');
        $this->addSql('CREATE TABLE ville (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, code_postal VARCHAR(5) NOT NULL)');
        $this->addSql('DROP TABLE table_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE table_name (column_1 INTEGER DEFAULT NULL)');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE sortie');
        $this->addSql('DROP TABLE sortie_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ville');
    }
}
