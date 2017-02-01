<?php

namespace Oro\Component\Layout\Extension\Theme\Model;

class ThemeFactory implements ThemeFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function create($themeName, array $themeDefinition)
    {
        $theme = new Theme(
            $themeName,
            isset($themeDefinition['parent']) ? $themeDefinition['parent'] : null
        );

        if (isset($themeDefinition['label'])) {
            $theme->setLabel($themeDefinition['label']);
        }
        if (isset($themeDefinition['screenshot'])) {
            $theme->setScreenshot($themeDefinition['screenshot']);
        }
        if (isset($themeDefinition['icon'])) {
            $theme->setIcon($themeDefinition['icon']);
        }
        if (isset($themeDefinition['logo'])) {
            $theme->setLogo($themeDefinition['logo']);
        }
        if (isset($themeDefinition['directory'])) {
            $theme->setDirectory($themeDefinition['directory']);
        }
        if (isset($themeDefinition['groups'])) {
            $theme->setGroups((array)$themeDefinition['groups']);
        }
        if (isset($themeDefinition['description'])) {
            $theme->setDescription($themeDefinition['description']);
        }
        if (isset($themeDefinition['config'])) {
            $theme->setConfig($themeDefinition['config']);
        }

        if (isset($themeDefinition['config']['page_templates']['templates'])) {
            foreach ($themeDefinition['config']['page_templates']['templates'] as $pageTemplateConfig) {
                $pageTemplate = new PageTemplate(
                    $pageTemplateConfig['label'],
                    $pageTemplateConfig['key'],
                    $pageTemplateConfig['routeName']
                );
                $pageTemplate->setDescription($pageTemplateConfig['description']);
                $pageTemplate->setScreenshot($pageTemplateConfig['screenshot']);
                $theme->addPageTemplate($pageTemplate);
            }
        }

        return $theme;
    }
}
