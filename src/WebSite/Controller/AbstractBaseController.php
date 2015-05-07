<?php
namespace Website\Controller;


abstract class AbstractBaseController {
    public function getConnection() {
        //Use Doctrine DBAL here
        /*****/
        $config = new \Doctrine\DBAL\Configuration();
        //for this array use config_dev.yml and YamlComponents
        // http://symfony.com/…/curr…/components/yaml/introduction.html
        $connectionParams = array(
            'dbname' => 'transversalproject',
            'user' => 'root',
            'password' => '',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        );
        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        // http://docs.doctrine-project.org/…/data-retrieval-and-manip…
        // it's much better if you use QueryBuilder : http://docs.doctrine-project.org/…/refer…/query-builder.html
    }
}