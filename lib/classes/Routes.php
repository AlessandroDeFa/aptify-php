<?php
class Routes {
    public static function Registration() {
        header("Location: /admin/auth/register");
        exit;
    }

    public static function Login() {
        header("Location: /admin/auth/login");
        exit;
    }

    public static function Dashboard() {
        header("Location: /admin/contents");
        exit;
    }

    public static function NotFound() {
        header("Location: /404");
        exit;
    }

    public static function redirectTo($url) {
        header("Location: $url");
        exit;
    }
}
?>
