<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 #------------------------------------    
    # Author: Bdtask Ltd
    # Author link: https://www.bdtask.com/
    # Dynamic style php file
    # Developed by :Isahaq
    #------------------------------------    

class Invoice_model extends CI_Model {


 public function customer_list(){
     $query = $this->db->select('*')
                ->from('customer_information')
                ->where('status', '1')
                ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
 }

    public function tax_fileds(){
        return $taxfield = $this->db->select('tax_name,default_value')
                ->from('tax_settings')
                ->get()
                ->result_array();
    }

        public function pos_customer_setup() {
        $query = $this->db->select('*')
                ->from('customer_information')
                ->where('customer_name', 'Walking Customer')
                ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
 
    public function allproduct(){
        $this->db->select('*');
        $this->db->from('product_information');
		$this->db->where('temporal', '0');
        $this->db->order_by('product_name','asc');				  
        //$this->db->limit(30);
        $query   = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
    }
	
   public function todays_invoice(){
        $this->db->select('a.*,b.customer_name');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
        $this->db->where('a.date', date('Y-m-d'));
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

        public function customer_dropdown()
    {
        $data = $this->db->select("*")
            ->from('customer_information')
            ->get()
            ->result();

        $list[''] = 'Select Customer';
        if (!empty($data)) {
            foreach($data as $value)
                $list[$value->customer_id] = $value->customer_name;
            return $list;
        } else {
            return false; 
        }
    }

        public function customer_search($customer_id){
        $query = $this->db->select('*')
                          ->from('customer_information')
                          ->group_start()
                          ->like('customer_name', $customer_id)
                          ->or_like('customer_mobile', $customer_id)
                          ->group_end()
                          ->limit(30)
                          ->get();
                          if ($query->num_rows() > 0) {
                              return $query->result_array();  
                          }
                          return false;
    }

      public function count_invoice() {
        return $this->db->count_all("invoice");
    }

     public function getInvoiceList($postData=null){
         $response = array();
         $usertype = $this->session->userdata('user_type');
         $fromdate = $this->input->post('fromdate',TRUE);
         $todate   = $this->input->post('todate',TRUE);
         if(!empty($fromdate)){
            $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
         }else{
            $datbetween = "";
         }
         ## Read value
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         ## Search 
         $searchQuery = "";
         if($searchValue != ''){
            $searchQuery = " (b.customer_name like '%".$searchValue."%' or a.invoice like '%".$searchValue."%' or a.date like'%".$searchValue."%' or a.invoice_id like'%".$searchValue."%' or u.first_name like'%".$searchValue."%'or u.last_name like'%".$searchValue."%')";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
          if($searchValue != '')
          $this->db->where($searchQuery);
          
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
         if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
            $this->db->where($searchQuery);
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("a.*,b.customer_name,u.first_name,u.last_name");
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         $this->db->where($searchQuery);
       
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
  
         foreach($records as $record ){
			 
			 
			 
			 
			 
			 
			 $status = '';	 
			 $this->db->select('a.total_tax,
								a.*,
								b.*,
								c.*,
								d.product_id,
								d.product_name,
								d.product_details,
								d.unit,
								d.product_model,
								a.paid_amount as paid_amount,
								a.due_amount as due_amount'
							  );
			 $this->db->from('invoice a');
			 $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
			 $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
			 $this->db->join('product_information d', 'd.product_id = c.product_id');
			 $this->db->where('a.invoice_id', $record->invoice_id);
			 $this->db->where('c.quantity >', 0);
			 $query = $this->db->get();			
			 $detalles =  $query->result_array();
				 
			 $ne = count($detalles);
			 
			 if($ne > 1){				 
				$status = '<table>';				 
				foreach ($detalles as $arreglo){
					
					 if($arreglo['florist_status'] == '0' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-danger">Pendiente</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-warning">En proceso de entrega</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '1'){
						 $stt = '<span class="label label-success">Entregado</span>';
					 }
					
					$status.= '<tr>
					 				<td>'.$arreglo['product_name'].'</td>
									<td>'.$stt.'</td>
					 	  	   <tr>';
				}
				$status.= '</table>';
			 
			 }else{
				 
				 if($detalles[0]['florist_status'] == '0' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-danger">Pendiente</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-warning">En proceso de entrega</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '1'){
					 $status = '<span class="label label-success">Entregado</span>';
				 }
			 }
			 
			 
			 if($record->cancelado=='1'){
			 	$status = '<span class="label label-danger">Venta cancelada</span><br>'.$record->motivo_cancelacion;
			 }
			 
			 
          	$button = '';
          	$base_url = base_url();
          	$jsaction = "return confirm('Are You Sure ?')";	
			 
			 
			$user_type = $this->session->userdata('user_type');
			 
			 
			if($record->cancelado=='1'){
				
				if($user_type=='1'){
				
				$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
				}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';
				
				
			 	
			}else{
			
			
						 
			if($this->session->userdata('level')=='1'){
				$button .='  <a href="'.$base_url.'invoice_details_cs/'.$record->invoice_id.'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Cliente"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';		
			}
			 
			 $button .='  <a href="'.$base_url.'invoice_details_del/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Reparto"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
           	$button .='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Taller"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'invoice_pad_print/'.$record->invoice_id.'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pad_print').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'pos_print/'.$record->invoice_id.'" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pos_invoice').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';
			 
          	$button .='  <a href="'.$base_url.'download_invoice/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('download').'"><i class="fa fa-download"></i></a>';
				
			if($user_type=='1'){			 
			 	$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
			}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';

			  if($this->permission1->method('manage_invoice','update')->access()){
				 //$button .=' <a href="'.$base_url.'invoice_edit/'.$record->invoice_id.'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('update').'"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
			 }      
				
				
			}

          	$details ='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
            $data[] = array( 
                'sl'               =>$sl,
                'invoice'          =>$details,
                'salesman'         =>$record->first_name.' '.$record->last_name,
                'customer_name'    =>$record->customer_name,
                'final_date'       =>date("d-M-Y",strtotime($record->date)),
                'total_amount'     =>$record->total_amount,
				'tipo_pago'        =>$record->tipo_pago,
				'status'		   =>$status,
                'button'           =>$button,
				
                
            ); 
            $sl++;
			 
			 
			 
			 
			 
			 
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }
	
	
	
	
	
	
	
	
	
	public function getInvoiceListPc($postData=null){
         $response = array();
         $usertype = $this->session->userdata('user_type');
         $fromdate = $this->input->post('fromdate',TRUE);
         $todate   = $this->input->post('todate',TRUE);
         if(!empty($fromdate)){
            $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
         }else{
            $datbetween = "";
         }
         ## Read value
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         ## Search 
         $searchQuery = "";
         if($searchValue != ''){
            $searchQuery = " (b.customer_name like '%".$searchValue."%' or a.invoice like '%".$searchValue."%' or a.date like'%".$searchValue."%' or a.invoice_id like'%".$searchValue."%' or u.first_name like'%".$searchValue."%'or u.last_name like'%".$searchValue."%')";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
		 $this->db->where('a.tipo_pago', 'Credito Interno');
		 $this->db->or_where('a.tipo_pago', 'Anticipo');
		
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
          if($searchValue != '')
          $this->db->where($searchQuery);
          
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
		 $this->db->where('a.tipo_pago', 'Credito Interno');
		 $this->db->or_where('a.tipo_pago', 'Anticipo');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
         if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
            $this->db->where($searchQuery);
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("a.*,b.customer_name,u.first_name,u.last_name");
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
		 $this->db->where('a.tipo_pago', 'Credito Interno');
		 $this->db->or_where('a.tipo_pago', 'Anticipo');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         $this->db->where($searchQuery);
       
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
  
         foreach($records as $record ){
			 
			 $status = '';	 
			 $this->db->select('a.total_tax,
								a.*,
								b.*,
								c.*,
								d.product_id,
								d.product_name,
								d.product_details,
								d.unit,
								d.product_model,
								a.paid_amount as paid_amount,
								a.due_amount as due_amount'
							  );
			 $this->db->from('invoice a');
			 $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
			 $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
			 $this->db->join('product_information d', 'd.product_id = c.product_id');
			 $this->db->where('a.invoice_id', $record->invoice_id);
			 $this->db->where('c.quantity >', 0);
			 $query = $this->db->get();			
			 $detalles =  $query->result_array();
				 
			 $ne = count($detalles);
			 
			 if($ne > 1){				 
				$status = '<table>';				 
				foreach ($detalles as $arreglo){
					
					 if($arreglo['florist_status'] == '0' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-danger">Pendiente</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-warning">En proceso de entrega</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '1'){
						 $stt = '<span class="label label-success">Entregado</span>';
					 }
					
					$status.= '<tr>
					 				<td>'.$arreglo['product_name'].'</td>
									<td>'.$stt.'</td>
					 	  	   <tr>';
				}
				$status.= '</table>';
			 
			 }else{
				 
				 if($detalles[0]['florist_status'] == '0' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-danger">Pendiente</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-warning">En proceso de entrega</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '1'){
					 $status = '<span class="label label-success">Entregado</span>';
				 }
			 }
			 
			 
          	$button = '';
          	$base_url = base_url();
          	$jsaction = "return confirm('Are You Sure ?')";		
			 
			if($this->session->userdata('level')=='1'){
				$button .='  <a href="'.$base_url.'invoice_details_cs/'.$record->invoice_id.'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('invoice').'"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';		
			}
           	$button .='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('invoice').'"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
			 
			 $button .=' <button inv="'.$record->invoice_id.'"  class="btn btn-default btn-sm mover_pagado" data-toggle="tooltip" data-placement="left" title="Cambiar a pagado">
			  Cambiar a pagado
			</button>';

			 
			 
			 

         	//$button .='  <a href="'.$base_url.'invoice_pad_print/'.$record->invoice_id.'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pad_print').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';

         	//$button .='  <a href="'.$base_url.'pos_print/'.$record->invoice_id.'" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pos_invoice').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';
          	
			 //$button .='  <a href="'.$base_url.'download_invoice/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('download').'"><i class="fa fa-download"></i></a>';

			  if($this->permission1->method('manage_invoice','update')->access()){
				 //$button .=' <a href="'.$base_url.'invoice_edit/'.$record->invoice_id.'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('update').'"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
			 }      

          	$details ='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
            $data[] = array( 
                'sl'               =>$sl,
                'invoice'          =>$details,
                'salesman'         =>$record->first_name.' '.$record->last_name,
                'customer_name'    =>$record->customer_name,
				'tipo_pago'    	   =>$record->tipo_pago,
                'final_date'       =>date("d-M-Y",strtotime($record->date)),
                'total_amount'     =>$record->total_amount,
				'status'		   =>$status,
                'button'           =>$button,
                
            ); 
            $sl++;
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }
	
	
	
	
	
	
	
	public function getArrangementList($postData=null){
         $response = array();
         
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         
         ## Total number of records without filtering
         $this->db->select('a.invoice as factura, a.cancelado as cancelado, a.invoice_id as invoice_id, a.invoice as invoice, a.date as fecha, u.first_name as nombre_vendedor, u.last_name as apellido_vendedor, a.delivery_multiple as multiple, IF(a.delivery_multiple="1", b.destinatario, a.destinatario) as recibe, IF(a.delivery_multiple="1", b.fecha_entrega, a.fecha_entrega) as entrega, IF(a.delivery_multiple="1", b.direccion, a.direccion) as direccion, IF(a.delivery_multiple="1", b.repartidor, a.repartidor_caja) as id_repartidor');
         $this->db->from('invoice a');
         $this->db->join('invoice_details x', 'x.invoice_id = a.invoice_id','left');
         $this->db->join('invoice_caja b', 'b.invoice_id = x.invoice_id AND b.id_arreglo = x.product_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         //$this->db->having('id_repartidor', $this->session->userdata('fdid'));		
		 $this->db->having(array('id_repartidor =' => $this->session->userdata('fdid'), 'cancelado = ' => '0'));

         $records = $this->db->get()->result();
         $totalRecords = count($records);

         ## Total number of record with filtering
         $this->db->select('a.invoice as factura, a.cancelado as cancelado, a.invoice_id as invoice_id, a.invoice as invoice, a.date as fecha, u.first_name as nombre_vendedor, u.last_name as apellido_vendedor, a.delivery_multiple as multiple, IF(a.delivery_multiple="1", b.destinatario, a.destinatario) as recibe, IF(a.delivery_multiple="1", b.fecha_entrega, a.fecha_entrega) as entrega, IF(a.delivery_multiple="1", b.direccion, a.direccion) as direccion, IF(a.delivery_multiple="1", b.repartidor, a.repartidor_caja) as id_repartidor');
         $this->db->from('invoice a');
         $this->db->join('invoice_details x', 'x.invoice_id = a.invoice_id','left');
         $this->db->join('invoice_caja b', 'b.invoice_id = x.invoice_id AND b.id_arreglo = x.product_id','left');		
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         //$this->db->having('id_repartidor', $this->session->userdata('fdid'));
		 $this->db->having(array('id_repartidor =' => $this->session->userdata('fdid'), 'cancelado = ' => '0'));
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = count($records);

         ## Fetch records
         $this->db->select('a.invoice as factura, a.cancelado as cancelado, a.tipo_pago as tipo_pago, x.product_id as id_arreglo, a.invoice_id as invoice_id, a.invoice as invoice, a.date as fecha, u.first_name as nombre_vendedor, u.last_name as apellido_vendedor, a.delivery_multiple as multiple, IF(a.delivery_multiple="1", b.destinatario, a.destinatario) as recibe, IF(a.delivery_multiple="1", b.fecha_entrega, a.fecha_entrega) as entrega, IF(a.delivery_multiple="1", b.hora_entrega, a.hora_entrega) as hora_entrega, IF(a.delivery_multiple="1", b.direccion, a.direccion) as direccion, IF(a.delivery_multiple="1", b.repartidor, a.repartidor_caja) as id_repartidor, x.delivery_status as status');
         $this->db->from('invoice a');
		 $this->db->join('invoice_details x', 'x.invoice_id = a.invoice_id','left');
         $this->db->join('invoice_caja b', 'b.invoice_id = x.invoice_id AND b.id_arreglo = x.product_id','left');		
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         //$this->db->having('id_repartidor', $this->session->userdata('fdid'));
		 $this->db->having(array('id_repartidor =' => $this->session->userdata('fdid'), 'cancelado = ' => '0'));

         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
  
         foreach($records as $record ){
          $button = '';
          $base_url = base_url();
          $jsaction = "return confirm('Are You Sure ?')";

          $button .='  <a href="'.$base_url.'invoice_details_del/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('invoice').'"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
		  

          	$details ='  <a href="'.$base_url.'invoice_details_del/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
			$this->db->select('*');
         	$this->db->from('deliveryman');
			$this->db->where('id', $record->id_repartidor);
			$deliveryman = $this->db->get()->result();
			$dm = $deliveryman[0];
			 
			if($record->status=='0'){
				$button .='  <button invoice="'.$record->invoice_id.'" product="'.$record->id_arreglo.'" class="btn btn-primary btn-sm entregar" data-toggle="tooltip" data-placement="left" title="Entregar"><i class="fa fa-check" aria-hidden="true"></i></button>';
				
				$status = 'Pendiente';
			}else if($record->status=='1'){
				
				$status = 'Entregado';
			}
			 
            $data[] = array( 
                'sl'        	=>$sl,
                'factura'   	=>$details,
                'vendedor'  	=>$record->nombre_vendedor.' '.$record->apellido_vendedor,
                'fecha'     	=>date("d-M-Y",strtotime($record->fecha)),
                'recibe'    	=>$record->recibe,                
                'entrega'   	=>date("d/m/Y",strtotime($record->entrega)).'<br>'.$record->hora_entrega,
                'direccion' 	=>$record->direccion,
				'tipo_pago' 	=>$record->tipo_pago,
                'repartidor'	=>$dm->first_name.' '.$dm->last_name,
				'status' 		=>$status,
                'button'        =>$button,
                
            ); 
            $sl++;
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }
	
	
	
	
	
	
	
	public function getArrangementList1($postData=null){
         $response = array();
         
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         
         ## Total number of records without filtering
         $this->db->select('a.invoice as factura, a.cancelado as cancelado, a.invoice_id as invoice_id, a.invoice as invoice, a.date as fecha, u.first_name as nombre_vendedor, u.last_name as apellido_vendedor, a.delivery_multiple as multiple, IF(a.delivery_multiple="1", b.destinatario, a.destinatario) as recibe, IF(a.delivery_multiple="1", b.fecha_entrega, a.fecha_entrega) as entrega, IF(a.delivery_multiple="1", b.direccion, a.direccion) as direccion, IF(a.delivery_multiple="1", b.florista, a.florista_taller) as id_florista');
         $this->db->from('invoice a');
		 $this->db->join('invoice_details x', 'x.invoice_id = a.invoice_id','left');
         $this->db->join('invoice_taller b', 'b.invoice_id = x.invoice_id AND b.id_arreglo = x.product_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         //$this->db->having('id_florista', $this->session->userdata('fdid'));
		$this->db->having('cancelado', '0');

         $records = $this->db->get()->result();
         $totalRecords = count($records);

         ## Total number of record with filtering
         $this->db->select('a.invoice as factura, a.cancelado as cancelado, a.invoice_id as invoice_id, a.invoice as invoice, a.date as fecha, u.first_name as nombre_vendedor, u.last_name as apellido_vendedor, a.delivery_multiple as multiple, IF(a.delivery_multiple="1", b.destinatario, a.destinatario) as recibe, IF(a.delivery_multiple="1", b.fecha_entrega, a.fecha_entrega) as entrega, IF(a.delivery_multiple="1", b.direccion, a.direccion) as direccion, IF(a.delivery_multiple="1", b.florista, a.florista_taller) as id_florista');
         $this->db->from('invoice a');
         $this->db->join('invoice_details x', 'x.invoice_id = a.invoice_id','left');
         $this->db->join('invoice_taller b', 'b.invoice_id = x.invoice_id AND b.id_arreglo = x.product_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         //$this->db->having('id_florista', $this->session->userdata('fdid'));
		$this->db->having('cancelado', '0');
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = count($records);

         ## Fetch records
         $this->db->select('a.invoice as factura, a.cancelado as cancelado, a.tipo_pago as tipo_pago, x.id as id_detail, x.product_id as id_arreglo, a.invoice_id as invoice_id, a.invoice as invoice, a.date as fecha, u.first_name as nombre_vendedor, u.last_name as apellido_vendedor, a.delivery_multiple as multiple, IF(a.delivery_multiple="1", b.destinatario, a.destinatario) as recibe, IF(a.delivery_multiple="1", b.fecha_entrega, a.fecha_entrega) as entrega, IF(a.delivery_multiple="1", b.hora_entrega, a.hora_entrega) as hora_entrega, IF(a.delivery_multiple="1", b.direccion, a.direccion) as direccion, IF(a.delivery_multiple="1", b.florista, a.florista_taller) as id_florista, x.florist_status as status');
         $this->db->from('invoice a');
         $this->db->join('invoice_details x', 'x.invoice_id = a.invoice_id','left');
         $this->db->join('invoice_taller b', 'b.invoice_id = x.invoice_id AND b.id_arreglo = x.product_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         //$this->db->having('id_florista', $this->session->userdata('fdid'));
		 $this->db->having('cancelado', '0');

         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
		
		 
  		$cont = 0;
         foreach($records as $record ){
			 
		  
		  //if($record->cancelado!='1'){
			  
			  $cont++;
		  
		  
			 
			 
          $button = '';
          $base_url = base_url();
          $jsaction = "return confirm('Are You Sure ?')";

          $button .='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('invoice').'"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
		  

          $details ='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
			$this->db->select('*');
         	$this->db->from('florist');
			$this->db->where('id', $record->id_florista);
			$deliveryman = $this->db->get()->result();
			$dm = $deliveryman[0];
			 
			if($record->status=='0'){
				$button .='  <button invoice="'.$record->invoice_id.'" product="'.$record->id_arreglo.'" class="btn btn-primary btn-sm florista" data-toggle="tooltip" data-placement="left" title="Terminado"><i class="fa fa-check" aria-hidden="true"></i></button>';
				
				$status = 'Pendiente';
			}else if($record->status=='1'){
				
				$status = 'Terminado';
			}
			
			 
			$this->db->select('*');
         	$this->db->from('product_information');
			$this->db->where('product_id', $record->id_arreglo);
			$info_arreglo = $this->db->get()->result();
			$ia = $info_arreglo[0];
			 
			$img = !empty($ia->image)?$ia->image:'assets/img/icons/default.jpg';
			
			$image = '<a id="arropt_'.$record->id_detail.'" href="'.$img.'"><img src="'.$img.'" width="100"></a><script>$("#arropt_'.$record->id_detail.'").fancybox();</script>';
				
			 
            $data[] = array( 
                'sl'        	=>$sl,
				//'image'   		=>$image,
                'factura'   	=>$details,
                'vendedor'  	=>$record->nombre_vendedor.' '.$record->apellido_vendedor,
                'fecha'     	=>date("d-M-Y",strtotime($record->fecha)),
                'recibe'    	=>$record->recibe,                
                'entrega'   	=>date("d/m/Y",strtotime($record->entrega)).'<br>'.$record->hora_entrega,
                'direccion' 	=>$record->direccion,
				'tipo_pago' 	=>$record->tipo_pago,
                'florista'	    =>$dm->first_name.' '.$dm->last_name.'<br>'.$image,
				'status' 		=>$status,
                'button'        =>$button,
                
            ); 
            $sl++;
			 
			 //}
			 
			 
			 
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }
	
	
	
	
	
	
	


public function invoice_taxinfo($invoice_id){
       return $this->db->select('*')   
            ->from('tax_collection')
            ->where('relation_id',$invoice_id)
            ->get()
            ->result_array(); 
    }

        public function retrieve_invoice_editdata($invoice_id) {
        $this->db->select('a.*, sum(c.quantity) as sum_quantity, a.total_tax as taxs,a. prevous_due,b.customer_name,c.*,c.tax as total_tax,c.product_id,d.product_name,d.product_model,d.tax,d.unit,d.*');
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('a.invoice_id', $invoice_id);
        $this->db->group_by('d.product_id');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

     public function invoice_entry() {
		 
		 date_default_timezone_set('America/Mexico_City');
		 
		 
        $tablecolumn         = $this->db->list_fields('tax_collection');
        $num_column          = count($tablecolumn)-4;
        $invoice_id          = $this->generator(10);
        $invoice_id          = strtoupper($invoice_id);
        $createby            = $this->session->userdata('id');
        $createdate          = date('Y-m-d H:i:s');
        $product_id          = $this->input->post('product_id');
        $currency_details    = $this->db->select('*')->from('web_setting')->get()->result_array();
        $quantity            = $this->input->post('product_quantity',TRUE);
        $invoice_no_generated= $this->input->post('invoic_no');
        $changeamount        = $this->input->post('change',TRUE);
        if($changeamount > 0){
           $paidamount = $this->input->post('n_total',TRUE);

        }else{
             $paidamount = $this->input->post('paid_amount',TRUE);
        }

     	$bank_id = $this->input->post('bank_id',TRUE);
        if(!empty($bank_id)){
       		$bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id',$bank_id)->get()->row()->bank_name;    
       		$bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bankname)->get()->row()->HeadCode;
   		}else{
    		$bankcoaid='';
   		}

        $available_quantity = $this->input->post('available_quantity',TRUE);
       
        $result = array();
        foreach ($available_quantity as $k => $v) {
            if ($v < $quantity[$k]) {
                $this->session->set_userdata(array('error_message' => display('you_can_not_buy_greater_than_available_qnty')));
                redirect('Cinvoice');
            }
        }


        $customer_id = $this->input->post('customer_id',TRUE);
      
        //Full or partial Payment record.
        $paid_amount    = $this->input->post('paid_amount',TRUE);
        $transection_id = $this->generator(8);
        $tax_v = 0;
             for($j=0;$j<$num_column;$j++){
                $taxfield        = 'tax'.$j;
                $taxvalue        = 'total_tax'.$j;
              $taxdata[$taxfield]=$this->input->post($taxvalue);
              $tax_v    += $this->input->post($taxvalue);
            }
            $taxdata['customer_id'] = $customer_id;
            $taxdata['date']        = (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d'));
            $taxdata['relation_id'] = $invoice_id;
            if($tax_v > 0){
            $this->db->insert('tax_collection',$taxdata);
              }


        //Data inserting into invoice table
		 
			$delim = (!empty($this->input->post('delim',TRUE))? '1' : '');
		 
		 
		 	$instore = (!empty($this->input->post('instore',TRUE))? '1' : '0');
		 
		 
		 
		 	$cancelado = (!empty($this->input->post('cancel',TRUE))? '1' : '0');
		 
		 	
		 
		 	$datainv = array();
		 
		 
		 	$txt_tc = '';
		 
		 
		 
		 	 $invoice_no = $this->input->post('invoice_no',TRUE);
		 
			 $this->db->select_max('invoice', 'invoice_no');
			 $query      = $this->db->get('invoice');
			 $result     = $query->result_array();
			 $invoice_no_db = $result[0]['invoice_no'];
			 if ($invoice_no == $invoice_no_db) {
				$invoice_no = $invoice_no_db + 1;
			 }
		 
		 
		 			 
		 	if($delim=='1'){		
				
				
			
				$datainv = array(
					'invoice_id'      => $invoice_id,
					'customer_id'     => $customer_id,
					'date'            => (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d')),
					'total_amount'    => $this->input->post('grand_total_price',TRUE),
					'total_tax'       => $this->input->post('total_tax',TRUE),
					'invoice'         => $invoice_no,					
					'invoice_details' => (!empty($this->input->post('inva_details',TRUE))?$this->input->post('inva_details',TRUE):'Gracias por tu compra'),
					'invoice_discount'=> $this->input->post('invoice_discount',TRUE),
					'total_discount'  => $this->input->post('total_discount',TRUE),
					'paid_amount'     => $this->input->post('paid_amount',TRUE),
					'due_amount'      => $this->input->post('due_amount',TRUE),
					'prevous_due'     => $this->input->post('previous',TRUE),
					'shipping_cost'   => $this->input->post('shipping_cost',TRUE),
					'sales_by'        => $this->session->userdata('id'),
					'status'          => 1,
					'payment_type'    =>  $this->input->post('paytype',TRUE),
					'bank_id'         =>  (!empty($this->input->post('bank_id',TRUE))?$this->input->post('bank_id',TRUE):null),
					'delivery_multiple'         => $delim,
					'instore'         => $instore,
					'branchoffice'    => $this->input->post('branchoffice',TRUE),
					'tipo_venta'      => $this->input->post('tipo_venta',TRUE),
					'tipo_pago'       => $this->input->post('tipo_pago',TRUE),
					'seller'    	  => $this->session->userdata('fullname'),
					
					'cancelado'       	 => $cancelado,
					'motivo_cancelacion' => $this->input->post('motivo',TRUE),
					
				);
				
				
			}else{
				
				
				if($instore=='1'){
					
					
					$datainv = array(
						'invoice_id'      => $invoice_id,
						'customer_id'     => $customer_id,
						'date'            => (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d')),
						'total_amount'    => $this->input->post('grand_total_price',TRUE),
						'total_tax'       => $this->input->post('total_tax',TRUE),
						'invoice'         => $invoice_no,
						'invoice_details' => (!empty($this->input->post('inva_details',TRUE))?$this->input->post('inva_details',TRUE):'Gracias por tu compra'),
						'invoice_discount'=> $this->input->post('invoice_discount',TRUE),
						'total_discount'  => $this->input->post('total_discount',TRUE),
						'paid_amount'     => $this->input->post('paid_amount',TRUE),
						'due_amount'      => $this->input->post('due_amount',TRUE),
						'prevous_due'     => $this->input->post('previous',TRUE),
						'shipping_cost'   => $this->input->post('shipping_cost',TRUE),
						'sales_by'        => $this->session->userdata('id'),
						'status'          => 1,
						'payment_type'    =>  $this->input->post('paytype',TRUE),
						'bank_id'         =>  (!empty($this->input->post('bank_id',TRUE))?$this->input->post('bank_id',TRUE):null),

						'delivery_multiple'         	=> $delim,
						'instore'         => $instore,
						'branchoffice'     				=> $this->input->post('branchoffice',TRUE),
						'fecha_entrega'         		=> date('Y-m-d', strtotime($this->input->post('dh_instore',TRUE))),
						'destinatario'         			=> '',
						'direccion'         			=> '',
						'lat'         					=> '',
						'lon'         					=> '',
						'direccion2'         			=> '',
						'telefono'         				=> '',
						'descripcion_entrega'         	=> '',


						'nombre_cliente'         	=> $this->input->post('nombre_cliente',TRUE),
						'telefono_cliente'         	=> $this->input->post('telefono_cliente',TRUE),

						'destinatario_reparto' 			=> '',
						'fecha_entrega_reparto' 		=> date('Y-m-d', strtotime($this->input->post('dh_instore',TRUE))),
						'direccion_reparto' 			=> '',
						'descripcion_entrega_reparto' 	=> '',
						'cliente_caja' 					=> $this->input->post('cliente_caja',TRUE),
						'fecha_entrega_caja' 			=> date('Y-m-d', strtotime($this->input->post('dh_instore',TRUE))),
						'destinatario_caja' 			=> '',
						'direccion_caja' 				=> '',
						'descripcion_entrega_caja' 		=> '',
						'repartidor_caja' 				=> '',
						'fecha_entrega_taller' 			=> date('Y-m-d', strtotime($this->input->post('dh_instore',TRUE))),
						'florista_taller' 				=> $this->input->post('florista_taller',TRUE),
						'cantidad_modelo' 				=> $this->input->post('cantidad_modelo',TRUE),
						'modelo' 						=> $this->input->post('modelo',TRUE),
						'cantidad_bases' 				=> $this->input->post('cantidad_bases',TRUE),
						'bases' 						=> $this->input->post('bases',TRUE),
						'cantidad_flores' 				=> $this->input->post('cantidad_flores',TRUE),
						'flores' 						=> $this->input->post('flores',TRUE),
						'cantidad_otros' 				=> $this->input->post('cantidad_otros',TRUE),
						'otros' 						=> $this->input->post('otros',TRUE),
						'descripcion_entrega_taller' 	=> $this->input->post('descripcion_entrega_taller',TRUE),
						'tipo_venta'     				=> $this->input->post('tipo_venta',TRUE),
						'tipo_pago'     				=> $this->input->post('tipo_pago',TRUE),

						'hora_entrega'     				=> date('h:i A', strtotime($this->input->post('dh_instore',TRUE))),
						'hora_entrega_reparto'     		=> date('h:i A', strtotime($this->input->post('dh_instore',TRUE))),
						'hora_entrega_caja'     		=> date('h:i A', strtotime($this->input->post('dh_instore',TRUE))),
						'hora_entrega_taller'     		=> date('h:i A', strtotime($this->input->post('dh_instore',TRUE))),
						'seller'    	  				=> $this->session->userdata('fullname'),
						'zona'       	  				=> '',
						'mensaje'       	  			=> $this->input->post('mensaje',TRUE),
						
						'cancelado'       	 => $cancelado,
						'motivo_cancelacion' => $this->input->post('motivo',TRUE),
					);
					
					
					
					
				
				}else{
					
					
					
					$datainv = array(
						'invoice_id'      => $invoice_id,
						'customer_id'     => $customer_id,
						'date'            => (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d')),
						'total_amount'    => $this->input->post('grand_total_price',TRUE),
						'total_tax'       => $this->input->post('total_tax',TRUE),
						'invoice'         => $invoice_no,
						'invoice_details' => (!empty($this->input->post('inva_details',TRUE))?$this->input->post('inva_details',TRUE):'Gracias por tu compra'),
						'invoice_discount'=> $this->input->post('invoice_discount',TRUE),
						'total_discount'  => $this->input->post('total_discount',TRUE),
						'paid_amount'     => $this->input->post('paid_amount',TRUE),
						'due_amount'      => $this->input->post('due_amount',TRUE),
						'prevous_due'     => $this->input->post('previous',TRUE),
						'shipping_cost'   => $this->input->post('shipping_cost',TRUE),
						'sales_by'        => $this->session->userdata('id'),
						'status'          => 1,
						'payment_type'    =>  $this->input->post('paytype',TRUE),
						'bank_id'         =>  (!empty($this->input->post('bank_id',TRUE))?$this->input->post('bank_id',TRUE):null),

						'delivery_multiple'         	=> $delim,
						'instore'         => $instore,
						'branchoffice'     				=> $this->input->post('branchoffice',TRUE),
						'fecha_entrega'         		=> $this->input->post('fecha_entrega',TRUE),
						'destinatario'         			=> $this->input->post('destinatario',TRUE),
						'direccion'         			=> $this->input->post('direccion',TRUE),
						'lat'         					=> $this->input->post('lat',TRUE),
						'lon'         					=> $this->input->post('lon',TRUE),
						'direccion2'         			=> $this->input->post('direccion2',TRUE),
						'telefono'         				=> $this->input->post('telefono',TRUE),
						'descripcion_entrega'         	=> $this->input->post('descripcion_entrega',TRUE),


						'nombre_cliente'         	=> $this->input->post('nombre_cliente',TRUE),
						'telefono_cliente'         	=> $this->input->post('telefono_cliente',TRUE),

						'destinatario_reparto' 			=> $this->input->post('destinatario_reparto',TRUE),
						'fecha_entrega_reparto' 		=> $this->input->post('fecha_entrega_reparto',TRUE),
						'direccion_reparto' 			=> $this->input->post('direccion_reparto',TRUE),
						'descripcion_entrega_reparto' 	=> $this->input->post('descripcion_entrega_reparto',TRUE),
						'cliente_caja' 					=> $this->input->post('cliente_caja',TRUE),
						'fecha_entrega_caja' 			=> $this->input->post('fecha_entrega_caja',TRUE),
						'destinatario_caja' 			=> $this->input->post('destinatario_caja',TRUE),
						'direccion_caja' 				=> $this->input->post('direccion_caja',TRUE),
						'descripcion_entrega_caja' 		=> $this->input->post('descripcion_entrega_caja',TRUE),
						'repartidor_caja' 				=> $this->input->post('repartidor_caja',TRUE),
						'fecha_entrega_taller' 			=> $this->input->post('fecha_entrega_taller',TRUE),
						'florista_taller' 				=> $this->input->post('florista_taller',TRUE),
						'cantidad_modelo' 				=> $this->input->post('cantidad_modelo',TRUE),
						'modelo' 						=> $this->input->post('modelo',TRUE),
						'cantidad_bases' 				=> $this->input->post('cantidad_bases',TRUE),
						'bases' 						=> $this->input->post('bases',TRUE),
						'cantidad_flores' 				=> $this->input->post('cantidad_flores',TRUE),
						'flores' 						=> $this->input->post('flores',TRUE),
						'cantidad_otros' 				=> $this->input->post('cantidad_otros',TRUE),
						'otros' 						=> $this->input->post('otros',TRUE),
						'descripcion_entrega_taller' 	=> $this->input->post('descripcion_entrega_taller',TRUE),
						'tipo_venta'     				=> $this->input->post('tipo_venta',TRUE),
						'tipo_pago'     				=> $this->input->post('tipo_pago',TRUE),

						'hora_entrega'     				=> $this->input->post('hora_entrega',TRUE),
						'hora_entrega_reparto'     		=> $this->input->post('hora_entrega_reparto',TRUE),
						'hora_entrega_caja'     		=> $this->input->post('hora_entrega_caja',TRUE),
						'hora_entrega_taller'     		=> $this->input->post('hora_entrega_taller',TRUE),
						'seller'    	  				=> $this->session->userdata('fullname'),
						'zona'       	  				=> $this->input->post('zona',TRUE),
						'mensaje'       	  			=> $this->input->post('mensaje',TRUE),
						
						'cancelado'       	 => $cancelado,
						'motivo_cancelacion' => $this->input->post('motivo',TRUE),
					);
					
					
					
				
				
				
				}
				
				
				

				
				
				$txt_tc = $this->input->post('telefono_cliente',TRUE);
				
				$config_data = $this->db->select('*')->from('sms_settings')->get()->row();
				if($config_data->isservice == 1){
					
					$id_florista = $this->input->post('florista_taller',TRUE);
					$info_florista = $this->db->select('*')->from('florist')->where('id', $id_florista)->get()->row();
					$txt_tf = $info_florista->phone;				
					$txt_tf = str_replace("(", "", $txt_tf);
					$txt_tf = str_replace(")", "", $txt_tf);
					$txt_tf = str_replace(" ", "", $txt_tf);
					$txt_tf = str_replace("-", "", $txt_tf);	
					
					$message_f = 'Acaba de realizarse la venta de un nuevo arreglo, es importante que revises tu bandeja de pedidos y comentar cuando ya lo tengas terminado.';		
					
					if($txt_tf!=''){
						$this->smsgateway->send([
							'apiProvider' => 'nexmo',
							'username'    => $config_data->api_key,
							'password'    => $config_data->api_secret,
							'from'        => $config_data->from,
							'to'          => '52'.$txt_tf,
							'message'     => $message_f
						]);						
					}
				}
				
				
				if($config_data->isreceive == 1 && $instore=='0'){
					
					$id_repartidor = $this->input->post('repartidor_caja',TRUE);
					$info_repartidor = $this->db->select('*')->from('deliveryman')->where('id', $id_repartidor)->get()->row();
					$txt_td = $info_repartidor->phone;				
					$txt_td = str_replace("(", "", $txt_td);
					$txt_td = str_replace(")", "", $txt_td);
					$txt_td = str_replace(" ", "", $txt_td);
					$txt_td = str_replace("-", "", $txt_td);
					
					$message_d = 'Acaba de realizarse la venta de un nuevo arreglo, por favor revisa tu bandeja de pedidos para enterarte el status del mismo, así como la hora de entrega.';	
					
					if($txt_td!=''){
						$this->smsgateway->send([
							'apiProvider' => 'nexmo',
							'username'    => $config_data->api_key,
							'password'    => $config_data->api_secret,
							'from'        => $config_data->from,
							'to'          => '52'.$txt_td,
							'message'     => $message_d
						]);						
					}
				}
		}
		 
		 //return var_dump($datainv);
		 //exit();
		 
         $this->db->insert('invoice', $datainv);
		 
		 
		 
		 if($delim=='1'){
			 
			 $id_arreglos 			= $this->input->post('id_arreglo',TRUE);
			 $arreglo 				= $this->input->post('arreglo',TRUE);
			 $fecha_entrega 		= $this->input->post('fecha_entrega',TRUE);
			 
			 $hora_entrega 		    = $this->input->post('hora_entrega',TRUE);
			 
			 $mensaje 		    	= $this->input->post('mensaje',TRUE);
			 
			 $destinatario 			= $this->input->post('destinatario',TRUE);
			 $direccion 			= $this->input->post('direccion',TRUE);
			 $lat 					= $this->input->post('lat',TRUE);
			 $lon 					= $this->input->post('lon',TRUE);
			 $direccion2 			= $this->input->post('direccion2',TRUE);
			 
			 
			 $radius 				= $this->input->post('radius',TRUE);
			 $telefono 				= $this->input->post('telefono',TRUE);
			 $descripcion_entrega 	= $this->input->post('descripcion_entrega',TRUE); 
			 
			 $nombre_cliente 	= $this->input->post('nombre_cliente',TRUE); 
			 $telefono_cliente 	= $this->input->post('telefono_cliente',TRUE); 
			 
			 
			 $cliente_caja 	= $this->input->post('cliente_caja',TRUE);
			 $repartidor_caja 	= $this->input->post('repartidor_caja',TRUE);
			 
			 $cantidad_bases	= $this->input->post('cantidad_bases',TRUE);
			 $cantidad_modelo	= $this->input->post('cantidad_modelo',TRUE);
			 $cantidad_flores	= $this->input->post('cantidad_flores',TRUE);
			 $cantidad_otros	= $this->input->post('cantidad_otros',TRUE);
			 $bases	= $this->input->post('bases',TRUE);
			 $modelo	= $this->input->post('modelo',TRUE);
			 $flores	= $this->input->post('flores',TRUE);
			 $otros	= $this->input->post('otros',TRUE);
			 $florista_taller	= $this->input->post('florista_taller',TRUE);
			 $zona	= $this->input->post('zona',TRUE);
			 
			 
			 
			 $cont1 = 0;
			 
			 $config_data = $this->db->select('*')->from('sms_settings')->get()->row();
			 
			 
			 foreach ($id_arreglos as $id_arreglo){
				 
				 $data_entrega = array(
					'invoice_id'    => $invoice_id,
					'customer_id'   => $customer_id,
					'id_arreglo'    => $id_arreglo,
					'arreglo'     	=> $arreglo[$cont1],
					'fecha_entrega' => $fecha_entrega[$cont1],
					'hora_entrega'  => $hora_entrega[$cont1],
					'destinatario' 	=> $destinatario[$cont1],
					'direccion' 	=> $direccion[$cont1],
					'lat' 			=> $lat[$cont1],
					'lon' 			=> $lon[$cont1],
					'direccion2' 	=> $direccion2[$cont1],
					'radius' 		=> $radius[$cont1],
					'telefono' 		=> $telefono[$cont1],
					 
					'nombre_cliente' 	=> $nombre_cliente[$cont1],
					'telefono_cliente' 	=> $telefono_cliente[$cont1],					 
					 
					'atencion' 		=> $this->session->userdata('fullname'),
					'descripcion_entrega' => $descripcion_entrega[$cont1],
					'zona' 			=> $zona[$cont1],
					'mensaje' 		=> $mensaje[$cont1]
				);				 
				$this->db->insert('invoice_entrega', $data_entrega);
				 
				 
				$data_reparto = array(
					'invoice_id'    => $invoice_id,
					'customer_id'   => $customer_id,
					'id_arreglo'    => $id_arreglo,
					'arreglo'     	=> $arreglo[$cont1],
					'fecha_entrega' => $fecha_entrega[$cont1],
					'hora_entrega'  => $hora_entrega[$cont1],
					'destinatario' 	=> $destinatario[$cont1],
					'direccion' 	=> $direccion[$cont1],
					'telefono' 		=> $telefono[$cont1],
					'atencion' 		=> $this->session->userdata('fullname'),
					'descripcion_entrega' => $descripcion_entrega[$cont1],
					'mensaje' 		=> $mensaje[$cont1]
				);				 
				$this->db->insert('invoice_reparto', $data_reparto);
				 
				 
				$data_caja = array(
					'invoice_id'    => $invoice_id,
					'customer_id'   => $customer_id,
					'id_arreglo'    => $id_arreglo,
					'arreglo'     	=> $arreglo[$cont1],
					'cliente'     	=> $cliente_caja[$cont1],
					'fecha_entrega' => $fecha_entrega[$cont1],
					'hora_entrega'  => $hora_entrega[$cont1],
					'destinatario' 	=> $destinatario[$cont1],
					'direccion' 	=> $direccion[$cont1],
					'repartidor' 	=> $repartidor_caja[$cont1],
					'atencion' 		=> $this->session->userdata('fullname'),
					'descripcion_entrega' => $descripcion_entrega[$cont1],
					'mensaje' 		=> $mensaje[$cont1]
				);				 
				$this->db->insert('invoice_caja', $data_caja);
				 
				 
				 
				 $data_taller = array(
					'invoice_id'    => $invoice_id,
					'customer_id'   => $customer_id,
					'id_arreglo'    => $id_arreglo,
					'florista'		=> $florista_taller[$cont1],
					'arreglo'     	=> $arreglo[$cont1],
					'fecha_entrega' => $fecha_entrega[$cont1],
					'hora_entrega'  => $hora_entrega[$cont1],
					'destinatario' 	=> $destinatario[$cont1],
					'direccion' 	=> $direccion[$cont1],
					'telefono'		=> $telefono[$cont1],
					'descripcion_entrega' 	=> $descripcion_entrega[$cont1],					 
					'cantidad_modelo'		=> $cantidad_modelo[$cont1],
					'cantidad_bases'		=> $cantidad_bases[$cont1],
					'cantidad_flores'		=> $cantidad_flores[$cont1],
					'cantidad_otros'		=> $cantidad_otros[$cont1],
					'modelo'				=> $modelo[$cont1],
					'bases'					=> $bases[$cont1],
					'flores'				=> $flores[$cont1],
					'otros'					=> $otros[$cont1],					 
					'atencion' 		=> $this->session->userdata('fullname'),
					'mensaje' 		=> $mensaje[$cont1]
					
				);				 
				$this->db->insert('invoice_taller', $data_taller);
				 
				 
				 
				 
				if($config_data->isservice == 1){
					
					$id_florista = $florista_taller[$cont1];
					$info_florista = $this->db->select('*')->from('florist')->where('id', $id_florista)->get()->row();
					$txt_tf = $info_florista->phone;				
					$txt_tf = str_replace("(", "", $txt_tf);
					$txt_tf = str_replace(")", "", $txt_tf);
					$txt_tf = str_replace(" ", "", $txt_tf);
					$txt_tf = str_replace("-", "", $txt_tf);	
					
					$message_f = 'Acaba de realizarse la venta de un nuevo arreglo, es importante que revises tu bandeja de pedidos y comentar cuando ya lo tengas terminado.';				
					if($txt_tf!=''){
						$this->smsgateway->send([
							'apiProvider' => 'nexmo',
							'username'    => $config_data->api_key,
							'password'    => $config_data->api_secret,
							'from'        => $config_data->from,
							'to'          => '52'.$txt_tf,
							'message'     => $message_f
						]);
					}
				}
				
				
				if($config_data->isreceive == 1 && $instore=='0'){
					
					$id_repartidor = $repartidor_caja[$cont1];
					$info_repartidor = $this->db->select('*')->from('deliveryman')->where('id', $id_repartidor)->get()->row();
					$txt_td = $info_repartidor->phone;				
					$txt_td = str_replace("(", "", $txt_td);
					$txt_td = str_replace(")", "", $txt_td);
					$txt_td = str_replace(" ", "", $txt_td);
					$txt_td = str_replace("-", "", $txt_td);
					
					$message_d = 'Acaba de realizarse la venta de un nuevo arreglo, por favor revisa tu bandeja de pedidos para enterarte el status del mismo, así como la hora de entrega.';				
					
					if($txt_td!=''){
						$this->smsgateway->send([
							'apiProvider' => 'nexmo',
							'username'    => $config_data->api_key,
							'password'    => $config_data->api_secret,
							'from'        => $config_data->from,
							'to'          => '52'.$txt_td,
							'message'     => $message_d
						]);
					}
				}
				 
				$cont1++;
				 
			 }
			 
			 
			 $txt_tc = $telefono_cliente[0];
		 }
		 
		 
		 
        $prinfo  = $this->db->select('product_id,Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id',$product_id)->group_by('product_id')->get()->result(); 
    $purchase_ave = [];
    $i=0;
    foreach ($prinfo as $avg) {
      $purchase_ave [] =  $avg->product_rate*$quantity[$i];
      $i++;
    }
    $sumval   = array_sum($purchase_ave);
    $cusifo   = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();
    $headn    = $customer_id.'-'.$cusifo->customer_name;
    $coainfo  = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
    $customer_headcode = $coainfo->HeadCode;
// Cash in Hand debit
      $cc = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INV',
      'VDate'          =>  $createdate,
      'COAID'          =>  1020101,
      'Narration'      =>  'Cash in Hand in Sale for Invoice No - '.$invoice_no_generated.' customer- '.$cusifo->customer_name,
      'Debit'          =>  $paidamount,
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $createby,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 
     
     // bank ledger
 $bankc = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  $bankcoaid,
      'Narration'      =>  'Paid amount for customer  Invoice No - '.$invoice_no_generated.' customer -'.$cusifo->customer_name,
      'Debit'          =>  $paidamount,
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $createby,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 

       ///Inventory credit
       $coscr = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INV',
      'VDate'          =>  $createdate,
      'COAID'          =>  10107,
      'Narration'      =>  'Inventory credit For Invoice No'.$invoice_no_generated,
      'Debit'          =>  0,
      'Credit'         =>  $sumval,//purchase price asbe
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       $this->db->insert('acc_transaction',$coscr);
       
    // Customer Transactions
    //Customer debit for Product Value
    $cosdr = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INV',
      'VDate'          =>  $createdate,
      'COAID'          =>  $customer_headcode,
      'Narration'      =>  'Customer debit For Invoice No -  '.$invoice_no_generated.' Customer '.$cusifo->customer_name,
      'Debit'          =>  $this->input->post('n_total',TRUE)-(!empty($this->input->post('previous',TRUE))?$this->input->post('previous',TRUE):0),
      'Credit'         =>  0,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       $this->db->insert('acc_transaction',$cosdr);

  $total_saleamnt = $this->input->post('n_total',TRUE)-(!empty($this->input->post('previous',TRUE))?$this->input->post('previous',TRUE):0);
  $withoutinventory = $total_saleamnt - $sumval;
  $income = $withoutinventory - $this->input->post('total_tax',TRUE);

         $pro_sale_income = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  303,
      'Narration'      =>  'Sale Income For Invoice NO - '.$invoice_no_generated.' Customer '.$cusifo->customer_name,
      'Debit'          =>  0,
      'Credit'         =>  $income,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       $this->db->insert('acc_transaction',$pro_sale_income);

       $tax_info = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  50203,
      'Narration'      =>  'Sale Income For Invoice NO - '.$invoice_no_generated.' Customer '.$cusifo->customer_name,
      'Debit'          =>  $this->input->post('total_tax',TRUE),
      'Credit'         =>  0,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       $this->db->insert('acc_transaction',$tax_info);

       ///Customer credit for Paid Amount
       $cuscredit = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INV',
      'VDate'          =>  $createdate,
      'COAID'          =>  $customer_headcode,
      'Narration'      =>  'Customer credit for Paid Amount For Customer Invoice NO- '.$invoice_no_generated.' Customer- '.$cusifo->customer_name,
      'Debit'          =>  0,
      'Credit'         =>  $paidamount,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       if(!empty($this->input->post('paid_amount',TRUE))){
            $this->db->insert('acc_transaction',$cuscredit);
          
        if($this->input->post('paytype',TRUE) == 2){
        $this->db->insert('acc_transaction',$bankc);
        
        }
            if($this->input->post('paytype',TRUE) == 1){
        $this->db->insert('acc_transaction',$cc);
        }
       
  }
     $customerinfo = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();

        $rate                = $this->input->post('product_rate',TRUE);
        $p_id                = $this->input->post('product_id',TRUE);
        $total_amount        = $this->input->post('total_price',TRUE);
        $discount_rate       = $this->input->post('discount_amount',TRUE);
        $discount_per        = $this->input->post('discount',TRUE);
        $tax_amount          = $this->input->post('tax',TRUE);
        $invoice_description = $this->input->post('desc',TRUE);
        $serial_n            = $this->input->post('serial_no',TRUE);
		 
		$txt_products = '(';

        for ($i = 0, $n = count($p_id); $i < $n; $i++) {
            $product_quantity = $quantity[$i];
            $product_rate     = $rate[$i];
            $product_id       = $p_id[$i];
            $serial_no        = (!empty($serial_n[$i])?$serial_n[$i]:null);
            $total_price      = $total_amount[$i];
            $supplier_rate    = $this->supplier_price($product_id);
            $disper           = $discount_per[$i];
            $discount         = is_numeric($product_quantity) * is_numeric($product_rate) * is_numeric($disper) / 100;
            $tax              = $tax_amount[$i];
            $description      = (!empty($invoice_description)?$invoice_description[$i]:null);
           
            $data1 = array(
                'invoice_details_id' => $this->generator(15),
                'invoice_id'         => $invoice_id,
                'product_id'         => $product_id,
                'serial_no'          => $serial_no,
                'quantity'           => $product_quantity,
                'rate'               => $product_rate,
                'discount'           => $discount,
                'description'        => $description,
                'discount_per'       => $disper,
                'tax'                => $tax,
                'paid_amount'        => $paidamount,
                'due_amount'         => $this->input->post('due_amount',TRUE),
                'supplier_rate'      => $supplier_rate,
                'total_price'        => $total_price,
                'status'             => 1
            );
            if (!empty($quantity)) {
                $this->db->insert('invoice_details', $data1);
            }
			
			$info_product = $this->db->select('*')->from('product_information')->where('product_id', $product_id)->get()->row();
			$txt_products.= $info_product->product_name.', ';
        }
		 
		$txt_products = substr($txt_products, 0, -2);		 
		$txt_products.= ')';
			
		$txt_tc = str_replace("(", "", $txt_tc);
		$txt_tc = str_replace(")", "", $txt_tc);
		$txt_tc = str_replace(" ", "", $txt_tc);
		$txt_tc = str_replace("-", "", $txt_tc);
			
		 
        /*$message = 'Mr.'.$customerinfo->customer_name.',
        '.'You have purchase  '.$this->input->post('grand_total_price',TRUE).' '. $currency_details[0]['currency'].' You have paid .'.$this->input->post('paid_amount',TRUE).' '. $currency_details[0]['currency'];*/
		 
		$message = 'Gracias por pensar en Encromex para esos momentos tan importantes en la vida, le informamos que usted acaba de comprar el arreglo '.$txt_products.' el cual será enviado a la dirección que nos dejo en nuestra base de datos, le informaremos con otro mensaje cuando éste haya sido entregado.';

   		$config_data = $this->db->select('*')->from('sms_settings')->get()->row();
        if($config_data->isinvoice == 1){
			
			if($txt_tc!=''){
				$this->smsgateway->send([
					'apiProvider' => 'nexmo',
					'username'    => $config_data->api_key,
					'password'    => $config_data->api_secret,
					'from'        => $config_data->from,
					'to'          => '52'.$txt_tc,
					'message'     => $message
				]);
			}
      	}
		 
    
        return $invoice_id;
    }


        public function update_invoice() {
        $tablecolumn = $this->db->list_fields('tax_collection');
        $num_column  = count($tablecolumn)-4;
        $invoice_id  = $this->input->post('invoice_id',TRUE);
        $invoice_no  = $this->input->post('invoice',TRUE);
        $createby    = $this->session->userdata('id');
        $createdate  = date('Y-m-d H:i:s');
        $customer_id = $this->input->post('customer_id',TRUE);
        $quantity    = $this->input->post('product_quantity',TRUE);
        $product_id  = $this->input->post('product_id',TRUE);

       $changeamount = $this->input->post('change',TRUE);
        if($changeamount > 0){
        $paidamount = $this->input->post('n_total',TRUE);

        }else{
        $paidamount = $this->input->post('paid_amount',TRUE);
        }


   $bank_id = $this->input->post('bank_id',TRUE);
        if(!empty($bank_id)){
       $bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id',$bank_id)->get()->row()->bank_name;
    
       $bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bankname)->get()->row()->HeadCode;
   }else{
    $bankcoaid='';
   }
   
             $transection_id =$this->generator(8);


            $this->db->where('VNo', $invoice_id);
            $this->db->delete('acc_transaction');
            $this->db->where('relation_id', $invoice_id);
            $this->db->delete('tax_collection');
      
        $data = array(
            'invoice_id'      => $invoice_id,
            'customer_id'     => $this->input->post('customer_id',TRUE),
            'date'            => $this->input->post('invoice_date',TRUE),
            'total_amount'    => $this->input->post('grand_total_price',TRUE),
            'total_tax'       => $this->input->post('total_tax',TRUE),
            'invoice_details' => $this->input->post('inva_details',TRUE),
            'due_amount'      => $this->input->post('due_amount',TRUE),
            'paid_amount'     => $this->input->post('paid_amount',TRUE),
            'invoice_discount'=> $this->input->post('invoice_discount',TRUE),
            'total_discount'  => $this->input->post('total_discount',TRUE),
            'prevous_due'     => $this->input->post('previous',TRUE),
            'shipping_cost'   => $this->input->post('shipping_cost',TRUE),
            'payment_type'    =>  $this->input->post('paytype',TRUE),
            'bank_id'         =>  (!empty($this->input->post('bank_id',TRUE))?$this->input->post('bank_id',TRUE):null),
        );
      

     
        $prinfo  = $this->db->select('product_id,Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id',$product_id)->group_by('product_id')->get()->result(); 
    $purchase_ave = [];
    $i=0;
    foreach ($prinfo as $avg) {
      $purchase_ave [] =  $avg->product_rate*$quantity[$i];
      $i++;
    }
   $sumval = array_sum($purchase_ave);

   $cusifo = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();
    $headn = $customer_id.'-'.$cusifo->customer_name;
    $coainfo = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
    $customer_headcode = $coainfo->HeadCode;
// Cash in Hand debit
      $cc = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INV',
      'VDate'          =>  $createdate,
      'COAID'          =>  1020101,
      'Narration'      =>  'Cash in Hand for sale for Invoice No -'.$invoice_no.' Customer '.$cusifo->customer_name,
      'Debit'          =>  $paidamount,
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $createby,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 
     

       ///Inventory credit
       $coscr = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INV',
      'VDate'          =>  $createdate,
      'COAID'          =>  10107,
      'Narration'      =>  'Inventory credit For Invoice No'.$invoice_no,
      'Debit'          =>  0,
      'Credit'         =>  $sumval,//purchase price asbe
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       $this->db->insert('acc_transaction',$coscr);
       
        // bank ledger
 $bankc = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  $bankcoaid,
      'Narration'      =>  'Paid amount for  Invoice NO- '.$invoice_no.' customer '.$cusifo->customer_name,
      'Debit'          =>  $paidamount,
      'Credit'         =>  0,
      'IsPosted'       =>  1,
      'CreateBy'       =>  $createby,
      'CreateDate'     =>  $createdate,
      'IsAppove'       =>  1
    ); 

/// Sale income
   $pro_sale_income = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INVOICE',
      'VDate'          =>  $createdate,
      'COAID'          =>  303,
      'Narration'      =>  'Sale Income From Invoice NO - '.$invoice_no.' Customer '.$cusifo->customer_name,
      'Debit'          =>  0,
      'Credit'         =>  $this->input->post('n_total',TRUE)-(!empty($this->input->post('previous',TRUE))?$this->input->post('previous',TRUE):0),
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       $this->db->insert('acc_transaction',$pro_sale_income);
    //Customer debit for Product Value
    $cosdr = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INV',
      'VDate'          =>  $createdate,
      'COAID'          =>  $customer_headcode,
      'Narration'      =>  'Customer debit For Invoice NO - '.$invoice_no.' customer-  '.$cusifo->customer_name,
      'Debit'          =>  $this->input->post('grand_total_price',TRUE),
      'Credit'         =>  0,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 
       $this->db->insert('acc_transaction',$cosdr);

       ///Customer credit for Paid Amount
       $customer_credit = array(
      'VNo'            =>  $invoice_id,
      'Vtype'          =>  'INV',
      'VDate'          =>  $createdate,
      'COAID'          =>  $customer_headcode,
      'Narration'      =>  'Customer credit for Paid Amount For Invoice No -'.$invoice_no.' Customer '.$cusifo->customer_name,
      'Debit'          =>  0,
      'Credit'         =>  $paidamount,
      'IsPosted'       => 1,
      'CreateBy'       => $createby,
      'CreateDate'     => $createdate,
      'IsAppove'       => 1
    ); 

if ($invoice_id != '') {
            $this->db->where('invoice_id', $invoice_id);
            $this->db->update('invoice', $data);
        }
if(!empty($this->input->post('paid_amount',TRUE))){
            $this->db->insert('acc_transaction',$customer_credit);
        if($this->input->post('paytype') == 2){
        $this->db->insert('acc_transaction',$bankc);
   
        }
            if($this->input->post('paytype') == 1){
        $this->db->insert('acc_transaction',$cc);
        }
       
  }

     

         for($j=0;$j<$num_column;$j++){
                $taxfield = 'tax'.$j;
                $taxvalue = 'total_tax'.$j;
              $taxdata[$taxfield]=$this->input->post($taxvalue);
            }
            $taxdata['customer_id'] = $customer_id;
            $taxdata['date']        = (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d'));
            $taxdata['relation_id'] = $invoice_id;
            $this->db->insert('tax_collection',$taxdata);

        // Inserting for Accounts adjustment.
        ############ default table :: customer_payment :: inflow_92mizdldrv #################

        $invoice_d_id  = $this->input->post('invoice_details_id',TRUE);
        $quantity      = $this->input->post('product_quantity',TRUE);
        $rate          = $this->input->post('product_rate',TRUE);
        $p_id          = $this->input->post('product_id',TRUE);
        $total_amount  = $this->input->post('total_price',TRUE);
        $discount_rate = $this->input->post('discount_amount',TRUE);
        $discount_per  = $this->input->post('discount',TRUE);
        $invoice_description = $this->input->post('desc',TRUE);
        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_details');
        $serial_n       = $this->input->post('serial_no',TRUE);
        for ($i = 0, $n = count($p_id); $i < $n; $i++) {
            $product_quantity = $quantity[$i];
            $product_rate     = $rate[$i];
            $product_id       = $p_id[$i];
            $serial_no        = (!empty($serial_n[$i])?$serial_n[$i]:null);
            $total_price      = $total_amount[$i];
            $supplier_rate    = $this->supplier_price($product_id);
            $discount         = $discount_rate[$i];
            $dis_per          = $discount_per[$i];
           $desciption        = $invoice_description[$i];
            if (!empty($tax_amount[$i])) {
                $tax = $tax_amount[$i];
            } else {
                $tax = $this->input->post('tax');
            }


            $data1 = array(
                'invoice_details_id' => $this->generator(15),
                'invoice_id'         => $invoice_id,
                'product_id'         => $product_id,
                'serial_no'          => $serial_no,
                'quantity'           => $product_quantity,
                'rate'               => $product_rate,
                'discount'           => $discount,
                'total_price'        => $total_price,
                'discount_per'       => $dis_per,
                'tax'                => $this->input->post('total_tax',TRUE),
                'paid_amount'        => $paidamount,
                 'supplier_rate'     => $supplier_rate,
                'due_amount'         => $this->input->post('due_amount',TRUE),
                 'description'       => $desciption,
            );
            $this->db->insert('invoice_details', $data1);



           

            $customer_id = $this->input->post('customer_id',TRUE);
          
        }

        return $invoice_id;
    }


    //POS invoice entry
	



	
	


	
	public function invoice_entry_edit() {

		date_default_timezone_set('America/Mexico_City');

		$tablecolumn         = $this->db->list_fields('tax_collection');
		$num_column          = count($tablecolumn)-4;
		$invoice_id          = $this->input->post('edit_invoice_id');
		$createby            = $this->session->userdata('id');
		$createdate          = date('Y-m-d H:i:s');
		$product_id          = $this->input->post('product_id');
		$currency_details    = $this->db->select('*')->from('web_setting')->get()->result_array();
		$quantity            = $this->input->post('product_quantity',TRUE);
		$invoice_no_generated= $this->input->post('invoic_no');
		$changeamount        = $this->input->post('change',TRUE);


		$this->db->where('invoice_id', $invoice_id)
				->delete("invoice");

		$this->db->where('invoice_id', $invoice_id)
			->delete("invoice_details");

		$this->db->where('VNo', $invoice_id)
			->delete("acc_transaction");		

		$this->db->where('invoice_id', $invoice_id)
			->delete("invoice_caja");
		$this->db->where('invoice_id', $invoice_id)
			->delete("invoice_entrega");
		$this->db->where('invoice_id', $invoice_id)
			->delete("invoice_reparto");
		$this->db->where('invoice_id', $invoice_id)
			->delete("invoice_taller");


		if($changeamount > 0){
			$paidamount = $this->input->post('n_total',TRUE);
		}else{
			$paidamount = $this->input->post('paid_amount',TRUE);
		}

		$bank_id = $this->input->post('bank_id',TRUE);
		if(!empty($bank_id)){
			$bankname = $this->db->select('bank_name')->from('bank_add')->where('bank_id',$bank_id)->get()->row()->bank_name;    
			$bankcoaid = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$bankname)->get()->row()->HeadCode;
		}else{
			$bankcoaid='';
		}

		$available_quantity = $this->input->post('available_quantity',TRUE);

		$result = array();
		foreach ($available_quantity as $k => $v) {
			if ($v < $quantity[$k]) {
				$this->session->set_userdata(array('error_message' => display('you_can_not_buy_greater_than_available_qnty')));
				redirect('Cinvoice');
			}
		}


		$customer_id = $this->input->post('customer_id',TRUE);

		$paid_amount    = $this->input->post('paid_amount',TRUE);
		$transection_id = $this->generator(8);
		$tax_v = 0;
		for($j=0;$j<$num_column;$j++){
			$taxfield        = 'tax'.$j;
			$taxvalue        = 'total_tax'.$j;
			$taxdata[$taxfield]=$this->input->post($taxvalue);
			$tax_v    += $this->input->post($taxvalue);
		}
		$taxdata['customer_id'] = $customer_id;
		$taxdata['date']        = (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d'));
		$taxdata['relation_id'] = $invoice_id;
		if($tax_v > 0){
			$this->db->insert('tax_collection',$taxdata);
		}

		$delim = (!empty($this->input->post('delim',TRUE))? '1' : '');
		$instore = (!empty($this->input->post('instore',TRUE))? '1' : '0');		
		$cancelado = (!empty($this->input->post('cancel',TRUE))? '1' : '0');		
				
		$datainv = array();
		$invoice_no = $this->input->post('invoice_no',TRUE);

		if($delim=='1'){		



			$datainv = array(
				'invoice_id'      => $invoice_id,
				'customer_id'     => $customer_id,
				'date'            => (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d')),
				'total_amount'    => $this->input->post('grand_total_price',TRUE),
				'total_tax'       => $this->input->post('total_tax',TRUE),
				'invoice'         => $invoice_no,					
				'invoice_details' => (!empty($this->input->post('inva_details',TRUE))?$this->input->post('inva_details',TRUE):'Gracias por tu compra'),
				'invoice_discount'=> $this->input->post('invoice_discount',TRUE),
				'total_discount'  => $this->input->post('total_discount',TRUE),
				'paid_amount'     => $this->input->post('paid_amount',TRUE),
				'due_amount'      => $this->input->post('due_amount',TRUE),
				'prevous_due'     => $this->input->post('previous',TRUE),
				'shipping_cost'   => $this->input->post('shipping_cost',TRUE),
				'sales_by'        => $this->session->userdata('id'),
				'status'          => 1,
				'payment_type'    =>  $this->input->post('paytype',TRUE),
				'bank_id'         =>  (!empty($this->input->post('bank_id',TRUE))?$this->input->post('bank_id',TRUE):null),
				'delivery_multiple'         => $delim,
				'instore'         => $instore,
				'branchoffice'    => $this->input->post('branchoffice',TRUE),
				'tipo_venta'      => $this->input->post('tipo_venta',TRUE),
				'tipo_pago'       => $this->input->post('tipo_pago',TRUE),
				'seller'    	  => $this->session->userdata('fullname'),

				'cancelado'       	 => $cancelado,
				'motivo_cancelacion' => $this->input->post('motivo',TRUE),
			);


		}else{

			if($instore=='1'){

				$datainv = array(
					'invoice_id'      => $invoice_id,
					'customer_id'     => $customer_id,
					'date'            => (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d')),
					'total_amount'    => $this->input->post('grand_total_price',TRUE),
					'total_tax'       => $this->input->post('total_tax',TRUE),
					'invoice'         => $invoice_no,
					'invoice_details' => (!empty($this->input->post('inva_details',TRUE))?$this->input->post('inva_details',TRUE):'Gracias por tu compra'),
					'invoice_discount'=> $this->input->post('invoice_discount',TRUE),
					'total_discount'  => $this->input->post('total_discount',TRUE),
					'paid_amount'     => $this->input->post('paid_amount',TRUE),
					'due_amount'      => $this->input->post('due_amount',TRUE),
					'prevous_due'     => $this->input->post('previous',TRUE),
					'shipping_cost'   => $this->input->post('shipping_cost',TRUE),
					'sales_by'        => $this->session->userdata('id'),
					'status'          => 1,
					'payment_type'    =>  $this->input->post('paytype',TRUE),
					'bank_id'         =>  (!empty($this->input->post('bank_id',TRUE))?$this->input->post('bank_id',TRUE):null),

					'delivery_multiple'         	=> $delim,
					'instore'         => $instore,
					'branchoffice'     				=> $this->input->post('branchoffice',TRUE),
					'fecha_entrega'         		=> date('Y-m-d', strtotime($this->input->post('dh_instore',TRUE))),
					'destinatario'         			=> '',
					'direccion'         			=> '',
					'lat'         					=> '',
					'lon'         					=> '',
					'direccion2'         			=> '',
					'telefono'         				=> '',
					'descripcion_entrega'         	=> '',


					'nombre_cliente'         	=> $this->input->post('nombre_cliente',TRUE),
					'telefono_cliente'         	=> $this->input->post('telefono_cliente',TRUE),

					'destinatario_reparto' 			=> '',
					'fecha_entrega_reparto' 		=> date('Y-m-d', strtotime($this->input->post('dh_instore',TRUE))),
					'direccion_reparto' 			=> '',
					'descripcion_entrega_reparto' 	=> '',
					'cliente_caja' 					=> $this->input->post('cliente_caja',TRUE),
					'fecha_entrega_caja' 			=> date('Y-m-d', strtotime($this->input->post('dh_instore',TRUE))),
					'destinatario_caja' 			=> '',
					'direccion_caja' 				=> '',
					'descripcion_entrega_caja' 		=> '',
					'repartidor_caja' 				=> '',
					'fecha_entrega_taller' 			=> date('Y-m-d', strtotime($this->input->post('dh_instore',TRUE))),
					'florista_taller' 				=> $this->input->post('florista_taller',TRUE),
					'cantidad_modelo' 				=> $this->input->post('cantidad_modelo',TRUE),
					'modelo' 						=> $this->input->post('modelo',TRUE),
					'cantidad_bases' 				=> $this->input->post('cantidad_bases',TRUE),
					'bases' 						=> $this->input->post('bases',TRUE),
					'cantidad_flores' 				=> $this->input->post('cantidad_flores',TRUE),
					'flores' 						=> $this->input->post('flores',TRUE),
					'cantidad_otros' 				=> $this->input->post('cantidad_otros',TRUE),
					'otros' 						=> $this->input->post('otros',TRUE),
					'descripcion_entrega_taller' 	=> $this->input->post('descripcion_entrega_taller',TRUE),
					'tipo_venta'     				=> $this->input->post('tipo_venta',TRUE),
					'tipo_pago'     				=> $this->input->post('tipo_pago',TRUE),

					'hora_entrega'     				=> date('h:i A', strtotime($this->input->post('dh_instore',TRUE))),
					'hora_entrega_reparto'     		=> date('h:i A', strtotime($this->input->post('dh_instore',TRUE))),
					'hora_entrega_caja'     		=> date('h:i A', strtotime($this->input->post('dh_instore',TRUE))),
					'hora_entrega_taller'     		=> date('h:i A', strtotime($this->input->post('dh_instore',TRUE))),
					'seller'    	  				=> $this->session->userdata('fullname'),
					'zona'       	  				=> '',
					'mensaje'       	  			=> $this->input->post('mensaje',TRUE),
					
					'cancelado'       	 => $cancelado,
					'motivo_cancelacion' => $this->input->post('motivo',TRUE),
				);





			}else{



				$datainv = array(
					'invoice_id'      => $invoice_id,
					'customer_id'     => $customer_id,
					'date'            => (!empty($this->input->post('invoice_date',TRUE))?$this->input->post('invoice_date',TRUE):date('Y-m-d')),
					'total_amount'    => $this->input->post('grand_total_price',TRUE),
					'total_tax'       => $this->input->post('total_tax',TRUE),
					'invoice'         => $invoice_no,
					'invoice_details' => (!empty($this->input->post('inva_details',TRUE))?$this->input->post('inva_details',TRUE):'Gracias por tu compra'),
					'invoice_discount'=> $this->input->post('invoice_discount',TRUE),
					'total_discount'  => $this->input->post('total_discount',TRUE),
					'paid_amount'     => $this->input->post('paid_amount',TRUE),
					'due_amount'      => $this->input->post('due_amount',TRUE),
					'prevous_due'     => $this->input->post('previous',TRUE),
					'shipping_cost'   => $this->input->post('shipping_cost',TRUE),
					'sales_by'        => $this->session->userdata('id'),
					'status'          => 1,
					'payment_type'    =>  $this->input->post('paytype',TRUE),
					'bank_id'         =>  (!empty($this->input->post('bank_id',TRUE))?$this->input->post('bank_id',TRUE):null),

					'delivery_multiple'         	=> $delim,
					'instore'         => $instore,
					'branchoffice'     				=> $this->input->post('branchoffice',TRUE),
					'fecha_entrega'         		=> $this->input->post('fecha_entrega',TRUE),
					'destinatario'         			=> $this->input->post('destinatario',TRUE),
					'direccion'         			=> $this->input->post('direccion',TRUE),
					'lat'         					=> $this->input->post('lat',TRUE),
					'lon'         					=> $this->input->post('lon',TRUE),
					'direccion2'         			=> $this->input->post('direccion2',TRUE),
					'telefono'         				=> $this->input->post('telefono',TRUE),
					'descripcion_entrega'         	=> $this->input->post('descripcion_entrega',TRUE),


					'nombre_cliente'         	=> $this->input->post('nombre_cliente',TRUE),
					'telefono_cliente'         	=> $this->input->post('telefono_cliente',TRUE),

					'destinatario_reparto' 			=> $this->input->post('destinatario_reparto',TRUE),
					'fecha_entrega_reparto' 		=> $this->input->post('fecha_entrega_reparto',TRUE),
					'direccion_reparto' 			=> $this->input->post('direccion_reparto',TRUE),
					'descripcion_entrega_reparto' 	=> $this->input->post('descripcion_entrega_reparto',TRUE),
					'cliente_caja' 					=> $this->input->post('cliente_caja',TRUE),
					'fecha_entrega_caja' 			=> $this->input->post('fecha_entrega_caja',TRUE),
					'destinatario_caja' 			=> $this->input->post('destinatario_caja',TRUE),
					'direccion_caja' 				=> $this->input->post('direccion_caja',TRUE),
					'descripcion_entrega_caja' 		=> $this->input->post('descripcion_entrega_caja',TRUE),
					'repartidor_caja' 				=> $this->input->post('repartidor_caja',TRUE),
					'fecha_entrega_taller' 			=> $this->input->post('fecha_entrega_taller',TRUE),
					'florista_taller' 				=> $this->input->post('florista_taller',TRUE),
					'cantidad_modelo' 				=> $this->input->post('cantidad_modelo',TRUE),
					'modelo' 						=> $this->input->post('modelo',TRUE),
					'cantidad_bases' 				=> $this->input->post('cantidad_bases',TRUE),
					'bases' 						=> $this->input->post('bases',TRUE),
					'cantidad_flores' 				=> $this->input->post('cantidad_flores',TRUE),
					'flores' 						=> $this->input->post('flores',TRUE),
					'cantidad_otros' 				=> $this->input->post('cantidad_otros',TRUE),
					'otros' 						=> $this->input->post('otros',TRUE),
					'descripcion_entrega_taller' 	=> $this->input->post('descripcion_entrega_taller',TRUE),
					'tipo_venta'     				=> $this->input->post('tipo_venta',TRUE),
					'tipo_pago'     				=> $this->input->post('tipo_pago',TRUE),

					'hora_entrega'     				=> $this->input->post('hora_entrega',TRUE),
					'hora_entrega_reparto'     		=> $this->input->post('hora_entrega_reparto',TRUE),
					'hora_entrega_caja'     		=> $this->input->post('hora_entrega_caja',TRUE),
					'hora_entrega_taller'     		=> $this->input->post('hora_entrega_taller',TRUE),
					'seller'    	  				=> $this->session->userdata('fullname'),
					'zona'       	  				=> $this->input->post('zona',TRUE),
					'mensaje'       	  			=> $this->input->post('mensaje',TRUE),
					
					'cancelado'       	 => $cancelado,
					'motivo_cancelacion' => $this->input->post('motivo',TRUE),
				);
			}

			$config_data = $this->db->select('*')->from('sms_settings')->get()->row();

		}



		$this->db->insert('invoice', $datainv);


		if($delim=='1'){

			$id_arreglos 			= $this->input->post('id_arreglo',TRUE);
			$arreglo 				= $this->input->post('arreglo',TRUE);
			$fecha_entrega 		= $this->input->post('fecha_entrega',TRUE);

			$hora_entrega 		    = $this->input->post('hora_entrega',TRUE);

			$mensaje 		    	= $this->input->post('mensaje',TRUE);

			$destinatario 			= $this->input->post('destinatario',TRUE);
			$direccion 			= $this->input->post('direccion',TRUE);
			$lat 					= $this->input->post('lat',TRUE);
			$lon 					= $this->input->post('lon',TRUE);
			$direccion2 			= $this->input->post('direccion2',TRUE);


			$radius 				= $this->input->post('radius',TRUE);
			$telefono 				= $this->input->post('telefono',TRUE);
			$descripcion_entrega 	= $this->input->post('descripcion_entrega',TRUE); 

			$nombre_cliente 	= $this->input->post('nombre_cliente',TRUE); 
			$telefono_cliente 	= $this->input->post('telefono_cliente',TRUE); 


			$cliente_caja 	= $this->input->post('cliente_caja',TRUE);
			$repartidor_caja 	= $this->input->post('repartidor_caja',TRUE);

			$cantidad_bases	= $this->input->post('cantidad_bases',TRUE);
			$cantidad_modelo	= $this->input->post('cantidad_modelo',TRUE);
			$cantidad_flores	= $this->input->post('cantidad_flores',TRUE);
			$cantidad_otros	= $this->input->post('cantidad_otros',TRUE);
			$bases	= $this->input->post('bases',TRUE);
			$modelo	= $this->input->post('modelo',TRUE);
			$flores	= $this->input->post('flores',TRUE);
			$otros	= $this->input->post('otros',TRUE);
			$florista_taller	= $this->input->post('florista_taller',TRUE);
			$zona	= $this->input->post('zona',TRUE);



			$cont1 = 0;

			$config_data = $this->db->select('*')->from('sms_settings')->get()->row();


			foreach ($id_arreglos as $id_arreglo){

				$data_entrega = array(
					'invoice_id'    => $invoice_id,
					'customer_id'   => $customer_id,
					'id_arreglo'    => $id_arreglo,
					'arreglo'     	=> $arreglo[$cont1],
					'fecha_entrega' => $fecha_entrega[$cont1],
					'hora_entrega'  => $hora_entrega[$cont1],
					'destinatario' 	=> $destinatario[$cont1],
					'direccion' 	=> $direccion[$cont1],
					'lat' 			=> $lat[$cont1],
					'lon' 			=> $lon[$cont1],
					'direccion2' 	=> $direccion2[$cont1],
					'radius' 		=> $radius[$cont1],
					'telefono' 		=> $telefono[$cont1],

					'nombre_cliente' 	=> $nombre_cliente[$cont1],
					'telefono_cliente' 	=> $telefono_cliente[$cont1],					 

					'atencion' 		=> $this->session->userdata('fullname'),
					'descripcion_entrega' => $descripcion_entrega[$cont1],
					'zona' 			=> $zona[$cont1],
					'mensaje' 		=> $mensaje[$cont1]
				);				 
				$this->db->insert('invoice_entrega', $data_entrega);


				$data_reparto = array(
					'invoice_id'    => $invoice_id,
					'customer_id'   => $customer_id,
					'id_arreglo'    => $id_arreglo,
					'arreglo'     	=> $arreglo[$cont1],
					'fecha_entrega' => $fecha_entrega[$cont1],
					'hora_entrega'  => $hora_entrega[$cont1],
					'destinatario' 	=> $destinatario[$cont1],
					'direccion' 	=> $direccion[$cont1],
					'telefono' 		=> $telefono[$cont1],
					'atencion' 		=> $this->session->userdata('fullname'),
					'descripcion_entrega' => $descripcion_entrega[$cont1],
					'mensaje' 		=> $mensaje[$cont1]
				);				 
				$this->db->insert('invoice_reparto', $data_reparto);


				$data_caja = array(
					'invoice_id'    => $invoice_id,
					'customer_id'   => $customer_id,
					'id_arreglo'    => $id_arreglo,
					'arreglo'     	=> $arreglo[$cont1],
					'cliente'     	=> $cliente_caja[$cont1],
					'fecha_entrega' => $fecha_entrega[$cont1],
					'hora_entrega'  => $hora_entrega[$cont1],
					'destinatario' 	=> $destinatario[$cont1],
					'direccion' 	=> $direccion[$cont1],
					'repartidor' 	=> $repartidor_caja[$cont1],
					'atencion' 		=> $this->session->userdata('fullname'),
					'descripcion_entrega' => $descripcion_entrega[$cont1],
					'mensaje' 		=> $mensaje[$cont1]
				);				 
				$this->db->insert('invoice_caja', $data_caja);



				$data_taller = array(
					'invoice_id'    => $invoice_id,
					'customer_id'   => $customer_id,
					'id_arreglo'    => $id_arreglo,
					'florista'		=> $florista_taller[$cont1],
					'arreglo'     	=> $arreglo[$cont1],
					'fecha_entrega' => $fecha_entrega[$cont1],
					'hora_entrega'  => $hora_entrega[$cont1],
					'destinatario' 	=> $destinatario[$cont1],
					'direccion' 	=> $direccion[$cont1],
					'telefono'		=> $telefono[$cont1],
					'descripcion_entrega' 	=> $descripcion_entrega[$cont1],					 
					'cantidad_modelo'		=> $cantidad_modelo[$cont1],
					'cantidad_bases'		=> $cantidad_bases[$cont1],
					'cantidad_flores'		=> $cantidad_flores[$cont1],
					'cantidad_otros'		=> $cantidad_otros[$cont1],
					'modelo'				=> $modelo[$cont1],
					'bases'					=> $bases[$cont1],
					'flores'				=> $flores[$cont1],
					'otros'					=> $otros[$cont1],					 
					'atencion' 		=> $this->session->userdata('fullname'),
					'mensaje' 		=> $mensaje[$cont1]

				);				 
				$this->db->insert('invoice_taller', $data_taller);

				$cont1++;

			}	
		}



		$prinfo  = $this->db->select('product_id,Avg(rate) as product_rate')->from('product_purchase_details')->where_in('product_id',$product_id)->group_by('product_id')->get()->result(); 

		$purchase_ave = [];

		$i=0;

		foreach ($prinfo as $avg) {
			$purchase_ave [] =  $avg->product_rate*$quantity[$i];
			$i++;
		}

		$sumval   = array_sum($purchase_ave);
		$cusifo   = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();
		$headn    = $customer_id.'-'.$cusifo->customer_name;
		$coainfo  = $this->db->select('*')->from('acc_coa')->where('HeadName',$headn)->get()->row();
		$customer_headcode = $coainfo->HeadCode;

		$cc = array(
			'VNo'            =>  $invoice_id,
			'Vtype'          =>  'INV',
			'VDate'          =>  $createdate,
			'COAID'          =>  1020101,
			'Narration'      =>  'Cash in Hand in Sale for Invoice No - '.$invoice_no_generated.' customer- '.$cusifo->customer_name,
			'Debit'          =>  $paidamount,
			'Credit'         =>  0,
			'IsPosted'       =>  1,
			'CreateBy'       =>  $createby,
			'CreateDate'     =>  $createdate,
			'IsAppove'       =>  1
		); 


		$bankc = array(
			'VNo'            =>  $invoice_id,
			'Vtype'          =>  'INVOICE',
			'VDate'          =>  $createdate,
			'COAID'          =>  $bankcoaid,
			'Narration'      =>  'Paid amount for customer  Invoice No - '.$invoice_no_generated.' customer -'.$cusifo->customer_name,
			'Debit'          =>  $paidamount,
			'Credit'         =>  0,
			'IsPosted'       =>  1,
			'CreateBy'       =>  $createby,
			'CreateDate'     =>  $createdate,
			'IsAppove'       =>  1
		); 

		///Inventory credit
		$coscr = array(
			'VNo'            =>  $invoice_id,
			'Vtype'          =>  'INV',
			'VDate'          =>  $createdate,
			'COAID'          =>  10107,
			'Narration'      =>  'Inventory credit For Invoice No'.$invoice_no_generated,
			'Debit'          =>  0,
		'Credit'         =>  $sumval,//purchase price asbe
		'IsPosted'       => 1,
		'CreateBy'       => $createby,
		'CreateDate'     => $createdate,
		'IsAppove'       => 1
		); 
		$this->db->insert('acc_transaction',$coscr);


		$cosdr = array(
			'VNo'            =>  $invoice_id,
			'Vtype'          =>  'INV',
			'VDate'          =>  $createdate,
			'COAID'          =>  $customer_headcode,
			'Narration'      =>  'Customer debit For Invoice No -  '.$invoice_no_generated.' Customer '.$cusifo->customer_name,
			'Debit'          =>  $this->input->post('n_total',TRUE)-(!empty($this->input->post('previous',TRUE))?$this->input->post('previous',TRUE):0),
			'Credit'         =>  0,
			'IsPosted'       => 1,
			'CreateBy'       => $createby,
			'CreateDate'     => $createdate,
			'IsAppove'       => 1
		); 
		$this->db->insert('acc_transaction',$cosdr);

		$total_saleamnt = $this->input->post('n_total',TRUE)-(!empty($this->input->post('previous',TRUE))?$this->input->post('previous',TRUE):0);

		$withoutinventory = $total_saleamnt - $sumval;

		$income = $withoutinventory - $this->input->post('total_tax',TRUE);

		$pro_sale_income = array(
			'VNo'            =>  $invoice_id,
			'Vtype'          =>  'INVOICE',
			'VDate'          =>  $createdate,
			'COAID'          =>  303,
			'Narration'      =>  'Sale Income For Invoice NO - '.$invoice_no_generated.' Customer '.$cusifo->customer_name,
			'Debit'          =>  0,
			'Credit'         =>  $income,
			'IsPosted'       => 1,
			'CreateBy'       => $createby,
			'CreateDate'     => $createdate,
			'IsAppove'       => 1
		); 
		$this->db->insert('acc_transaction',$pro_sale_income);

		$tax_info = array(
			'VNo'            =>  $invoice_id,
			'Vtype'          =>  'INVOICE',
			'VDate'          =>  $createdate,
			'COAID'          =>  50203,
			'Narration'      =>  'Sale Income For Invoice NO - '.$invoice_no_generated.' Customer '.$cusifo->customer_name,
			'Debit'          =>  $this->input->post('total_tax',TRUE),
			'Credit'         =>  0,
			'IsPosted'       => 1,
			'CreateBy'       => $createby,
			'CreateDate'     => $createdate,
			'IsAppove'       => 1
		); 
		$this->db->insert('acc_transaction',$tax_info);

		///Customer credit for Paid Amount
		$cuscredit = array(
			'VNo'            =>  $invoice_id,
			'Vtype'          =>  'INV',
			'VDate'          =>  $createdate,
			'COAID'          =>  $customer_headcode,
			'Narration'      =>  'Customer credit for Paid Amount For Customer Invoice NO- '.$invoice_no_generated.' Customer- '.$cusifo->customer_name,
			'Debit'          =>  0,
			'Credit'         =>  $paidamount,
			'IsPosted'       => 1,
			'CreateBy'       => $createby,
			'CreateDate'     => $createdate,
			'IsAppove'       => 1
		); 
		if(!empty($this->input->post('paid_amount',TRUE))){
			$this->db->insert('acc_transaction',$cuscredit);

			if($this->input->post('paytype',TRUE) == 2){
				$this->db->insert('acc_transaction',$bankc);

			}
			if($this->input->post('paytype',TRUE) == 1){
				$this->db->insert('acc_transaction',$cc);
			}

		}
		$customerinfo = $this->db->select('*')->from('customer_information')->where('customer_id',$customer_id)->get()->row();

		$rate                = $this->input->post('product_rate',TRUE);
		$p_id                = $this->input->post('product_id',TRUE);
		$total_amount        = $this->input->post('total_price',TRUE);
		$discount_rate       = $this->input->post('discount_amount',TRUE);
		$discount_per        = $this->input->post('discount',TRUE);
		$tax_amount          = $this->input->post('tax',TRUE);
		$invoice_description = $this->input->post('desc',TRUE);
		$serial_n            = $this->input->post('serial_no',TRUE);

		$txt_products = '(';

		for ($i = 0, $n = count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate     = $rate[$i];
			$product_id       = $p_id[$i];
			$serial_no        = (!empty($serial_n[$i])?$serial_n[$i]:null);
			$total_price      = $total_amount[$i];
			$supplier_rate    = $this->supplier_price($product_id);
			$disper           = $discount_per[$i];
			$discount         = is_numeric($product_quantity) * is_numeric($product_rate) * is_numeric($disper) / 100;
			$tax              = $tax_amount[$i];
			$description      = (!empty($invoice_description)?$invoice_description[$i]:null);

			$data1 = array(
				'invoice_details_id' => $this->generator(15),
				'invoice_id'         => $invoice_id,
				'product_id'         => $product_id,
				'serial_no'          => $serial_no,
				'quantity'           => $product_quantity,
				'rate'               => $product_rate,
				'discount'           => $discount,
				'description'        => $description,
				'discount_per'       => $disper,
				'tax'                => $tax,
				'paid_amount'        => $paidamount,
				'due_amount'         => $this->input->post('due_amount',TRUE),
				'supplier_rate'      => $supplier_rate,
				'total_price'        => $total_price,
				'status'             => 1
			);
			if (!empty($quantity)) {
				$this->db->insert('invoice_details', $data1);
			}		
		}

		return $invoice_id;

	}

	
	


	







    public function pos_invoice_setup($product_id) {
        $product_information = $this->db->select('*')
                ->from('product_information')
                ->join('supplier_product', 'product_information.product_id = supplier_product.product_id', 'left')
                ->where('product_information.product_id', $product_id)
                ->get()
                ->row();

        if ($product_information != null) {

            $this->db->select('SUM(a.quantity) as total_purchase');
            $this->db->from('product_purchase_details a');
            $this->db->where('a.product_id', $product_id);
            $total_purchase = $this->db->get()->row();

            $this->db->select('SUM(b.quantity) as total_sale');
            $this->db->from('invoice_details b');
            $this->db->where('b.product_id', $product_id);
            $total_sale = $this->db->get()->row();

            $available_quantity = 100; //($total_purchase->total_purchase - $total_sale->total_sale);
          
          $data2 = (object) array(
                        'total_product'  => $available_quantity,
                        'supplier_price' => $product_information->supplier_price,
                        'price'          => $product_information->price,
                        'supplier_id'    => $product_information->supplier_id,
                        'product_id'     => $product_id, //$product_information->product_id,
                        'product_name'   => $product_information->product_name,
                        'product_model'  => $product_information->product_model,
                        'unit'           => $product_information->unit,
                        'tax'            => $product_information->tax,
                        'image'          => $product_information->image,
                        'serial_no'      => $product_information->serial_no,
            );

        

            return $data2;
        } else {
            return false;
        }
    }



 public function searchprod($cid)
    { 
        $this->db->select('*');
        $this->db->from('product_information');
        if($cid !='all'){
        $this->db->where('category_id',$cid);
      }
        $this->db->order_by('product_name','asc');
        $query   = $this->db->get();
        $itemlist=$query->result();
        if($cid = ''){
          return false;
        }else{
           return $itemlist;
        }
       
    }
	




	
	public function searchprodins($cid){ 
		
        $this->db->select('a.*');
		$this->db->from('product_information a');
        $this->db->join('insumo_product b', 'b.product_id = a.product_id');
        $this->db->where('b.insumo_id', $cid);  
        $this->db->order_by('a.product_name','asc');
        $query   = $this->db->get();
        $itemlist=$query->result();
        if($cid = ''){
          return false;
        }else{
           return $itemlist;
        }
       
    }
	





	






 public function searchprod_byname($pname= null)
    { 
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->like('product_name',$pname);
        $this->db->order_by('product_name','asc');
        $this->db->limit(20);
        $query = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
    }


    public function walking_customer(){
       return $data = $this->db->select('*')->from('customer_information')->like('customer_name','walking','after')->get()->result_array();
    }

        public function category_dropdown()
    {
        $data = $this->db->select("*")
            ->from('product_category')
            ->get()
            ->result();

        $list = array('' => 'select_category');
        if (!empty($data)) {
            foreach($data as $value)
                $list[$value->category_id] = $value->category_name;
            return $list;
        } else {
            return false; 
        }
    }

     public function category_list() {
        $this->db->select('*');
        $this->db->from('product_category');
        $this->db->where('status',1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

      //Retrieve company Edit Data
    public function retrieve_company() {
        $this->db->select('*');
        $this->db->from('company_information');
        $this->db->limit('1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

       public function retrieve_setting_editdata() {
        $this->db->select('*');
        $this->db->from('web_setting');
        $this->db->where('setting_id', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
        //Get Supplier rate of a product
    public function supplier_rate($product_id) {
        $this->db->select('supplier_price');
        $this->db->from('supplier_product');
        $this->db->where(array('product_id' => $product_id));
        $query = $this->db->get();
        return $query->result_array();

        $this->db->select('Avg(rate) as supplier_price');
        $this->db->from('product_purchase_details');
        $this->db->where(array('product_id' => $product_id));
        $query = $this->db->get()->row();
        return $query->result_array();
    }

     public function supplier_price($product_id) {
        $this->db->select('supplier_price');
        $this->db->from('supplier_product');
        $this->db->where(array('product_id' => $product_id));
        $supplier_product = $this->db->get()->row();
   

        $this->db->select('Avg(rate) as supplier_price');
        $this->db->from('product_purchase_details');
        $this->db->where(array('product_id' => $product_id));
        $purchasedetails = $this->db->get()->row();
      $price = (!empty($purchasedetails->supplier_price)?$purchasedetails->supplier_price:$supplier_product->supplier_price);
 
        return (!empty($price)?$price:0);
    }


        public function autocompletproductdata($product_name){
            $query=$this->db->select('*')
                ->from('product_information')
                ->like('product_name', $product_name, 'both')
                ->or_like('product_model', $product_name, 'both')
                ->order_by('product_name','asc')
                ->limit(15)
                ->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();  
        }
        return false;
    }


        public function retrieve_invoice_html_data($invoice_id) {
        $this->db->select('a.total_tax,
                        a.*,
                        b.*,
                        c.*,
                        d.product_id,
                        d.product_name,
                        d.product_details,
                        d.unit,
                        d.product_model,
                        a.paid_amount as paid_amount,
                        a.due_amount as due_amount'
                    );
        $this->db->from('invoice a');
        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->join('product_information d', 'd.product_id = c.product_id');
        $this->db->where('a.invoice_id', $invoice_id);
        $this->db->where('c.quantity >', 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
	


	public function reparto_invoice_html_data($invoice_id) {
        $this->db->select('*, b.zona as nombre_zona');
        $this->db->from('invoice_entrega a');
		$this->db->join('zonas b', 'b.id = a.zona', 'left');
        $this->db->where('a.invoice_id', $invoice_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
	

	public function caja_invoice_html_data($invoice_id) {
        $this->db->select('*, CONCAT(b.first_name, " ",b.last_name) AS nombre_repartidor');
        $this->db->from('invoice_caja a');
		$this->db->join('deliveryman b', 'b.id = a.repartidor');
        $this->db->where('a.invoice_id', $invoice_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
	

	public function taller_invoice_html_data($invoice_id) {
        $this->db->select('*, CONCAT(b.first_name, " ",b.last_name) AS nombre_florista');
        $this->db->from('invoice_taller a');
		$this->db->join('florist b', 'b.id = a.florista');
        $this->db->where('a.invoice_id', $invoice_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
	




	



     public function user_invoice_data($user_id){
   return  $this->db->select('*')->from('users')->where('user_id',$user_id)->get()->row();
 }

   // product information retrieve by product id
    public function get_total_product_invoic($product_id) {
        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('product_purchase_details a');
        $this->db->where('a.product_id', $product_id);
        $total_purchase = $this->db->get()->row();

        $this->db->select('SUM(b.quantity) as total_sale');
        $this->db->from('invoice_details b');
        $this->db->where('b.product_id', $product_id);
        $total_sale = $this->db->get()->row();

        $this->db->select('a.*,b.*');
        $this->db->from('product_information a');
        $this->db->join('supplier_product b', 'a.product_id=b.product_id');
        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));
        $product_information = $this->db->get()->row();

        $available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);
        $tablecolumn = $this->db->list_fields('tax_collection');
               $num_column = count($tablecolumn)-4;
  $taxfield='';
  $taxvar = [];
   for($i=0;$i<$num_column;$i++){
    $taxfield = 'tax'.$i;
    $data2[$taxfield] = (!empty($product_information->$taxfield)?$product_information->$taxfield:0);
    $taxvar[$i]       = (!empty($product_information->$taxfield)?$product_information->$taxfield:0);
    $data2['taxdta']  = $taxvar;
   }

    $content =explode(',', $product_information->serial_no);

        $html = "";
        if (empty($content)) {
            $html .="No Serial Found !";
        }else{
            // Select option created for product
            $html .="<select name=\"serial_no[]\"   class=\"serial_no_1 form-control\" id=\"serial_no_1\">";
                $html .= "<option value=''>".display('select_one')."</option>";
                foreach ($content as $serial) {
                    $html .="<option value=".$serial.">".$serial."</option>";
                }   
            $html .="</select>";
        }

       
            $data2['total_product']  = $available_quantity;
            $data2['supplier_price'] = $product_information->supplier_price;
            $data2['price']          = $product_information->price;
            $data2['supplier_id']    = $product_information->supplier_id;
            $data2['unit']           = $product_information->unit;
            $data2['tax']            = $product_information->tax;
            $data2['serial']         = $html;
            $data2['txnmber']        = $num_column;
        

        return $data2;
    }

        public function generator($lenth) {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 8);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }


       public function stock_qty_check($product_id){
        $this->db->select('SUM(a.quantity) as total_purchase');
        $this->db->from('product_purchase_details a');
        $this->db->where('a.product_id', $product_id);
        $total_purchase = $this->db->get()->row();

        $this->db->select('SUM(b.quantity) as total_sale');
        $this->db->from('invoice_details b');
        $this->db->where('b.product_id', $product_id);
        $total_sale = $this->db->get()->row();

        $this->db->select('a.*,b.*');
        $this->db->from('product_information a');
        $this->db->join('supplier_product b', 'a.product_id=b.product_id');
        $this->db->where(array('a.product_id' => $product_id, 'a.status' => 1));
        $product_information = $this->db->get()->row();

        $available_quantity = ($total_purchase->total_purchase - $total_sale->total_sale);
        return (!empty($available_quantity)?$available_quantity:0);

    }


    public function bdtask_invoice_pos_print_direct($invoice_id = null){
        $invoice_detail = $this->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
                ->from('tax_settings')
                ->where('is_show',1)
                ->get()
                ->result_array();
        $txregname ='';
        foreach($taxfield as $txrgname){
        $regname = $txrgname['tax_name'].' Reg No  - '.$txrgname['reg_no'].', ';
        $txregname .= $regname;
        }  
        $subTotal_quantity = 0;
        $subTotal_cartoon = 0;
        $subTotal_discount = 0;
        $subTotal_ammount = 0;
        $descript = 0;
        $isserial = 0;
        $is_discount = 0;
        $isunit = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $this->occational->dateConvert($invoice_detail[$k]['date']);
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }

            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                 if(!empty($invoice_detail[$k]['description'])){
                    $descript = $descript+1;
                    
                }
                 if(!empty($invoice_detail[$k]['serial_no'])){
                    $isserial = $isserial+1;
                    
                }
                 if(!empty($invoice_detail[$k]['unit'])){
                    $isunit = $isunit+1;
                    
                }
                    if(!empty($invoice_detail[$k]['discount_per'])){
                    $is_discount = $is_discount+1;
                    
                }
            }
        }

        
        $totalbal = $invoice_detail[0]['total_amount']+$invoice_detail[0]['prevous_due'];
        $user_id  = $invoice_detail[0]['sales_by'];
        $currency_details = $this->retrieve_setting_editdata();
        $users    = $this->user_invoice_data($user_id);
        $data = array(
        'title'                => display('pos_print'),
        'invoice_id'           => $invoice_detail[0]['invoice_id'],
        'invoice_no'           => $invoice_detail[0]['invoice'],
        'customer_name'        => $invoice_detail[0]['customer_name'],
        'customer_address'     => $invoice_detail[0]['customer_address'],
        'customer_mobile'      => $invoice_detail[0]['customer_mobile'],
        'customer_email'       => $invoice_detail[0]['customer_email'],
        'final_date'           => $invoice_detail[0]['final_date'],
        'invoice_details'      => $invoice_detail[0]['invoice_details'],
        'total_amount'         => number_format($totalbal, 2, '.', ','),
        'subTotal_cartoon'     => $subTotal_cartoon,
        'subTotal_quantity'    => $subTotal_quantity,
        'invoice_discount'     => number_format($invoice_detail[0]['invoice_discount'], 2, '.', ','),
        'total_discount'       => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
        'total_tax'            => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
        'subTotal_ammount'     => number_format($subTotal_ammount, 2, '.', ','),
        'paid_amount'          => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
        'due_amount'           => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
        'shipping_cost'        => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
        'invoice_all_data'     => $invoice_detail,
        'previous'             => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
         'is_discount'         => $is_discount,
        'users_name'           => $users->first_name.' '.$users->last_name,
        'tax_regno'            => $txregname,
        'is_desc'              => $descript,
        'is_serial'            => $isserial,
        'is_unit'              => $isunit,
        'company_info'         => $this->retrieve_company(),
        'currency'             => $currency_details[0]['currency'],
        'position'             => $currency_details[0]['currency_position'],
        'discount_type'        => $currency_details[0]['discount_type'],
        'logo'                 => $currency_details[0]['invoice_logo'],

        );

       return $data;

    }


       public function product_list() {
        $this->db->select('*');
        $this->db->from('product_information');
        $this->db->where('status',1);
        $this->db->limit(30);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }

    public function bdtask_print_settingdata(){
        $this->db->select('*');
        $this->db->from('print_setting');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }
	


	public function create_deliveryman($data = []){
        $this->db->insert('deliveryman',$data);          
        return true;
     }
	
	public function update_deliveryman($data = []){
         return $this->db->where('id',$data['id'])
            ->update('deliveryman',$data); 
            
    }
	

	public function single_deliveryman_data($id){
        return $this->db->select('*')
            ->from('deliveryman')
            ->where('id', $id)
            ->get()
            ->row();
     }
	

	public function deliveryman_list(){
        $this->db->select('*');
        $this->db->from('deliveryman');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
   }
	
	public function delete_deliveryman($id){
        $this->db->where('id', $id)
            ->delete("deliveryman");
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
	


	public function alldeliveryman(){
		$this->db->select('*');
		$this->db->from('deliveryman');
		$this->db->order_by('id','asc');
		$query   = $this->db->get();
		$itemlist=$query->result();
		return $itemlist;
	}
	

	



	public function create_branchoffice($data = []){
        $this->db->insert('branchoffice',$data);          
        return true;
     }
	
	public function update_branchoffice($data = []){
         return $this->db->where('id',$data['id'])
            ->update('branchoffice',$data); 
            
    }
	

	public function single_branchoffice_data($id){
        return $this->db->select('*')
            ->from('branchoffice')
            ->where('id', $id)
            ->get()
            ->row();
     }
	

	public function branchoffice_list(){
        $this->db->select('*');
        $this->db->from('branchoffice');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
   }
	
	public function delete_branchoffice($id){
        $this->db->where('id', $id)
            ->delete("branchoffice");
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
	


	public function allbranchoffice(){
		$this->db->select('*');
		$this->db->from('branchoffice');
		$this->db->order_by('id','asc');
		$query   = $this->db->get();
		$itemlist=$query->result();
		return $itemlist;
	}

	
	





	public function create_florist($data = []){
        $this->db->insert('florist',$data);          
        return true;
     }
    
    public function update_florist($data = []){
         return $this->db->where('id',$data['id'])
            ->update('florist',$data); 
            
    }
    

    public function single_florist_data($id){
        return $this->db->select('*')
            ->from('florist')
            ->where('id', $id)
            ->get()
            ->row();
     }
    

    public function florist_list(){
        $this->db->select('a.*,b.branchoffice');		
        $this->db->from('florist a');
		$this->db->join('branchoffice b', 'a.branchoffice=b.id', 'left');
        $this->db->order_by('a.id', 'DESC');
        $query = $this->db->get();
		
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
   }
    
    public function delete_florist($id){
        $this->db->where('id', $id)
            ->delete("florist");
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
    


    public function allflorist(){
        $this->db->select('*');
        $this->db->from('florist');
        $this->db->order_by('id','asc');
        $query   = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
    }	
	


 	public function getmincat(){
        $this->db->select_min('category_id');
        $this->db->from('product_category');
        $query   = $this->db->get();
        $result=$query->result();       
        return $result[0]->category_id;
    }
	

	public function itemscategory($cat){
		
		$this->db->select('*');
        $this->db->from('product_information');
		$this->db->where('category_id',$cat);
        $this->db->order_by('product_name','asc');        
        $query   = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
		
    }
	



	public function details_product($id){		
        $this->db->select('a.cantidad,a.price, a.total, b.product_name, b.product_model');        
        $this->db->from('insumo_product a');
        $this->db->join('insumo_information b', 'a.insumo_id=b.id', 'left');
        $this->db->where('a.product_id', $id);
        $this->db->order_by('b.product_name','asc');
        $query   = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
    }
	

	

	public function entregar_invoice_product($invoice, $product){
        return $this->db->set('delivery_status', '1')
					->where('invoice_id', $invoice)
					->where('product_id', $product)
            		->update('invoice_details'); 
    }
	


	public function mover_pagado_invoice($invoice, $tipo){
		

		$this->db->select('total_amount');
        $this->db->from('invoice');
		$this->db->where('invoice_id', $invoice);
        $query  = $this->db->get();
        $result=$query->result();       
        $total_amount =  $result[0]->total_amount;
		

        return $this->db->set('tipo_pago', $tipo)
					->set('paid_amount', $total_amount)
					->set('due_amount', 0)
					->where('invoice_id', $invoice)
            		->update('invoice'); 
    }

	public function florista_invoice_product($invoice, $product){
        return $this->db->set('florist_status', '1')
					->where('invoice_id', $invoice)
					->where('product_id', $product)
            		->update('invoice_details'); 
    }
	







	public function create_zona($data = []){
        $this->db->insert('zonas',$data);          
        return true;
     }
	
	public function update_zona($data = []){
         return $this->db->where('id',$data['id'])
            ->update('zonas',$data); 
            
    }
	

	public function single_zona_data($id){
        return $this->db->select('*')
            ->from('zonas')
            ->where('id', $id)
            ->get()
            ->row();
     }
	

	public function zona_list(){
        $this->db->select('*');
        $this->db->from('zonas');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
   }
	
	public function delete_zona($id){
        $this->db->where('id', $id)
            ->delete("zonas");
        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
	


	public function allzonas(){
		$this->db->select('*');
		$this->db->from('zonas');
		$this->db->order_by('id','asc');
		$query   = $this->db->get();
		$itemlist=$query->result();
		return $itemlist;
	}
	

	
	public function insumo_list() {
		$this->db->select('*');
		$this->db->from('insumo_information');
		$this->db->order_by('product_name', 'asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	

	public function create_product($data = []){
        return $this->db->insert('product_information',$data);
    }
	


	public function delete_invoice($invoice){
        $this->db->where('invoice_id', $invoice)
            ->delete("invoice");
		
		$this->db->where('invoice_id', $invoice)
            ->delete("invoice_details");
		
		$this->db->where('VNo', $invoice)
            ->delete("acc_transaction");		
		
		$this->db->where('invoice_id', $invoice)
            ->delete("invoice_caja");
		$this->db->where('invoice_id', $invoice)
            ->delete("invoice_entrega");
		$this->db->where('invoice_id', $invoice)
            ->delete("invoice_reparto");
		$this->db->where('invoice_id', $invoice)
            ->delete("invoice_taller");

     }
	

	public function get_invoice_data($invoice){
		return $this->db->select('*')
            ->from('invoice')
            ->where('invoice_id', $invoice)
            ->get()
            ->row();
	}
	



	public function get_invoice_details($invoice){	
		$this->db->select('*');
		$this->db->from('invoice_details');
		$this->db->where('invoice_id', $invoice);	
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	public function get_invoice_caja($invoice){
		$this->db->select('*');
		$this->db->from('invoice_caja');
		$this->db->where('invoice_id', $invoice);	
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}


	public function get_invoice_entrega($invoice){
		$this->db->select('*');
		$this->db->from('invoice_entrega');
		$this->db->where('invoice_id', $invoice);	
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	public function get_invoice_reparto($invoice){
		$this->db->select('*');
		$this->db->from('invoice_reparto');
		$this->db->where('invoice_id', $invoice);	
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}

	public function get_invoice_taller($invoice){
		$this->db->select('*');
		$this->db->from('invoice_taller');
		$this->db->where('invoice_id', $invoice);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}
	

	public function all_flores(){
		$this->db->select('*');
		$this->db->from('insumo_information');
		$this->db->where('category_id', '4');
		$this->db->or_where('category_id', '5');
		$this->db->or_where('category_id', '6');
		$this->db->or_where('category_id', '7');
		$this->db->or_where('category_id', '8');
		$this->db->or_where('category_id', '10');
		
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			//return $query->result_array();
			
			$insumos = array();
			foreach ($query->result_array() as $insumo) {
				
				$this->db->select('a.*');
				$this->db->from('product_information a');
				$this->db->join('insumo_product b', 'b.product_id = a.product_id');
				$this->db->where('b.insumo_id', $insumo['id']);  
				$this->db->order_by('a.product_name','asc');
				$query   = $this->db->get();
				$itemlist= $query->result_array();
				if(count($itemlist)>0){					
					array_push($insumos, $insumo);
				}
				
			}
			
			return $insumos;
			
			

		}
		return false;
	} 
	

	public function all_bases(){
		$this->db->select('*');
		$this->db->from('insumo_information');
		$this->db->where('category_id', '13');
				
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			//return $query->result_array();
			
			$insumos = array();
			foreach ($query->result_array() as $insumo) {
				
				$this->db->select('a.*');
				$this->db->from('product_information a');
				$this->db->join('insumo_product b', 'b.product_id = a.product_id');
				$this->db->where('b.insumo_id', $insumo['id']);  
				$this->db->order_by('a.product_name','asc');
				$query   = $this->db->get();
				$itemlist= $query->result_array();
				if(count($itemlist)>0){					
					array_push($insumos, $insumo);
				}				
			}			
			return $insumos;
		}
		return false;
	}
	

	public function all_customers_invoice() {
        $this->db->select('
            customer_id as invoice_id, 
            create_date as date, 
            customer_name as nombre_cliente,
            customer_mobile,
            customer_email,
            custom_discount as invoice_discount,
        ');
        $this->db->from('customer_information');
        $this->db->order_by('create_date', 'DESC');
        $this->db->limit(20);
        return $this->db->get()->result_array();
    }
	

	

	




	public function getInvoiceList_efectivo($postData=null){
         $response = array();
         $usertype = $this->session->userdata('user_type');
         $fromdate = $this->input->post('fromdate',TRUE);
         $todate   = $this->input->post('todate',TRUE);
		
		 $seller   = $this->input->post('seller',TRUE);
		
         if(!empty($fromdate)){
            $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
         }else{
            $datbetween = "";
         }
		
		
         ## Read value
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         ## Search 
         $searchQuery = "";
         if($searchValue != ''){
            $searchQuery = " (b.customer_name like '%".$searchValue."%' or a.invoice like '%".$searchValue."%' or a.date like'%".$searchValue."%' or a.invoice_id like'%".$searchValue."%' or u.first_name like'%".$searchValue."%'or u.last_name like'%".$searchValue."%')";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);
		
		 
		if($seller!='Todos'){
			$this->db->where('seller', $seller);
		}

			
		 $this->db->where('tipo_pago', 'efectivo');
		
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
         if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
            $this->db->where($searchQuery);
		
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 } 
		
		 $this->db->where('tipo_pago', 'efectivo');
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("a.*,b.customer_name,u.first_name,u.last_name");
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);
		


		if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 } 
		
		 $this->db->where('tipo_pago', 'efectivo');
       
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
		
		
		 
		
		 
		
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
  
         foreach($records as $record ){
			 
			 
			 
			 
			 
			 
			 $status = '';	 
			 $this->db->select('a.total_tax,
								a.*,
								b.*,
								c.*,
								d.product_id,
								d.product_name,
								d.product_details,
								d.unit,
								d.product_model,
								a.paid_amount as paid_amount,
								a.due_amount as due_amount'
							  );
			 $this->db->from('invoice a');
			 $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
			 $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
			 $this->db->join('product_information d', 'd.product_id = c.product_id');
			 $this->db->where('a.invoice_id', $record->invoice_id);
			 $this->db->where('c.quantity >', 0);
			 $query = $this->db->get();			
			 $detalles =  $query->result_array();
				 
			 $ne = count($detalles);
			 
			 if($ne > 1){				 
				$status = '<table>';				 
				foreach ($detalles as $arreglo){
					
					 if($arreglo['florist_status'] == '0' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-danger">Pendiente</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-warning">En proceso de entrega</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '1'){
						 $stt = '<span class="label label-success">Entregado</span>';
					 }
					
					$status.= '<tr>
					 				<td>'.$arreglo['product_name'].'</td>
									<td>'.$stt.'</td>
					 	  	   <tr>';
				}
				$status.= '</table>';
			 
			 }else{
				 
				 if($detalles[0]['florist_status'] == '0' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-danger">Pendiente</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-warning">En proceso de entrega</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '1'){
					 $status = '<span class="label label-success">Entregado</span>';
				 }
			 }
			 
			 
			 if($record->cancelado=='1'){
			 	$status = '<span class="label label-danger">Venta cancelada</span><br>'.$record->motivo_cancelacion;
			 }
			 
			 
          	$button = '';
          	$base_url = base_url();
          	$jsaction = "return confirm('Are You Sure ?')";	
			 
			 
			$user_type = $this->session->userdata('user_type');
			 
			 
			if($record->cancelado=='1'){
				
				if($user_type=='1'){
				
				$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
				}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';
				
				
			 	
			}else{
			
			
						 
			if($this->session->userdata('level')=='1'){
				$button .='  <a href="'.$base_url.'invoice_details_cs/'.$record->invoice_id.'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Cliente"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';		
			}
			 
			 $button .='  <a href="'.$base_url.'invoice_details_del/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Reparto"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
           	$button .='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Taller"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'invoice_pad_print/'.$record->invoice_id.'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pad_print').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'pos_print/'.$record->invoice_id.'" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pos_invoice').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';
			 
          	$button .='  <a href="'.$base_url.'download_invoice/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('download').'"><i class="fa fa-download"></i></a>';
				
			if($user_type=='1'){			 
			 	$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
			}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';

			  if($this->permission1->method('manage_invoice','update')->access()){
				 //$button .=' <a href="'.$base_url.'invoice_edit/'.$record->invoice_id.'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('update').'"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
			 }      
				
				
			}

          	$details ='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
            $data[] = array( 
                'sl'               =>$sl,
                'invoice'          =>$details,
                'salesman'         =>$record->first_name.' '.$record->last_name,
                'customer_name'    =>$record->customer_name,
                'final_date'       =>date("d-M-Y",strtotime($record->date)),
                'total_amount'     =>$record->total_amount,
				'tipo_pago'        =>$record->tipo_pago,
				'status'		   =>$status,
                'button'           =>$button,
				
                
            ); 
            $sl++;
			 
			 
			 
			 
			 
			 
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }
	





	public function getInvoiceList_tarjeta($postData=null){
         $response = array();
         $usertype = $this->session->userdata('user_type');
         $fromdate = $this->input->post('fromdate',TRUE);
         $todate   = $this->input->post('todate',TRUE);
		 $seller   = $this->input->post('seller',TRUE);
         if(!empty($fromdate)){
            $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
         }else{
            $datbetween = "";
         }
         ## Read value
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         ## Search 
         $searchQuery = "";
         if($searchValue != ''){
            $searchQuery = " (b.customer_name like '%".$searchValue."%' or a.invoice like '%".$searchValue."%' or a.date like'%".$searchValue."%' or a.invoice_id like'%".$searchValue."%' or u.first_name like'%".$searchValue."%'or u.last_name like'%".$searchValue."%')";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 } 
		 $this->db->where('tipo_pago', 'Tarjeta');        
		
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
         if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
            $this->db->where($searchQuery);
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 } 
		 $this->db->where('tipo_pago', 'Tarjeta');
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("a.*,b.customer_name,u.first_name,u.last_name");
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);


		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 } 
		 $this->db->where('tipo_pago', 'Tarjeta');
       
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
		
		 
		
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
  
         foreach($records as $record ){
			 
			 
			 
			 
			 
			 
			 $status = '';	 
			 $this->db->select('a.total_tax,
								a.*,
								b.*,
								c.*,
								d.product_id,
								d.product_name,
								d.product_details,
								d.unit,
								d.product_model,
								a.paid_amount as paid_amount,
								a.due_amount as due_amount'
							  );
			 $this->db->from('invoice a');
			 $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
			 $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
			 $this->db->join('product_information d', 'd.product_id = c.product_id');
			 $this->db->where('a.invoice_id', $record->invoice_id);
			 $this->db->where('c.quantity >', 0);
			 $query = $this->db->get();			
			 $detalles =  $query->result_array();
				 
			 $ne = count($detalles);
			 
			 if($ne > 1){				 
				$status = '<table>';				 
				foreach ($detalles as $arreglo){
					
					 if($arreglo['florist_status'] == '0' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-danger">Pendiente</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-warning">En proceso de entrega</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '1'){
						 $stt = '<span class="label label-success">Entregado</span>';
					 }
					
					$status.= '<tr>
					 				<td>'.$arreglo['product_name'].'</td>
									<td>'.$stt.'</td>
					 	  	   <tr>';
				}
				$status.= '</table>';
			 
			 }else{
				 
				 if($detalles[0]['florist_status'] == '0' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-danger">Pendiente</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-warning">En proceso de entrega</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '1'){
					 $status = '<span class="label label-success">Entregado</span>';
				 }
			 }
			 
			 
			 if($record->cancelado=='1'){
			 	$status = '<span class="label label-danger">Venta cancelada</span><br>'.$record->motivo_cancelacion;
			 }
			 
			 
          	$button = '';
          	$base_url = base_url();
          	$jsaction = "return confirm('Are You Sure ?')";	
			 
			 
			$user_type = $this->session->userdata('user_type');
			 
			 
			if($record->cancelado=='1'){
				
				if($user_type=='1'){
				
				$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
				}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';
				
				
			 	
			}else{
			
			
						 
			if($this->session->userdata('level')=='1'){
				$button .='  <a href="'.$base_url.'invoice_details_cs/'.$record->invoice_id.'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Cliente"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';		
			}
			 
			 $button .='  <a href="'.$base_url.'invoice_details_del/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Reparto"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
           	$button .='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Taller"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'invoice_pad_print/'.$record->invoice_id.'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pad_print').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'pos_print/'.$record->invoice_id.'" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pos_invoice').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';
			 
          	$button .='  <a href="'.$base_url.'download_invoice/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('download').'"><i class="fa fa-download"></i></a>';
				
			if($user_type=='1'){			 
			 	$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
			}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';

			  if($this->permission1->method('manage_invoice','update')->access()){
				 //$button .=' <a href="'.$base_url.'invoice_edit/'.$record->invoice_id.'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('update').'"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
			 }      
				
				
			}

          	$details ='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
            $data[] = array( 
                'sl'               =>$sl,
                'invoice'          =>$details,
                'salesman'         =>$record->first_name.' '.$record->last_name,
                'customer_name'    =>$record->customer_name,
                'final_date'       =>date("d-M-Y",strtotime($record->date)),
                'total_amount'     =>$record->total_amount,
				'tipo_pago'        =>$record->tipo_pago,
				'status'		   =>$status,
                'button'           =>$button,
				
                
            ); 
            $sl++;
			 
			 
			 
			 
			 
			 
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }


	
	



	public function getInvoiceList_transferencia($postData=null){
         $response = array();
         $usertype = $this->session->userdata('user_type');
         $fromdate = $this->input->post('fromdate',TRUE);
         $todate   = $this->input->post('todate',TRUE);
		 $seller   = $this->input->post('seller',TRUE);
         if(!empty($fromdate)){
            $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
         }else{
            $datbetween = "";
         }
         ## Read value
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         ## Search 
         $searchQuery = "";
         if($searchValue != ''){
            $searchQuery = " (b.customer_name like '%".$searchValue."%' or a.invoice like '%".$searchValue."%' or a.date like'%".$searchValue."%' or a.invoice_id like'%".$searchValue."%' or u.first_name like'%".$searchValue."%'or u.last_name like'%".$searchValue."%')";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		 $this->db->where('tipo_pago', 'Transferencia Bancaria');        
		
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
         if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
            $this->db->where($searchQuery);
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		 $this->db->where('tipo_pago', 'Transferencia Bancaria');
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("a.*,b.customer_name,u.first_name,u.last_name");
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);
		

		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		 $this->db->where('tipo_pago', 'Transferencia Bancaria');
       
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
		 
		 
		
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
  
         foreach($records as $record ){
			 
			 
			 
			 
			 
			 
			 $status = '';	 
			 $this->db->select('a.total_tax,
								a.*,
								b.*,
								c.*,
								d.product_id,
								d.product_name,
								d.product_details,
								d.unit,
								d.product_model,
								a.paid_amount as paid_amount,
								a.due_amount as due_amount'
							  );
			 $this->db->from('invoice a');
			 $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
			 $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
			 $this->db->join('product_information d', 'd.product_id = c.product_id');
			 $this->db->where('a.invoice_id', $record->invoice_id);
			 $this->db->where('c.quantity >', 0);
			 $query = $this->db->get();			
			 $detalles =  $query->result_array();
				 
			 $ne = count($detalles);
			 
			 if($ne > 1){				 
				$status = '<table>';				 
				foreach ($detalles as $arreglo){
					
					 if($arreglo['florist_status'] == '0' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-danger">Pendiente</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-warning">En proceso de entrega</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '1'){
						 $stt = '<span class="label label-success">Entregado</span>';
					 }
					
					$status.= '<tr>
					 				<td>'.$arreglo['product_name'].'</td>
									<td>'.$stt.'</td>
					 	  	   <tr>';
				}
				$status.= '</table>';
			 
			 }else{
				 
				 if($detalles[0]['florist_status'] == '0' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-danger">Pendiente</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-warning">En proceso de entrega</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '1'){
					 $status = '<span class="label label-success">Entregado</span>';
				 }
			 }
			 
			 
			 if($record->cancelado=='1'){
			 	$status = '<span class="label label-danger">Venta cancelada</span><br>'.$record->motivo_cancelacion;
			 }
			 
			 
          	$button = '';
          	$base_url = base_url();
          	$jsaction = "return confirm('Are You Sure ?')";	
			 
			 
			$user_type = $this->session->userdata('user_type');
			 
			 
			if($record->cancelado=='1'){
				
				if($user_type=='1'){
				
				$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
				}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';
				
				
			 	
			}else{
			
			
						 
			if($this->session->userdata('level')=='1'){
				$button .='  <a href="'.$base_url.'invoice_details_cs/'.$record->invoice_id.'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Cliente"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';		
			}
			 
			 $button .='  <a href="'.$base_url.'invoice_details_del/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Reparto"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
           	$button .='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Taller"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'invoice_pad_print/'.$record->invoice_id.'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pad_print').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'pos_print/'.$record->invoice_id.'" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pos_invoice').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';
			 
          	$button .='  <a href="'.$base_url.'download_invoice/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('download').'"><i class="fa fa-download"></i></a>';
				
			if($user_type=='1'){			 
			 	$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
			}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';

			  if($this->permission1->method('manage_invoice','update')->access()){
				 //$button .=' <a href="'.$base_url.'invoice_edit/'.$record->invoice_id.'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('update').'"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
			 }      
				
				
			}

          	$details ='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
            $data[] = array( 
                'sl'               =>$sl,
                'invoice'          =>$details,
                'salesman'         =>$record->first_name.' '.$record->last_name,
                'customer_name'    =>$record->customer_name,
                'final_date'       =>date("d-M-Y",strtotime($record->date)),
                'total_amount'     =>$record->total_amount,
				'tipo_pago'        =>$record->tipo_pago,
				'status'		   =>$status,
                'button'           =>$button,
				
                
            ); 
            $sl++;
			 
			 
			 
			 
			 
			 
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }



	




	public function getInvoiceList_anticipo($postData=null){
         $response = array();
         $usertype = $this->session->userdata('user_type');
         $fromdate = $this->input->post('fromdate',TRUE);
         $todate   = $this->input->post('todate',TRUE);
		 $seller   = $this->input->post('seller',TRUE);
         if(!empty($fromdate)){
            $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
         }else{
            $datbetween = "";
         }
         ## Read value
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         ## Search 
         $searchQuery = "";
         if($searchValue != ''){
            $searchQuery = " (b.customer_name like '%".$searchValue."%' or a.invoice like '%".$searchValue."%' or a.date like'%".$searchValue."%' or a.invoice_id like'%".$searchValue."%' or u.first_name like'%".$searchValue."%'or u.last_name like'%".$searchValue."%')";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		 $this->db->where('tipo_pago', 'Anticipo');        
		
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
         if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
            $this->db->where($searchQuery);
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		 $this->db->where('tipo_pago', 'Anticipo');
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("a.*,b.customer_name,u.first_name,u.last_name");
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);

		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		
       	 $this->db->where('tipo_pago', 'Anticipo');

         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
		
		 
		
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
  
         foreach($records as $record ){
			 
			 
			 
			 
			 
			 
			 $status = '';	 
			 $this->db->select('a.total_tax,
								a.*,
								b.*,
								c.*,
								d.product_id,
								d.product_name,
								d.product_details,
								d.unit,
								d.product_model,
								a.paid_amount as paid_amount,
								a.due_amount as due_amount'
							  );
			 $this->db->from('invoice a');
			 $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
			 $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
			 $this->db->join('product_information d', 'd.product_id = c.product_id');
			 $this->db->where('a.invoice_id', $record->invoice_id);
			 $this->db->where('c.quantity >', 0);
			 $query = $this->db->get();			
			 $detalles =  $query->result_array();
				 
			 $ne = count($detalles);
			 
			 if($ne > 1){				 
				$status = '<table>';				 
				foreach ($detalles as $arreglo){
					
					 if($arreglo['florist_status'] == '0' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-danger">Pendiente</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-warning">En proceso de entrega</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '1'){
						 $stt = '<span class="label label-success">Entregado</span>';
					 }
					
					$status.= '<tr>
					 				<td>'.$arreglo['product_name'].'</td>
									<td>'.$stt.'</td>
					 	  	   <tr>';
				}
				$status.= '</table>';
			 
			 }else{
				 
				 if($detalles[0]['florist_status'] == '0' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-danger">Pendiente</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-warning">En proceso de entrega</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '1'){
					 $status = '<span class="label label-success">Entregado</span>';
				 }
			 }
			 
			 
			 if($record->cancelado=='1'){
			 	$status = '<span class="label label-danger">Venta cancelada</span><br>'.$record->motivo_cancelacion;
			 }
			 
			 
          	$button = '';
          	$base_url = base_url();
          	$jsaction = "return confirm('Are You Sure ?')";	
			 
			 
			$user_type = $this->session->userdata('user_type');
			 
			 
			if($record->cancelado=='1'){
				
				if($user_type=='1'){
				
				$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
				}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';
				
				
			 	
			}else{
			
			
						 
			if($this->session->userdata('level')=='1'){
				$button .='  <a href="'.$base_url.'invoice_details_cs/'.$record->invoice_id.'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Cliente"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';		
			}
			 
			 $button .='  <a href="'.$base_url.'invoice_details_del/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Reparto"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
           	$button .='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Taller"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'invoice_pad_print/'.$record->invoice_id.'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pad_print').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'pos_print/'.$record->invoice_id.'" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pos_invoice').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';
			 
          	$button .='  <a href="'.$base_url.'download_invoice/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('download').'"><i class="fa fa-download"></i></a>';
				
			if($user_type=='1'){			 
			 	$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
			}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';

			  if($this->permission1->method('manage_invoice','update')->access()){
				 //$button .=' <a href="'.$base_url.'invoice_edit/'.$record->invoice_id.'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('update').'"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
			 }      
				
				
			}

          	$details ='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
            $data[] = array( 
                'sl'               =>$sl,
                'invoice'          =>$details,
                'salesman'         =>$record->first_name.' '.$record->last_name,
                'customer_name'    =>$record->customer_name,
                'final_date'       =>date("d-M-Y",strtotime($record->date)),
                'total_amount'     =>$record->total_amount,
				'tipo_pago'        =>$record->tipo_pago,
				'status'		   =>$status,
                'button'           =>$button,
				
                
            ); 
            $sl++;
			 
			 
			 
			 
			 
			 
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }
	



	public function getInvoiceList_porcobrar($postData=null){
         $response = array();
         $usertype = $this->session->userdata('user_type');
         $fromdate = $this->input->post('fromdate',TRUE);
         $todate   = $this->input->post('todate',TRUE);
		 $seller   = $this->input->post('seller',TRUE);
         if(!empty($fromdate)){
            $datbetween = "(a.date BETWEEN '$fromdate' AND '$todate')";
         }else{
            $datbetween = "";
         }
         ## Read value
         $draw         = $postData['draw'];
         $start        = $postData['start'];
         $rowperpage   = $postData['length']; // Rows display per page
         $columnIndex  = $postData['order'][0]['column']; // Column index
         $columnName   = $postData['columns'][$columnIndex]['data']; 
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue  = $postData['search']['value']; // Search value

         ## Search 
         $searchQuery = "";
         if($searchValue != ''){
            $searchQuery = " (b.customer_name like '%".$searchValue."%' or a.invoice like '%".$searchValue."%' or a.date like'%".$searchValue."%' or a.invoice_id like'%".$searchValue."%' or u.first_name like'%".$searchValue."%'or u.last_name like'%".$searchValue."%')";
         }

         ## Total number of records without filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		 $this->db->where('tipo_pago', 'Credito Interno');        
		
         $records = $this->db->get()->result();
         $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
         $this->db->select('count(*) as allcount');
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
         if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
            $this->db->where($searchQuery);
		
		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		 $this->db->where('tipo_pago', 'Credito Interno');
          
         $records = $this->db->get()->result();
         $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
         $this->db->select("a.*,b.customer_name,u.first_name,u.last_name");
         $this->db->from('invoice a');
         $this->db->join('customer_information b', 'b.customer_id = a.customer_id','left');
         $this->db->join('users u', 'u.user_id = a.sales_by','left');
         /*if($usertype == 2){
          $this->db->where('a.sales_by',$this->session->userdata('user_id'));
         }*/
          if(!empty($fromdate) && !empty($todate)){
             $this->db->where($datbetween);
         }
         if($searchValue != '')
         	$this->db->where($searchQuery);		

		 if($seller!='Todos'){
			$this->db->where('seller', $seller);
		 }
		 $this->db->where('tipo_pago', 'Credito Interno');
       
         $this->db->order_by($columnName, $columnSortOrder);
         $this->db->limit($rowperpage, $start);
		
		 
		
         $records = $this->db->get()->result();
         $data = array();
         $sl =1;
  
         foreach($records as $record ){
			 
			 
			 
			 
			 
			 
			 $status = '';	 
			 $this->db->select('a.total_tax,
								a.*,
								b.*,
								c.*,
								d.product_id,
								d.product_name,
								d.product_details,
								d.unit,
								d.product_model,
								a.paid_amount as paid_amount,
								a.due_amount as due_amount'
							  );
			 $this->db->from('invoice a');
			 $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
			 $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
			 $this->db->join('product_information d', 'd.product_id = c.product_id');
			 $this->db->where('a.invoice_id', $record->invoice_id);
			 $this->db->where('c.quantity >', 0);
			 $query = $this->db->get();			
			 $detalles =  $query->result_array();
				 
			 $ne = count($detalles);
			 
			 if($ne > 1){				 
				$status = '<table>';				 
				foreach ($detalles as $arreglo){
					
					 if($arreglo['florist_status'] == '0' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-danger">Pendiente</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '0'){
						 $stt = '<span class="label label-warning">En proceso de entrega</span>';
					 }else if($arreglo['florist_status'] == '1' && $arreglo['delivery_status'] == '1'){
						 $stt = '<span class="label label-success">Entregado</span>';
					 }
					
					$status.= '<tr>
					 				<td>'.$arreglo['product_name'].'</td>
									<td>'.$stt.'</td>
					 	  	   <tr>';
				}
				$status.= '</table>';
			 
			 }else{
				 
				 if($detalles[0]['florist_status'] == '0' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-danger">Pendiente</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '0'){
					 $status = '<span class="label label-warning">En proceso de entrega</span>';
				 }else if($detalles[0]['florist_status'] == '1' && $detalles[0]['delivery_status'] == '1'){
					 $status = '<span class="label label-success">Entregado</span>';
				 }
			 }
			 
			 
			 if($record->cancelado=='1'){
			 	$status = '<span class="label label-danger">Venta cancelada</span><br>'.$record->motivo_cancelacion;
			 }
			 
			 
          	$button = '';
          	$base_url = base_url();
          	$jsaction = "return confirm('Are You Sure ?')";	
			 
			 
			$user_type = $this->session->userdata('user_type');
			 
			 
			if($record->cancelado=='1'){
				
				if($user_type=='1'){
				
				$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
				}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';
				
				
			 	
			}else{
			
			
						 
			if($this->session->userdata('level')=='1'){
				$button .='  <a href="'.$base_url.'invoice_details_cs/'.$record->invoice_id.'" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Cliente"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';		
			}
			 
			 $button .='  <a href="'.$base_url.'invoice_details_del/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Reparto"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';
			 
           	$button .='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="left" title="Ticket Taller"><i class="fa fa-window-restore" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'invoice_pad_print/'.$record->invoice_id.'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pad_print').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';

         	$button .='  <a href="'.$base_url.'pos_print/'.$record->invoice_id.'" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('pos_invoice').'"><i class="fa fa-fax" aria-hidden="true"></i></a>';
			 
          	$button .='  <a href="'.$base_url.'download_invoice/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="'.display('download').'"><i class="fa fa-download"></i></a>';
				
			if($user_type=='1'){			 
			 	$button .='  <button invoice="'.$record->invoice_id.'" class="btn btn-default btn-sm delete_inv" title="Eliminar venta"><i class="fa fa-trash-o"></i></button>';
			}
			 
			 $button .='  <a href="'.$base_url.'edit_gui_pos/'.$record->invoice_id.'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Editar venta"><i class="fa fa-edit"></i></a>';

			  if($this->permission1->method('manage_invoice','update')->access()){
				 //$button .=' <a href="'.$base_url.'invoice_edit/'.$record->invoice_id.'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="'. display('update').'"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
			 }      
				
				
			}

          	$details ='  <a href="'.$base_url.'invoice_details/'.$record->invoice_id.'" class="" >'.$record->invoice.'</a>';
               
            $data[] = array( 
                'sl'               =>$sl,
                'invoice'          =>$details,
                'salesman'         =>$record->first_name.' '.$record->last_name,
                'customer_name'    =>$record->customer_name,
                'final_date'       =>date("d-M-Y",strtotime($record->date)),
                'total_amount'     =>$record->total_amount,
				'tipo_pago'        =>$record->tipo_pago,
				'status'		   =>$status,
                'button'           =>$button,
				
                
            ); 
            $sl++;
			 
			 
			 
			 
			 
			 
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 
    }
	


	public function get_sellers(){	
		$this->db->select('seller');
		$this->db->from('invoice');
		$this->db->group_by('seller');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return false;
	}





	
	



}

