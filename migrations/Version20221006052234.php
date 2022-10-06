<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006052234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cliente (id UUID NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id, created)) PARTITION BY RANGE (created)');
        // avoid using default partition as this could generate issues
        // when trying to create a new partition where range collides
        // with any value already inserted in default partition
        // $this->addSql('CREATE TABLE client_part_default PARTITION OF cliente DEFAULT');
        $this->addSql("CREATE TABLE client_part_2018 PARTITION OF cliente FOR VALUES FROM ('2018-01-01') TO ('2019-01-01')");
        $this->addSql("CREATE TABLE client_part_2019 PARTITION OF cliente FOR VALUES FROM ('2019-01-01') TO ('2020-01-01')");
        $this->addSql("CREATE TABLE client_part_2020 PARTITION OF cliente FOR VALUES FROM ('2020-01-01') TO ('2021-01-01')");
        $this->addSql("CREATE TABLE client_part_2021 PARTITION OF cliente FOR VALUES FROM ('2021-01-01') TO ('2022-01-01')");
        $this->addSql('COMMENT ON COLUMN cliente.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE venta (id INT NOT NULL, cliente_id UUID DEFAULT NULL, cliente_created_id TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, precio INT NOT NULL, experiencia VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8FE7EE55DE734E51C2FE1562 ON venta (cliente_id, cliente_created_id)');
        $this->addSql('COMMENT ON COLUMN venta.cliente_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE venta ADD CONSTRAINT FK_8FE7EE55DE734E51C2FE1562 FOREIGN KEY (cliente_id, cliente_created_id) REFERENCES cliente (id, created) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE venta DROP CONSTRAINT FK_8FE7EE55DE734E51C2FE1562');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE venta');
    }
}
