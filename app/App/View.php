<?php
    namespace {{NAMESPACE}}\app;

    use {{NAMESPACE}}\BladeInit;

    class View {
        public static function render(string $view, $model)
        {
            $bladeView = str_replace('/', '.', $view);

            try {
                $rendered = BladeInit::getInstance()->make($bladeView, $model)->render();
                echo $rendered;
                return;
            } catch (\Exception $e) {
                error_log("Blade rendering failed for view {$bladeView}: " . $e->getMessage());
            }

            $defaultPath = dirname(__DIR__, 2) . '/resources/Views/' . $view . '.php';
            $fallbackPath = dirname(__DIR__, 2) . '/services/' . $view . '.php';

            if (file_exists($defaultPath)) {
                require $defaultPath;
            } elseif (file_exists($fallbackPath)) {
                require $fallbackPath;
            } else {
                http_response_code(500);
                echo "View not found: {$view}";
                exit;
            }
        }
    }
?>