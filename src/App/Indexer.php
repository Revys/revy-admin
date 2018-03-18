<?php

namespace Revys\RevyAdmin\App;

use Symfony\Component\Finder\SplFileInfo;

class Indexer
{
    protected static $cache_directory;
    protected static $map_file;

    protected static $map = [];

    public function __construct()
    {
        self::$cache_directory = storage_path('revy') . DIRECTORY_SEPARATOR . "admin";
        self::$map_file = self::$cache_directory . DIRECTORY_SEPARATOR . "autoload_map.php";

        if (! is_dir(self::$cache_directory))
            \File::makeDirectory(self::$cache_directory, 0755, true);

        if (! \File::exists(self::$map_file))
            $this->index();
    }

    /**
     * @param string $alias
     * @return string|null
     */
    public function getMappedClass($alias)
    {
        if (! self::$map)
            self::$map = include_once self::$map_file;

        if (isset(self::$map[$alias]))
            return '\\' . self::$map[$alias];
    }

    public function mapClass($alias, $class)
    {
        if (! self::$map)
            self::$map = include_once self::$map_file;

        self::$map[$alias] = $class;

        $content = '<?php return ' . var_export(self::$map, true) . ';';

        file_put_contents(self::$map_file, $content);
    }

    public function index()
    {
        $map = array();

        $directories = array(
            base_path('admin')
        );

        foreach ($directories as $directory) {
            if (! \File::exists($directory))
                continue;

            $files = \File::allFiles($directory);

            if (count($files)) {
                foreach ($files as $file) {
                    $alias = snake_case($file->getBasename('.php'));
                    $class = self::getClassName($file);
                    $map[$alias] = $class;
                }
            }
        }

        $content = '<?php return ' . var_export($map, true) . ';';

        file_put_contents(self::$map_file, $content);
    }

    public static function getClassName(SplFileInfo $file)
    {
        $src = file_get_contents($file);

        if (preg_match('/^namespace\s+(.+?);\s*$/sm', $src, $m))
            $namespace = $m[1];

        return (isset($namespace) ? $namespace . '\\' : '') . str_replace('.php', '', $file->getFileName());
    }
}