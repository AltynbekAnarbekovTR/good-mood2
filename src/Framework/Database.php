<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException, PDOStatement;

class Database
{
  private PDO $connection;
  private PDOStatement $stmt;

  public function __construct(
          string $driver = null,
          array $config = null,
          string $username = null,
          string $password = null
  ) {
    $config = http_build_query(data: $config, arg_separator: ';');

    $dsn = "{$driver}:{$config}";

    try {
      $this->connection = new PDO(
              $dsn, $username, $password, [
                          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                  ]
      );
    } catch (PDOException $e) {
      die("Unable to connect to database");
    }
  }

  public function query(string $query, array $params = []): Database
  {
    $this->stmt = $this->connection->prepare($query);
    $this->stmt->execute($params);

    return $this;
  }

  public function count()
  {
    return $this->stmt->fetchColumn();
  }

  public function find($className = null)
  {
    if ($className) {
      $this->stmt->setFetchMode(PDO::FETCH_CLASS, $className, [$this]);
    }

    return $this->stmt->fetch();
  }

  public function lastInsertId()
  {
    return $this->connection->lastInsertId();
  }

  public function findAll($className = null, $fetchAssoc = false)
  {
    if ($className) {
      return $this->stmt->fetchAll(PDO::FETCH_CLASS, $className, [$this]);
    }

    if ($fetchAssoc) {
      return $this->stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    return $this->stmt->fetchAll();
  }

  public function findAllColumn()
  {
    return $this->stmt->fetchAll(PDO::FETCH_COLUMN);
  }
}
