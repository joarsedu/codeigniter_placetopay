<?php

class Orden_model extends CI_Model
{
    // tabla principal del modelo
    private $tabla = 'orders';

    public function __construct()
    {
        //parent::__construct();
        $this->load->database();
    }

    /**
     * guardar
     *
     * Almacena los datos de una tabla.
     *
     * @param array $campos             Array con los datos a guardar
     *
     * @return array
     */
    public function guardar(array $campos): array
    {
        $this->db->insert($this->tabla, $campos);
        return ($this->db->affected_rows() >= 1) ?
        array("status" => true, "id" => $this->db->insert_id()) :
        $result = $this->db->error();
        $result["status"] = false;
    }

    /**
     * Actualizar los datos de una tabla.
     *
     * @param array $data
     * @param array $where
     *
     * @return    result boolean
     *
     */
    public function actualizar(array $data, array $where): bool
    {
        $data["updated_at"] = date('Y-m-d H:i:s');
        $this->db->set($data);
        $this->db->where($where);
        return $this->db->update($this->tabla);
    }

    /**
     * Consulta los datos de una tabla.
     *
     * @param int $id
     * @param array $where
     *
     * @return    result boolean
     *
     */
    public function getById(int $id): object
    {
        $where["id"] = $id;

        $query = $this->db->select('*')
            ->from($this->tabla)
            ->where($where)
            ->get();
        return $query->row();
    }

    /**
     * Consulta los datos de una tabla.
     *
     * @return    array
     *
     */
    public function getAll(): array
    {
        $query = $this->db->select('o.id AS orden_id, o.status, p.id AS producto_id, p.nombre, p.precio')
            ->from($this->tabla. " AS o")
            ->join("productos AS p", 'p.id = o.producto_id')
            ->order_by("o.id")
            ->get();
        return $query->result();
    }
}
