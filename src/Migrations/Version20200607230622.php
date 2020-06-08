<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200607230622 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marcador_etiqueta ADD marcador_id INT DEFAULT NULL, ADD etiqueta_id INT DEFAULT NULL, ADD creado DATETIME NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BB323D722 FOREIGN KEY (marcador_id) REFERENCES marcador (id)');
        $this->addSql('ALTER TABLE marcador_etiqueta ADD CONSTRAINT FK_DCF4C7BD53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id)');
        $this->addSql('CREATE INDEX IDX_DCF4C7BB323D722 ON marcador_etiqueta (marcador_id)');
        $this->addSql('CREATE INDEX IDX_DCF4C7BD53DA3AB ON marcador_etiqueta (etiqueta_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BB323D722');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP FOREIGN KEY FK_DCF4C7BD53DA3AB');
        $this->addSql('DROP INDEX IDX_DCF4C7BB323D722 ON marcador_etiqueta');
        $this->addSql('DROP INDEX IDX_DCF4C7BD53DA3AB ON marcador_etiqueta');
        $this->addSql('ALTER TABLE marcador_etiqueta DROP marcador_id, DROP etiqueta_id, DROP creado, CHANGE id id INT NOT NULL');
    }
}
