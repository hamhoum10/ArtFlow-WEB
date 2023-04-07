<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230407023725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livraison_commande DROP FOREIGN KEY FK_CD16506782EA2E54');
        $this->addSql('ALTER TABLE livraison_commande DROP FOREIGN KEY FK_CD1650678E54FB25');
        $this->addSql('DROP TABLE livraison_commande');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livraison_commande (livraison_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_CD1650678E54FB25 (livraison_id), INDEX IDX_CD16506782EA2E54 (commande_id), PRIMARY KEY(livraison_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE livraison_commande ADD CONSTRAINT FK_CD16506782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison_commande ADD CONSTRAINT FK_CD1650678E54FB25 FOREIGN KEY (livraison_id) REFERENCES livraison (id) ON DELETE CASCADE');
    }
}
