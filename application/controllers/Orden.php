<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orden extends CI_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        //cargamos el modelo
        $this->load->model(["orden_model", "producto_model"]);

        //cargamos la libreria para saber el userAgent
        $this->load->library(['user_agent']);

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
        $this->all();
    }

    /**
     * Muestra una nueva orden
     *
     * @param int $id                           Id del producto
     *
     * @return void
     */
    public function nueva(int $id): void
    {
        //consultamos los datos del producto
        $producto = $this->producto_model->getById($id);
        $this->unit->run($this->producto_model->getById($id), 'is_object', 'Prueba del modelo producto_model', 'se prueba el metodo getById');

        $data["prueba"] = $this->unit->report();
        $data["producto"] = $producto;
        $data["contenido"] = "nueva_view";
        $this->load->view('template/render_view', $data);
    }

    /**
     * Guarda una orden
     *
     * @return void
     */
    public function guardar(): void
    {
        //consultamos los datos del producto
        $producto = $this->producto_model->getById($_POST["producto_id"]);

        //creamos la orden
        $dataOrden["producto_id"] = $producto->id;
        $dataOrden["customer_name"] = $_POST["nombre"];
        $dataOrden["customer_email"] = $_POST["email"];
        $dataOrden["customer_mobile"] = $_POST["celular"];
        $resultOrden = $this->orden_model->guardar($dataOrden);
        if ($resultOrden["status"]) {
            //obtenemos la conexion a placetopay
            $placetopay = $this->getClient();

            // configuramos los datos
            $reference = "Compra de " . $producto->nombre . " - " . $producto->id;
            $request = [
                'payment' => [
                    'reference' => $reference,
                    'description' => 'Testing payment',
                    'amount' => [
                        'currency' => 'COP',
                        'total' => $producto->precio,
                    ],
                ],
                'expiration' => date('c', strtotime('+2 days')),
                'returnUrl' => base_url() . 'orden/response/' . $resultOrden["id"],
                'ipAddress' => $this->input->ip_address(),
                'userAgent' => $this->agent->browser(),
            ];

            //realizamos la solicitud
            $response = $placetopay->request($request);

            //si es satisfactoria
            if ($response->isSuccessful()) {
                //actualizamos los campos request_id y process_url de la orden
                $dataUpdateOrden["request_id"] = $response->requestId();
                $dataUpdateOrden["process_url"] = $response->processUrl();
                $whereUpdateOrden["id"] = $resultOrden["id"];
                $this->orden_model->actualizar($dataUpdateOrden, $whereUpdateOrden);

                // STORE THE $response->requestId() and $response->processUrl() on your DB associated with the payment order
                // Redirect the client to the processUrl or display it on the JS extension
                header('Location: ' . $response->processUrl());
            } else {
                // There was some error so check the message and log it
                $data["mensaje"] = $response->status()->message();
                $data["contenido"] = "guardar_view";
                $this->load->view('template/render_view', $data);
            }
        } else {
            $data["mensaje"] = "No se pudo almacenar la orden";
            $data["contenido"] = "guardar_view";
            $this->load->view('template/render_view', $data);
        }
    }

    /**
     * Metodo donde se recibe la respuesta de la solicitud de pago
     *
     * @param int $id           Id de la orden
     *
     * @return array
     */
    public function response(int $id): void
    {
        //consultamos los datos de la orden
        $orden = $this->orden_model->getById($id);

        //obtenemos la conexion a placetopay
        $placetopay = $this->getClient();

        //consultamos los datos de la transaccion
        $response = $placetopay->query($orden->request_id);

        if ($response->isSuccessful()) {
            // In order to use the functions please refer to the Dnetix\Redirection\Message\RedirectInformation class

            if ($response->status()->isApproved()) {
                // The payment has been approved
                //actualizamos el campo status a PAYED
                $dataUpdateOrden["status"] = "PAYED";

            } elseif ($response->status()->isRejected()) {
                //actualizamos el campo status a REJECTED
                $dataUpdateOrden["status"] = "REJECTED";
            }
            $whereUpdateOrden["id"] = $id;
            $this->orden_model->actualizar($dataUpdateOrden, $whereUpdateOrden);
            redirect("orden/all");
        } else {
            // There was some error with the connection so check the message
            $data["mensaje"] = $response->status()->message();
            $data["contenido"] = "guardar_view";
            $this->load->view('template/render_view', $data);
        }
    }

    /**
     * Muestra todas las ordenes
     *
     * @return void
     *
     */
    public function all(): void
    {
        //consultamos todas las ordenes
        $ordenes = $this->orden_model->getAll();
        $this->unit->run($this->producto_model->getAll(), 'is_array', 'Prueba del modelo orden_model', 'se prueba el metodo getAll');

        $data["prueba"] = $this->unit->report();
        $data["ordenes"] = $ordenes;
        $data["contenido"] = "ordenes_view";
        $this->load->view('template/render_view', $data);
    }

    /**
     * Comprueba la solicitud
     *
     * @param int $id           Id de la orden
     *
     * @return array
     */
    public function comprobar(int $id): void
    {
        //consultamos los datos de la orden
        $orden = $this->orden_model->getById($id);

        //redireccionamos a la url de procesamiento
        header('Location: ' . $orden->process_url);
    }

    /**
     * Prepara los datos de la firma.
     *
     *
     */
    private function getClient()
    {
        return new Dnetix\Redirection\PlacetoPay([
            'login' => LOGIN_PLACETOPAY,
            'tranKey' => TRANKEY_PLACETOPAY,
            'url' => URL_DEV_PLACETOPAY,
        ]);
    }

}
