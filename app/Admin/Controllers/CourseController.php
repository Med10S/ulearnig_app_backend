<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\CourseType;
use App\Models\Course;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;

class CourseController extends AdminController
{

    protected function grid()
    {
        $grid = new Grid(new Course());
        //the first argument is the database field
        $grid->column('id', __('Id'));
        //display help you ti do oppenration on the value 
        $grid->column('user_token', __('Teacher'))->display(function ($token) {
            //for further processing data, 
            //you can creat any methode inside it or do opperation
            return User::where('token', '=', $token)->value('name');

        });
        $grid->column('name', __('Name'));
        $grid->column('thumbnail', __('Thumbnail'))->image('', 50, 50);


        $grid->column('description', __('Description'));
        $grid->column('type_id', __('Type id'));
        $grid->column('price', __('Price'));
        $grid->column('lesson_num', __('Lesson num'));
        $grid->column('video_length', __('Video length'));

        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('description', __('Description'));
        $show->field('price', __('Price'));
        $show->field('lesson_num', __('Lesson num'));
        $show->field('video_length', __('Video length'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Course());
        $form->text('name', __('Name'));
        //get our category
        //key value pair
        //last is the key
        //value => key
        $result = CourseType::pluck('title', 'id');
        //select method helps you select one of the 
        //options comes from result options
        $form->select('type_id', __('Category'))->options($result);
        $form->image('thumbnail', __('Thumbnail'))->uniqueName();
        //file is use for video or pdf or docs
        $form->file('video', __('Video'))->uniqueName();
        $form->text('description', __('Description'));
        //decimal helps with retriving float format
        $form->decimal('price', __('Price'));
        $form->number('lesson_num', __('Lesson Number'));
        $form->number('video_length', __('Video length'));
        //to have acces to the list of users
        $result = User::pluck('name', 'token');
        $form->select('user_token', __('Teacher'))->options($result);
        $form->display('created_at', __('Created at'));
        $form->display('updated_at', __('Updated at'));

        return $form;
    }
}