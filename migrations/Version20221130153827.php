<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130153827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE keys_save (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, api_key1 VARCHAR(255) NOT NULL, api_key2 VARCHAR(255) DEFAULT NULL, api_key3 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE otra_info (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, cantidad INT DEFAULT NULL, codigo_moneda VARCHAR(3) DEFAULT NULL, monto_pagar INT DEFAULT NULL, return_url VARCHAR(255) DEFAULT NULL, cancel_url VARCHAR(255) DEFAULT NULL, notificacion_url VARCHAR(255) DEFAULT NULL, referencia VARCHAR(255) DEFAULT NULL, expira_dias INT DEFAULT NULL, lenguaje VARCHAR(2) DEFAULT NULL, tipo_uso INT DEFAULT NULL, reason_id INT NOT NULL, name_metodo VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario_contra (id INT AUTO_INCREMENT NOT NULL, usuario VARCHAR(60) DEFAULT NULL, contraseña VARCHAR(255) DEFAULT NULL, nombre_metodo VARCHAR(60) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE keys_save');
        $this->addSql('DROP TABLE otra_info');
        $this->addSql('DROP TABLE usuario_contra');
    }
}
