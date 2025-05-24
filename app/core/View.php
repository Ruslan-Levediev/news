<?php
namespace App\Core;

class View {
    private string $template;

    public function __construct(string $template) {
        $this->template = $template;
    }

    public function render(array $data = []): void {
        extract($data);

        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'] . '/project/app/views/' . $this->template . '.php';
        $content = ob_get_clean();

        
        require $_SERVER['DOCUMENT_ROOT'] . '/project/app/views/layouts/main.php';
    }
}
