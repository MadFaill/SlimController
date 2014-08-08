SlimController
==============

Набор компонентов, позволяющих полноценно использовать Контроллеры в Slim.
Присутствует некое подобие **HMVC** так как можно дергать модуль внутри модуля и тому подобное.
Что позволяет использовать некоторые методы конкретных действий внутри других действий.

Как это работает
================

**Инициализация**

```php

$slim = new \Slim\Slim();
$app = new \SlimController\Controller($slim);

$app->add_command('/set/:aaa/', "main::main");

$app->add_route('/test/:a/:b/', function($a, $b) { var_dump($a, $b); });
$app->add_route('/', 'main::index');

/**
 * Формирование модуля
 * По сути, тут и определяется как и где лежат модули
 */
$app->registerModuleDispatcherCallback(function($module_name, $controller) {
	$class_name = sprintf('\Modules\%s\Module%s', ucfirst($module_name), ucfirst($module_name));
	return new $class_name($controller);
});


// запуск приложения
// определение консольный запуск или же из под апача
$mode = (!isset($_SERVER['HTTP_HOST'])) ?
	\SlimController\Controller::MODE_CLI :
	\SlimController\Controller::MODE_WEB;

$app->run($mode);

```

Теперь внутри модуля уже можно использовать `экшены` они же `действия`.
Более подробный пример можно посмотреть в папке с примерами `examples`. Рассматриваются базовые действия.

```php

// запуск метода конкретного экшена
$this->createAction('action-name')->someMethod();

// создание саб-экшена
$this->createAction('action-name')->createAction('another-action');

// дергает другой модуль и действие в нем
$this->getController()->createModule('module-name')->createAction('action-name);

```

Доступны как консольные роуты так и роуты в web. Консольные строятся на базе роутера Slim {via('CONSOLE')}
что позволяет им игнорироваться при обходе web роутов и наоборот рассматриваться в случае запуска из-под консоли.


Установка
=========

```json
{
    "require": {
        "mad-tools/slim-controller": "dev-master"
    }
}
```