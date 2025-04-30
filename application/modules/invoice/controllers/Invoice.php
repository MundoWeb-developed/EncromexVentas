<?php
defined('BASEPATH') or exit('No direct script access allowed');

#------------------------------------    
# Author: Bdtask Ltd
# Author link: https://www.bdtask.com/
# Dynamic style php file
# Developed by :Isahaq
#------------------------------------    

class Invoice extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array(
            'invoice_model',
            'customer/customer_model',
            'quote_model'
        ));
        if (! $this->session->userdata('isLogIn'))
            redirect('login');
    }




    public function arr_insumos_invoice()
    {

        $product_id = $this->input->post('product_id', TRUE);
        $cantidad = $this->input->post('cantidad', TRUE);
        $product_details = $this->invoice_model->pos_invoice_setup($product_id);
        $detalles = $this->invoice_model->details_product($product_id);

        $tr = " ";

        if (!empty($product_details)) {

            $qty = (int)$cantidad;

            foreach ($detalles as $dt) {
                $nc = (float)$dt->cantidad * $qty;
                $nt = (float)$dt->total * $qty;
                $tr .= "<tr>
                        <td>" . $dt->product_name . " (" . $dt->product_model . ")</td>
                        <td>" . $nc . "</td>
                        <!--<td>" . $dt->price . "</td>
                        <td>" . $nt . "</td>-->
                      </tr>";
            }
            echo $tr;
        } else {
            return false;
        }
    }








    public function bdtask_delete_zona($id = null)
    {
        if ($this->invoice_model->delete_zona($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("zona_list");
    }


    public function bdtask_zona_list()
    {

        $data['title']        = 'Lista de zonas';
        $data['employee_list'] = $this->invoice_model->zona_list();
        $data['module']       = "invoice";
        $data['page']         = "zona_list";
        echo modules::run('template/layout', $data);
    }




    public function bdtask_add_zona($id = null)
    {

        $data['title']         = 'Zonas';

        $this->form_validation->set_rules('zona', 'zona', 'required|max_length[100]');
        $this->form_validation->set_rules('descripcion', 'descripción', 'max_length[500]');

        $data['employee'] = (object)$postData = [
            'id'            => $this->input->post('id', true),
            'zona'         => $this->input->post('zona', true),
            'descripcion' => $this->input->post('descripcion', true),
        ];

        #-------------------------------#
        if ($this->form_validation->run()) {

            if (empty($id)) {
                if ($this->invoice_model->create_zona($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                    redirect("zona_list");
                } else {
                    $this->session->set_flashdata('error_message',  display('please_try_again'));
                    redirect("zona_list");
                }
            } else {
                if ($this->invoice_model->update_zona($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                    redirect("add_zona/" . $id);
                }
                redirect("zona_list/");
            }
        } else {
            if (!empty($id)) {
                $data['employee']    = $this->invoice_model->single_zona_data($id);
                $data['title']       = 'Zonas';
            }

            $data['module']        = "invoice";
            $data['page']          = "add_zona";
            echo modules::run('template/layout', $data);
        }
    }









    public function bdtask_delete_florist($id = null)
    {
        if ($this->invoice_model->delete_florist($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("florist_list");
    }


    public function bdtask_florist_list()
    {

        $data['title']        = 'Lista de floristas';
        $data['employee_list'] = $this->invoice_model->florist_list();
        $data['module']       = "invoice";
        $data['page']         = "florist_list";
        echo modules::run('template/layout', $data);
    }



    public function bdtask_add_florist($id = null)
    {

        $data['title']         = 'Florista';

        $this->form_validation->set_rules('first_name', display('first_name'), 'required|max_length[100]');
        $this->form_validation->set_rules('last_name', display('last_name'), 'required|max_length[100]');
        $this->form_validation->set_rules('phone', display('phone'), 'max_length[20]');

        $data['employee'] = (object)$postData = [
            'id'            => $this->input->post('id', true),
            'first_name'    => $this->input->post('first_name', true),
            'last_name'     => $this->input->post('last_name', true),
            'branchoffice'     => $this->input->post('branchoffice', true),
            'phone'         => $this->input->post('phone', true),
            'email'         => $this->input->post('email', true),
            'address' => $this->input->post('address', true),
        ];

        #-------------------------------#
        if ($this->form_validation->run()) {

            if (empty($id)) {
                if ($this->invoice_model->create_florist($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                    redirect("florist_list");
                } else {
                    $this->session->set_flashdata('error_message',  display('please_try_again'));
                    redirect("florist_list");
                }
            } else {
                if ($this->invoice_model->update_florist($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                    redirect("add_florist/" . $id);
                }
                redirect("florist_list/");
            }
        } else {
            if (!empty($id)) {
                $data['employee']    = $this->invoice_model->single_florist_data($id);
                $data['title']       = 'Florista';
            }

            $data['branchoffice']      = $this->invoice_model->allbranchoffice();

            $data['module']        = "invoice";
            $data['page']          = "add_florist";
            echo modules::run('template/layout', $data);
        }
    }








    public function bdtask_delete_branchoffice($id = null)
    {
        if ($this->invoice_model->delete_branchoffice($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("branchoffice_list");
    }


    public function bdtask_branchoffice_list()
    {

        $data['title']        = 'Lista de sucursales';
        $data['employee_list'] = $this->invoice_model->branchoffice_list();
        $data['module']       = "invoice";
        $data['page']         = "branchoffice_list";
        echo modules::run('template/layout', $data);
    }




    public function bdtask_add_branchoffice($id = null)
    {

        $data['title']         = 'Sucursal';

        $this->form_validation->set_rules('branchoffice', 'Sucursal', 'required|max_length[100]');
        $this->form_validation->set_rules('phone', display('phone'), 'max_length[20]');

        $data['employee'] = (object)$postData = [
            'id'            => $this->input->post('id', true),
            'branchoffice'    => $this->input->post('branchoffice', true),
            'phone'         => $this->input->post('phone', true),
            'email'         => $this->input->post('email', true),
            'address' => $this->input->post('address', true),
        ];

        #-------------------------------#
        if ($this->form_validation->run()) {

            if (empty($id)) {
                if ($this->invoice_model->create_branchoffice($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                    redirect("branchoffice_list");
                } else {
                    $this->session->set_flashdata('error_message',  display('please_try_again'));
                    redirect("branchoffice_list");
                }
            } else {
                if ($this->invoice_model->update_branchoffice($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                    redirect("add_branchoffice/" . $id);
                }
                redirect("branchoffice_list/");
            }
        } else {
            if (!empty($id)) {
                $data['employee']    = $this->invoice_model->single_branchoffice_data($id);
                $data['title']       = 'Sucursal';
            }

            $data['module']        = "invoice";
            $data['page']          = "add_branchoffice";
            echo modules::run('template/layout', $data);
        }
    }



    public function bdtask_delete_deliveryman($id = null)
    {
        if ($this->invoice_model->delete_deliveryman($id)) {
            $this->session->set_flashdata('message', display('delete_successfully'));
        } else {
            $this->session->set_flashdata('exception', display('please_try_again'));
        }

        redirect("deliveryman_list");
    }


    public function bdtask_deliveryman_list()
    {

        $data['title']        = 'Lista de repartidores';
        $data['employee_list'] = $this->invoice_model->deliveryman_list();
        $data['module']       = "invoice";
        $data['page']         = "deliveryman_list";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_add_deliveryman($id = null)
    {

        $data['title']         = 'Repartidor';

        $this->form_validation->set_rules('first_name', display('first_name'), 'required|max_length[100]');
        $this->form_validation->set_rules('last_name', display('last_name'), 'required|max_length[100]');
        $this->form_validation->set_rules('phone', display('phone'), 'max_length[20]');

        $data['employee'] = (object)$postData = [
            'id'            => $this->input->post('id', true),
            'first_name'    => $this->input->post('first_name', true),
            'last_name'     => $this->input->post('last_name', true),
            'phone'         => $this->input->post('phone', true),
            'email'         => $this->input->post('email', true),
            'address' => $this->input->post('address', true),
        ];

        #-------------------------------#
        if ($this->form_validation->run()) {

            if (empty($id)) {
                if ($this->invoice_model->create_deliveryman($postData)) {
                    $this->session->set_flashdata('message', display('save_successfully'));
                    redirect("deliveryman_list");
                } else {
                    $this->session->set_flashdata('error_message',  display('please_try_again'));
                    redirect("deliveryman_list");
                }
            } else {
                if ($this->invoice_model->update_deliveryman($postData)) {
                    $this->session->set_flashdata('message', display('update_successfully'));
                } else {
                    $this->session->set_flashdata('exception', display('please_try_again'));
                    redirect("add_deliveryman/" . $id);
                }
                redirect("deliveryman_list/");
            }
        } else {
            if (!empty($id)) {
                $data['employee']    = $this->invoice_model->single_deliveryman_data($id);
                $data['title']       = 'Repartidor';
            }

            $data['module']        = "invoice";
            $data['page']          = "add_deliveryman";
            echo modules::run('template/layout', $data);
        }
    }







    function bdtask_invoice_form()
    {
        $walking_customer      = $this->invoice_model->pos_customer_setup();
        $data['customer_name'] = $walking_customer[0]['customer_name'];
        $data['customer_id']   = $walking_customer[0]['customer_id'];
        $data['invoice_no']    = $this->number_generator();
        $data['title']         = display('add_invoice');
        $data['taxes']         = $this->invoice_model->tax_fileds();
        $data['module']        = "invoice";
        $data['page']          = "add_invoice_form";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_invoice_list()
    {
        $data['title']        = display('manage_invoice');
        $data['total_invoice'] = $this->invoice_model->count_invoice();
        $data['module']       = "invoice";
        $data['page']         = "invoice";
        echo modules::run('template/layout', $data);
    }



    public function bdtask_invoice_list_pc()
    {
        $data['title']        = display('manage_invoice');
        $data['total_invoice'] = $this->invoice_model->count_invoice();
        $data['module']       = "invoice";
        $data['page']         = "invoice_pc";
        echo modules::run('template/layout', $data);
    }



    public function bdtask_accumulated()
    {
        $data['title']        = 'Venta acumulada';
        $data['sellers']      = $this->invoice_model->get_sellers();
        $data['module']       = "invoice";
        $data['page']         = "accumulated";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_arrangement_list()
    {
        $data['title']        = "Lista de arreglos";
        //$data['total_invoice']= $this->invoice_model->count_invoice();
        $data['module']       = "invoice";
        $data['page']         = "arrangement";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_arrangement_list1()
    {
        $data['title']        = "Lista de arreglos";
        //$data['total_invoice']= $this->invoice_model->count_invoice();
        $data['module']       = "invoice";
        $data['page']         = "arrangement1";
        echo modules::run('template/layout', $data);
    }


    public function CheckInvoiceList()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getInvoiceList($postData);
        echo json_encode($data);
    }





    public function CheckInvoiceListPc()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getInvoiceListPc($postData);
        echo json_encode($data);
    }


    public function CheckArrangementList()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getArrangementList($postData);
        echo json_encode($data);
    }


    public function CheckArrangementList1()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getArrangementList1($postData);
        echo json_encode($data);
    }


    public function bdtask_invoice_details($invoice_id = null)
    {

        $invoice_detail     = $this->invoice_model->retrieve_invoice_html_data($invoice_id);

        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $invoice_detail[$k]['date'];
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount  = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }

            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }


        $totalbal      = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword = $totalbal;
        $user_id       = $invoice_detail[0]['sales_by'];
        $users         = $this->invoice_model->user_invoice_data($user_id);


        $delim = $invoice_detail[0]['delivery_multiple'];

        if ($delim == '1') {

            $data_reparto    = $this->invoice_model->reparto_invoice_html_data($invoice_id);
            $data_caja       = $this->invoice_model->caja_invoice_html_data($invoice_id);
            $data_taller     = $this->invoice_model->taller_invoice_html_data($invoice_id);

            $data = array(
                'title'             => 'Ticket - Venta',
                //'title'             => display('invoice_details'),
                'invoice_id'        => $invoice_detail[0]['invoice_id'],
                'invoice_no'        => $invoice_detail[0]['invoice'],
                'customer_name'     => $invoice_detail[0]['customer_name'],
                'customer_address'  => $invoice_detail[0]['customer_address'],
                'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
                'customer_email'    => $invoice_detail[0]['customer_email'],
                'final_date'        => $invoice_detail[0]['final_date'],
                'invoice_details'   => $invoice_detail[0]['invoice_details'],
                'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
                'subTotal_quantity' => $subTotal_quantity,
                'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
                'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
                'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
                'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
                'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
                'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
                'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
                'invoice_all_data'  => $invoice_detail,
                'am_inword'         => $amount_inword,
                'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
                'users_name'        => $users->first_name . ' ' . $users->last_name,
                'tax_regno'         => $txregname,
                'is_desc'           => $descript,
                'is_serial'         => $isserial,
                'is_unit'           => $isunit,
                'delivery_multiple' => $invoice_detail[0]['delivery_multiple'],

                'branchoffice' => $invoice_detail[0]['branchoffice'],

                'data_reparto'      => $data_reparto,
                'data_caja'      => $data_caja,
                'data_taller'      => $data_taller,
                'tipo_venta'   => $invoice_detail[0]['tipo_venta'],
                'seller'   => $invoice_detail[0]['seller'],
                'tipo_pago'   => $invoice_detail[0]['tipo_pago'],
                'creacion'   => $invoice_detail[0]['creacion'],

            );
        } else {


            $data_f = (array)$this->invoice_model->single_florist_data($invoice_detail[0]['florista_taller']);
            $data_d = (array)$this->invoice_model->single_deliveryman_data($invoice_detail[0]['repartidor_caja']);
            $data_z = (array)$this->invoice_model->single_zona_data($invoice_detail[0]['zona']);

            $zona = $data_z['zona'];

            $florista_taller = $data_f['first_name'] . ' ' . $data_f['last_name'];
            $repartidor_caja = $data_d['first_name'] . ' ' . $data_d['last_name'];

            $data = array(
                'title'             => 'Ticket - Venta',
                //'title'             => display('invoice_details'),
                'invoice_id'        => $invoice_detail[0]['invoice_id'],
                'invoice_no'        => $invoice_detail[0]['invoice'],
                'customer_name'     => $invoice_detail[0]['customer_name'],
                'customer_address'  => $invoice_detail[0]['customer_address'],
                'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
                'customer_email'    => $invoice_detail[0]['customer_email'],
                'final_date'        => $invoice_detail[0]['final_date'],
                'invoice_details'   => $invoice_detail[0]['invoice_details'],
                'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
                'subTotal_quantity' => $subTotal_quantity,
                'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
                'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
                'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
                'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
                'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
                'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
                'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
                'invoice_all_data'  => $invoice_detail,
                'am_inword'         => $amount_inword,
                'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
                'users_name'        => $users->first_name . ' ' . $users->last_name,
                'tax_regno'         => $txregname,
                'is_desc'           => $descript,
                'is_serial'         => $isserial,
                'is_unit'           => $isunit,


                'delivery_multiple'   => $invoice_detail[0]['delivery_multiple'],

                'branchoffice' => $invoice_detail[0]['branchoffice'],

                'fecha_entrega'   => $invoice_detail[0]['fecha_entrega'],
                'hora_entrega'   => $invoice_detail[0]['hora_entrega'],
                'destinatario'   => $invoice_detail[0]['destinatario'],
                'direccion'   => $invoice_detail[0]['direccion'],

                'direccion2'   => $invoice_detail[0]['direccion2'],
                'nombre_cliente'   => $invoice_detail[0]['nombre_cliente'],
                'telefono_cliente'   => $invoice_detail[0]['telefono_cliente'],


                'telefono'   => $invoice_detail[0]['telefono'],
                'descripcion_entrega'   => $invoice_detail[0]['descripcion_entrega'],

                'destinatario_reparto'   => $invoice_detail[0]['destinatario_reparto'],
                'fecha_entrega_reparto'   => $invoice_detail[0]['fecha_entrega_reparto'],
                'direccion_reparto'   => $invoice_detail[0]['direccion_reparto'],
                'descripcion_entrega_reparto'   => $invoice_detail[0]['descripcion_entrega_reparto'],

                'cliente_caja'   => $invoice_detail[0]['cliente_caja'],
                'fecha_entrega_caja'   => $invoice_detail[0]['fecha_entrega_caja'],
                'destinatario_caja'   => $invoice_detail[0]['destinatario_caja'],
                'direccion_caja'   => $invoice_detail[0]['direccion_caja'],
                'descripcion_entrega_caja'   => $invoice_detail[0]['descripcion_entrega_caja'],
                'repartidor_caja'   => $repartidor_caja,

                'fecha_entrega_taller'   => $invoice_detail[0]['fecha_entrega_taller'],
                'florista_taller'   => $florista_taller,
                'cantidad_modelo'   => $invoice_detail[0]['cantidad_modelo'],
                'modelo'   => $invoice_detail[0]['modelo'],
                'cantidad_bases'   => $invoice_detail[0]['cantidad_bases'],
                'bases'   => $invoice_detail[0]['bases'],
                'cantidad_flores'   => $invoice_detail[0]['cantidad_flores'],
                'flores'   => $invoice_detail[0]['flores'],
                'cantidad_otros'   => $invoice_detail[0]['cantidad_otros'],
                'otros'   => $invoice_detail[0]['otros'],
                'descripcion_entrega_taller'   => $invoice_detail[0]['descripcion_entrega_taller'],
                'tipo_venta'   => $invoice_detail[0]['tipo_venta'],
                'seller'   => $invoice_detail[0]['seller'],
                'tipo_pago'   => $invoice_detail[0]['tipo_pago'],
                'zona'   => $zona,
                'mensaje'   => $invoice_detail[0]['mensaje'],
                'creacion'   => $invoice_detail[0]['creacion'],
            );
        }
        $data['module']     = "invoice";
        $data['page']       = "invoice_html";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_quote_details($invoice_id = null)
    {

        $invoice_detail     = $this->quote_model->retrieve_invoice_html_data($invoice_id);

        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $invoice_detail[$k]['date'];
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount  = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }

            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }


        $totalbal      = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword = $totalbal;
        $user_id       = $invoice_detail[0]['sales_by'];
        $users         = $this->invoice_model->user_invoice_data($user_id);


        $delim = $invoice_detail[0]['delivery_multiple'];

        if ($delim == '1') {

            $data_reparto    = $this->invoice_model->reparto_invoice_html_data($invoice_id);
            $data_caja       = $this->invoice_model->caja_invoice_html_data($invoice_id);
            $data_taller     = $this->invoice_model->taller_invoice_html_data($invoice_id);

            $data = array(
                'title'             => 'Ticket - Venta',
                //'title'             => display('invoice_details'),
                'invoice_id'        => $invoice_detail[0]['invoice_id'],
                'invoice_no'        => $invoice_detail[0]['invoice'],
                'customer_name'     => $invoice_detail[0]['customer_name'],
                'customer_address'  => $invoice_detail[0]['customer_address'],
                'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
                'customer_email'    => $invoice_detail[0]['customer_email'],
                'final_date'        => $invoice_detail[0]['final_date'],
                'invoice_details'   => $invoice_detail[0]['invoice_details'],
                'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
                'subTotal_quantity' => $subTotal_quantity,
                'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
                'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
                'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
                'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
                'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
                'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
                'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
                'invoice_all_data'  => $invoice_detail,
                'am_inword'         => $amount_inword,
                'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
                'users_name'        => $users->first_name . ' ' . $users->last_name,
                'tax_regno'         => $txregname,
                'is_desc'           => $descript,
                'is_serial'         => $isserial,
                'is_unit'           => $isunit,
                'delivery_multiple' => $invoice_detail[0]['delivery_multiple'],

                'branchoffice' => $invoice_detail[0]['branchoffice'],

                'data_reparto'      => $data_reparto,
                'data_caja'      => $data_caja,
                'data_taller'      => $data_taller,
                'tipo_venta'   => $invoice_detail[0]['tipo_venta'],
                'seller'   => $invoice_detail[0]['seller'],
                'tipo_pago'   => $invoice_detail[0]['tipo_pago'],
                'creacion'   => $invoice_detail[0]['creacion'],

            );
        } else {


            $data_f = (array)$this->invoice_model->single_florist_data($invoice_detail[0]['florista_taller']);
            $data_d = (array)$this->invoice_model->single_deliveryman_data($invoice_detail[0]['repartidor_caja']);
            $data_z = (array)$this->invoice_model->single_zona_data($invoice_detail[0]['zona']);

            $zona = $data_z['zona'];

            $florista_taller = $data_f['first_name'] . ' ' . $data_f['last_name'];
            $repartidor_caja = $data_d['first_name'] . ' ' . $data_d['last_name'];

            $data = array(
                'title'             => 'Ticket - Venta',
                //'title'             => display('invoice_details'),
                'invoice_id'        => $invoice_detail[0]['quote_id'],
                'invoice_no'        => $invoice_detail[0]['quote'],
                'customer_name'     => $invoice_detail[0]['customer_name'],
                'customer_address'  => $invoice_detail[0]['customer_address'],
                'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
                'customer_email'    => $invoice_detail[0]['customer_email'],
                'final_date'        => $invoice_detail[0]['final_date'],
                'invoice_details'   => $invoice_detail[0]['quote_details'],
                'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
                'subTotal_quantity' => $subTotal_quantity,
                'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
                'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
                'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
                'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
                'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
                'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
                'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
                'invoice_all_data'  => $invoice_detail,
                'am_inword'         => $amount_inword,
                'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['quote_discount'],
                'users_name'        => $users->first_name . ' ' . $users->last_name,
                'tax_regno'         => $txregname,
                'is_desc'           => $descript,
                'is_serial'         => $isserial,
                'is_unit'           => $isunit,


                'delivery_multiple'   => $invoice_detail[0]['delivery_multiple'],

                'branchoffice' => $invoice_detail[0]['branchoffice'],

                'fecha_entrega'   => $invoice_detail[0]['fecha_entrega'],
                'hora_entrega'   => $invoice_detail[0]['hora_entrega'],
                'destinatario'   => $invoice_detail[0]['destinatario'],
                'direccion'   => $invoice_detail[0]['direccion'],

                'direccion2'   => $invoice_detail[0]['direccion2'],
                'nombre_cliente'   => $invoice_detail[0]['nombre_cliente'],
                'telefono_cliente'   => $invoice_detail[0]['telefono_cliente'],


                'telefono'   => $invoice_detail[0]['telefono'],
                'descripcion_entrega'   => $invoice_detail[0]['descripcion_entrega'],

                'destinatario_reparto'   => $invoice_detail[0]['destinatario_reparto'],
                'fecha_entrega_reparto'   => $invoice_detail[0]['fecha_entrega_reparto'],
                'direccion_reparto'   => $invoice_detail[0]['direccion_reparto'],
                'descripcion_entrega_reparto'   => $invoice_detail[0]['descripcion_entrega_reparto'],

                'cliente_caja'   => $invoice_detail[0]['cliente_caja'],
                'fecha_entrega_caja'   => $invoice_detail[0]['fecha_entrega_caja'],
                'destinatario_caja'   => $invoice_detail[0]['destinatario_caja'],
                'direccion_caja'   => $invoice_detail[0]['direccion_caja'],
                'descripcion_entrega_caja'   => $invoice_detail[0]['descripcion_entrega_caja'],
                'repartidor_caja'   => $repartidor_caja,

                'fecha_entrega_taller'   => $invoice_detail[0]['fecha_entrega_taller'],
                'florista_taller'   => $florista_taller,
                'cantidad_modelo'   => $invoice_detail[0]['cantidad_modelo'],
                'modelo'   => $invoice_detail[0]['modelo'],
                'cantidad_bases'   => $invoice_detail[0]['cantidad_bases'],
                'bases'   => $invoice_detail[0]['bases'],
                'cantidad_flores'   => $invoice_detail[0]['cantidad_flores'],
                'flores'   => $invoice_detail[0]['flores'],
                'cantidad_otros'   => $invoice_detail[0]['cantidad_otros'],
                'otros'   => $invoice_detail[0]['otros'],
                'descripcion_entrega_taller'   => $invoice_detail[0]['descripcion_entrega_taller'],
                'tipo_venta'   => $invoice_detail[0]['tipo_venta'],
                'seller'   => $invoice_detail[0]['seller'],
                'tipo_pago'   => $invoice_detail[0]['tipo_pago'],
                'zona'   => $zona,
                'mensaje'   => $invoice_detail[0]['mensaje'],
                'creacion'   => $invoice_detail[0]['creacion'],
            );
        }
        $data['module']     = "invoice";
        $data['page']       = "quote_html";
        echo modules::run('template/layout', $data);
    }
    public function bdtask_invoice_details_cs($invoice_id = null)
    {

        $invoice_detail     = $this->invoice_model->retrieve_invoice_html_data($invoice_id);

        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $invoice_detail[$k]['date'];
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount  = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }

            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }
        $totalbal      = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword = $totalbal;
        $user_id       = $invoice_detail[0]['sales_by'];
        $users         = $this->invoice_model->user_invoice_data($user_id);


        $delim = $invoice_detail[0]['delivery_multiple'];

        if ($delim == '1') {

            $data_reparto    = $this->invoice_model->reparto_invoice_html_data($invoice_id);
            $data_caja       = $this->invoice_model->caja_invoice_html_data($invoice_id);
            $data_taller     = $this->invoice_model->taller_invoice_html_data($invoice_id);

            $data = array(
                'title'             => 'Ticket - cliente',
                //'title'             => display('invoice_details'),
                'invoice_id'        => $invoice_detail[0]['invoice_id'],
                'invoice_no'        => $invoice_detail[0]['invoice'],
                'customer_name'     => $invoice_detail[0]['customer_name'],
                'customer_address'  => $invoice_detail[0]['customer_address'],
                'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
                'customer_email'    => $invoice_detail[0]['customer_email'],
                'final_date'        => $invoice_detail[0]['final_date'],
                'invoice_details'   => $invoice_detail[0]['invoice_details'],
                'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
                'subTotal_quantity' => $subTotal_quantity,
                'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
                'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
                'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
                'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
                'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
                'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
                'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
                'invoice_all_data'  => $invoice_detail,
                'am_inword'         => $amount_inword,
                'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
                'users_name'        => $users->first_name . ' ' . $users->last_name,
                'tax_regno'         => $txregname,
                'is_desc'           => $descript,
                'is_serial'         => $isserial,
                'is_unit'           => $isunit,
                'delivery_multiple' => $invoice_detail[0]['delivery_multiple'],

                'branchoffice' => $invoice_detail[0]['branchoffice'],

                'data_reparto'      => $data_reparto,
                'data_caja'      => $data_caja,
                'data_taller'      => $data_taller,
                'tipo_venta'   => $invoice_detail[0]['tipo_venta'],
                'seller'   => $invoice_detail[0]['seller'],
                'tipo_pago'   => $invoice_detail[0]['tipo_pago'],
                'creacion'   => $invoice_detail[0]['creacion'],

            );
        } else {
            $data_f = (array)$this->invoice_model->single_florist_data($invoice_detail[0]['florista_taller']);
            $data_d = (array)$this->invoice_model->single_deliveryman_data($invoice_detail[0]['repartidor_caja']);

            $florista_taller = $data_f['first_name'] . ' ' . $data_f['last_name'];
            $repartidor_caja = $data_d['first_name'] . ' ' . $data_d['last_name'];

            $data = array(
                'title'             => 'Ticket - cliente',
                //'title'             => display('invoice_details'),
                'invoice_id'        => $invoice_detail[0]['invoice_id'],
                'invoice_no'        => $invoice_detail[0]['invoice'],
                'customer_name'     => $invoice_detail[0]['customer_name'],
                'customer_address'  => $invoice_detail[0]['customer_address'],
                'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
                'customer_email'    => $invoice_detail[0]['customer_email'],
                'final_date'        => $invoice_detail[0]['final_date'],
                'invoice_details'   => $invoice_detail[0]['invoice_details'],
                'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
                'subTotal_quantity' => $subTotal_quantity,
                'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
                'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
                'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
                'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
                'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
                'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
                'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
                'invoice_all_data'  => $invoice_detail,
                'am_inword'         => $amount_inword,
                'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
                'users_name'        => $users->first_name . ' ' . $users->last_name,
                'tax_regno'         => $txregname,
                'is_desc'           => $descript,
                'is_serial'         => $isserial,
                'is_unit'           => $isunit,


                'delivery_multiple'   => $invoice_detail[0]['delivery_multiple'],

                'branchoffice' => $invoice_detail[0]['branchoffice'],
                'fecha_entrega'   => $invoice_detail[0]['fecha_entrega'],

                'nombre_cliente'   => $invoice_detail[0]['nombre_cliente'],
                'telefono_cliente'   => !empty($invoice_detail[0]['telefono_cliente']) ? $invoice_detail[0]['telefono_cliente'] : "-------",

                'hora_entrega'   => $invoice_detail[0]['hora_entrega'],
                'destinatario'   => $invoice_detail[0]['destinatario'],
                'direccion'   => $invoice_detail[0]['direccion'],
                'telefono'   => $invoice_detail[0]['telefono'],
                'descripcion_entrega'   => $invoice_detail[0]['descripcion_entrega'],

                'destinatario_reparto'   => $invoice_detail[0]['destinatario_reparto'],
                'fecha_entrega_reparto'   => $invoice_detail[0]['fecha_entrega_reparto'],
                'direccion_reparto'   => $invoice_detail[0]['direccion_reparto'],
                'descripcion_entrega_reparto'   => $invoice_detail[0]['descripcion_entrega_reparto'],

                'cliente_caja'   => $invoice_detail[0]['cliente_caja'],
                'fecha_entrega_caja'   => $invoice_detail[0]['fecha_entrega_caja'],
                'destinatario_caja'   => $invoice_detail[0]['destinatario_caja'],
                'direccion_caja'   => $invoice_detail[0]['direccion_caja'],
                'descripcion_entrega_caja'   => $invoice_detail[0]['descripcion_entrega_caja'],
                'repartidor_caja'   => $repartidor_caja,

                'fecha_entrega_taller'   => $invoice_detail[0]['fecha_entrega_taller'],
                'florista_taller'   => $florista_taller,
                'cantidad_modelo'   => $invoice_detail[0]['cantidad_modelo'],
                'modelo'   => $invoice_detail[0]['modelo'],
                'cantidad_bases'   => $invoice_detail[0]['cantidad_bases'],
                'bases'   => $invoice_detail[0]['bases'],
                'cantidad_flores'   => $invoice_detail[0]['cantidad_flores'],
                'flores'   => $invoice_detail[0]['flores'],
                'cantidad_otros'   => $invoice_detail[0]['cantidad_otros'],
                'otros'   => $invoice_detail[0]['otros'],
                'descripcion_entrega_taller'   => $invoice_detail[0]['descripcion_entrega_taller'],
                'tipo_venta'   => $invoice_detail[0]['tipo_venta'],
                'seller'   => $invoice_detail[0]['seller'],
                'tipo_pago'   => $invoice_detail[0]['tipo_pago'],
                'creacion'   => $invoice_detail[0]['creacion'],
            );
        }
        $data['module']     = "invoice";
        $data['page']       = "invoice_cs_html";
        echo modules::run('template/layout', $data);
    }
    public function bdtask_invoice_details_del($invoice_id = null)
    {

        $invoice_detail     = $this->invoice_model->retrieve_invoice_html_data($invoice_id);

        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $invoice_detail[$k]['date'];
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount  = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }

            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }


        $totalbal      = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword = $totalbal;
        $user_id       = $invoice_detail[0]['sales_by'];
        $users         = $this->invoice_model->user_invoice_data($user_id);


        $delim = $invoice_detail[0]['delivery_multiple'];

        if ($delim == '1') {

            $data_reparto    = $this->invoice_model->reparto_invoice_html_data($invoice_id);
            $data_caja       = $this->invoice_model->caja_invoice_html_data($invoice_id);
            $data_taller     = $this->invoice_model->taller_invoice_html_data($invoice_id);

            $data = array(
                'title'             => 'Ticket - reparto',
                //'title'             => display('invoice_details'),
                'invoice_id'        => $invoice_detail[0]['invoice_id'],
                'invoice_no'        => $invoice_detail[0]['invoice'],
                'customer_name'     => $invoice_detail[0]['customer_name'],
                'customer_address'  => $invoice_detail[0]['customer_address'],
                'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
                'customer_email'    => $invoice_detail[0]['customer_email'],
                'final_date'        => $invoice_detail[0]['final_date'],
                'invoice_details'   => $invoice_detail[0]['invoice_details'],
                'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
                'subTotal_quantity' => $subTotal_quantity,
                'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
                'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
                'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
                'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
                'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
                'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
                'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
                'invoice_all_data'  => $invoice_detail,
                'am_inword'         => $amount_inword,
                'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
                'users_name'        => $users->first_name . ' ' . $users->last_name,
                'tax_regno'         => $txregname,
                'is_desc'           => $descript,
                'is_serial'         => $isserial,
                'is_unit'           => $isunit,
                'delivery_multiple' => $invoice_detail[0]['delivery_multiple'],

                'branchoffice' => $invoice_detail[0]['branchoffice'],

                'data_reparto'      => $data_reparto,
                'data_caja'      => $data_caja,
                'data_taller'      => $data_taller,
                'tipo_venta'   => $invoice_detail[0]['tipo_venta'],
                'seller'   => $invoice_detail[0]['seller'],
                'tipo_pago'   => $invoice_detail[0]['tipo_pago'],
                'creacion'   => $invoice_detail[0]['creacion'],

            );
        } else {


            $data_f = (array)$this->invoice_model->single_florist_data($invoice_detail[0]['florista_taller']);
            $data_d = (array)$this->invoice_model->single_deliveryman_data($invoice_detail[0]['repartidor_caja']);
            $data_z = (array)$this->invoice_model->single_zona_data($invoice_detail[0]['zona']);

            $zona = $data_z['zona'];

            $florista_taller = $data_f['first_name'] . ' ' . $data_f['last_name'];
            $repartidor_caja = $data_d['first_name'] . ' ' . $data_d['last_name'];

            $data = array(
                'title'             => 'Ticket - reparto',
                //'title'             => display('invoice_details'),
                'invoice_id'        => $invoice_detail[0]['invoice_id'],
                'invoice_no'        => $invoice_detail[0]['invoice'],
                'customer_name'     => $invoice_detail[0]['customer_name'],
                'customer_address'  => $invoice_detail[0]['customer_address'],
                'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
                'customer_email'    => $invoice_detail[0]['customer_email'],
                'final_date'        => $invoice_detail[0]['final_date'],
                'invoice_details'   => $invoice_detail[0]['invoice_details'],
                'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
                'subTotal_quantity' => $subTotal_quantity,
                'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
                'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
                'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
                'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
                'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
                'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
                'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
                'invoice_all_data'  => $invoice_detail,
                'am_inword'         => $amount_inword,
                'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
                'users_name'        => $users->first_name . ' ' . $users->last_name,
                'tax_regno'         => $txregname,
                'is_desc'           => $descript,
                'is_serial'         => $isserial,
                'is_unit'           => $isunit,


                'delivery_multiple'   => $invoice_detail[0]['delivery_multiple'],

                'branchoffice' => $invoice_detail[0]['branchoffice'],
                'fecha_entrega'   => $invoice_detail[0]['fecha_entrega'],

                'nombre_cliente'   => $invoice_detail[0]['nombre_cliente'],

                'hora_entrega'   => $invoice_detail[0]['hora_entrega'],
                'destinatario'   => $invoice_detail[0]['destinatario'],
                'direccion'   => $invoice_detail[0]['direccion'],
                'telefono'   => $invoice_detail[0]['telefono'],
                'descripcion_entrega'   => $invoice_detail[0]['descripcion_entrega'],

                'destinatario_reparto'   => $invoice_detail[0]['destinatario_reparto'],
                'fecha_entrega_reparto'   => $invoice_detail[0]['fecha_entrega_reparto'],
                'direccion_reparto'   => $invoice_detail[0]['direccion_reparto'],
                'descripcion_entrega_reparto'   => $invoice_detail[0]['descripcion_entrega_reparto'],

                'cliente_caja'   => $invoice_detail[0]['cliente_caja'],
                'fecha_entrega_caja'   => $invoice_detail[0]['fecha_entrega_caja'],
                'destinatario_caja'   => $invoice_detail[0]['destinatario_caja'],
                'direccion_caja'   => $invoice_detail[0]['direccion_caja'],
                'descripcion_entrega_caja'   => $invoice_detail[0]['descripcion_entrega_caja'],
                'repartidor_caja'   => $repartidor_caja,

                'fecha_entrega_taller'   => $invoice_detail[0]['fecha_entrega_taller'],
                'florista_taller'   => $florista_taller,
                'cantidad_modelo'   => $invoice_detail[0]['cantidad_modelo'],
                'modelo'   => $invoice_detail[0]['modelo'],
                'cantidad_bases'   => $invoice_detail[0]['cantidad_bases'],
                'bases'   => $invoice_detail[0]['bases'],
                'cantidad_flores'   => $invoice_detail[0]['cantidad_flores'],
                'flores'   => $invoice_detail[0]['flores'],
                'cantidad_otros'   => $invoice_detail[0]['cantidad_otros'],
                'otros'   => $invoice_detail[0]['otros'],
                'descripcion_entrega_taller'   => $invoice_detail[0]['descripcion_entrega_taller'],
                'tipo_venta'   => $invoice_detail[0]['tipo_venta'],
                'seller'   => $invoice_detail[0]['seller'],
                'tipo_pago'   => $invoice_detail[0]['tipo_pago'],
                'creacion'   => $invoice_detail[0]['creacion'],
                'zona'   => $zona,
            );
        }

        $data['module']     = "invoice";
        $data['page']       = "invoice_del_html";
        echo modules::run('template/layout', $data);
    }
    public function bdtask_invoice_pad_print($invoice_id)
    {
        $invoice_detail = $this->invoice_model->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
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
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }

        $totalbal      = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword = $this->numbertowords->convert_number($totalbal);
        $user_id       = $invoice_detail[0]['sales_by'];
        $users         = $this->invoice_model->user_invoice_data($user_id);
        $data = array(
            'title'            => display('pad_print'),
            'invoice_id'       => $invoice_detail[0]['invoice_id'],
            'invoice_no'       => $invoice_detail[0]['invoice'],
            'customer_name'    => $invoice_detail[0]['customer_name'],
            'customer_address' => $invoice_detail[0]['customer_address'],
            'customer_mobile'  => $invoice_detail[0]['customer_mobile'],
            'customer_email'   => $invoice_detail[0]['customer_email'],
            'final_date'       => $invoice_detail[0]['final_date'],
            'print_setting'    => $this->invoice_model->bdtask_print_settingdata(),
            'invoice_details'  => $invoice_detail[0]['invoice_details'],
            'total_amount'     => number_format($totalbal, 2, '.', ','),
            'subTotal_cartoon' => $subTotal_cartoon,
            'subTotal_quantity' => $subTotal_quantity,
            'invoice_discount' => number_format($invoice_detail[0]['invoice_discount'], 2, '.', ','),
            'total_discount'   => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
            'total_tax'        => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
            'subTotal_ammount' => number_format($subTotal_ammount, 2, '.', ','),
            'paid_amount'      => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
            'due_amount'       => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
            'shipping_cost'   => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
            'invoice_all_data' => $invoice_detail,
            'previous'         => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
            'am_inword'        => $amount_inword,
            'is_discount'      => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],

            'users_name'       => $users->first_name . ' ' . $users->last_name,
            'tax_regno'        => $txregname,
            'is_desc'          => $descript,
            'is_serial'        => $isserial,
            'is_unit'          => $isunit,

        );

        $data['module']     = "invoice";
        $data['page']       = "pad_print";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_invoice_pos_print($invoice_id = null)
    {
        $invoice_detail = $this->invoice_model->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $is_discount       = 0;
        $isunit            = 0;
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
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
                if (!empty($invoice_detail[$k]['discount_per'])) {
                    $is_discount = $is_discount + 1;
                }
            }
        }


        $totalbal = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $user_id  = $invoice_detail[0]['sales_by'];
        $users    = $this->invoice_model->user_invoice_data($user_id);
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
            'users_name'           => $users->first_name . ' ' . $users->last_name,
            'tax_regno'            => $txregname,
            'is_desc'              => $descript,
            'is_serial'            => $isserial,
            'is_unit'              => $isunit,

        );

        $data['module']     = "invoice";
        $data['page']       = "pos_print";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_pos_print_direct()
    {
        $invoice_id = $this->input->post('invoice_id', true);
        $invoice_detail = $this->invoice_model->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $is_discount       = 0;
        $isunit            = 0;
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
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
                if (!empty($invoice_detail[$k]['discount_per'])) {
                    $is_discount = $is_discount + 1;
                }
            }
        }


        $totalbal = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $user_id  = $invoice_detail[0]['sales_by'];
        $users    = $this->invoice_model->user_invoice_data($user_id);
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
            'users_name'           => $users->first_name . ' ' . $users->last_name,
            'tax_regno'            => $txregname,
            'is_desc'              => $descript,
            'is_serial'            => $isserial,
            'is_unit'              => $isunit,
            'url'                  => $this->input->post('url', TRUE),

        );

        $data['module']     = "invoice";
        $data['page']       = "pos_invoice_html_direct";
        echo modules::run('template/layout', $data);
    }


    public function bdtask_download_invoice($invoice_id = null)
    {
        $invoice_detail = $this->invoice_model->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
        $is_discount       = 0;
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
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['discount_per'])) {
                    $is_discount = $is_discount + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }

        $currency_details = $this->invoice_model->retrieve_setting_editdata();
        $company_info     = $this->invoice_model->retrieve_company();
        $totalbal         = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword    = $this->numbertowords->convert_number($totalbal);
        $user_id          = $invoice_detail[0]['sales_by'];
        $users            = $this->invoice_model->user_invoice_data($user_id);
        $data = array(
            'title'             => display('invoice_details'),
            'invoice_id'        => $invoice_detail[0]['invoice_id'],
            'customer_info'     => $invoice_detail,
            'invoice_no'        => $invoice_detail[0]['invoice'],
            'customer_name'     => $invoice_detail[0]['customer_name'],
            'customer_address'  => $invoice_detail[0]['customer_address'],
            'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
            'customer_email'    => $invoice_detail[0]['customer_email'],
            'final_date'        => $invoice_detail[0]['final_date'],
            'invoice_details'   => $invoice_detail[0]['invoice_details'],
            'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
            'subTotal_quantity' => $subTotal_quantity,
            'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
            'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
            'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
            'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
            'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
            'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
            'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
            'invoice_all_data'  => $invoice_detail,
            'company_info'      => $company_info,
            'currency'          => $currency_details[0]['currency'],
            'position'          => $currency_details[0]['currency_position'],
            'discount_type'     => $currency_details[0]['discount_type'],
            'currency_details'  => $currency_details,
            'am_inword'         => $amount_inword,
            'is_discount'       => $is_discount,
            'users_name'        => $users->first_name . ' ' . $users->last_name,
            'tax_regno'         => $txregname,
            'is_desc'           => $descript,
            'is_serial'         => $isserial,
            'is_unit'           => $isunit,
        );



        $this->load->library('pdfgenerator');
        $dompdf = new DOMPDF();
        $page = $this->load->view('invoice/invoice_download', $data, true);
        $file_name = time();
        $dompdf->load_html($page, 'UTF-8');
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents("assets/data/pdf/invoice/$file_name.pdf", $output);
        $filename = $file_name . '.pdf';
        $file_path = base_url() . 'assets/data/pdf/invoice/' . $filename;

        $this->load->helper('download');
        force_download('./assets/data/pdf/invoice/' . $filename, NULL);
        redirect("invoice_list");
    }

    public function bdtask_manual_sales_insert()
    {

        session_start();
        unset($_SESSION["personalizado"]);

        $this->form_validation->set_rules('customer_id', display('customer_name'), 'required|max_length[15]');
        $this->form_validation->set_rules('paytype', display('payment_type'), 'required|max_length[20]');
        //$this->form_validation->set_rules('invoice_no', display('invoice_no') ,'required|max_length[20]|is_unique[invoice.invoice]');
        $this->form_validation->set_rules('invoice_no', display('invoice_no'), 'required|max_length[20]');
        $this->form_validation->set_rules('product_id[]', display('product'), 'required|max_length[20]');
        $this->form_validation->set_rules('product_quantity[]', display('quantity'), 'required|max_length[20]');
        $this->form_validation->set_rules('product_rate[]', display('rate'), 'required|max_length[20]');
        $normal = $this->input->post('is_normal');

        if ($this->form_validation->run() === true) {
            if ($this->input->post('quote') == 1) {
                try {
                    $invoice_id = $this->quote_model->invoice_entry();
                } catch (Exception $e) {
                    echo json_encode($e->getMessage());
                    exit();
                }
            } else {
                $invoice_id = $this->invoice_model->invoice_entry();
            }
            if (!empty($invoice_id)) {
                $data['status'] = true;
                $data['invoice_id'] = $invoice_id;
                $data['message'] = display('save_successfully');
                $mailsetting = $this->db->select('*')->from('email_config')->get()->result_array();
                if ($mailsetting[0]['isinvoice'] == 1) {
                    $mail = $this->invoice_pdf_generate($invoice_id);
                    if ($mail == 0) {
                        $data['exception'] = $this->session->set_userdata(array('error_message' => display('please_config_your_mail_setting')));
                    }
                }
                if ($normal == 1) {
                    if ($this->input->post('quote' == 1)) {
                        $printdata = $this->bdtask_quote_details_directprint($invoice_id);
                        $data["quote"] = 1;
                    } else {
                        $printdata = $this->bdtask_invoice_details_directprint($invoice_id);
                    }
                    $data['details'] = $this->load->view('invoice/invoice_html_manual', $printdata, true);
                } else {
                    if ($this->input->post('quote') == 1) {
                        $printdata = $this->quote_model->bdtask_qoute_pos_print_direct($invoice_id);
                        $data["quote"] = 1;
                    } else {
                        $printdata = $this->invoice_model->bdtask_invoice_pos_print_direct($invoice_id);
                    }
                    $data['details'] = $this->load->view('invoice/pos_print', $printdata, true);
                }
            } else {
                $data['status'] = false;
                $data['exception'] = 'Please Try Again';
            }
        } else {
            $data['status'] = false;
            $data['exception'] = validation_errors();
        }
        echo json_encode($data);
    }

    public function bdtask_manual_sales_update()
    {
        try {
            session_start();
            unset($_SESSION["personalizado"]);

            $this->form_validation->set_rules('customer_id', display('customer_name'), 'required|max_length[15]');
            $this->form_validation->set_rules('paytype', display('payment_type'), 'required|max_length[20]');
            $this->form_validation->set_rules('invoice_no', display('invoice_no'), 'required|max_length[20]');
            $this->form_validation->set_rules('product_id[]', display('product'), 'required|max_length[20]');
            $this->form_validation->set_rules('product_quantity[]', display('quantity'), 'required|max_length[20]');
            $this->form_validation->set_rules('product_rate[]', display('rate'), 'required|max_length[20]');

            if ($this->form_validation->run() === true) {
                $invoice_id = $this->invoice_model->invoice_entry_edit();
                if (!empty($invoice_id)) {
                    $data['status'] = true;
                    $data['invoice_id'] = $invoice_id;
                    $data['message'] = display('save_successfully');
                } else {
                    $data['status'] = false;
                    $data['exception'] = 'Please Try Again';
                }
            } else {
                $data['status'] = false;
                $data['exception'] = validation_errors();
            }
        } catch (Exception $e) {
            // Captura cualquier excepción que ocurra
            $data['status'] = false;
            $data['exception'] = 'An error occurred: ' . $e->getMessage();
        }

        echo json_encode($data);
    }


    public function bdtask_edit_invoice($invoice_id = null)
    {
        $invoice_detail = $this->invoice_model->retrieve_invoice_editdata($invoice_id);
        $taxinfo        = $this->invoice_model->invoice_taxinfo($invoice_id);
        $taxfield       = $this->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $i = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                $stock = $this->invoice_model->stock_qty_check($invoice_detail[$k]['product_id']);
                $invoice_detail[$k]['stock_qty'] = $stock + $invoice_detail[$k]['quantity'];
            }
        }

        $currency_details = $this->invoice_model->retrieve_setting_editdata();
        $data = array(
            'title'           => display('invoice_edit'),
            'invoice_id'      => $invoice_detail[0]['invoice_id'],
            'customer_id'     => $invoice_detail[0]['customer_id'],
            'customer_name'   => $invoice_detail[0]['customer_name'],
            'date'            => $invoice_detail[0]['date'],
            'invoice_details' => $invoice_detail[0]['invoice_details'],
            'invoice'         => $invoice_detail[0]['invoice'],
            'total_amount'    => $invoice_detail[0]['total_amount'],
            'paid_amount'     => $invoice_detail[0]['paid_amount'],
            'due_amount'      => $invoice_detail[0]['due_amount'],
            'invoice_discount' => $invoice_detail[0]['invoice_discount'],
            'total_discount'  => $invoice_detail[0]['total_discount'],
            'unit'            => $invoice_detail[0]['unit'],
            'tax'             => $invoice_detail[0]['tax'],
            'taxes'          => $taxfield,
            'prev_due'        => $invoice_detail[0]['prevous_due'],
            'net_total'       => $invoice_detail[0]['prevous_due'] + $invoice_detail[0]['total_amount'],
            'shipping_cost'   => $invoice_detail[0]['shipping_cost'],
            'total_tax'       => $invoice_detail[0]['taxs'],
            'invoice_all_data' => $invoice_detail,
            'taxvalu'         => $taxinfo,
            'discount_type'   => $currency_details[0]['discount_type'],
            'bank_id'         => $invoice_detail[0]['bank_id'],
            'paytype'         => $invoice_detail[0]['payment_type'],
        );
        $data['module']     = "invoice";
        $data['page']       = "edit_invoice_form";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_update_invoice()
    {
        $this->form_validation->set_rules('customer_id', display('customer_name'), 'required|max_length[15]');
        $this->form_validation->set_rules('paytype', display('payment_type'), 'required|max_length[20]');
        $this->form_validation->set_rules('invoice_no', display('invoice_no'), 'required|max_length[20]');
        $this->form_validation->set_rules('product_id[]', display('product'), 'required|max_length[20]');
        $this->form_validation->set_rules('product_quantity[]', display('quantity'), 'required|max_length[20]');
        $this->form_validation->set_rules('product_rate[]', display('rate'), 'required|max_length[20]');

        if ($this->form_validation->run() === true) {
            $invoice_id = $this->invoice_model->update_invoice();
            if (!empty($invoice_id)) {
                $data['status'] = true;
                $data['invoice_id'] = $invoice_id;
                $data['message'] = display('update_successfully');
                $mailsetting = $this->db->select('*')->from('email_config')->get()->result_array();
                if ($mailsetting[0]['isinvoice'] == 1) {
                    $mail = $this->invoice_pdf_generate($invoice_id);
                    if ($mail == 0) {
                        $data['exception'] = $this->session->set_userdata(array('error_message' => display('please_config_your_mail_setting')));
                    }
                }
                $data['details'] = $this->load->view('invoice/invoice_html', $data, true);
            } else {
                $data['status'] = false;
                $data['exception'] = 'Please Try Again';
            }
        } else {
            $data['status'] = false;
            $data['exception'] = validation_errors();
        }
        echo json_encode($data);
    }

    function bdtask_pos_invoice()
    {
        $taxfield = $this->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $tablecolumn   = $this->db->list_fields('tax_collection');
        $num_column    = count($tablecolumn) - 4;
        $walking_customer      = $this->invoice_model->pos_customer_setup();
        $data['customer_name'] = $walking_customer[0]['customer_name'];
        $data['customer_id']   = $walking_customer[0]['customer_id'];
        $data['invoice_no']    = $this->number_generator();
        $data['title']         = display('pos_invoice');
        $data['taxes']         = $this->invoice_model->tax_fileds();
        $data['taxnumber']     = $num_column;
        $data['module']        = "invoice";
        $data['page']          = "add_pos_invoice_form";
        echo modules::run('template/layout', $data);
    }



    public function bdtask_gui_pos()
    {
        $taxfield = $this->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $tablecolumn       = $this->db->list_fields('tax_collection');
        $num_column        = count($tablecolumn) - 4;




        $mincat = $this->invoice_model->getmincat();


        $data['title']         = display('gui_pos');
        $saveid                = $this->session->userdata('id');
        $walking_customer      = $this->invoice_model->walking_customer();
        $data['customer_id']   = $walking_customer[0]['customer_id'];
        $data['customer_name'] = $walking_customer[0]['customer_name'];
        $data['categorylist']  = $this->invoice_model->category_list();
        $customer_details      = $this->invoice_model->pos_customer_setup();
        $data['customerlist']  = $this->invoice_model->customer_dropdown();
        $data['customer_name'] = $customer_details[0]['customer_name'];
        $data['customer_id']   = $customer_details[0]['customer_id'];



        $data['itemlist']      = $this->invoice_model->allproduct();


        $data['flores']     = $this->invoice_model->all_flores();
        $data['bases']     = $this->invoice_model->all_bases();

        $data['items_customer']     = $this->invoice_model->all_customers_invoice();

        $data['branchoffice']     = $this->invoice_model->allbranchoffice();
        $data['deliveryman']      = $this->invoice_model->alldeliveryman();
        $data['florist']      = $this->invoice_model->allflorist();
        $data['zonas']      = $this->invoice_model->allzonas();

        $data['insumos']      = $this->invoice_model->insumo_list();


        $data['insumos']      = $this->invoice_model->insumo_list();

        $data['product_list']  = $this->invoice_model->product_list();
        $data['taxes']         = $taxfield;
        $data['taxnumber']     = $num_column;
        $data['invoice_no']    = $this->number_generator();
        $data['todays_invoice'] = $this->invoice_model->todays_invoice();

        $data['module']        = "invoice";
        $data['page']          = "gui_pos_invoice";
        echo modules::run('template/layout', $data);
    }

    public function bdtask_get_customer_data()
    {
        $customer_id = $this->input->post('customer_id');

        $this->db->select('customer_name, customer_mobile, customer_email, custom_discount');
        $this->db->from('customer_information');
        $this->db->where('customer_id', $customer_id);
        $customer = $this->db->get()->row_array();

        if (!$customer) {
            echo json_encode(array('error' => 'Cliente no encontrado'));
            exit;
        }

        echo json_encode($customer);
        exit;
    }

    public function get_customer_details()
    {
        $customer_id = $this->input->post('customer_id');

        $this->db->select('customer_name, customer_mobile, customer_email');
        $this->db->from('customer_information');
        $this->db->where('customer_id', $customer_id);
        $customer = $this->db->get()->row_array();

        echo json_encode($customer);
        exit;
    }


    public function bdtask_edit_gui_pos($invoice_id = null)
    {

        $taxfield = $this->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $tablecolumn       = $this->db->list_fields('tax_collection');
        $num_column        = count($tablecolumn) - 4;
        $mincat = $this->invoice_model->getmincat();

        $invoice_data    =    $this->invoice_model->get_invoice_data($invoice_id);
        $invoice_details =    $this->invoice_model->get_invoice_details($invoice_id);
        $invoice_caja      =    $this->invoice_model->get_invoice_caja($invoice_id);
        $invoice_entrega =    $this->invoice_model->get_invoice_entrega($invoice_id);
        $invoice_reparto =    $this->invoice_model->get_invoice_reparto($invoice_id);
        $invoice_taller  =    $this->invoice_model->get_invoice_taller($invoice_id);


        $data['title']         = 'Editar venta';
        $saveid                = $this->session->userdata('id');
        $walking_customer      = $this->invoice_model->walking_customer();
        $data['customer_id']   = $walking_customer[0]['customer_id'];
        $data['customer_name'] = $walking_customer[0]['customer_name'];
        $data['categorylist']  = $this->invoice_model->category_list();
        $customer_details      = $this->invoice_model->pos_customer_setup();
        $data['customerlist']  = $this->invoice_model->customer_dropdown();
        $data['customer_name'] = $customer_details[0]['customer_name'];
        $data['customer_id']   = $customer_details[0]['customer_id'];
        $data['itemlist']      = $this->invoice_model->allproduct();
        $data['branchoffice']  = $this->invoice_model->allbranchoffice();
        $data['deliveryman']   = $this->invoice_model->alldeliveryman();
        $data['florist']       = $this->invoice_model->allflorist();
        $data['zonas']         = $this->invoice_model->allzonas();
        $data['insumos']       = $this->invoice_model->insumo_list();
        $data['product_list']  = $this->invoice_model->product_list();
        $data['taxes']         = $taxfield;
        $data['taxnumber']     = $num_column;
        $data['invoice_no']    = $invoice_data->invoice; //$this->number_generator();
        $data['todays_invoice'] = $this->invoice_model->todays_invoice();

        $data['invoice_data']         = $invoice_data;
        $data['invoice_details']     = $invoice_details;
        $data['invoice_caja']         = $invoice_caja;
        $data['invoice_entrega']     = $invoice_entrega;
        $data['invoice_reparto']     = $invoice_reparto;
        $data['invoice_taller']     = $invoice_taller;


        $data['module']        = "invoice";
        $data['page']          = "edit_gui_pos_invoice";
        echo modules::run('template/layout', $data);
    }



    public function bdtask_getdata_invoice()
    {


        $invoice_id       = $this->input->post('invoice_id', TRUE);

        $invoice_data    =    $this->invoice_model->get_invoice_data($invoice_id);
        $invoice_details =    $this->invoice_model->get_invoice_details($invoice_id);
        $invoice_caja      =    $this->invoice_model->get_invoice_caja($invoice_id);
        $invoice_entrega =    $this->invoice_model->get_invoice_entrega($invoice_id);
        $invoice_reparto =    $this->invoice_model->get_invoice_reparto($invoice_id);
        $invoice_taller  =    $this->invoice_model->get_invoice_taller($invoice_id);

        $data['invoice_no']            = $invoice_data->invoice;
        $data['invoice_data']         = $invoice_data;
        $data['invoice_details']     = $invoice_details;
        $data['invoice_caja']         = $invoice_caja;
        $data['invoice_entrega']     = $invoice_entrega;
        $data['invoice_reparto']     = $invoice_reparto;
        $data['invoice_taller']     = $invoice_taller;

        echo json_encode($data);
    }



    public function getitemlist()
    {

        $catid       = $this->input->post('category_id', TRUE);
        $category_id = (!empty($catid) ? $catid : '');
        $getproduct  = $this->invoice_model->searchprod($category_id);

        if (!empty($getproduct)) {
            $data['itemlist'] = $getproduct;
            $this->load->view('invoice/getproductlist', $data);
        } else {
            $title['title'] = 'Product Not found';
            $this->load->view('invoice/productnot_found', $title);
        }
    }
    public function bdtask_get_prodins()
    {
        $id_insumo       = $this->input->post('id_insumo', TRUE);
        $getproduct  = $this->invoice_model->searchprodins($id_insumo);
        if (!empty($getproduct)) {
            $data['itemlist'] = $getproduct;
            $this->load->view('invoice/getproductlist', $data);
        } else {
            $title['title'] = 'Product Not found';
            $this->load->view('invoice/productnot_found', $title);
        }
    }
    public function getitemlist_byname()
    {
        $product_name     = $this->input->post('product_name', TRUE);
        $getproduct       = $this->invoice_model->searchprod_byname($product_name);
        if (!empty($getproduct)) {
            $data['itemlist'] = $getproduct;
            $this->load->view('invoice/getproductlist', $data);
        } else {
            $title['title']   = 'Product Not found';
            $this->load->view('invoice/productnot_found', $title);
        }
    }



    public function getitemlist_byproductname()
    {
        $prod       = $this->input->post('product_name', TRUE);
        $catid      = $this->input->post('category_id', TRUE);
        $getproduct = $this->invoice_model->searchprod_byname($catid, $prod);
        if (!empty($getproduct)) {
            $data['itemlist'] = $getproduct;
            $this->load->view('invoice/getproductlist', $data);
        } else {
            $title['title'] = 'Product Not found';
            $this->load->view('invoice/productnot_found', $title);
        }
    }

    public function gui_pos_invoice()
    {
        $product_id = $this->input->post('product_id', TRUE);
        $tipo_precio = $this->input->post('tipo_precio', TRUE); // 1, 2, 3 o 4
        $product_details = $this->invoice_model->pos_invoice_setup($product_id);
        // echo "<pre>";
        // var_dump($product_details);
        // echo "</pre>";
        // exit;

        $category_id = $this->db
            ->select('category_id')
            ->from('product_information')
            ->where('product_id', $product_id)
            ->get()
            ->row('category_id');
        $taxfield       = $this->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $prinfo = $this->db->select('*')->from('product_information')->where('product_id', $product_id)->get()->result_array();
        $precio_campo = 'price'; // Default
        $precio_valor = $product_details->price; // Default
        if ($tipo_precio && in_array($tipo_precio, [2, 3, 4])) {
            $precio_campo = 'price_' . $tipo_precio;
            $precio_valor = property_exists($product_details, $precio_campo) ? $product_details->$precio_campo : null;

            if ($precio_valor === null || $precio_valor === '') {
                $precio_valor = $product_details->price;
                // Comentado para evitar problemas visuales en la tabla
                // echo "<script>alert('⚠️ El producto \"" . $product_details->product_name . "\" no tiene definido el Precio $tipo_precio. Se usará el Precio 1.');</script>";
            }
        }
        $tr = " ";
        if (!empty($product_details)) {
            // $product_id = $this->generator(5);
            $serialdata = explode(',', $product_details->serial_no);
            $qty = 1; // Siempre 1 por defecto
            $html = "";
            if (empty($serialdata)) {
                $html .= "No Serial Found !";
            } else {
                // Select option created for product
                $html .= "<select name=\"serial_no[]\"   class=\"serial_no_1 form-control\" id=\"serial_no_" . $product_details->product_id . "\">";
                $html .= "<option value=''>" . display('select_one') . "</option>";
                foreach ($serialdata as $serial) {
                    $html .= "<option value=" . $serial . ">" . $serial . "</option>";
                }
                $html .= "</select>";
            }

            $tr .= "<tr id=\"row_" . $product_details->product_id . "\" >
                        <td class=\"\" style=\"width:220px\">
                            <input type=\"text\" name=\"product_name\" onkeypress=\"invoice_productList('" . $product_details->product_id . "');\" class=\"form-control productSelection\" value=\"" . $product_details->product_name . "- (" . $product_details->product_model . ")\" placeholder=\"" . display('product_name') . "\" required readonly>
                            <input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_" . $product_details->product_id . "\" name=\"product_id[]\" id=\"SchoolHiddenId_" . $product_details->product_id . "\" value=\"" . $product_details->product_id . "\"/>
                            <input type=\"hidden\" id=\"SchoolHiddenCatId_" . $product_details->product_id . "\" value=\"" . $category_id . "\"/>
                        </td>
                        <td>
                            <input type=\"text\" name=\"product_quantity[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" class=\"total_qntt_" . $product_details->product_id . " form-control text-right item_product_invoice \" id=\"total_qntt_" . $product_details->product_id . "\" placeholder=\"0.00\" min=\"0\" value='" . $qty . "' required=\"required\"  np=\"" . $product_details->product_name . "\"  idp=\"" . $product_details->product_id . "\" />
                        </td>
                        <td style=\"width:85px\">
                            <input type=\"text\" name=\"product_rate[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" value='" . $precio_valor . "' id=\"price_item_" . $product_details->product_id . "\" class=\"price_item1 form-control text-right\" required placeholder=\"0.00\" min=\"0\"/>
                        </td>

                        <td class=\"\">
                            <input type=\"text\" name=\"discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"discount_" . $product_details->product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\" min=\"0\"/>

                          
                        </td>

                        <td class=\"text-right\" style=\"width:100px\">
                            <input class=\"total_price form-control text-right\" type=\"text\" name=\"total_price[]\" id=\"total_price_" . $product_details->product_id . "\" value='" . $precio_valor . "' tabindex=\"-1\" readonly=\"readonly\"/>
                        </td>

                        <td>";

            $sl = 0;
            foreach ($taxfield as $taxes) {
                $txs = 'tax' . $sl;
                $tr .= "<input type=\"hidden\" id=\"total_tax" . $sl . "_" . $product_details->product_id . "\" class=\"total_tax" . $sl . "_" . $product_details->product_id . "\" value='" . $prinfo[0][$txs] . "'/>
                            <input type=\"hidden\" id=\"all_tax" . $sl . "_" . $product_details->product_id . "\" class=\" total_tax" . $sl . "\" value='" . ($prinfo[0][$txs] * $precio_valor) . "' name=\"tax[]\"/>";
                $sl++;
            }

            $tr .= "<input type=\"hidden\" id=\"total_discount_" . $product_details->product_id . "\" />
                            <input type=\"hidden\" id=\"all_discount_" . $product_details->product_id . "\" class=\"total_discount dppr\"/>
                            <a style=\"text-align: right;\" class=\"btn btn-danger btn-xs\" href=\"#\"  onclick=\"deleteRow(this)\">" . '<i class="fa fa-close"></i>' . "</a>
                            <a style=\"text-align: right;\" class=\"btn btn-success btn-xs\" href=\"#\"  onclick=\"detailsmodal('" . $product_details->product_name . "','" . $product_details->total_product . "','" . $product_details->product_model . "','" . $product_details->unit . "','" . $precio_valor . "','" . $product_details->image . "')\">" . '<i class="fa fa-eye"></i>' . "</a>
                        </td>
                    </tr>";
                echo trim($tr);
        } else {
            return false;
        }
    }


    //Insert pos invoice
    public function insert_pos_invoice()
    {
        $product_id      = $this->input->post('product_id', TRUE);
        $tipo_precio = $this->input->post('tipo_precio', TRUE); // 1, 2, 3 o 4
        $product_details = $this->invoice_model->pos_invoice_setup($product_id);
        $taxfield = $this->db->select('tax_name,default_value')
            ->from('tax_settings')
            ->get()
            ->result_array();
        $prinfo = $this->db->select('*')->from('product_information')->where('product_id', $product_id)->get()->result_array();
        $precio_valor = $product_details->price; // por defecto
        if ($tipo_precio && in_array($tipo_precio, [2, 3, 4])) {
            $campo_precio = 'price_' . $tipo_precio;
            $precio_valor = isset($product_details->$campo_precio) ? $product_details->$campo_precio : null;

            if ($precio_valor === null || $precio_valor === 0) {
                $precio_valor = $product_details->price;
                echo "<script>alert('⚠️ El producto \"" . $product_details->product_name . "\" no tiene definido el Precio $tipo_precio. Se usará el Precio 1.');</script>";
            }
        }
        $tr = " ";
        if (!empty($product_details)) {
            $product_id = $this->generator(5);
            $serialdata = explode(',', $product_details->serial_no);
            if ($product_details->total_product > 0) {
                $qty = 1;
            } else {
                $qty = 1;
            }

            $html = "";
            if (empty($serialdata)) {
                $html .= "No Serial Found !";
            } else {
                // Select option created for product
                $html .= "<select name=\"serial_no[]\"   class=\"serial_no_1 form-control\" id=\"serial_no_" . $product_details->product_id . "\">";
                $html .= "<option value=''>" . display('select_one') . "</option>";
                foreach ($serialdata as $serial) {
                    $html .= "<option value=" . $serial . ">" . $serial . "</option>";
                }
                $html .= "</select>";
            }

            $tr .= "<tr id=\"row_" . $product_details->product_id . "\">
                        <td class=\"\" style=\"width:220px\">
                            
                            <input type=\"text\" name=\"product_name\" onkeypress=\"invoice_productList('" . $product_details->product_id . "');\" class=\"form-control productSelection \" value='" . $product_details->product_name . "- (" . $product_details->product_model . ")" . "' placeholder='" . display('product_name') . "' required=\"\" id=\"product_name_" . $product_details->product_id . "\" tabindex=\"\" readonly>

                            <input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_" . $product_details->product_id . "\" name=\"product_id[]\" id=\"SchoolHiddenId_" . $product_details->product_id . "\" value = \"$product_details->product_id\"/>
                            
                        </td>
                         <td>
                             <input type=\"text\" name=\"desc[]\" class=\"form-control text-right \"  />
                                        </td>
                                        <td style=\"display:none;\">" . $html . "</td>
                        <td>
                            <input type=\"text\" name=\"available_quantity[]\" class=\"form-control text-right available_quantity_" . $product_details->product_id . "\" value='" . $product_details->total_product . "' readonly=\"\" id=\"available_quantity_" . $product_details->product_id . "\"/>
                        </td>

                        <td>
                            <input class=\"form-control text-right unit_'" . $product_details->product_id . "' valid\" value=\"$product_details->unit\" readonly=\"\" aria-invalid=\"false\" type=\"text\">
                        </td>
                    
                        <td>
                            <input type=\"text\" name=\"product_quantity[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" class=\"total_qntt_" . $product_details->product_id . " form-control text-right\" id=\"total_qntt_" . $product_details->product_id . "\" placeholder=\"0.00\" min=\"0\" value='" . $qty . "'/>
                        </td>

                        <td style=\"width:85px\">
                            <input type=\"text\" name=\"product_rate[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" value='" . $precio_valor . "' id=\"price_item_" . $product_details->product_id . "\" class=\"price_item1 form-control text-right\" required placeholder=\"0.00\" min=\"0\"/>
                        </td>

                        <td class=\"\">
                            <input type=\"text\" name=\"discount[]\" onkeyup=\"quantity_calculate('" . $product_details->product_id . "');\" onchange=\"quantity_calculate('" . $product_details->product_id . "');\" id=\"discount_" . $product_details->product_id . "\" class=\"form-control text-right\" placeholder=\"0.00\" min=\"0\"/>

                           
                        </td>

                        <td class=\"text-right\" style=\"width:100px\">
                            <input class=\"total_price form-control text-right\" type=\"text\" name=\"total_price[]\" id=\"total_price_" . $product_details->product_id . "\" value='" . $precio_valor . "' tabindex=\"-1\" readonly=\"readonly\"/>
                        </td>

                        <td>";
            $sl = 0;
            foreach ($taxfield as $taxes) {
                $txs = 'tax' . $sl;
                $tr .= "<input type=\"hidden\" id=\"total_tax" . $sl . "_" . $product_details->product_id . "\" class=\"total_tax" . $sl . "_" . $product_details->product_id . "\" value='" . $prinfo[0][$txs] . "'/>
                            <input type=\"hidden\" id=\"all_tax" . $sl . "_" . $product_details->product_id . "\" class=\" total_tax" . $sl . "\" value='" . $prinfo[0][$txs] * $precio_valor . "' name=\"tax[]\"/>";
                $sl++;
            }

            $tr .= "<input type=\"hidden\" id=\"total_discount_" . $product_details->product_id . "\" />
                            <input type=\"hidden\" id=\"all_discount_" . $product_details->product_id . "\" class=\"total_discount dppr\"/>
                            <button  class=\"btn btn-danger btn-xs text-center\" type=\"button\"  onclick=\"deleteRow(this)\">" . '<i class="fa fa-close"></i>' . "</button>
                        </td>
                    </tr>";
            echo $tr;
        } else {
            return false;
        }
    }

    public function invoice_inserted_data_manual()
    {
        $data['title']      = display('invoice_print');
        $invoice_id         = $this->input->post('invoice_id', TRUE);
        $invoice_detail     = $this->invoice_model->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $invoice_detail[$k]['date'];
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }

            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }
        $totalbal      = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword = $totalbal;
        $user_id       = $invoice_detail[0]['sales_by'];
        $users         = $this->invoice_model->user_invoice_data($user_id);
        $data = array(
            'title'             => display('invoice_details'),
            'invoice_id'        => $invoice_detail[0]['invoice_id'],
            'invoice_no'        => $invoice_detail[0]['invoice'],
            'customer_name'     => $invoice_detail[0]['customer_name'],
            'customer_address'  => $invoice_detail[0]['customer_address'],
            'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
            'customer_email'    => $invoice_detail[0]['customer_email'],
            'final_date'        => $invoice_detail[0]['final_date'],
            'invoice_details'   => $invoice_detail[0]['invoice_details'],
            'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
            'subTotal_quantity' => $subTotal_quantity,
            'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
            'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
            'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
            'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
            'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
            'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
            'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
            'invoice_all_data'  => $invoice_detail,
            'am_inword'         => $amount_inword,
            'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
            'users_name'        => $users->first_name . ' ' . $users->last_name,
            'tax_regno'         => $txregname,
            'is_desc'           => $descript,
            'is_serial'         => $isserial,
            'is_unit'           => $isunit,
        );
        $data['module']     = "invoice";
        $data['page']       = "invoice_html_manual";
        echo modules::run('template/layout', $data);
    }
    /*invoice no generator*/
    public function number_generator()
    {
        $this->db->select_max('invoice', 'invoice_no');
        $query = $this->db->get('invoice');
        $result = $query->row(); // Mejor práctica que result_array()[0]

        // Si no hay registros, inicia en 1. Si hay, suma +1.
        return ($result->invoice_no) ? $result->invoice_no + 1 : 1;
    }

    public function number_generator_sucursal($sucursal_id)
    {
        //$sucursal_id = $this->input->post('sucursal_id');
        $sucursal_id = str_replace('%20', ' ', $sucursal_id);
        $sql = "SELECT COUNT(id) as invoice from invoice where branchoffice = '$sucursal_id'";
        $query = $this->db->query($sql);
        //$query = $this->db->get('invoice');
        $result = $query->row(); // Mejor práctica que result_array()[0]

        // Si no hay registros, inicia en 1. Si hay, suma +1.
        echo json_encode([
            'invoice_no' => ($result->invoice) ? $result->invoice + 1 : 1
        ]);
    }

    public function bdtask_customer_autocomplete()
    {
        $customer_id    = $this->input->post('customer_id', TRUE);
        $customer_info  = $this->invoice_model->customer_search($customer_id);

        $list[''] = '';
        foreach ($customer_info as $value) {
            $json_customer[] = array('label' => $value['customer_name'], 'value' => $value['customer_id']);
        }
        echo json_encode($json_customer);
    }

    /*product autocomple search*/
    public function bdtask_autocomplete_product()
    {
        $product_name   = $this->input->post('product_name', TRUE);
        $product_info   = $this->invoice_model->autocompletproductdata($product_name);
        if (!empty($product_info)) {
            $list[''] = '';
            foreach ($product_info as $value) {
                $json_product[] = array('label' => $value['product_name'] . '(' . $value['product_model'] . ')', 'value' => $value['product_id']);
            }
        } else {
            $json_product[] = 'No Product Found';
        }
        echo json_encode($json_product);
    }

    /*after selecting product retrieve product info*/
    public function retrieve_product_data_inv()
    {
        $product_id   = $this->input->post('product_id', TRUE);
        $product_info = $this->invoice_model->get_total_product_invoic($product_id);
        echo json_encode($product_info);
    }

    /*after select customer retrieve customer previous balance*/
    public function previous()
    {
        $customer_id = $this->input->post('customer_id', TRUE);
        $this->db->select("a.*,b.HeadCode,((select ifnull(sum(Debit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)-(select ifnull(sum(Credit),0) from acc_transaction where COAID= `b`.`HeadCode` AND IsAppove = 1)) as balance");
        $this->db->from('customer_information a');
        $this->db->join('acc_coa b', 'a.customer_id = b.customer_id', 'left');
        $this->db->where('a.customer_id', $customer_id);
        $result = $this->db->get()->result_array();
        $balance = $result[0]['balance'];
        $b = (!empty($balance) ? $balance : 0);
        if ($b) {
            echo  $b;
        } else {
            echo  $b;
        }
    }
    public function instant_customer()
    {

        $data = array(
            'customer_name'    => $this->input->post('customer_name', TRUE),
            'customer_address' => $this->input->post('address', TRUE),
            'customer_mobile'  => $this->input->post('mobile', TRUE),
            'customer_email'   => $this->input->post('email', TRUE),
            'status'           => 1
        );

        $result = $this->db->insert('customer_information', $data);
        if ($result) {

            $customer_id = $this->db->insert_id();

            //Customer  basic information adding.
            $coa = $this->customer_model->headcode();
            if ($coa->HeadCode != NULL) {
                $headcode = $coa->HeadCode + 1;
            } else {
                $headcode = "102030001";
            }
            $c_acc      = $customer_id . '-' . $this->input->post('customer_name', TRUE);
            $createby   = $this->session->userdata('id');
            $createdate = date('Y-m-d H:i:s');

            $customer_coa = [
                'HeadCode'         => $headcode,
                'HeadName'         => $c_acc,
                'PHeadName'        => 'Customer Receivable',
                'HeadLevel'        => '4',
                'IsActive'         => '1',
                'IsTransaction'    => '1',
                'IsGL'             => '0',
                'HeadType'         => 'A',
                'IsBudget'         => '0',
                'IsDepreciation'   => '0',
                'customer_id'      => $customer_id,
                'DepreciationRate' => '0',
                'CreateBy'         => $createby,
                'CreateDate'       => $createdate,
            ];
            //Previous balance adding -> Sending to customer model to adjust the data.
            $this->db->insert('acc_coa', $customer_coa);
            $data['status']        = true;
            $data['message']       = display('save_successfully');
            $data['customer_id']   = $customer_id;
            $data['customer_name'] = $data['customer_name'];
        } else {
            $data['status'] = false;
            $data['exception'] = display('please_try_again');
        }
        echo json_encode($data);
    }
    public function bdtask_invoice_details_directprint($invoice_id = null)
    {
        $invoice_detail     = $this->invoice_model->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $invoice_detail[$k]['date'];
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }
            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }
        $totalbal = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword     = $totalbal;
        $user_id           = $invoice_detail[0]['sales_by'];
        $users             = $this->invoice_model->user_invoice_data($user_id);
        $company_info      = $this->invoice_model->retrieve_company();
        $currency_details  = $this->invoice_model->retrieve_setting_editdata();
        $data = array(
            'title'             => display('invoice_details'),
            'invoice_id'        => $invoice_detail[0]['invoice_id'],
            'invoice_no'        => $invoice_detail[0]['invoice'],
            'customer_name'     => $invoice_detail[0]['customer_name'],
            'customer_address'  => $invoice_detail[0]['customer_address'],
            'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
            'customer_email'    => $invoice_detail[0]['customer_email'],
            'final_date'        => $invoice_detail[0]['final_date'],
            'invoice_details'   => $invoice_detail[0]['invoice_details'],
            'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
            'subTotal_quantity' => $subTotal_quantity,
            'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
            'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
            'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
            'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
            'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
            'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
            'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
            'invoice_all_data'  => $invoice_detail,
            'am_inword'         => $amount_inword,
            'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['invoice_discount'],
            'users_name'        => $users->first_name . ' ' . $users->last_name,
            'tax_regno'         => $txregname,
            'is_desc'           => $descript,
            'is_serial'         => $isserial,
            'is_unit'           => $isunit,
            'discount_type'     => $currency_details[0]['discount_type'],
            'company_info'      => $company_info,
            'logo'              => $currency_details[0]['invoice_logo'],
            'position'          => $currency_details[0]['currency_position'],
            'currency'          => $currency_details[0]['currency'],
        );
        return $data;
    }
    public function bdtask_quote_details_directprint($invoice_id = null)
    {
        $invoice_detail     = $this->quote_model->retrieve_invoice_html_data($invoice_id);
        $taxfield = $this->db->select('*')
            ->from('tax_settings')
            ->where('is_show', 1)
            ->get()
            ->result_array();
        $txregname = '';
        foreach ($taxfield as $txrgname) {
            $regname = $txrgname['tax_name'] . ' Reg No  - ' . $txrgname['reg_no'] . ', ';
            $txregname .= $regname;
        }
        $subTotal_quantity = 0;
        $subTotal_cartoon  = 0;
        $subTotal_discount = 0;
        $subTotal_ammount  = 0;
        $descript          = 0;
        $isserial          = 0;
        $isunit            = 0;
        if (!empty($invoice_detail)) {
            foreach ($invoice_detail as $k => $v) {
                $invoice_detail[$k]['final_date'] = $invoice_detail[$k]['date'];
                $subTotal_quantity = $subTotal_quantity + $invoice_detail[$k]['quantity'];
                $subTotal_ammount = $subTotal_ammount + $invoice_detail[$k]['total_price'];
            }
            $i = 0;
            foreach ($invoice_detail as $k => $v) {
                $i++;
                $invoice_detail[$k]['sl'] = $i;
                if (!empty($invoice_detail[$k]['description'])) {
                    $descript = $descript + 1;
                }
                if (!empty($invoice_detail[$k]['serial_no'])) {
                    $isserial = $isserial + 1;
                }
                if (!empty($invoice_detail[$k]['unit'])) {
                    $isunit = $isunit + 1;
                }
            }
        }
        $totalbal = $invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'];
        $amount_inword     = $totalbal;
        $user_id           = $invoice_detail[0]['sales_by'];
        $users             = $this->invoice_model->user_invoice_data($user_id);
        $company_info      = $this->invoice_model->retrieve_company();
        $currency_details  = $this->invoice_model->retrieve_setting_editdata();
        $data = array(
            'title'             => display('invoice_details'),
            'invoice_id'        => $invoice_detail[0]['quote_id'],
            'invoice_no'        => $invoice_detail[0]['quote'],
            'customer_name'     => $invoice_detail[0]['customer_name'],
            'customer_address'  => $invoice_detail[0]['customer_address'],
            'customer_mobile'   => $invoice_detail[0]['customer_mobile'],
            'customer_email'    => $invoice_detail[0]['customer_email'],
            'final_date'        => $invoice_detail[0]['final_date'],
            'invoice_details'   => $invoice_detail[0]['quote_details'],
            'total_amount'      => number_format($invoice_detail[0]['total_amount'] + $invoice_detail[0]['prevous_due'], 2, '.', ','),
            'subTotal_quantity' => $subTotal_quantity,
            'total_discount'    => number_format($invoice_detail[0]['total_discount'], 2, '.', ','),
            'total_tax'         => number_format($invoice_detail[0]['total_tax'], 2, '.', ','),
            'subTotal_ammount'  => number_format($subTotal_ammount, 2, '.', ','),
            'paid_amount'       => number_format($invoice_detail[0]['paid_amount'], 2, '.', ','),
            'due_amount'        => number_format($invoice_detail[0]['due_amount'], 2, '.', ','),
            'previous'          => number_format($invoice_detail[0]['prevous_due'], 2, '.', ','),
            'shipping_cost'     => number_format($invoice_detail[0]['shipping_cost'], 2, '.', ','),
            'invoice_all_data'  => $invoice_detail,
            'am_inword'         => $amount_inword,
            'is_discount'       => $invoice_detail[0]['total_discount'] - $invoice_detail[0]['quote_discount'],
            'users_name'        => $users->first_name . ' ' . $users->last_name,
            'tax_regno'         => $txregname,
            'is_desc'           => $descript,
            'is_serial'         => $isserial,
            'is_unit'           => $isunit,
            'discount_type'     => $currency_details[0]['discount_type'],
            'company_info'      => $company_info,
            'logo'              => $currency_details[0]['invoice_logo'],
            'position'          => $currency_details[0]['currency_position'],
            'currency'          => $currency_details[0]['currency'],
        );
        return $data;
    }
    public function generator($lenth)
    {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "N", "M", "O", "P", "Q", "R", "S", "U", "V", "T", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        for ($i = 0; $i < $lenth; $i++) {
            $rand_value = rand(0, 34);
            $rand_number = $number["$rand_value"];
            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
    public function number_generator_ajax()
    {
        $this->db->select_max('invoice', 'invoice_no');
        $query      = $this->db->get('invoice');
        $result     = $query->result_array();
        $invoice_no = $result[0]['invoice_no'];
        if ($invoice_no != '') {
            $invoice_no = $invoice_no + 1;
        } else {
            $invoice_no = 1000;
        }
        echo  $invoice_no;
    }
    public function bdtask_getdata_deliveryman()
    {
        $deliveryman = $this->input->post('deliveryman', true);
        $detail = (array)$this->invoice_model->single_deliveryman_data($deliveryman);
        echo json_encode($detail);
    }
    public function bdtask_getdata_florist()
    {
        $florist = $this->input->post('florist', true);
        $detail = (array)$this->invoice_model->single_florist_data($florist);
        echo json_encode($detail);
    }
    public function bdtask_entregar_invoice()
    {
        $invoice  = $this->input->post('invoice', true);
        $product  = $this->input->post('product', true);
        $this->invoice_model->entregar_invoice_product($invoice, $product);
    }
    public function bdtask_pagado_invoice()
    {
        $invoice  = $this->input->post('invoice', true);
        $tipo  = $this->input->post('tipo', true);
        $this->invoice_model->mover_pagado_invoice($invoice, $tipo);
    }
    public function bdtask_florista_invoice()
    {
        $invoice  = $this->input->post('invoice', true);
        $product  = $this->input->post('product', true);
        $this->invoice_model->florista_invoice_product($invoice, $product);
    }
    public function bdtask_product_form_arrp($id = null)
    {
        $this->form_validation->set_rules('product_name', display('product_name'), 'required|max_length[200]');
        $this->form_validation->set_rules('price', display('price'), 'required|max_length[12]');
        $product_id = (!empty($this->input->post('product_id', TRUE)) ? $this->input->post('product_id', TRUE) : $this->generator(8));
        $utilidad = $this->input->post('utilidad', TRUE);
        $insumo_cantidad = $this->input->post('insumo_cantidad', TRUE);
        $insumo_id = $this->input->post('insumo_id', TRUE);
        $insumo_price = $this->input->post('insumo_price', TRUE);
        $insumo_total = $this->input->post('insumo_total', TRUE);
        $data['product'] = (object)$postData = [
            'product_id'   => (!empty($id) ? $id : $product_id),
            'product_name' => $this->input->post('product_name', TRUE),
            'category_id'  => 0,
            'unit'         => 1,
            'tax'          => 0,
            'serial_no'    => '',
            'price'        => $this->input->post('price', TRUE),
            'product_model' => 'Personalizado',
            'product_details' => $this->input->post('description', TRUE),
            'image'        => '',
            'utilidad'        => $this->input->post('utilidad', TRUE),
            'status'       => 1,
            'temporal'     => 1,
            'iva'               => $this->input->post('iva', TRUE),
        ];
        if ($this->form_validation->run() === true) {
            if ($this->invoice_model->create_product($postData)) {
                $cont_ip = 0;
                foreach ($insumo_id as $iid) {
                    $price = $insumo_price[$cont_ip];
                    $cantidad = $insumo_cantidad[$cont_ip];
                    $total = $insumo_total[$cont_ip];
                    $supp_prd = array(
                        'product_id' => $product_id,
                        'insumo_id'  => $iid,
                        'price'      => $price,
                        'cantidad'      => $cantidad,
                        'total'      => $total
                    );
                    $this->db->insert('insumo_product', $supp_prd);
                    $cont_ip++;
                }
                $this->session->set_flashdata('message', display('save_successfully'));
            } else {
                $this->session->set_flashdata('exception', display('please_try_again'));
            }
            session_start();
            $_SESSION["personalizado"] = $product_id;
            redirect('gui_pos');
        }
    }
    public function delete_invoice()
    {
        $invoice  = $this->input->post('invoice', TRUE);
        $insumo_info = $this->invoice_model->delete_invoice($invoice);
    }
    public function CheckInvoiceList_efectivo()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getInvoiceList_efectivo($postData);
        echo json_encode($data);
    }
    public function CheckInvoiceList_tarjeta()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getInvoiceList_tarjeta($postData);
        echo json_encode($data);
    }
    public function CheckInvoiceList_transferencia()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getInvoiceList_transferencia($postData);
        echo json_encode($data);
    }
    public function CheckInvoiceList_anticipo()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getInvoiceList_anticipo($postData);
        echo json_encode($data);
    }
    public function CheckInvoiceList_porcobrar()
    {
        $postData = $this->input->post();
        $data     = $this->invoice_model->getInvoiceList_porcobrar($postData);
        echo json_encode($data);
    }
    public function getProductDetailsById()
    {
        $product_id = $this->input->post('product_id', TRUE);
        $product = $this->invoice_model->pos_invoice_setup($product_id);

        if ($product) {
            echo json_encode(["error" => false, "image" => $product->image, "name" => $product->product_name]);
        } else {
            echo json_encode(["error" => true, "image" => null]);
        }
    }

    public function get_customer_category_discount()
    {
        $this->load->database();

        $customer_id = $this->input->post('customer_id');
        $category_id = $this->input->post('category_id');

        if ($customer_id && $category_id) {
            $discount = $this->db->select('discount_percentage')
                ->from('customer_category_discount')
                ->where('customer_id', $customer_id)
                ->where('category_id', $category_id)
                ->get()
                ->row();

            if ($discount) {
                echo json_encode(['success' => true, 'discount' => (float) $discount->discount_percentage]);
            } else {
                echo json_encode(['success' => false, 'discount' => 0]);
            }
        } else {
            echo json_encode(['success' => false, 'discount' => 0]);
        }
    }
    public function get_product_category()
    {
        $this->load->database();

        $product_id = $this->input->post('product_id');

        if ($product_id) {
            $product = $this->db->select('category_id')
                ->from('product_information')
                ->where('product_id', $product_id)
                ->get()
                ->row();

            if ($product) {
                echo json_encode(['success' => true, 'category_id' => (int)$product->category_id]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
