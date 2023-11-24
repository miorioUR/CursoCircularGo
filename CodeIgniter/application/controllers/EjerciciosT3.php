<?php

class EjerciciosT3 extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index(){
		$this->ejercicio4_t3();
	}
	public function ejercicio1_t3(){
		$this->load->model("EjerciciosT3_model");

		$data["all_productos"] = $this->EjerciciosT3_model->getProductos();
		$data["all_categorias"] = $this->EjerciciosT3_model->getCategorias();
		$data["all_productos_by_nombre"] = $this->EjerciciosT3_model->getProductosOrderedByNombre();



		$data["all_zapatillas"] = $this->EjerciciosT3_model->getZapatillas();
		$data["zapa"] = $this->EjerciciosT3_model->getZapa();
		$data["avg_precio"] = $this->EjerciciosT3_model->avgPrecio();
		$data["num_productos_by_categoria"] = $this->EjerciciosT3_model->numProductosByCategoria();
		$data["categorias_comunes"] = $this->EjerciciosT3_model->getCategoriasComunes();

		//$this->EjerciciosT3_model->addNewProductos();
		//$this->EjerciciosT3_model->updateProducto7();

		$this->load->view("ejercicios/tema3_1.php", $data);
	}
	public function ejercicio2_t3(){
		$this->load->model("EjerciciosT3_model");
		$this->load->library('table');

		$tabla_productos = array(array("NOMBRE","MARCA","CANTIDAD","PRECIO","CATEGORIA"));
		$content = $this->EjerciciosT3_model->getProductosOrderedByNombre();
		foreach ($content as $row){
			$tabla_productos[] = $row;
		}
		$template = array('table_open' => '<table border="1px solid black" cellpadding="4" cellspacing="0">',);
		$this->table->set_template($template);

		$data["all_productos_by_nombre"] = $this->table->generate($tabla_productos);

		$this->load->view("ejercicios/tema3_2.php", $data);
	}
	public function ejercicio3_t3(){
		$this->load->model("EjerciciosT3_model");
		$this->load->library('table');
		$this->load->library('pagination');
		$this->load->helper('url');

		$config= array();
		$config["base_url"] = base_url("EjerciciosT3/ejercicio3_t3");
		$config['total_rows'] = $this->EjerciciosT3_model->getCountProducts();
		$config['per_page'] = 2;
		$config["uri_segment"] = 3;
		$this->pagination->initialize($config);

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data["links"] = $this->pagination->create_links();

		$tabla_productos = array(array("NOMBRE","MARCA","CANTIDAD","PRECIO","CATEGORIA"));
		$content = $this->EjerciciosT3_model->getProductsPaged($config["per_page"], $page);
		foreach ($content as $row){
			$tabla_productos[] = $row;
		}
		$template = array('table_open' => '<table border="1px solid black" cellpadding="4" cellspacing="0">',);
		$this->table->set_template($template);

		$data["productos"] = $this->table->generate($tabla_productos);
		$this->load->view("ejercicios/tema3_3.php", $data);
	}
	public function ejercicio4_t3(){
		$this->load->model("EjerciciosT3_model");
		$this->load->helper('form');

		$data['text1'] = array('id' => 'text1','class'=>'text');
		$data['text2'] = array('id' => 'text2','class'=>'text');
		$data['bicho1'] = array('id' => 'bicho1','class'=>'check');
		$data['bicho2'] = array('id' => 'bicho2','class'=>'check');
		$data['bicho3'] = array('id' => 'bicho3','class'=>'check');
		$data['status1'] = array('id' => 'status1','class'=>'radio');
		$data['status2'] = array('id' => 'status2','class'=>'radio');
		$data['status3'] = array('id' => 'status3','class'=>'radio');
		$data['attributes'] = array('id' => 'idform', 'name' => 'form', 'class' => 'forms', 'method' => 'POST');
		$data['hidden'] = rand(0,999);
		$data['textarea_atts'] = array('name' => 'textarea1','id' => 'text3', 'value' => 'texto_grande', 'cols' => 50, 'rows' => 8);
		$data['dropdown_opts'] = array('a' => 'opcion a', 'b' => 'opcion b', 'c' => 'opcion c', 'd' => 'opcion d');
		$data['boton_js'] = "onClick = 'alert( \" Evento onclick ACTIVADO \")'";
		$this->load->view("ejercicios/tema3_4.php", $data);
	}
}
