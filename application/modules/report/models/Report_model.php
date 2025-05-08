<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report_model extends CI_Model
{
    public function bdtask_getStock($postData = null)
    {
        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value
        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (a.product_name like '%" . $searchValue . "%' or a.product_model like '%" . $searchValue . "%') ";
        }
        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_information a');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->group_by('a.product_id');
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('product_information a');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->group_by('a.product_id');
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;

        ## Fetch records
        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model
                ");
        $this->db->from('product_information a');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->group_by('a.product_id');
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        $sl = 1;
        foreach ($records as $record) {
            $stockin = $this->db->select('sum(quantity) as totalSalesQnty')->from('invoice_details')->where('product_id', $record->product_id)->get()->row();
            $stockout = $this->db->select('sum(quantity) as totalPurchaseQnty,Avg(rate) as purchaseprice')->from('product_purchase_details')->where('product_id', $record->product_id)->get()->row();


            $sprice = (!empty($record->price) ? $record->price : 0);
            $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
            $stock =  (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) - (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0);
            $data[] = array(
                'sl'            =>   $sl,
                'product_name'  =>  $record->product_name,
                'product_model' =>  $record->product_model,
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $pprice,
                'totalPurchaseQnty' => $stockout->totalPurchaseQnty,
                'totalSalesQnty' =>  $stockin->totalSalesQnty,
                'stok_quantity' => sprintf('%0.2f', $stock),
                'total_sale_price' => ($stockout->totalPurchaseQnty - $stockin->totalSalesQnty) * $sprice,
                'purchase_total' => ($stockout->totalPurchaseQnty - $stockin->totalSalesQnty) * $pprice,
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
    public function bdtask_getStock_insumo($postData = null)
    {
        $response = array();
        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (a.product_name like '%" . $searchValue . "%' or a.product_model like '%" . $searchValue . "%') ";
        }
        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('insumo_information a');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->group_by('a.product_id');
        $records = $this->db->get()->num_rows();
        $totalRecords = $records;
        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->from('insumo_information a');
        if ($searchValue != '') {
            $this->db->where($searchQuery);
        }
        $this->db->group_by('a.product_id');
        $records = $this->db->get()->num_rows();
        $totalRecordwithFilter = $records;
        ## Fetch records
        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model
                ");
        $this->db->from('insumo_information a');
        if ($searchValue != '')
            $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->group_by('a.product_id');
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $data = array();
        //$sl =1;
        foreach ($records as $record) {

            $this->db->select('a.*, b.cantidad as cantidad');
            $this->db->from('product_information a');
            $this->db->join('insumo_product b', 'b.product_id = a.product_id', 'left');
            $this->db->join('insumo_information c', 'c.id = b.insumo_id', 'left');
            $this->db->where('c.product_id', $record->product_id);
            $query = $this->db->get();
            $utilizado = 0;
            $productos = $query->num_rows();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $product) {
                    $usar = (int)$product['cantidad'];
                    $this->db->select('b.cancelado as cancelado, a.*, b.invoice_id as inv_id');
                    $this->db->from('invoice_details a');
                    $this->db->join('invoice b', 'b.invoice_id = a.invoice_id', 'left');
                    $this->db->where('a.product_id', $product['product_id']);
                    $this->db->having('cancelado', '0');
                    $query   = $this->db->get();

                    if ($query->num_rows() > 0) {
                        foreach ($query->result_array() as $detail) {

                            $usado_venta = (int)$detail['quantity'];
                            $total_usado = $usar * $usado_venta;
                            $utilizado += $total_usado;
                        }
                    }
                }
            }
            $stockin = $this->db->select('sum(quantity) as totalSalesQnty')->from('invoice_details')->where('product_id', $record->product_id)->get()->row();
            $stockout = $this->db->select('sum(quantity) as totalPurchaseQnty,Avg(rate) as purchaseprice')->from('insumo_purchase_details')->where('product_id', $record->product_id)->get()->row();
            $sprice = (!empty($record->price) ? $record->price : 0);
            $pprice = (!empty($stockout->purchaseprice) ? sprintf('%0.2f', $stockout->purchaseprice) : 0);
            $stock =  (!empty($stockout->totalPurchaseQnty) ? $stockout->totalPurchaseQnty : 0) - (!empty($stockin->totalSalesQnty) ? $stockin->totalSalesQnty : 0);
            $existencias = $stock - $utilizado;
            $this->db->select('a.purchase_date');
            $this->db->from('insumo_purchase a');
            $this->db->join('insumo_purchase_details b', 'b.purchase_id = a.purchase_id', 'left');
            $this->db->where('b.product_id', $record->product_id);
            $this->db->order_by('a.purchase_date', 'desc');
            $query2 = $this->db->get();

            $fecha = 'No existen compras';
            if ($query2->num_rows() > 0) {
                $result2 = $query2->result();
                $fecha = date('d/m/Y', strtotime($result2[0]->purchase_date));
            }
            $data[] = array(
                //'sl'            =>   $sl,
                'product_name'  =>  $record->product_name,
                'product_model' =>  $record->product_model,
                'sales_price'   =>  sprintf('%0.2f', $sprice),
                'purchase_p'    =>  $pprice,
                'totalPurchaseQnty' => $stockout->totalPurchaseQnty,
                'totalSalesQnty' =>  $stockin->totalSalesQnty,
                'stok_quantity' => sprintf('%0.2f', $existencias),
                'total_sale_price' => ($stockout->totalPurchaseQnty - $stockin->totalSalesQnty) * $sprice,
                'purchase_total' => ($stockout->totalPurchaseQnty - $stockin->totalSalesQnty) * $pprice,
                'date'    =>  $fecha,
                'used'    =>  sprintf('%0.2f', $utilizado),
                'options'    =>  '<button type="button" class="btn btn-primary get_purchases_insumo" insumo="' . $record->product_id . '" data-toggle="modal" data-target="#view_insumo"><i class="fa fa-cog" aria-hidden="true"></i> Opciones</button>',
            );
            //$sl++;
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
    public function totalnumberof_product()
    {

        $this->db->select("a.*,
                a.product_name,
                a.product_id,
                a.product_model,
                c.supplier_price
                ");
        //$this->db->from('product_information a');    
        //$this->db->join('supplier_product c','c.product_id = a.product_id','left');		
        $this->db->from('insumo_information a');
        $this->db->join('supplier_insumo c', 'c.product_id = a.product_id', 'left');
        $this->db->group_by('a.product_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        }
        return false;
    }
    public function accounts_closing_data()
    {
        $last_closing_amount = $this->get_last_closing_amount();
        $cash_in = $this->cash_data_receipt();
        $cash_out = $this->cash_data();
        if ($last_closing_amount != null) {
            $last_closing_amount = $last_closing_amount[0]['amount'];
            $cash_in_hand = ($last_closing_amount + $cash_in) - $cash_out;
        } else {
            $last_closing_amount = 0;
            $cash_in_hand = $cash_in - $cash_out;
        }
        return array(
            "last_day_closing" => number_format($last_closing_amount, 2, '.', ','),
            "cash_in"          => number_format($cash_in, 2, '.', ','),
            "cash_out"         => number_format($cash_out, 2, '.', ','),
            "cash_in_hand"     => number_format($cash_in_hand, 2, '.', ',')
        );
    }
    public function get_last_closing_amount()
    {
        $sql = "SELECT amount FROM daily_closing WHERE date = (SELECT MAX(date) FROM daily_closing)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }
    public function cash_data_receipt()
    {
        //-----------
        $cash = 0;
        $datse = date('Y-m-d');
        $this->db->select('sum(Debit) as amount');
        $this->db->from('acc_transaction');
        $this->db->where('COAID', 1020101);
        $this->db->where('VDate', $datse);
        $result_amount = $this->db->get();
        $amount = $result_amount->result_array();
        $cash += $amount[0]['amount'];
        return $cash;
    }
    public function cash_data()
    {
        //-----------
        $cash = 0;
        $datse = date('Y-m-d');
        $this->db->select('sum(Credit) as amount');
        $this->db->from('acc_transaction');
        $this->db->where('COAID', 1020101);
        $this->db->where('VDate', $datse);
        $result_amount = $this->db->get();
        $amount = $result_amount->result_array();
        $cash += $amount[0]['amount'];
        return $cash;
    }

    //CLOSING ENTRY
    public function daily_closing_entry($data)
    {
        return $this->db->insert('daily_closing', $data);
    }
    public function get_closing_report()
    {
        $this->db->select("* ,cash_in - cash_out as 'cash_in_hand'");
        $this->db->from('daily_closing');
        $this->db->where('status', 1);
        $this->db->order_by('date', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_date_wise_closing_report($from_date, $to_date)
    {
        $dateRange = "date BETWEEN '$from_date' AND '$to_date'";
        $this->db->select("* ,cash_in - cash_out as 'cash_in_hand'");
        $this->db->from('daily_closing');
        $this->db->where('status', 1);
        $this->db->where($dateRange, NULL, FALSE);
        $this->db->order_by('date', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    //Retrieve todays_sales_report
    public function todays_sales_report()
    {
        $today = date('Y-m-d');
        $this->db->select("a.*,b.customer_id,b.customer_name");
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.date', $today);
        $this->db->order_by('a.invoice_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //Retrieve all Report
    public function retrieve_dateWise_SalesReports($from_date, $to_date)
    {
        $this->db->select("a.*, b.*");
        $this->db->from('invoice a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->order_by('a.branchoffice', 'asc'); // Ordenar por sucursal
        $this->db->order_by('a.date', 'desc');        // Luego por fecha descendente
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function get_all_branchoffices()
    {
        $this->db->select('DISTINCT(branchoffice)');
        $this->db->from('invoice');
        $this->db->order_by('branchoffice', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return array_column($query->result_array(), 'branchoffice');
        }
        return [];
    }
    //Retrieve todays_purchase_report
    public function todays_purchase_report()
    {
        $today = date('Y-m-d');
        $this->db->select("a.*,b.supplier_id,b.supplier_name");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('a.purchase_date', $today);
        $this->db->order_by('a.purchase_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //    ======= its for  todays_customer_receipt ===========
    public function todays_customer_receipt($today = null)
    {
        $this->db->select('a.*,b.HeadName,c.customer_name');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->join('customer_information c', 'b.customer_id=c.customer_id');
        $this->db->where('a.Credit >', 0);
        $this->db->where('DATE(a.VDate)', $today);
        $this->db->where('a.IsAppove', 1);
        $query = $this->db->get();
        return $query->result();
    }
    public function filter_customer_wise_receipt($custome_id = null, $from_date = null)
    {
        $this->db->select('a.*,b.HeadName');
        $this->db->from('acc_transaction a');
        $this->db->join('acc_coa b', 'a.COAID=b.HeadCode');
        $this->db->where('b.customer_id', $custome_id);
        $this->db->where('a.Credit >', 0);
        $this->db->where('DATE(a.VDate)', $from_date);
        $this->db->where('a.IsAppove', 1);
        $query = $this->db->get();
        return $query->result();
    }
    public function customerinfo_rpt($customer_id)
    {
        return $this->db->select('*')
            ->from('customer_information')
            ->where('customer_id', $customer_id)
            ->get()
            ->result_array();
    }
    // ======================= user sales report ================
    public function user_sales_report($from_date, $to_date, $user_id)
    {
        $this->db->select("sum(total_amount) as amount,count(a.invoice_id) as toal_invoice,a.*,b.first_name,b.last_name");
        $this->db->from('invoice a');
        $this->db->join('users b', 'b.user_id = a.sales_by', 'left');
        if (!empty($user_id)) {
            $this->db->where('a.sales_by', $user_id);
        }
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->group_by('a.sales_by');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function userList()
    {
        $this->db->select("*");
        $this->db->from('users');
        $this->db->order_by('first_name', 'asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function retrieve_dateWise_DueReports($from_date, $to_date)
    {
        $this->db->select("a.*,b.*,c.*");
        $this->db->from('invoice a');
        $this->db->join('invoice_details c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->group_by('a.invoice_id');
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // ================= Shipping cost ===========================
    public function retrieve_dateWise_Shippingcost($from_date, $to_date)
    {
        $this->db->select("a.*");
        $this->db->from('invoice a');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->group_by('a.invoice_id');
        $this->db->order_by('a.date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //Retrieve todays_purchase_report
    public function bdtask_purchase_report($from_date, $to_date)
    {
        $today = date('Y-m-d');
        $this->db->select("a.*,b.supplier_id,b.supplier_name");
        $this->db->from('product_purchase a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('a.purchase_date >=', $from_date);
        $this->db->where('a.purchase_date <=', $to_date);
        $this->db->order_by('a.purchase_date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function category_list_product()
    {
        $this->db->select('*');
        $this->db->from('product_category');
        $this->db->where('status', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //    ============= its for purchase_report_category_wise ===============
    public function purchase_report_category_wise($from_date, $to_date, $category)
    {
        $this->db->select('b.product_name, b.product_model, SUM(a.quantity) as quantity, SUM(a.total_amount) as total_amount, d.purchase_date, c.category_name');
        $this->db->group_by('b.product_id, c.category_id');
        $this->db->from('product_purchase_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        $this->db->join('product_purchase d', 'd.purchase_id = a.purchase_id');
        $this->db->where('d.purchase_date >=', $from_date);
        $this->db->where('d.purchase_date <=', $to_date);
        if ($category) {
            $this->db->where('c.category_id', $category);
        }
        $query = $this->db->get();
        return $query->result();
    }
    //RETRIEVE DATE WISE SINGE PRODUCT REPORT
    public function retrieve_product_sales_report($from_date, $to_date, $product_id)
    {
        $this->db->select("a.*,b.product_name,b.product_model,c.date,c.invoice,c.total_amount,d.customer_name");
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('invoice c', 'c.invoice_id = a.invoice_id');
        $this->db->join('customer_information d', 'd.customer_id = c.customer_id');
        $this->db->where('c.date >=', $from_date);
        $this->db->where('c.date <=', $to_date);
        if ($product_id) {
            $this->db->where('a.product_id', $product_id);
        }
        $this->db->order_by('c.date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function product_list()
    {
        $this->db->select('*');
        $this->db->from('product_information');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //    ============= its for sales_report_category_wise ===============
    public function sales_report_category_wise($from_date, $to_date, $category)
    {
        $this->db->select('b.product_name, b.product_model, sum(a.quantity) as quantity, sum(a.total_price) as total_price, d.date, c.category_name');
        $this->db->from('invoice_details a');
        $this->db->join('product_information b', 'b.product_id = a.product_id');
        $this->db->join('product_category c', 'c.category_id = b.category_id');
        $this->db->join('invoice d', 'd.invoice_id = a.invoice_id');
        $this->db->where('d.date >=', $from_date);
        $this->db->where('d.date <=', $to_date);
        if ($category) {
            $this->db->where('b.category_id', $category);
        }
        $this->db->group_by('b.product_id, c.category_id');
        $query = $this->db->get();
        return $query->result();
    }
    // sales return data
    public function sales_return_list($start, $end)
    {
        $this->db->select('a.net_total_amount,a.*,b.customer_name');
        $this->db->from('product_return a');
        $this->db->join('customer_information b', 'b.customer_id = a.customer_id');
        $this->db->where('usablity', 1);
        $this->db->where('a.date_return >=', $start);
        $this->db->where('a.date_return <=', $end);
        $this->db->order_by('a.date_return', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // return supplier
    public function supplier_return($start, $end)
    {
        $this->db->select('a.net_total_amount,a.*,b.supplier_name');
        $this->db->from('product_return a');
        $this->db->join('supplier_information b', 'b.supplier_id = a.supplier_id');
        $this->db->where('usablity', 2);
        $this->db->where('a.date_return >=', $start);
        $this->db->where('a.date_return <=', $end);
        $this->db->order_by('a.date_return', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    // tax report query
    public function retrieve_dateWise_tax($from_date, $to_date)
    {
        $this->db->select("a.*");
        $this->db->from('invoice a');
        $this->db->where('a.date >=', $from_date);
        $this->db->where('a.date <=', $to_date);
        $this->db->group_by('a.invoice_id');
        $this->db->order_by('a.date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    //Total profit report
    public function total_profit_report_by_branch($start_date, $end_date, $branch_name)
    {
        $this->db->select("a.date, a.invoice, a.branchoffice, b.invoice_id,
        CAST(SUM(b.total_price) AS DECIMAL(16,2)) as total_sale,
        CAST(SUM(b.quantity * b.supplier_rate) AS DECIMAL(16,2)) as total_supplier_rate,
        CAST(SUM(b.total_price) - SUM(b.quantity * b.supplier_rate) AS DECIMAL(16,2)) AS total_profit", FALSE);
        $this->db->from('invoice a');
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');
        $this->db->where('a.date >=', $start_date);
        $this->db->where('a.date <=', $end_date);

        // Aquí está el filtro por sucursal
        if (!empty($branch_name)) {
            $this->db->where('a.branchoffice', $branch_name);
        }

        $this->db->group_by(['b.invoice_id', 'a.branchoffice']);
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function total_profit_report($start_date, $end_date)
    {
        $this->db->select("a.date, a.invoice, a.branchoffice, b.invoice_id,
            CAST(SUM(b.total_price) AS DECIMAL(16,2)) as total_sale,
            CAST(SUM(b.quantity * b.supplier_rate) AS DECIMAL(16,2)) as total_supplier_rate,
            CAST(SUM(b.total_price) - SUM(b.quantity * b.supplier_rate) AS DECIMAL(16,2)) AS total_profit", FALSE);
        $this->db->from('invoice a');
        $this->db->join('invoice_details b', 'b.invoice_id = a.invoice_id');
        $this->db->where('a.date >=', $start_date);
        $this->db->where('a.date <=', $end_date);
        $this->db->group_by(['b.invoice_id', 'a.branchoffice']);
        $this->db->order_by('a.invoice', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
    }
    public function get_purchases_insumo($insumo)
    {
        $this->db->select('a.*');
        $this->db->from('insumo_purchase a');
        $this->db->join('insumo_purchase_details b', 'b.purchase_id = a.purchase_id', 'left');
        $this->db->where('b.product_id', $insumo);
        $this->db->order_by('a.purchase_date', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $table = '<table class="table table-condensed" id="purchases">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Factura</th>
								<th>Monto</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>';
            foreach ($query->result_array() as $purchase) {
                $table .= '<tr>
								<td>' . date('d/m/Y', strtotime($purchase['purchase_date'])) . '</td>
								<td>' . $purchase['chalan_no'] . '</td>
								<td>' . $purchase['grand_total_amount'] . '</td>
								<td><a href="' . base_url() . 'insumo_purchase_edit/' . $purchase['purchase_id'] . '" class="btn btn-primary">Gestionar</a></td>
						  </tr>';
            }
            $table .= '</tbody></table>			
						<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
						<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
						<script>
							$(document).ready( function () {
								$("#purchases").DataTable();
							});
						</script>
						';
            return $table;
        }
        return 'No existen compras asociadas';
    }
}
