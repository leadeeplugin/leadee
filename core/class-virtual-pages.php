<?php
class VirtualPages
{
    public function init_pages($plugin_name, $page)
    {
        add_action('template_redirect', function () use ($plugin_name, $page) {
            $this->genHead($plugin_name, $page);
            require_once LEADEE_PLUGIN_DIR . '/core/partials/header.php';
            require_once LEADEE_PLUGIN_DIR . "/core/pages/{$page}.php";
            require_once LEADEE_PLUGIN_DIR . '/core/partials/footer.php';
            exit;
        });
    }

    private function genHead($plugin_name, $page)
    {
        $admin_language = get_user_locale();
        $head = '<!DOCTYPE html>
        <html lang="' . $admin_language . '" prefix="og: http://ogp.me/ns#">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
            <title>' . ucfirst($plugin_name) . ' - ' . ucfirst($page) . '</title>';
        echo $head;
    }
}


