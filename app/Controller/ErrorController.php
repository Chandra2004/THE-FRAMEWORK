<?php
    namespace {{NAMESPACE}}\Controller;

    use {{NAMESPACE}}\App\Config;
    use {{NAMESPACE}}\App\View;

    class ErrorController {
        private $homeModel;

        public function __construct() {
            $this->homeModel = new HomeModel();
        }

        function error404() {
            $getAllMobils = $this->homeModel->getAllMobils();
            
            $model = [
                'title' => "404 | Darma Sakti Travel Bandung",
                'allMobils' => !empty($getAllMobils['mobils']) ? $getAllMobils['mobils'] : []
            ];

            View::render('interface.partials.top-bottom.header', $model);
            View::render('error.error404');
            View::render('interface.partials.section.car-list', $model);
            View::render('interface.partials.top-bottom.footer');
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
