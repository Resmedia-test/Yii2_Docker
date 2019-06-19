<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m190422_084305_add_fields_tables
 */
class m190422_084305_add_fields_tables extends Migration
{
    public $settings = "{{%settings}}";
    public $pages = "{{%pages}}";
    public $menus_links = "{{%menus_links}}";
    public $menus = "{{%menus}}";
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('users', [
            'id' => 1,
            'auth_key' => 'z877xs847krp',
            'last_login' => time(),
            'name' => 'Admin',
            'status' => User::STATUS_ACTIVE,
            'password_hash' => '$2y$13$.wzhXxRPDah7yE.gTlby..Jrv/p.u/ScLJsJbWZb9LK4wFqGYIDW6',
            'email' => 'test@testsite.docker',
        ]);

        $this->insert('auth_assignments', [
            'item_name' => 'admin',
            'user_id' => 1,
            'created_at' => time(),
        ]);

        $this->insert($this->pages, [
            'url' => '/',
            'title' => 'Главная',
            'content' => '',
            'time_update' => time(),
            'status' => 1
        ]);

        $this->insert($this->pages, [
            'url' => '/contacts',
            'title' => 'Контакты',
            'content' => '',
            'time_update' => time(),
            'status' => 1
        ]);

        $this->insert($this->pages, [
            'url' => '/about',
            'title' => 'О сайте',
            'content' => '',
            'time_update' => time(),
            'status' => 1
        ]);

        $this->insert($this->menus, [
            'id' => 1,
            'name' => 'main',
            'title' => 'Главное',
            'levels' => 0,
            'status' => 1
        ]);

        $this->insert($this->menus, [
            'id' => 2,
            'name' => 'footer',
            'title' => 'Нижнее',
            'levels' => 0,
            'status' => 1
        ]);

        $this->insert($this->menus_links, [
            'menu_id' => 1,
            'parent_id' => 0,
            'class' => '',
            'title' => 'Контакты',
            'url' => '/contacts',
            'order' => 1,
            'status' => 1
        ]);

        $this->insert($this->menus_links, [
            'menu_id' => 1,
            'parent_id' => 0,
            'class' => '',
            'title' => 'О сайте',
            'url' => '/about',
            'order' => 2,
            'status' => 1
        ]);

        $this->insert($this->menus_links, [
            'menu_id' => 1,
            'parent_id' => 0,
            'class' => '',
            'title' => 'Публикации',
            'url' => '/articles',
            'order' => 3,
            'status' => 1
        ]);

        $this->insert($this->menus_links, [
            'menu_id' => 1,
            'parent_id' => 0,
            'class' => '',
            'title' => 'Пользователи',
            'url' => '/users',
            'order' => 4,
            'status' => 1
        ]);

        $this->insert($this->settings, [
            'code' => 'site_name',
            'name' => 'Название сайта',
            'value' => 'Название Сайта',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'site_desc',
            'name' => 'Описание сайта',
            'value' => 'Сайт нацелен на реализацию...',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'email_feedback',
            'name' => 'Обратная почта для писем',
            'value' => 'feedback@testsite.docker',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'copy',
            'name' => 'Копирайт',
            'value' => 'Название Сайта',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'address',
            'name' => 'Обратный адрес',
            'value' => 'Адрес компании',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'phone',
            'name' => 'Телефон компании',
            'value' => '+7 (000) 000-00-00',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'rules',
            'name' => 'Права',
            'value' => 'Все права защищены',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'yandex',
            'name' => 'Счетчик Яндекс',
            'value' => '',
            'element' => 'textarea',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'google',
            'name' => 'Счетчик Google',
            'value' => '',
            'element' => 'textarea',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'moderatorEmail',
            'name' => 'Email модератора для уведомлений',
            'value' => 'moderator@testsite.docker',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'adminEmail',
            'name' => 'Email админа для уведомлений',
            'value' => 'admin@testsite.docker',
            'element' => 'text',
            'status' => 1,
        ]);

        $this->insert($this->settings, [
            'code' => 'email_requestArticle',
            'name' => 'Email модератора статей',
            'value' => 'admin@testsite.docker',
            'element' => 'text',
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
