<?php

class VOrder
{
    private $smarty;

    public function __construct()
    {
        $this->smarty = StartSmarty::configuration();
    }

    public function showOrders($orders, $page, $itemsPerPage)
    {
        $this->smarty->assign('orders', $orders);
        $this->smarty->assign('page', $page);
        $this->smarty->assign('itemsPerPage', $itemsPerPage);
        $this->smarty->display('orders_list.tpl');
    }

    public function showOrder($order)
    {
        $this->smarty->assign('order', $order);
        $this->smarty->display('order_detail.tpl');
    }

    public function showAddOrderForm()
    {
        $this->smarty->display('order_add.tpl');
    }

    public function showEditOrderForm($order)
    {
        $this->smarty->assign('order', $order);
        $this->smarty->display('order_edit.tpl');
    }
}
