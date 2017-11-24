<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    public function setup()
    {

        $this->io()->section("Clone repositories");
        
        // Clone Importer API
        $this->taskGitStack()
        ->stopOnFail()
        ->cloneRepo("git@github.com:OpenDataStack/docker-elasticsearch-import-api.git")
        ->run();

        // Clone Symfony project
        $this->_mkdir('docker-elasticsearch-import-api/src');
        $this->taskGitStack()
        ->stopOnFail()
        ->cloneRepo("git@github.com:OpenDataStack/elasticsearch-import-api-symfony.git")
        ->dir("docker-elasticsearch-import-api/src")
        ->run();
        
        // Run composer install for the symfony app
        $this->taskComposerInstall()
        ->dir("docker-elasticsearch-import-api/src/elasticsearch-import-api-symfony/")
        ->run();

    }

    public function rebuild()
    {
        $this->taskExec('docker-compose')->arg('stop')->run();
        $this->taskExec('docker-compose')
        ->arg('up')
        ->option('build')
        ->run();
    }

}