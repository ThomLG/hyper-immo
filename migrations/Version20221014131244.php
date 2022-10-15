<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014131244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dwelling ADD type_id INT NOT NULL, ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE dwelling ADD CONSTRAINT FK_2E991EEC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE dwelling ADD CONSTRAINT FK_2E991EE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_2E991EEC54C8C93 ON dwelling (type_id)');
        $this->addSql('CREATE INDEX IDX_2E991EE12469DE2 ON dwelling (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dwelling DROP FOREIGN KEY FK_2E991EEC54C8C93');
        $this->addSql('ALTER TABLE dwelling DROP FOREIGN KEY FK_2E991EE12469DE2');
        $this->addSql('DROP INDEX IDX_2E991EEC54C8C93 ON dwelling');
        $this->addSql('DROP INDEX IDX_2E991EE12469DE2 ON dwelling');
        $this->addSql('ALTER TABLE dwelling DROP type_id, DROP category_id');
    }
}
