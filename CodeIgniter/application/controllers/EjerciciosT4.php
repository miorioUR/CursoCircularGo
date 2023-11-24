<?php
class EjerciciosT4 extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->verFicha();
	}
	public function verFicha(){
		$this->load->model("EjerciciosT3_model");
		$this->load->helper('form');

		$data['attributes'] = array('id' => 'formficha', 'name' => 'ficha', 'class' => 'forms', 'method' => 'POST');
		$data['txNombre'] = array('name' => 'txNombre' , 'id' => 'txNombre','class'=>'text');
		$data['txMarca'] = array('name' => 'txMarca' , 'id' => 'txMarca','class'=>'text');
		$data['txPrecio'] = array('name' => 'txPrecio' , 'id' => 'txPrecio','class'=>'text');
		$data['txCantidad'] = array('name' => 'txCantidad' , 'id' => 'txCantidad','class'=>'text');
		$categorias = $this->EjerciciosT3_model->getCategorias();
		$optCategoria = array('' => 'Elija una categoria');
		foreach($categorias as $cat){
			$optCategoria += array($cat['NOMBRE'] => $cat['NOMBRE']);
		}
		$data['optCategoria'] = $optCategoria;
		$data['selCategoria'] = array('name' => 'selCategoria' , 'id' => 'selCategoria', 'class' => 'select');
		$data['btSubmit'] = array('name' => 'btSubmit' , 'id' => 'btSubmit','class'=>'button' , 'value'=>'Guardar');
		$this->load->view("ejercicios/tema4/ficha.php",$data);
	}
}
