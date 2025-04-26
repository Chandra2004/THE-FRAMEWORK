<?php
    namespace {{NAMESPACE}}\Controller;

    use {{NAMESPACE}}\App\Config;
    use {{NAMESPACE}}\App\Database;
    use {{NAMESPACE}}\App\View;

    use {{NAMESPACE}}\Models\HomeModel;

    use Exception; // Ensure to include Exception

    class HomeController {
        function index() {
            Config::loadEnv(); // Muat file .env
            
            try {
                $db = Database::getInstance();
                $status = "success";
            } catch (Exception $e) {
                $status = $e->getMessage();
            }

            $model = [
                'status' => $status,
                'base_url' => Config::get('BASE_URL')
            ];

            View::render('interface/home', $model);
        
        
        }

        function user() {
            Config::loadEnv(); // Muat file .env
            
            $homeModel = new HomeModel();
            $data = $homeModel->getUserData();

            try {
                $db = Database::getInstance();
                $status = "success";
            } catch (Exception $e) {
                $status = $e->getMessage();
            }

            $model = [
                'userData' => $data['users'],
                'status' => $status,
                'base_url' => Config::get('BASE_URL')
            ];

            View::render('interface/user', $model);
        
        }

        function detail(string $id) {
            Config::loadEnv(); // Muat file .env
            
            $homeModel = new HomeModel();
            $data = $homeModel->getUserData();

            $userDetail = $homeModel->getUserDetail($id);

            $model = [
                'userData' => $data['users'],
                'user' => $userDetail,
                'base_url' => Config::get('BASE_URL')
            ];

            View::render('interface/detail', $model);
        
        }
    }
?>