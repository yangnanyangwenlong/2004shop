<?php

namespace App\Admin\Controllers;

use App\Goods;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Goods';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Goods());

        $grid->column('goods_id', __('Goods id'));
        $grid->column('cat_id', __('Cat id'));
        $grid->column('goods_sn', __('Goods sn'));
        $grid->column('goods_name', __('Goods name'));
        $grid->column('click_count', __('Click count'));
        $grid->column('goods_number', __('Goods number'));
        $grid->column('shop_price', __('Shop price'));
        $grid->column('keywords', __('Keywords'));
        $grid->column('goods_desc', __('Goods desc'));
        $grid->column('goods_img', __('商品图片'))->image();
        $grid->column('add_time', __('Add time'))->display(function($time){
            return date('Y-m-d H:i:s');
        });
        $grid->column('is_delete', __('Is delete'));
        $grid->column('sale_num', __('Sale num'));

        return $grid;
    }


    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Goods::findOrFail($id));

        $show->field('goods_id', __('Goods id'));
        $show->field('cat_id', __('Cat id'));
        $show->field('goods_sn', __('Goods sn'));
        $show->field('goods_name', __('Goods name'));
        $show->field('click_count', __('Click count'));
        $show->field('goods_number', __('Goods number'));
        $show->field('shop_price', __('Shop price'));
        $show->field('keywords', __('Keywords'));
        $show->field('goods_desc', __('Goods desc'));
        $show->field('goods_img', __('Goods img'));
        $show->field('add_time', __('Add time'));
        $show->field('is_delete', __('Is delete'));
        $show->field('sale_num', __('Sale num'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Goods());

        $form->number('cat_id', __('分类'));
        $form->text('goods_sn', __('商品编号'));
        $form->text('goods_name', __('Goods name'));
        $form->number('click_count', __('Click count'));
        $form->number('goods_number', __('Goods number'));
        $form->decimal('shop_price', __('Shop price'))->default(0.00);
        $form->text('keywords', __('Keywords'));
        $form->textarea('goods_desc', __('Goods desc'));
        $form->image('goods_img', __('商品图片'));
        $form->text('add_time', __('Add time'));
        $form->switch('is_delete', __('Is delete'));
        $form->number('sale_num', __('Sale num'));

        return $form;
    }
}
