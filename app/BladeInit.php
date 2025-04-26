<?php
    namespace {{NAMESPACE}};

    use Illuminate\View\Factory;
    use Illuminate\Events\Dispatcher;
    use Illuminate\View\Engines\EngineResolver;
    use Illuminate\View\Engines\PhpEngine;
    use Illuminate\View\Engines\CompilerEngine;
    use Illuminate\View\FileViewFinder;
    use Illuminate\Filesystem\Filesystem;
    use Illuminate\View\Compilers\BladeCompiler;

    class BladeInit {
        private static $blade;

        public static function init() {
            if (!self::$blade) {
                $filesystem = new Filesystem();
                $resolver = new EngineResolver();

                $resolver->register('blade', function () use ($filesystem) {
                    $compiler = new BladeCompiler($filesystem, __DIR__ . '/Storage/cache/views');
                    // Daftarkan directive @csrf
                    $compiler->directive('csrf', function () {
                        return "<?php echo '<input type=\"hidden\" name=\"_token\" value=\"' . \$_SESSION['csrf_token'] . '\">'; ?>";
                    });                    
                    return new CompilerEngine($compiler, $filesystem);
                });

                $resolver->register('php', function () {
                    return new PhpEngine();
                });

                $viewPath = [__DIR__ . '/View'];
                $finder = new FileViewFinder($filesystem, $viewPath);

                self::$blade = new Factory(
                    $resolver,
                    $finder,
                    new Dispatcher()
                );
            }
            return self::$blade;
        }

        public static function getInstance() {
            return self::init();
        }
    }
?>