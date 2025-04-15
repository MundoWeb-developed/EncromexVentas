<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 #------------------------------------    
    # Author: Bdtask Ltd
    # Author link: https://www.bdtask.com/
    # Dynamic style php file
    # Developed by :Isahaq
    #------------------------------------    

class User_model extends CI_Model {
 
	public function create($data = array())
	{
		$user_id = $this->generator(6);
		 $users = array(
		 	'user_id'    => $user_id,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'logo'       => $data['image'],
            'status'     => $data['status'],
			'branchoffice_id'=> $data['branchoffice_id'], // ← AÑADIR ESTA LÍNEA
        );
        $this->db->insert('users', $users);
		
		$deliveryman = "";
		$florist = "";
		
		if($data['user_type']=="3"){
			$florist = $data['florist'];
		}
		
		if($data['user_type']=="4"){
			$deliveryman = $data['deliveryman'];
		}		
		
        $user_login = array(
            'user_id'   	=> $user_id,
            'username'  	=> $data['email'],
            'password'  	=> $data['password'],
            'user_type' 	=> $data['user_type'],
			'deliveryman' 	=> $deliveryman,
			'florist'  		=> $florist,
            'status'    	=> $data['status'],
        );
        $this->db->insert('user_login', $user_login);
        return true;
	}

	public function read()
	{
		return $this->db->select("
				a.*, 
				CONCAT_WS(' ', a.first_name, a.last_name) AS fullname,b.*,b.status as status,b.username as email, c.branchoffice as sucursal_nombre
			")
			->from('users a')
			->join('user_login b','b.user_id = a.user_id')
			->join('branchoffice c', 'a.branchoffice_id = c.id', 'left') // LEFT JOIN
			->order_by('a.user_id', 'desc')
			->get()
			->result();
	}

	public function single($id = null)
	{
		return $this->db->select("
				a.*,a.logo as image,b.*,b.status as status,b.username as email
				,c.branchoffice as sucursal_nombre, c.id as branchoffice_id,
			")
			->from('users a')
			->join('user_login b','b.user_id = a.user_id')
			->join('branchoffice c', 'a.branchoffice_id = c.id', 'left')
			->where('a.user_id', $id)
			->order_by('a.user_id', 'desc')
			->get()
			->row();
	}

	public function update($data = array())
	{


		$userdata = array(
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'logo'       => $data['image'],
            'status'     => $data['status']
			,'branchoffice_id'=> $data['branchoffice_id'], // ← AÑADIR ESTA LÍNEA
        );
        $this->db->where('user_id', $data['user_id']);
        $this->db->update('users', $userdata);
				
		$deliveryman = "";
		$florist = "";
		
		if($data['user_type']=="3"){
			$florist = $data['florist'];
		}
		
		if($data['user_type']=="4"){
			$deliveryman = $data['deliveryman'];
		}
		
        $user_login = array(
            'username' 		=> $data['email'],
            'password' 		=> $data['password'],
            'user_type'		=> $data['user_type'],
			'deliveryman'	=> $deliveryman,
			'florist'  	 	=> $florist,
            'status'   		=> $data['status'],
        );
        $this->db->where('user_id', $data['user_id']);
        $this->db->update('user_login', $user_login);
        return true;
	}

	public function delete($id = null)
	{
		 $this->db->where('user_id', $id)
			->delete("users");
		$this->db->where('user_id', $id)
			->delete("user_login");	
		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	}



 public function generator($lenth) {
        $number = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 9);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
	
	
	
	public function alldeliveryman(){
        $this->db->select('*');
        $this->db->from('deliveryman');
        $this->db->order_by('id','asc');
        $query   = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
    }

    public function allflorist(){
        $this->db->select('*');
        $this->db->from('florist');
        $this->db->order_by('id','asc');
        $query   = $this->db->get();
        $itemlist=$query->result();
        return $itemlist;
    }


}
