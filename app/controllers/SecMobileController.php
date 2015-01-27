<?php

class SecMobileController extends BaseController {
    public function __construct()
    {
        View::composer('secm.main.*', function($view)
        {
            $view->with('title', 'iMMAP Security News');
        });
    }
    public function showIndex()
    {
            return View::make('secm.main.index');
    }
}
