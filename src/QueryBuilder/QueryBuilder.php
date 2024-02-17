<?php

namespace App\QueryBuilder;

use Exception;
use App\DB\DBConnect;

class QueryBuilder
{
    private $from;
    private $order = [];
    private $limit;
    private $offset = 0;
    private $where;
    private $groupBy;
    private $multipleWhere = [];
    private $multipleOrWhere = [];
    private $join = [];
    private $params = [];
    private $fields = ["*"];
    private $dbConnect;

    public function __construct(DBConnect $dbConnect = null)
    {
        $this->dbConnect = $dbConnect;
    }
    
    public function from(string $table, string $alias = null): self
    {
        $this->from = $alias === null ? "$table" : "$table $alias";
        return $this;
    }

    public function orderBy(string $key, string $direction): self
    {
        $direction = strtoupper($direction);
        $this->order[] = in_array($direction, ["ASC", "DESC"]) ? "$key $direction" : "$key";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = intval($limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        if ($this->limit === null) {
            throw new Exception("Cannot set OFFSET without setting LIMIT");
        }
        $this->offset = intval($offset);
        return $this;
    }

    public function page(int $page): self
    {
        $this->offset = $this->limit * (intval($page) - 1);
        return $this;
    }

    public function where(string $condition): self
    {
        $this->where = $condition;
        return $this;
    }

    public function multipleWhere(string $whereCondition): self
    {
        $this->multipleWhere[] = $whereCondition;
        return $this;
    }

    public function multipleOrWhere(string $whereCondition): self
    {
        $this->multipleOrWhere[] = $whereCondition;
        return $this;
    }

    public function groupBy(string $groupBy): self
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    public function join(string $joinCondition): self
    {
        $this->join[] = $joinCondition;
        return $this;
    }

    public function setParams(string $key, $value): self
    {
        $this->params[$key] = $value;
        return $this;
    }

    public function select(...$fields): self
    {
        if (is_array($fields[0])) {
            $fields = $fields[0];
        }
        $this->fields = $this->fields === ["*"] ? $fields : array_merge($this->fields, $fields);
        return $this;
    }

    public function toSQL(): string
    {
        $fields = implode(", ", $this->fields);
        $sql = "SELECT {$fields} FROM {$this->from}";

        if (!empty($this->join)) {
            $joins = implode(" ", $this->join);
            $sql .= " {$joins}";
        }

        if ($this->where) {
            $sql .= " WHERE {$this->where}";
        }

        if (!empty($this->multipleWhere)) {
            $wheres = implode(" AND ", $this->multipleWhere);
            $sql .= $this->where ? " AND {$wheres}" : " WHERE {$wheres}";
        }

        if (!empty($this->multipleOrWhere)) {
            $wheres = implode(" OR ", $this->multipleOrWhere);
            $sql .= $this->where ? " AND ({$wheres})" : " WHERE {$wheres}";
        }

        if (!empty($this->groupBy)) {
            $sql .= " GROUP BY {$this->groupBy}";
        }

        if (!empty($this->order)) {
            $sql .= " ORDER BY " . implode(", ", $this->order);
        }

        if ($this->limit > 0) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->limit > 0 && $this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }
        return $sql;
    }

    public function fetchAll()
    {
        try {
            $req = $this->getDBConnect();
            $result = $req->query($this->toSQL(), $this->params);
            return $result;
        } catch (\Throwable $th) {
            throw new Exception("Failed to execute the query " . $this->toSQL() . " : " . $th);
        }
    }

    public function myQuery(string $query, array $params = null): bool
    {
        try {
            $req = $this->getDBConnect();
            return $req->queryBuild($query, $params);
        } catch (\Throwable $th) {
            throw new Exception("Failed to execute the query " . $query . " : " . $th);
        }
    }

    public function customQuery(string $query, array $params = null): array
    {
        $req = $this->getDBConnect();
        return $req->query($query, $params);
    }

    public function count(string $field): int
    {
        $base = clone $this;
        $base->fields = [];
        $base->order = [];
        $base->select("COUNT($field) count");
        return (int)$base->limit(0)->fetchAll()[0]->count;
    }

    private function getDBConnect(): DBConnect
    {
        if ($this->dbConnect === null) {
            $this->dbConnect = new DBConnect();
        }
        return $this->dbConnect;
    }
}
