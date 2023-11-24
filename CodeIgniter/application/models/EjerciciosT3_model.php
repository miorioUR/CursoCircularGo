<?php
class EjerciciosT3_model extends CI_Model
{
	public function getProductos(){
		return $this->db->get("PRODUCTO")->result_array();
	}
	public function getCategorias(){
		return $this->db->get("CATEGORIA")->result_array();
	}
	public function getProductosOrderedByNombre(){
		$this->db->join("CATEGORIA", "PRODUCTO.FK_ID_CATEGORIA = CATEGORIA.PK_ID_CATEGORIA");
		$this->db->select("PRODUCTO.NOMBRE, coalesce(PRODUCTO.MARCA,'-'), PRODUCTO.CANTIDAD, PRODUCTO.PRECIO, CATEGORIA.NOMBRE as 'CATEGORIA'");
		$this->db->order_by("PRODUCTO.NOMBRE","ASC");
		return $this->db->get("PRODUCTO")->result_array();
	}
	public function getZapatillas(){
		$this->db->join("CATEGORIA", "PRODUCTO.FK_ID_CATEGORIA = CATEGORIA.PK_ID_CATEGORIA");
		$this->db->where("CATEGORIA.NOMBRE","Zapatillas");
		$this->db->select("PRODUCTO.*");
		return $this->db->get("PRODUCTO")->result_array();

	}
	public function getZapa(){
		$this->db->like("PRODUCTO.NOMBRE","Zapa","after");
		return $this->db->get("PRODUCTO")->result_array();
	}
	public function avgPrecio(){
		$this->db->select_avg("PRECIO");
		return $this->db->get("PRODUCTO")->result_array();
	}
	public function numProductosByCategoria(){
		$this->db->select("FK_ID_CATEGORIA, COUNT(FK_ID_CATEGORIA) as num");
		$this->db->group_by("FK_ID_CATEGORIA");
		return $this->db->get("PRODUCTO")->result_array();
	}
	public function getCategoriasComunes(){
		$this->db->group_by("FK_ID_CATEGORIA");
		$this->db->having("count(PK_ID_PRODUCTO) > 10");
		$this->db->select("FK_ID_CATEGORIA");
		return $this->db->get("PRODUCTO")->result_array();
	}
	public function addNewProductos(){
		$newprod1 = array(
			'PK_ID_PRODUCTO' => 8,
			'NOMBRE' => 'Crocs Chuerk',
			'MARCA' => 'Crocs',
			'FK_ID_CATEGORIA' => 1,
			'CANTIDAD' => 10,
			'PRECIO' => 25.95
		);
		$newprod2 = array(
			'PK_ID_PRODUCTO' => 9,
			'NOMBRE' => 'Crocs Rayo McQueen',
			'MARCA' => 'Crocs',
			'FK_ID_CATEGORIA' => 1,
			'CANTIDAD' => 95,
			'PRECIO' => 25.95
		);
		$this->db->insert("PRODUCTO",$newprod1);
		$this->db->insert("PRODUCTO",$newprod2);
	}
	public function updateProducto7(){
		$prod7 = array(
			'MARCA' => 'Jack & Jones',
			'CANTIDAD' => '8',
			'PRECIO' => '350.99'
		);
		$this->db->where("PK_ID_PRODUCTO", 7);
		$this->db->update("PRODUCTO",$prod7);
	}


	public function getCountProducts(){
		return $this->db->count_all_results("PRODUCTO");
	}
	public function getProductsPaged($limit,$offset){
		$this->db->join("CATEGORIA", "PRODUCTO.FK_ID_CATEGORIA = CATEGORIA.PK_ID_CATEGORIA");
		$this->db->select("PRODUCTO.NOMBRE, PRODUCTO.MARCA, PRODUCTO.CANTIDAD, PRODUCTO.PRECIO, CATEGORIA.NOMBRE as 'CATEGORIA'");
		$this->db->order_by("PRODUCTO.NOMBRE","ASC");
		return $this->db->get("PRODUCTO",$limit,$offset)->result_array();
	}
	public function getProductsPagedWithID($limit,$offset){
		$this->db->join("CATEGORIA", "PRODUCTO.FK_ID_CATEGORIA = CATEGORIA.PK_ID_CATEGORIA");
		$this->db->select("PRODUCTO.PK_ID_PRODUCTO, PRODUCTO.NOMBRE, PRODUCTO.MARCA, PRODUCTO.CANTIDAD, PRODUCTO.PRECIO, CATEGORIA.NOMBRE as 'CATEGORIA'");
		$this->db->order_by("PRODUCTO.NOMBRE","ASC");
		return $this->db->get("PRODUCTO",$limit,$offset)->result_array();
	}
	public function getProductobyID($id){
		$this->db->where("PK_ID_PRODUCTO",$id);
		return $this->db->get("PRODUCTO")->result_array();
	}
	public function addNewProducto($producto){
		$newprod = array(
			'NOMBRE' => $producto['nombre'],
			'MARCA' => $producto['marca'],
			'FK_ID_CATEGORIA' => $producto['categoria'],
			'CANTIDAD' => $producto['cantidad'],
			'PRECIO' => $producto['precio']
		);
		$this->db->insert("PRODUCTO",$newprod);
	}
	public function deleteProductoByID($id){
		$this->db->where('PK_ID_PRODUCTO', $id);
		$this->db->delete('PRODUCTO');
	}
}
