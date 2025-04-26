<?php
    namespace {{NAMESPACE}}\app;

    use {{NAMESPACE}}\BladeInit;
    
    class View {
        public static function render($view, $data = []) {
            echo BladeInit::getInstance()->make($view, $data)->render();
        }
    }
?>