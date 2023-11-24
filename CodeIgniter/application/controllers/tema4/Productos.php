<?php
class Productos extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->listar();
	}
	public function verFicha($id=null){
		$this->load->model("EjerciciosT3_model");
		$this->load->helper('form');

		if($id==null){
			$this->load->view("ejercicios/tema4/ficha");
			return;
		}

		$data['producto'] = $this->EjerciciosT3_model->getProductobyID($id);
		$this->load->view("ejercicios/tema4/ficha",$data);

	}
	public function listar(){
		$this->load->model("EjerciciosT3_model");
		$this->load->library('table');
		$this->load->library('pagination');
		$this->load->helper('url');
		$this->load->helper('form');

		//PAGINACION
		$tampagina=3;
		$config= array();
		$config['base_url'] = base_url("tema4/Productos/listar");
		$config['total_rows'] = $this->EjerciciosT3_model->getCountProducts();
		$config['per_page'] = $tampagina;
		$config['uri_segment'] = 4;
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);

		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
		$data["links"] = $this->pagination->create_links();

		//TABLA
		$numproductos = $this->EjerciciosT3_model->getCountProducts();
		$productos = $this->EjerciciosT3_model->getProductsPagedWithID($tampagina, $page);

		$this->table->set_heading("NOMBRE","MARCA","CANTIDAD","PRECIO","CATEGORIA");
		foreach ($productos as $producto){
			$id_link = anchor('tema4/productos/verFicha/'.$producto['PK_ID_PRODUCTO'], $producto['NOMBRE']);
			$this->table->add_row($id_link,$producto['MARCA'], $producto['CANTIDAD'], $producto['PRECIO'], $producto['CATEGORIA']);
			$numproductos++;
		}

		$template = array('table_open' => '<table border="1px solid black" cellpadding="4" cellspacing="0">',);
		$this->table->set_template($template);

		$data['tabla_productos'] = $this->table->generate();
		$data['numproductos'] = $numproductos;
		$data['pagina'] = $page;
		$data['tampagina'] = $tampagina;

		$this->load->view("ejercicios/tema4/listado.php", $data);
	}
	public function guardar(){
		$this->load->model("EjerciciosT3_model");
		$this->load->library('form_validation');

		$this->form_validation->set_rules('txNombre','Nombre','required');
		$this->form_validation->set_rules('txPrecio','Precio','required|greater_than[10]|less_than_equal_to[999.99]');
		$this->form_validation->set_rules('txCantidad','Cantidad','required|greater_than[0]');
		$this->form_validation->set_rules('selCategoria','Categoria','required');//Valido porque value por defecto es ""

		$nombre = $this->input->post('txNombre');
		$marca = $this->input->post('txMarca');
		$precio = $this->input->post('txPrecio');
		$cantidad = $this->input->post('txCantidad');
		$categoria = $this->input->post('selCategoria');
		if($this->form_validation->run() == FALSE){
			$this->load->view('ejercicios/tema4/ficha');
			return;
		}
		else{
			$newprod = array(
				'nombre' => $nombre,
				'marca' => $marca,
				'precio' => $precio,
				'cantidad' => $cantidad,
				'categoria' => $categoria
			);
			$this->EjerciciosT3_model->addNewProducto($newprod);
			$this->guardarOk();
		}
	}
	public function guardarOk(){
		echo "Guardado correctamente";
		die;
	}
	public function newProducto(){
		$this->load->model("EjerciciosT3_model");
		$this->load->library('form_validation');
		$this->load->view('ejercicios/tema4/ficha');
	}
	public function eliminar($id=null){
		$this->load->model("EjerciciosT3_model");
		$this->load->helper('form');
		if( $id==null){
			echo "No se ha podido eliminar el producto";
		}
		else{
			$this->EjerciciosT3_model->deleteProductoByID($id);
			echo "Producto eliminado correctamente";
		}


		$btVolver = array('name' => 'btEliminar' , 'id' => 'btVolver','class'=>'button' , 'value'=>'Volver al listado');
		echo form_open("/tema4/Productos/listar");
		echo form_submit($btVolver);
		echo form_close();

	}
}
