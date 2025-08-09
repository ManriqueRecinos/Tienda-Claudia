<?php
require_once __DIR__ . '/Conexion.php';
// Si tienes constantes, descomenta la siguiente línea y asegúrate de la ruta correcta
// require_once __DIR__ . '/Constante.php';
date_default_timezone_set('America/El_Salvador');

/**
 * Core simplificado, solo con las funciones solicitadas
 */
class Core
{
    public $conexion = null;
    private int $filasAfectadas = 0;
    private ?string $ultimoId = null;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    /**
     * Ejecuta INSERT/UPDATE/DELETE (sin parámetros)
     */
    public function ejecutar(string $sql): bool
    {
        $pdo = $this->conexion->getConexion();
        if (!$pdo) return false;
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $this->filasAfectadas = (int)$stmt->rowCount();
            // Intentar obtener último ID solo si es INSERT
            $this->ultimoId = null;
            if (preg_match('/^\s*INSERT\b/i', $sql)) {
                try { $this->ultimoId = $pdo->lastInsertId(); } catch (\Throwable $t) { $this->ultimoId = null; }
            }
            return $this->filasAfectadas > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Ejecuta SELECT y devuelve array asociativo o null
     */
    public function get_all(string $sql): ?array
    {
        $pdo = $this->conexion->getConexion();
        if (!$pdo) return null;
        try {
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $rows && count($rows) ? $rows : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function get_last_id(): ?string
    {
        return $this->ultimoId;
    }

    public function get_filas_afectadas(): int
    {
        return $this->filasAfectadas;
    }

    /**
     * Obtiene una sola fila (primer resultado)
     */
    public function getOne(string $sql): ?array
    {
        // Asegurar LIMIT 1 para eficiencia
        $sqlLimit = preg_match('/\blimit\b/i', $sql) ? $sql : ($sql . ' LIMIT 1');
        $rows = $this->get_all($sqlLimit);
        return $rows ? $rows[0] : null;
    }
}
