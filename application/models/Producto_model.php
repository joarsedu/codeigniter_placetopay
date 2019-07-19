<?php

class Producto_model extends CI_Model
{
    // tabla principal del modelo
    private $tabla = 'productos';

    public function __construct()
    {
        //parent::__construct();
        $this->load->database();
    }

    /**
     * Consulta los datos de una tabla, segun el id.
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
     * @param int $id
     * @param array $where
     *
     * @return    array
     *
     */
    public function getAll(): array
    {
        $query = $this->db->select('*')
            ->from($this->tabla)
            ->get();
        return $query->result();
    }
}
