<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221201052000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE otra_info MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON otra_info');
        $this->addSql('ALTER TABLE otra_info CHANGE nombre nombre VARCHAR(255) NOT NULL, CHANGE descripcion descripcion VARCHAR(255) NOT NULL, CHANGE codigo_moneda codigo_moneda VARCHAR(3) DEFAULT NULL, CHANGE lenguaje lenguaje VARCHAR(2) DEFAULT NULL, CHANGE tipo_uso tipo_uso INT DEFAULT NULL, CHANGE reason_id reason_id INT NOT NULL, CHANGE name_metodo name_metodo VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE otra_info ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE usuario_contra ADD id INT AUTO_INCREMENT NOT NULL, CHANGE contraseña contraseña VARCHAR(255) DEFAULT NULL, CHANGE nombre_metodo nombre_metodo VARCHAR(60) DEFAULT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE otra_info MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON otra_info');
        $this->addSql('ALTER TABLE otra_info CHANGE nombre nombre VARCHAR(60) NOT NULL, CHANGE descripcion descripcion VARCHAR(60) DEFAULT NULL, CHANGE codigo_moneda codigo_moneda TEXT DEFAULT NULL, CHANGE lenguaje lenguaje TEXT DEFAULT NULL, CHANGE tipo_uso tipo_uso TINYINT(1) DEFAULT NULL, CHANGE reason_id reason_id INT DEFAULT NULL, CHANGE name_metodo name_metodo VARCHAR(60) DEFAULT NULL');
        $this->addSql('ALTER TABLE otra_info ADD PRIMARY KEY (id, nombre)');
        $this->addSql('ALTER TABLE usuario_contra MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON usuario_contra');
        $this->addSql('ALTER TABLE usuario_contra DROP id, CHANGE contraseña contraseña VARCHAR(60) DEFAULT NULL, CHANGE nombre_metodo nombre_metodo VARCHAR(60) NOT NULL');
    }
}
