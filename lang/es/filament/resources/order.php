<?php

return [
    // General
    'label' => 'Orden',
    'plural_label' => 'Ordenes',
    'navigation_label' => 'Ordenes',

    // Table
    'table.columns.number' => 'Numero',
    'table.columns.creator' => 'Creador',
    'table.columns.server' => 'Servidor',
    'table.columns.product' => 'Producto',
    'table.columns.quantity' => 'Cantidad',
    'table.columns.price' => 'Precio',
    'table.columns.total' => 'Total',
    'table.columns.pending_balance' => 'Saldo pendiente',
    'table.columns.completed_at' => 'Fecha de finalización',
    'table.columns.status' => 'Estado',
    'table.columns.created_at' => 'Fecha de creación',
    'table.columns.updated_at' => 'Fecha de actualización',
    'table.columns.deleted_at' => 'Fecha de eliminación',
    'table.actions.process_order.label' => 'Procesar',
    'table.actions.complete_order.label' => 'Completar',
    'table.actions.complete_order.success' => 'La orden fue completada correctamente.',

    'table.filters.status.label' => 'Estado',
    'page.actions.process_order.label' => 'Procesar orden',
    'page.actions.process_order.success' => 'La orden fue pasada a en proceso.',
    'page.actions.complete_order.label' => 'Completar orden',
    'page.actions.complete_order.success' => 'La orden fue completada correctamente.',
    'tabs.all' => 'Todos',

    'form.fields.order_products.label' => 'Productos',

    'form.fields.product.label' => 'Producto',
    'form.fields.product.placeholder' => 'Selecciona un producto',
    'form.fields.product.helper_text' => '',
    'form.fields.product.hint' => '',

    'form.fields.quantity.label' => 'Cantidad',
    'form.fields.quantity.placeholder' => 'Ingresa la cantidad',
    'form.fields.quantity.helper_text' => '',
    'form.fields.quantity.hint' => '',

    'form.fields.unit_price.label' => 'Precio',
    'form.fields.unit_price.placeholder' => 'Ingresa el precio',
    'form.fields.unit_price.helper_text' => '',
    'form.fields.unit_price.hint' => '',

    'form.fields.total_price.label' => 'Total',
    'form.fields.total_price.placeholder' => 'Ingresa el total',
    'form.fields.total_price.helper_text' => '',
    'form.fields.total_price.hint' => '',

    'form.fields.grand_total.label' => 'Total de la orden',

    'form.fields.notes.label' => 'Notas',
    'form.fields.notes.placeholder' => 'Ingresa las notas de la orden',
    'form.fields.notes.helper_text' => 'Detalles adicionales sobre la orden.',
    'form.fields.notes.hint' => '',

    'form.sections.grand_total.label' => 'Resumen',

    'form.fields.status.label' => 'Estado',
    'form.fields.status.placeholder' => 'Selecciona el estado',
    'form.fields.status.helper_text' => 'Estado actual de la orden.',
    'form.fields.status.hint' => '',
    'form.fields.status.validation.completed_requires_full_payment' => 'Para marcar la orden como completada, los pagos deben cubrir el total de la orden.',

];
