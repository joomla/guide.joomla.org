<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_playgroundtour
 *
 * @copyright   (C) 2026 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Module\PlaygroundTour\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Uri\Uri;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Shows the guided tour of a User Guide tutorial: a screenshot with a play button that opens a Joomla Playground with
 * the tour in a dialog.
 */
class Dispatcher extends AbstractModuleDispatcher
{
    /**
     * The language of the tours for each language of the site; other languages get the English tour.
     */
    private const LANGUAGES = [
        'en-GB' => 'en',
        'de-DE' => 'de',
        'es-ES' => 'es',
        'fr-FR' => 'fr',
        'it-IT' => 'it',
        'nl-NL' => 'nl',
        'pt-PT' => 'pt',
        'pt-BR' => 'pt-br',
        'ru-RU' => 'ru',
        'fa-IR' => 'fa',
    ];

    /**
     * Returns the layout data; $tour is null when the page has no tour, and the module then shows nothing.
     */
    protected function getLayoutData()
    {
        $data         = parent::getLayoutData();
        $data['tour'] = null;
        $params       = $data['params'];

        // tours.json: { name: { number, paths: [...], languages: { code: { title, steps } } } }, made by
        // tools/build-module.mjs from the tours.
        $tours = json_decode((string) file_get_contents(JPATH_SITE . '/modules/mod_playgroundtour/tours.json'), true) ?: [];
        $name  = $params->get('tour', 'auto');

        if ($name === 'auto') {
            $name = $this->tourOfPage($tours);
        }

        if (!$name || !isset($tours[$name])) {
            return $data;
        }

        $tag  = $this->getApplication()->getLanguage()->getTag();
        $code = self::LANGUAGES[$tag] ?? 'en';

        if (!isset($tours[$name]['languages'][$code])) {
            $code = 'en';
        }

        $blueprints = rtrim($params->get('blueprints', 'blueprints/tours'), '/');
        $image      = 'media/mod_playgroundtour/images/' . $code . '/' . $name . '.jpg';

        $data['tour'] = [
            'name'   => $name,
            'number' => $tours[$name]['number'] ?? '',
            'title'  => $tours[$name]['languages'][$code]['title'],
            'steps'  => $tours[$name]['languages'][$code]['steps'],
            'lang'   => array_search($code, self::LANGUAGES, true),
            'link'   => $params->get('playground', 'https://joomla-projects.github.io/joomla-playground/')
                . '?blueprint-url=' . str_replace(['%2F', '%3A'], ['/', ':'], rawurlencode($blueprints . '/' . $code . '/' . $name . '.json')),
            'image'  => is_file(JPATH_ROOT . '/' . $image) ? Uri::root(true) . '/' . $image : null,
        ];

        return $data;
    }

    /**
     * The tour of the tutorial on this page, by the page's path in any language; null when the page is no tutorial.
     */
    private function tourOfPage(array $tours): ?string
    {
        $path = rawurldecode(Uri::getInstance()->getPath());
        $path = substr($path, \strlen(Uri::base(true)));
        $path = preg_replace(['#^/index\.php#', '#\.html$#', '#/+$#'], '', $path);
        $path = strtolower($path);

        foreach ($tours as $name => $tour) {
            if (\in_array($path, $tour['paths'] ?? [], true)) {
                return $name;
            }
        }

        return null;
    }
}
