<?php

namespace Revys\RevyAdmin\App;

use Illuminate\Support\Facades\Log;
use Revys\Revy\App\Language;
use Revys\Revy\App\Revy;
use Symfony\Component\Finder\Finder;

class TranslationsBase
{
    protected $groups = [];
    protected $translations = [];

    public function __construct()
    {
        // Backend
        if (config('revy.package_development')) {
            $this->addGroup(
                'revy-admin',
                \RevyAdmin::getPackagePath('translations'),
                [
                    \RevyAdmin::getPackagePath('App'),
                    \RevyAdmin::getPackagePath('resources')
                ],
                __('Админ-панель')
            );

            $this->addGroups(
                \RevyAdmin::getPackagePath('translations'),
                [
                    \RevyAdmin::getPackagePath('App'),
                    \RevyAdmin::getPackagePath('resources')
                ],
                [
                    'buttons' => __('Админ-панель: Кнопки'),
                    'alerts' => __('Админ-панель: Сообщения')
                ],
                \RevyAdmin::getPackageAlias()
            );

            $this->addGroup(
                'revy',
                \Revy::getPackagePath('translations'),
                [
                    \Revy::getPackagePath('App')
                ],
                __('Общее')
            );
        }

        // Frontend
        $this->addGroup(
            'frontend',
            resource_path('lang'),
            [
                app_path(),
                resource_path()
            ],
            __('Лицевая часть сайта')
        );
    }

    public function addGroup($name, $path, $sources, $title = null, $type = 'json', $packageAlias = null)
    {
        if (isset($this->groups[$name])) {
            return;
        }

        $title = $title ?? $name;

        $this->groups[$name] = [
            'name' => $name,
            'title' => $title,
            'path' => $path,
            'sources' => $sources,
            'type' => $type,
            'package_alias' => $packageAlias
        ];
    }

    public function addGroups($path, $sources, $titles = [], $packageAlias = null)
    {
//        $files = \File::allFiles($path);
//
//        if (count($files)) {
//            foreach ($files as $file) {
//                if ($file->getExtension() == 'php') {
//                    $group = $file->getBaseName('.php');
//
//                    $this->addGroup($group, $path, $sources, ($titles[$group] ?? $group), 'php', $packageAlias);
//                }
//            }
//        }
        if (count($titles)) {
            foreach ($titles as $name => $title) {
                $this->addGroup($name, $path, $sources, $title, 'php', $packageAlias);
            }
        }
    }

    public function getGroup($name)
    {
        if (! isset($this->groups[$name])) {
            throw new \Exception("Can't find translation group: " . $name);
        }
        return $this->groups[$name];
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function getTranslations($group, $withGroupInfo = false, $language = null)
    {
        $language = $language ?? Revy::getLocale();

        if (! isset($this->groups[$group])) {
            throw new \Exception("Can't find translation group: " . $group);
        }

        // if (isset($this->translations[$language][$group])) {
        // return $this->translations[$language][$group];
        // }

        if ($withGroupInfo) {
            $this->translations[$language][$group] = $this->getGroup($group);
            $this->translations[$language][$group]['phrases'] = $this->parse($this->groups[$group], $language);
            $this->translations[$language][$group]['language'] = $language;
        } else {
            $this->translations[$language][$group] = $this->parse($this->groups[$group], $language);
        }

        return $this->translations[$language][$group];
    }

    public function getAllTranslations($withGroupInfo = false, $language = null)
    {
        $translations = [];

        foreach ($this->groups as $name => $group) {
            $data = $this->getTranslations($name, $withGroupInfo, $language);

            if ($data != false) {
                $translations[$name] = $data;
            }
        }

        return $translations;
    }

    public function parse($group, $language)
    {
        $phrases = [];

        $path = $this->getFilePath($group, $language);

        if (! \File::exists($path)) {
            return $phrases;
        }

        if ($group['type'] == 'php') {
            $data = include $path;
        } else {
            $data = json_decode(file_get_contents($path), true);
        }

        if (is_array($data)) {
            $phrases = $data;
        }

        return $phrases;
    }

    public function saveTranslations($group, $translations, $language = false)
    {
        $language = $language ?? Revy::getLocale();

        $group = $this->getGroup($group);

        $path = $this->getFilePath($group, $language);

        if ($group['type'] == 'php') {
            $content = "<?php\nreturn " . var_export($translations, true) . ';';
        } else {
            $content = json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        if (! \File::exists(dirname($path))) {
            \File::makeDirectory(dirname($path), 0755, true);
        }

        file_put_contents($path, $content);
    }

    public function getFilePath($group, $language)
    {
        if ($group['type'] == 'php') {
            return $group['path'] . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $group['name'] . '.php';
        }

        return $group['path'] . DIRECTORY_SEPARATOR . $language . '.json';
    }

    /**
     * @param null $group
     * @param null $language
     * @throws \Exception
     */
    public function indexPhrases($group = null, $language = null)
    {
        $language = $language ?? Revy::getLocale();

        // if ($group !== null) {
        //     $paths = $this->getGroup($group)['sources'];

        //     foreach ($group['sources'] as $source) {
        //         $paths[$source] = $group['name'];
        //     }
        // } else {
        //     foreach ($this->getGroups() as $group) {
        //         foreach ($group['sources'] as $source) {
        //             $paths[$source] = $group['name'];
        //         }
        //     }
        // }

        // $sources = array_flip($paths);

        $groups = $this->getGroups();

        $functions = ['trans', 'trans_choice', 'Lang::get', 'Lang::choice', 'Lang::trans', 'Lang::transChoice', '@lang', '@choice', '__'];

        $groupPattern =                          // See https://regexr.com/3i79b
            // "[^\w|>]" .                       // Must not have an alphanum or _ or > before real method
            '(' . implode('|', $functions) . ')' .  // Must start with one of the functions
            "\(" .                               // Match opening parenthesis
            "[\'\"]" .                           // Match " or '
            '(([a-zA-Z0-9_-]+)::)*' .            // Package prefix
            '(' .                                // Start a new group to match:
                '([a-zA-Z0-9_-]+)' .               // Must start with group
                "([.|\/][^\1)]+)+" .             // Be followed by one or more items/keys
            ')' .                                // Close group
            "[\'\"]" .                           // Closing quote
            "[\),]";                             // Close parentheses or new parameter

        $stringPattern =
            // "[^\w|>]" .                                  // Must not have an alphanum or _ or > before real method
            '(' . implode('|', $functions) . ')' .     // Must start with one of the functions
            "\(" .                                          // Match opening parenthesis
            "(?P<quote>['\"])" .                            // Match " or ' and store in {quote}
            "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)" . // Match any string that can be {quote} escaped
            "\k{quote}" .                                   // Match " or ' previously matched
            "[\),]";                                        // Close parentheses or new parameter

        // Find all PHP files in the app folder, except for storage
        foreach ($groups as $group) {
            $groupKeys = [];
            $stringKeys = [];

            $finder = new Finder();
            $finder->in($group['sources'])->exclude('storage')->name('*.php')->files();

            /** @var \Symfony\Component\Finder\SplFileInfo $file */
            foreach ($finder as $file) {
                // Search the current file for the pattern
                if (preg_match_all("/$groupPattern/siU", $file->getContents(), $matches)) {
                    // Get all matches
                    foreach ($matches[4] as $key => $value) {
                        if ($matches[5][$key] == $group['name'])
                            $groupKeys[] = ($matches[2][$key]) . $value;
                    }
                }

                if (preg_match_all("/$stringPattern/siU", $file->getContents(), $matches)) {
                    foreach ($matches['string'] as $key) {
                        if (preg_match("/(^(([a-zA-Z0-9_-]+)::)*[a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) {
                            // group{.group}.key format, already in $groupKeys but also matched here
                            // do nothing, it has to be treated as a group
                            continue;
                        }
                        $stringKeys[] = $key;
                    }
                }
            }

            $translations = $this->getTranslations($group['name'], false, $language);

            if ($group['type'] == 'json') {
                $stringKeys = array_flip(array_unique($stringKeys));

                $stringKeys = array_map(function(){ return ''; }, $stringKeys);

                $translations = array_merge($stringKeys, array_intersect_key($translations, $stringKeys));
            } else {
                foreach ($groupKeys as $key => $value) {
                    $tmp = explode('.', $value);
                    $groupName = array_shift($tmp);
               
                    if ($groupName !== ($group['package_alias'] !== null ? $group['package_alias'] . '::' : '') . $group['name']) {
                        unset($groupKeys[$key]);
                    }

                    $groupKeys[$key] = implode($tmp, '.');
                }
        
                $groupKeys = array_flip(array_unique($groupKeys));

                $groupKeys = array_map(function(){ return ''; }, $groupKeys);

                $translations = array_merge($groupKeys, array_intersect_key($translations, $groupKeys));
            }

            $this->saveTranslations($group['name'], $translations, $language);
        }
    }

    public function missingKey($namespace, $group, $key)
    {
        // if(!in_array($group, $this->config['exclude_groups'])) {
        //     Translation::firstOrCreate(array(
        //         'locale' => $this->app['config']['app.locale'],
        //         'group' => $group,
        //         'key' => $key,
        //     ));
        // }
    }
}
