<?php
    namespace {{NAMESPACE}}\Controller;

    use {{NAMESPACE}}\App\Config;
    use {{NAMESPACE}}\App\View;

    use {{NAMESPACE}}\Models\HomeModel;

    class ErrorController {
        private $homeModel;

        public function __construct() {
            $this->homeModel = new HomeModel();
        }

        function error404() {
            $model = [];

            View::render('error.error404', $model);
        }
        
        function error500() {
            $model = [];

            View::render('error.error500', $model);
        }

        function payment() {
            $model = [];

            View::render('error.payment', $model);
        }

        function maintenance() {
            $model = [];

            View::render('error.maintenance', $model);
        }
    }
?>
