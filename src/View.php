<?php

namespace Skay1994\MyFramework;

class View
{
    public function __construct(
        protected Filesystem $file,
        protected Application $app
    )
    {
    }

    /**
     * Renders a view based on the provided path and optional data.
     *
     * @param string $path The path of the view to render.
     * @param array $data An optional array of data to pass to the view.
     * @throws \Throwable
     * @return string The rendered view content.
     */
    public function render(string $path, array $data = []): string
    {
        $parsed = explode('.', $path) ?? [];
        $path = $this->app->viewsPath($path);
        $path = "$path.php";

        if(count($parsed) > 1) {
            $file = end($parsed);

            unset($parsed[count($parsed) - 1]);
            $path = $this->app->viewsPath(...$parsed);

            $path .= "/$file.php";
        }
        $obLevel = ob_get_level();

        ob_start();

        try {
            $this->file->getRequired($path, $data);
        } catch (\Throwable $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }

            throw $e;
        }

        return ltrim(ob_get_clean());
    }
}