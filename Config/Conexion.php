<?php

/**
 * Clase Conexion para Neon Database
 * Principio SRP: Solo se encarga de manejar la conexión a la base de datos
 * Principio OCP: Abierta para extensión, cerrada para modificación
 */
date_default_timezone_set('America/El_Salvador');

class Conexion
{
    // Configuración para Neon Database
    private string $host = 'ep-nameless-sun-adl8xofh-pooler.c-2.us-east-1.aws.neon.tech';
    private string $database = 'neondb';
    private string $username = 'neondb_owner';
    private string $password = 'npg_cWohXu6LCw9b';
    private string $sslmode = 'require';
    private string $endpoint = 'ep-nameless-sun-adl8xofh'; // Endpoint ID extraído del host
    private int $port = 5432;
    
    private ?PDO $conexion = null;
    private string $error = '';

    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        $this->conectar();
    }

    /**
     * Establece una conexión con Neon Database
     * @return bool
     */
    private function conectar(): bool
    {
        // Verificar si ya existe una conexión
        if ($this->conexion !== null) {
            return true;
        }

        // Intentar múltiples métodos de conexión
        $metodosConexion = [
            'metodo1' => $this->conectarMetodo1(),
            'metodo2' => $this->conectarMetodo2(),
            'metodo3' => $this->conectarMetodo3(),
        ];

        foreach ($metodosConexion as $metodo => $resultado) {
            if ($resultado) {
                $this->error = "NO HAY ERROR - Conectado usando $metodo";
                return true;
            }
        }

        return false;
    }

    /**
     * Método 1: DSN con options endpoint
     */
    private function conectarMetodo1(): bool
    {
        try {
            $dsn = sprintf(
                "pgsql:host=%s;port=%d;dbname=%s;sslmode=%s;options=endpoint%%3D%s",
                $this->host,
                $this->port,
                $this->database,
                $this->sslmode,
                $this->endpoint
            );

            $this->conexion = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 30,
            ]);

            return true;
        } catch (PDOException $e) {
            $this->error = "Método 1 falló: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Método 2: DSN con parámetro endpoint en query string
     */
    private function conectarMetodo2(): bool
    {
        try {
            $dsn = sprintf(
                "pgsql:host=%s;port=%d;dbname=%s;sslmode=%s",
                $this->host,
                $this->port,
                $this->database,
                $this->sslmode
            );

            // Agregar endpoint como opción de conexión
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 30,
            ];

            $this->conexion = new PDO($dsn . ";options=endpoint=" . $this->endpoint, $this->username, $this->password, $options);

            return true;
        } catch (PDOException $e) {
            $this->error = "Método 2 falló: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Método 3: DSN con application_name
     */
    private function conectarMetodo3(): bool
    {
        try {
            $dsn = sprintf(
                "pgsql:host=%s;port=%d;dbname=%s;sslmode=%s;application_name=%s",
                $this->host,
                $this->port,
                $this->database,
                $this->sslmode,
                $this->endpoint
            );

            $this->conexion = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 30,
            ]);

            return true;
        } catch (PDOException $e) {
            $this->error = "Método 3 falló: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Desconecta de la base de datos
     * @return bool
     */
    public function desconectar(): bool
    {
        $this->conexion = null;
        return true;
    }

    /**
     * Obtiene la conexión PDO
     * @return PDO|null
     */
    public function getConexion(): ?PDO
    {
        if (!$this->isConnected()) {
            $this->conectar();
        }
        return $this->conexion;
    }

    /**
     * Verifica si hay conexión activa
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->conexion !== null;
    }

    /**
     * Obtiene el último error
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Establece un error personalizado
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * Inicia una transacción
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->conexion ? $this->conexion->beginTransaction() : false;
    }

    /**
     * Confirma una transacción
     * @return bool
     */
    public function commit(): bool
    {
        return $this->conexion ? $this->conexion->commit() : false;
    }

    /**
     * Revierte una transacción
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->conexion ? $this->conexion->rollBack() : false;
    }

    /**
     * Obtiene información del servidor
     * @return string
     */
    public function getServidor(): string
    {
        return $this->host;
    }

    /**
     * Obtiene el endpoint ID
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Obtiene información de debug de la conexión
     * @return array
     */
    public function getDebugInfo(): array
    {
        return [
            'host' => $this->host,
            'database' => $this->database,
            'username' => $this->username,
            'endpoint' => $this->endpoint,
            'port' => $this->port,
            'sslmode' => $this->sslmode,
            'connected' => $this->isConnected(),
            'error' => $this->error
        ];
    }
}
