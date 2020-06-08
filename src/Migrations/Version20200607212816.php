<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607212816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE marcador_etiqueta (id INT AUTO_INCREMENT NOT NULL, marcador_id INT DEFAULT NULL, etiqueta_id INT DEFAULT NULL, creado DATETIME NOT NULL, INDEX IDX_DCF4C7BB323D722 (marcador_id), INDEX IDX_DCF4C7BD53DA3AB (etiqueta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id)');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE marcador_etiqueta');
    }
}
