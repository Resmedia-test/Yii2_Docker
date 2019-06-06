<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m190422_084305_add_fields_tables
 */
class m190422_084305_add_fields_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('users', [
            'id' => 1,
            'auth_key' => 'zxs847krp',
            'last_login' => time(),
            'name' => 'Admin',
            'status' => User::STATUS_ACTIVE,
            'password_hash' => '$2y$13$D7t0GZveLzy3Quhi4LD54O/Suhobff4IscSNP12cQWFZj3KyJHGYO',
            'email' => 'root@resmedia.ru',
        ]);

        $this->insert('auth_assignments', [
            'item_name' => 'admin',
            'user_id' => 1,
            'created_at' => time(),
        ]);

        $this->insert('pages', [
            'url' => '/',
            'title' => 'Главная',
            'content' => '',
            'time_update' => time(),
            'status' => 1
        ]);

        $this->insert('pages', [
            'url' => '/contacts',
            'title' => 'Контакты',
            'content' => '',
            'time_update' => time(),
            'status' => 1
        ]);

        $this->insert('pages', [
            'url' => '/about',
            'title' => 'О сайте',
            'content' => '',
            'time_update' => time(),
            'status' => 1
        ]);

        $this->insert('pages', [
            'url' => '/handbook',
            'title' => 'Библиотека',
            'content' => '',
            'time_update' => time(),
            'status' => 1
        ]);

        $this->insert('menus', [
            'id' => 1,
            'name' => 'main',
            'title' => 'Главное',
            'levels' => 0,
            'status' => 1
        ]);

        $this->insert('menus', [
            'id' => 2,
            'name' => 'footer',
            'title' => 'Нижнее',
            'levels' => 0,
            'status' => 1
        ]);

        $this->insert('menus_links', [
            'menu_id' => 1,
            'parent_id' => 0,
            'class' => '',
            'title' => 'Контакты',
            'url' => '/contacts',
            'order' => 1,
            'status' => 1
        ]);

        $this->insert('menus_links', [
            'menu_id' => 1,
            'parent_id' => 0,
            'class' => '',
            'title' => 'О сайте',
            'url' => '/about',
            'order' => 2,
            'status' => 1
        ]);

        $this->insert('menus_links', [
            'menu_id' => 1,
            'parent_id' => 0,
            'class' => '',
            'title' => 'Справочник',
            'url' => '/handbook',
            'order' => 3,
            'status' => 1
        ]);

        $this->insert('settings', [
            'module_id' => '',
            'code' => 'site_name',
            'name' => 'Название сайта',
            'value' => 'Название Сайта',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert('settings', [
            'module_id' => '',
            'code' => 'copy',
            'name' => 'Копирайт',
            'value' => 'Название Сайта',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert('settings', [
            'module_id' => '',
            'code' => 'rules',
            'name' => 'Права',
            'value' => 'Все права защищены',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert('settings', [
            'module_id' => '',
            'code' => 'yandex',
            'name' => 'Счетчик Яндекс',
            'value' => '',
            'element' => 'textarea',
            'status' => 1,
        ]);

        $this->insert('settings', [
            'module_id' => '',
            'code' => 'google',
            'name' => 'Счетчик Google',
            'value' => '',
            'element' => 'textarea',
            'status' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
