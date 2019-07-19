<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inicio extends CI_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        //cargamos el modelo
        $this->load->model("producto_model");

        //activamos las pruebas unitarias, false para desactivarlas
        $this->unit->active(true);

    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        //consultamos los productos
        $data["productos"] = $this->producto_model->getAll();
        $this->unit->run($this->producto_model->getAll(), 'is_array', 'Prueba del modelo orden_model', 'se prueba el metodo getAll');

        $data["prueba"] = $this->unit->report();
        $data["contenido"] = "inicio_view";
        $this->load->view('template/render_view', $data);
    }
}
