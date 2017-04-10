<?php
// src/SH/NewsBundle/DependencyInjection/SHNewsExtension.php

namespace SH\NewsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SHNewsExtension extends Extension
{
  public function load(array $configs, ContainerBuilder $container)
  {
    $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    $loader->load('services.yml');
  }
}