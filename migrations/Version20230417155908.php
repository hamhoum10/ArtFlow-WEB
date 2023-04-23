<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417155908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id_article INT AUTO_INCREMENT NOT NULL, id_categorie INT DEFAULT NULL, nom_article VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, quantity INT NOT NULL, idArtiste INT DEFAULT NULL, INDEX IDX_23A0E668CBE5EBD (idArtiste), INDEX IDX_23A0E66C9486A13 (id_categorie), PRIMARY KEY(id_article)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artiste (id_artiste INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthplace VARCHAR(255) NOT NULL, birthdate VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phonenumber VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id_artiste)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id_categorie INT AUTO_INCREMENT NOT NULL, name_categorie VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favori (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_article INT DEFAULT NULL, INDEX IDX_EF85A2CC6B3CA4B (id_user), INDEX IDX_EF85A2CCDCA7A716 (id_article), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E668CBE5EBD FOREIGN KEY (idArtiste) REFERENCES artiste (id_artiste)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC6B3CA4B FOREIGN KEY (id_user) REFERENCES artiste (id_artiste)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CCDCA7A716 FOREIGN KEY (id_article) REFERENCES article (id_article)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E668CBE5EBD');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66C9486A13');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC6B3CA4B');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CCDCA7A716');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE artiste');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE favori');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
