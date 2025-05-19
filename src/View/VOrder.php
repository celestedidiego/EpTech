<?php

/**
 * Class VOrder
 * Gestisce la visualizzazione degli ordini tramite Smarty.
 */
class VOrder
{
    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * VOrder constructor.
     * Inizializza la configurazione di Smarty.
     */
    public function __construct()
    {
        $this->smarty = StartSmarty::configuration();
    }

    /**
     * Mostra la lista degli ordini con paginazione.
     * @param array $orders Lista degli ordini.
     * @param int $page Pagina corrente.
     * @param int $itemsPerPage Numero di elementi per pagina.
     * @return void
     */
    public function showOrders($orders, $page, $itemsPerPage)
    {
        $this->smarty->assign('orders', $orders);
        $this->smarty->assign('page', $page);
        $this->smarty->assign('itemsPerPage', $itemsPerPage);
        $this->smarty->display('orders_list.tpl');
    }

    /**
     * Mostra il dettaglio di un singolo ordine.
     * @param array $order Dati dell'ordine.
     * @return void
     */
    public function showOrder($order)
    {
        $this->smarty->assign('order', $order);
        $this->smarty->display('order_detail.tpl');
    }

    /**
     * Mostra il form per aggiungere un nuovo ordine.
     * @return void
     */
    public function showAddOrderForm()
    {
        $this->smarty->display('order_add.tpl');
    }

    /**
     * Mostra il form per modificare un ordine esistente.
     * @param array $order Dati dell'ordine da modificare.
     * @return void
     */
    public function showEditOrderForm($order)
    {
        $this->smarty->assign('order', $order);
        $this->smarty->display('order_edit.tpl');
    }
}