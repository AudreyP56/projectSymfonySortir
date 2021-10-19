<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211019100229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sortie_user (sortie_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(sortie_id, user_id))');
        $this->addSql('CREATE INDEX IDX_8A67684ACC72D953 ON sortie_user (sortie_id)');
        $this->addSql('CREATE INDEX IDX_8A67684AA76ED395 ON sortie_user (user_id)');
        $this->addSql('DROP INDEX IDX_2F577D59F0C17188');
        $this->addSql('CREATE TEMPORARY TABLE __temp__lieu AS SELECT id, ville_id_id, nom, rue, latitude, longitude FROM lieu');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('CREATE TABLE lieu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ville_id_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, rue VARCHAR(255) NOT NULL COLLATE BINARY, latitude VARCHAR(255) DEFAULT NULL COLLATE BINARY, longitude VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_2F577D59F0C17188 FOREIGN KEY (ville_id_id) REFERENCES ville (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO lieu (id, ville_id_id, nom, rue, latitude, longitude) SELECT id, ville_id_id, nom, rue, latitude, longitude FROM __temp__lieu');
        $this->addSql('DROP TABLE __temp__lieu');
        $this->addSql('CREATE INDEX IDX_2F577D59F0C17188 ON lieu (ville_id_id)');
        $this->addSql('DROP INDEX IDX_694309E4C0A8BFFA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__site AS SELECT id, ville_site_id, nom FROM site');
        $this->addSql('DROP TABLE site');
        $this->addSql('CREATE TABLE site (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ville_site_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_694309E4C0A8BFFA FOREIGN KEY (ville_site_id) REFERENCES ville (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO site (id, ville_site_id, nom) SELECT id, ville_site_id, nom FROM __temp__site');
        $this->addSql('DROP TABLE __temp__site');
        $this->addSql('CREATE INDEX IDX_694309E4C0A8BFFA ON site (ville_site_id)');
        $this->addSql('DROP INDEX IDX_3C3FD3F2D5E86FF');
        $this->addSql('DROP INDEX IDX_3C3FD3F2BA74394C');
        $this->addSql('DROP INDEX IDX_3C3FD3F2D936B2FA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sortie AS SELECT id, etat_id, lieu_id_id, organisateur_id, nom, date_heure_sortie, date_limite, nb_place, duree, description FROM sortie');
        $this->addSql('DROP TABLE sortie');
        $this->addSql('CREATE TABLE sortie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etat_id INTEGER NOT NULL, lieu_id_id INTEGER NOT NULL, organisateur_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL COLLATE BINARY, date_heure_sortie DATETIME NOT NULL, date_limite DATETIME NOT NULL, nb_place INTEGER NOT NULL, duree INTEGER NOT NULL, description CLOB DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_3C3FD3F2D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3C3FD3F2BA74394C FOREIGN KEY (lieu_id_id) REFERENCES lieu (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3C3FD3F2D936B2FA FOREIGN KEY (organisateur_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sortie (id, etat_id, lieu_id_id, organisateur_id, nom, date_heure_sortie, date_limite, nb_place, duree, description) SELECT id, etat_id, lieu_id_id, organisateur_id, nom, date_heure_sortie, date_limite, nb_place, duree, description FROM __temp__sortie');
        $this->addSql('DROP TABLE __temp__sortie');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D5E86FF ON sortie (etat_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2BA74394C ON sortie (lieu_id_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D936B2FA ON sortie (organisateur_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX IDX_8D93D649BB1E4E52');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, site_id_id, email, roles, password, nom, prenom, pseudo, telephone, photo FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_id_id INTEGER NOT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , password VARCHAR(255) NOT NULL COLLATE BINARY, nom VARCHAR(255) NOT NULL COLLATE BINARY, prenom VARCHAR(255) NOT NULL COLLATE BINARY, pseudo VARCHAR(255) NOT NULL COLLATE BINARY, telephone VARCHAR(10) DEFAULT NULL COLLATE BINARY, photo VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_8D93D649BB1E4E52 FOREIGN KEY (site_id_id) REFERENCES site (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, site_id_id, email, roles, password, nom, prenom, pseudo, telephone, photo) SELECT id, site_id_id, email, roles, password, nom, prenom, pseudo, telephone, photo FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649BB1E4E52 ON user (site_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sortie_user');
        $this->addSql('DROP INDEX IDX_2F577D59F0C17188');
        $this->addSql('CREATE TEMPORARY TABLE __temp__lieu AS SELECT id, ville_id_id, nom, rue, latitude, longitude FROM lieu');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('CREATE TABLE lieu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ville_id_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, rue VARCHAR(255) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO lieu (id, ville_id_id, nom, rue, latitude, longitude) SELECT id, ville_id_id, nom, rue, latitude, longitude FROM __temp__lieu');
        $this->addSql('DROP TABLE __temp__lieu');
        $this->addSql('CREATE INDEX IDX_2F577D59F0C17188 ON lieu (ville_id_id)');
        $this->addSql('DROP INDEX IDX_694309E4C0A8BFFA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__site AS SELECT id, ville_site_id, nom FROM site');
        $this->addSql('DROP TABLE site');
        $this->addSql('CREATE TABLE site (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ville_site_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO site (id, ville_site_id, nom) SELECT id, ville_site_id, nom FROM __temp__site');
        $this->addSql('DROP TABLE __temp__site');
        $this->addSql('CREATE INDEX IDX_694309E4C0A8BFFA ON site (ville_site_id)');
        $this->addSql('DROP INDEX IDX_3C3FD3F2D5E86FF');
        $this->addSql('DROP INDEX IDX_3C3FD3F2BA74394C');
        $this->addSql('DROP INDEX IDX_3C3FD3F2D936B2FA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sortie AS SELECT id, etat_id, lieu_id_id, organisateur_id, nom, date_heure_sortie, date_limite, nb_place, duree, description FROM sortie');
        $this->addSql('DROP TABLE sortie');
        $this->addSql('CREATE TABLE sortie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, etat_id INTEGER NOT NULL, lieu_id_id INTEGER NOT NULL, organisateur_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, date_heure_sortie DATETIME NOT NULL, date_limite DATETIME NOT NULL, nb_place INTEGER NOT NULL, duree INTEGER NOT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO sortie (id, etat_id, lieu_id_id, organisateur_id, nom, date_heure_sortie, date_limite, nb_place, duree, description) SELECT id, etat_id, lieu_id_id, organisateur_id, nom, date_heure_sortie, date_limite, nb_place, duree, description FROM __temp__sortie');
        $this->addSql('DROP TABLE __temp__sortie');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D5E86FF ON sortie (etat_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2BA74394C ON sortie (lieu_id_id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2D936B2FA ON sortie (organisateur_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX IDX_8D93D649BB1E4E52');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, site_id_id, email, roles, password, nom, prenom, pseudo, telephone, photo FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, site_id_id INTEGER NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, telephone VARCHAR(10) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, site_id_id, email, roles, password, nom, prenom, pseudo, telephone, photo) SELECT id, site_id_id, email, roles, password, nom, prenom, pseudo, telephone, photo FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649BB1E4E52 ON user (site_id_id)');
    }
}
