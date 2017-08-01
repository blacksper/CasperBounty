<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new CasperBounty\CasperBountyBundle\CasperBountyCasperBountyBundle(),
            //new Test\MyBundle\TestMyBundle(),
            new CasperBounty\ProjectsBundle\CasperBountyProjectsBundle(),
            new CasperBounty\TargetsBundle\CasperBountyTargetsBundle(),
            new CasperBounty\GroupsBundle\CasperBountyGroupsBundle(),
            new CasperBounty\ResultsBundle\CasperBountyResultsBundle(),
            new CasperBounty\TemplatesBundle\CasperBountyTemplatesBundle(),
            new CasperBounty\ToolsBundle\CasperBountyToolsBundle(),
            new CasperBounty\TasklistBundle\CasperBountyTasklistBundle(),
            new CasperBounty\RegexpBundle\CasperBountyRegexpBundle(),
            new CasperBounty\ConditionsBundle\CasperBountyConditionsBundle(),
            new CasperBounty\FrontBundle\CasperBountyFrontBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new CasperBounty\ProfilesBundle\CasperBountyProfilesBundle(),
            new CasperBounty\ScenarioBundle\CasperBountyScenarioBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
