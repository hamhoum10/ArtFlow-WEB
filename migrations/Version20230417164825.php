<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417164825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY article_ibfk_1');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY article_ibfk_2');
        $this->addSql('DROP INDEX id_artiste ON article');
        $this->addSql('CREATE INDEX IDX_23A0E66429A9C3F ON article (id_artiste)');
        $this->addSql('DROP INDEX id_categorie ON article');
        $this->addSql('CREATE INDEX IDX_23A0E66C9486A13 ON article (id_categorie)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT article_ibfk_1 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT article_ibfk_2 FOREIGN KEY (id_artiste) REFERENCES artiste (id_artiste)');
        $this->addSql('ALTER TABLE artiste CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE birthplace birthplace VARCHAR(255) NOT NULL, CHANGE birthdate birthdate VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE address address VARCHAR(255) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE client DROP username, CHANGE firstname firstname VARCHAR(200) NOT NULL, CHANGE lastname lastname VARCHAR(200) NOT NULL, CHANGE address address VARCHAR(200) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(200) NOT NULL, CHANGE email email VARCHAR(200) NOT NULL, CHANGE password password VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE commande ADD lesarticles LONGTEXT DEFAULT NULL, CHANGE prenom prenom VARCHAR(150) NOT NULL, CHANGE nom nom VARCHAR(150) NOT NULL, CHANGE status status VARCHAR(150) NOT NULL, CHANGE created_at created_at VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE panier DROP INDEX id_clientpanierFK, ADD UNIQUE INDEX UNIQ_24CC0DF2E173B1B8 (id_client)');
        $this->addSql('DROP INDEX code ON promocode');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66429A9C3F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66C9486A13');
        $this->addSql('DROP INDEX idx_23a0e66429a9c3f ON article');
        $this->addSql('CREATE INDEX id_artiste ON article (id_artiste)');
        $this->addSql('DROP INDEX idx_23a0e66c9486a13 ON article');
        $this->addSql('CREATE INDEX id_categorie ON article (id_categorie)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66429A9C3F FOREIGN KEY (id_artiste) REFERENCES artiste (id_artiste)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE artiste CHANGE firstname firstname VARCHAR(100) NOT NULL, CHANGE lastname lastname VARCHAR(100) NOT NULL, CHANGE birthplace birthplace VARCHAR(100) NOT NULL, CHANGE birthdate birthdate VARCHAR(20) NOT NULL, CHANGE description description VARCHAR(100) NOT NULL, CHANGE image image VARCHAR(200) NOT NULL, CHANGE address address VARCHAR(100) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE client ADD username VARCHAR(100) NOT NULL, CHANGE firstname firstname VARCHAR(100) NOT NULL, CHANGE lastname lastname VARCHAR(100) NOT NULL, CHANGE address address VARCHAR(100) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(30) NOT NULL, CHANGE email email VARCHAR(100) NOT NULL, CHANGE password password VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE commande DROP lesarticles, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE created_at created_at DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE panier DROP INDEX UNIQ_24CC0DF2E173B1B8, ADD INDEX id_clientpanierFK (id_client)');
        $this->addSql('CREATE UNIQUE INDEX code ON promocode (code)');
    }
}
