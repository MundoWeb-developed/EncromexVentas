<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subzone extends MX_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('subzone_model', 'invoice/invoice_model'));

        if (! $this->session->userdata('isLogIn'))
            redirect('login');
          
    }

    public function index(){
        $data['title']        = 'Lista de sub zonas';
        $data['subzones']= $this->subzone_model->all();
        $data['module']       = "subzone";
        $data['page']         = "index"; 
        echo modules::run('template/layout', $data);
    }

    public function create(){
        $data['title']        = 'Agregar sub zonas';
        $data['zones'] = $this->invoice_model->zona_list();
        $data['module']       = "subzone";
        $data['page']         = "create"; 
        echo modules::run('template/layout', $data);
    }

    public function store(){
        $this->form_validation->set_rules('name',display('name'),'required');
        $this->form_validation->set_rules('price',display('price'),'required');
        $this->form_validation->set_rules('zone',display('zone'),'required');

        if ($this->form_validation->run()) {
            $subzone = array(
                'name' => $this->input->post('name',true),
                'price' => str_replace(",", "", $this->input->post('price',true)),
                'status' => 1,
                'zone_id' => $this->input->post('zone',true)
            );
            
            if ($this->subzone_model->store($subzone)) { 
                $this->session->set_flashdata('message', display('save_successfully'));
            } else {
                $this->session->set_flashdata('error_message',  display('please_try_again'));
            }

            redirect("subzones");
        }else{
            $this->session->set_flashdata('error_message',  display('please_try_again'));
            redirect()->back();
        }
    }

    public function edit($id){
        $data['title']        = 'Editar subzona';
        $data['zones'] = $this->invoice_model->zona_list();
        $data['subzone'] = $this->subzone_model->getById($id);
        $data['module']       = "subzone";
        $data['page']         = "edit"; 
        echo modules::run('template/layout', $data);
    }

    public function update($id){
        $this->form_validation->set_rules('name',display('name'),'required');
        $this->form_validation->set_rules('price',display('price'),'required');
        $this->form_validation->set_rules('zone',display('zone'),'required');

        if ($this->form_validation->run()) {
            $subzone = array(
                'name' => $this->input->post('name',true),
                'price' => str_replace(",", "", $this->input->post('price',true)),
                'zone_id' => $this->input->post('zone',true)
            );
            
            if ($this->subzone_model->update($subzone, $id)) { 
                $this->session->set_flashdata('message', display('save_successfully'));
            } else {
                $this->session->set_flashdata('error_message',  display('please_try_again'));
            }

            redirect("subzones");
        }else{
            $this->session->set_flashdata('error_message',  display('please_try_again'));
            redirect()->back();
        }
    }

    public function destroy($id = null) {
        if ($this->subzone_model->update(['status' => 0], $id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }
        redirect("subzones");
        //redirect()->back();
    }

    public function getByZone(){
        $id = $this->input->post('id',true);
        echo json_encode($this->subzone_model->getByZone($id));
    }


}