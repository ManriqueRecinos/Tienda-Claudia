<?php
require_once 'Conexion.php';

/**
 * Clase Core para operaciones de base de datos
 * Principio SRP: Se encarga únicamente de las operaciones CRUD
 * Principio DIP: Depende de la abstracción de Conexion
 * Principio OCP: Abierta para extensión, cerrada para modificación
 */
class Core
{
    private Conexion $conexion;
    private string $informacion = '';
    private int $filasAfectadas = 0;
    private ?string $ultimoId = null;

    /**
     * Constructor - Principio DIP: Inyección de dependencias
     * @param Conexion|null $conexion
     */
    public function __construct(?Conexion $conexion = null)
    {
        // Si no se proporciona conexión, crear una nueva (Principio SRP)
        $this->conexion = $conexion ?? new Conexion();
    }

    /**
     * Función get_all - Equivalente a SELECT
     * Principio SRP: Solo se encarga de consultas de lectura
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function get_all(string $sql, array $params = []): ?array
    {
        try {
            $pdo = $this->conexion->getConexion();
            if (!$pdo) {
                $this->setError('No hay conexión disponible');
                return null;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $results = $stmt->fetchAll();
            $this->conexion->setError('NO HAY ERROR');
            
            return $results ?: null;

        } catch (PDOException $e) {
            $this->setError('Error en consulta: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Función ejecutar - Para INSERT, UPDATE, DELETE genéricos
     * Principio SRP: Se encarga de operaciones de escritura
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function ejecutar(string $sql, array $params = []): bool
    {
        try {
            $pdo = $this->conexion->getConexion();
            if (!$pdo) {
                $this->setError('No hay conexión disponible');
                return false;
            }

            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            $this->filasAfectadas = $stmt->rowCount();
            $this->ultimoId = $pdo->lastInsertId();
            
            if ($result && $this->filasAfectadas > 0) {
                $this->setInformacion('Operación ejecutada correctamente');
                $this->conexion->setError('NO HAY ERROR');
                return true;
            }
            
            $this->setInformacion('No se afectaron registros');
            return false;

        } catch (PDOException $e) {
            $this->setError('Error en ejecución: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Función delete - Específica para eliminaciones
     * Principio SRP: Se encarga únicamente de eliminaciones
     * @param string $tabla
     * @param array $condiciones
     * @return bool
     */
    public function delete(string $tabla, array $condiciones): bool
    {
        // Principio de validación - no permitir eliminaciones sin condiciones
        if (empty($condiciones)) {
            $this->setError('No se pueden eliminar registros sin condiciones');
            return false;
        }

        $whereClause = [];
        $params = [];
        
        foreach ($condiciones as $columna => $valor) {
            $whereClause[] = "$columna = :$columna";
            $params[":$columna"] = $valor;
        }
        
        $sql = "DELETE FROM $tabla WHERE " . implode(' AND ', $whereClause);
        
        if ($this->ejecutar($sql, $params)) {
            $this->setInformacion('Registro eliminado correctamente');
            return true;
        }
        
        $this->setInformacion('No se pudo eliminar el registro');
        return false;
    }

    /**
     * Función update - Específica para actualizaciones
     * Principio SRP: Se encarga únicamente de actualizaciones
     * @param string $tabla
     * @param array $datos
     * @param array $condiciones
     * @return bool
     */
    public function update(string $tabla, array $datos, array $condiciones): bool
    {
        // Validaciones - Principio de responsabilidad
        if (empty($datos)) {
            $this->setError('No hay datos para actualizar');
            return false;
        }

        if (empty($condiciones)) {
            $this->setError('No se pueden actualizar registros sin condiciones');
            return false;
        }

        $setClause = [];
        $whereClause = [];
        $params = [];
        
        // Construir SET clause
        foreach ($datos as $columna => $valor) {
            $setClause[] = "$columna = :set_$columna";
            $params[":set_$columna"] = $valor;
        }
        
        // Construir WHERE clause
        foreach ($condiciones as $columna => $valor) {
            $whereClause[] = "$columna = :where_$columna";
            $params[":where_$columna"] = $valor;
        }
        
        $sql = "UPDATE $tabla SET " . implode(', ', $setClause) . 
               " WHERE " . implode(' AND ', $whereClause);
        
        if ($this->ejecutar($sql, $params)) {
            $this->setInformacion('Registro actualizado correctamente');
            return true;
        }
        
        $this->setInformacion('No se pudo actualizar el registro');
        return false;
    }

    // Métodos auxiliares - Principio SRP: cada método tiene una responsabilidad específica

    /**
     * Obtiene el número de filas afectadas
     * @return int
     */
    public function getFilasAfectadas(): int
    {
        return $this->filasAfectadas;
    }

    /**
     * Obtiene el último ID insertado
     * @return string|null
     */
    public function getUltimoId(): ?string
    {
        return $this->ultimoId;
    }

    /**
     * Obtiene la información del último proceso
     * @return string
     */
    public function getInformacion(): string
    {
        return $this->informacion;
    }

    /**
     * Establece información personalizada
     * @param string $informacion
     */
    public function setInformacion(string $informacion): void
    {
        $this->informacion = $informacion;
    }

    /**
     * Obtiene el último error
     * @return string
     */
    public function getError(): string
    {
        return $this->conexion->getError();
    }

    /**
     * Establece un error personalizado
     * @param string $error
     */
    private function setError(string $error): void
    {
        $this->conexion->setError($error);
    }

    /**
     * Obtiene la conexión (para casos especiales)
     * @return Conexion
     */
    public function getConexion(): Conexion
    {
        return $this->conexion;
    }

    /**
     * Inicia transacción
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->conexion->beginTransaction();
    }

    /**
     * Confirma transacción
     * @return bool
     */
    public function commit(): bool
    {
        return $this->conexion->commit();
    }

    /**
     * Revierte transacción
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->conexion->rollback();
    }

    /**
     * Método para consultas personalizadas con un solo resultado
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function getOne(string $sql, array $params = []): ?array
    {
        $results = $this->get_all($sql . " LIMIT 1", $params);
        return $results ? $results[0] : null;
    }

    /**
     * Método para contar registros
     * @param string $tabla
     * @param array $condiciones
     * @return int
     */
    public function count(string $tabla, array $condiciones = []): int
    {
        $whereClause = '';
        $params = [];
        
        if (!empty($condiciones)) {
            $conditions = [];
            foreach ($condiciones as $columna => $valor) {
                $conditions[] = "$columna = :$columna";
                $params[":$columna"] = $valor;
            }
            $whereClause = " WHERE " . implode(' AND ', $conditions);
        }
        
        $sql = "SELECT COUNT(*) as total FROM $tabla" . $whereClause;
        $result = $this->getOne($sql, $params);
        
        return $result ? (int)$result['total'] : 0;
    }
}
