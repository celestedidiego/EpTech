<?php

class VShipping
{
    private $smarty;

    public function __construct()
    {
        $this->smarty = StartSmarty::configuration();
    }

    public function showAddresses($shippingAddresses)
    {
        $this->smarty->assign('shippingAddresses', $shippingAddresses);
        $this->smarty->display('shipping_addresses.tpl');
    }

    public function showAddForm()
    {
        $this->smarty->display('shipping_add.tpl');
    }

    public function showEditForm($shipping)
    {
        $this->smarty->assign('shipping', $shipping);
        $this->smarty->display('shipping_edit.tpl');
    }
}
