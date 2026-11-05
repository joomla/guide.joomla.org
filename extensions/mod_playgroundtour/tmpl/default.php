<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_playgroundtour
 *
 * @copyright   (C) 2026 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var array|null $tour */
/** @var \Joomla\Registry\Registry $params */
/** @var \Joomla\CMS\Application\SiteApplication $app */
/** @var \stdClass $module */

if (!$tour) {
    return;
}

$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('mod_playgroundtour');
$wa->useStyle('mod_playgroundtour')->useScript('mod_playgroundtour');

$title  = htmlspecialchars($tour['title'], ENT_QUOTES, 'UTF-8');
$link   = htmlspecialchars($tour['link'], ENT_QUOTES, 'UTF-8');
$steps  = Text::plural('MOD_PLAYGROUNDTOUR_N_STEPS', $tour['steps']);
$id     = 'mod-playgroundtour-' . $module->id;
$button = $params->get('display', 'poster') === 'button';
?>
<h2 class="mod-playgroundtour__heading"><?php echo Text::_('MOD_PLAYGROUNDTOUR_HEADING'); ?></h2>
<div class="mod-playgroundtour" data-playgroundtour>
    <?php if ($button) : ?>
        <button type="button" class="btn btn-primary mod-playgroundtour__button" aria-haspopup="dialog" aria-controls="<?php echo $id; ?>" data-playgroundtour-open>
            <span class="mod-playgroundtour__icon" aria-hidden="true"></span>
            <?php echo Text::_('MOD_PLAYGROUNDTOUR_PLAY'); ?>
        </button>
    <?php else : ?>
        <button type="button" class="mod-playgroundtour__poster" aria-haspopup="dialog" aria-controls="<?php echo $id; ?>" data-playgroundtour-open>
            <?php if ($tour['image']) : ?>
                <img src="<?php echo htmlspecialchars($tour['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="" width="768" height="480" loading="lazy">
            <?php else : ?>
                <span class="mod-playgroundtour__card" aria-hidden="true">
                    <span class="mod-playgroundtour__card-title"><?php echo $title; ?></span>
                    <span class="mod-playgroundtour__card-steps">1 / <?php echo (int) $tour['steps']; ?></span>
                </span>
            <?php endif; ?>
            <span class="mod-playgroundtour__play" aria-hidden="true"></span>
            <span class="mod-playgroundtour__caption">
                <strong><?php echo Text::_('MOD_PLAYGROUNDTOUR_PLAY'); ?></strong>
                <span><?php echo $title; ?> · <?php echo $steps; ?></span>
            </span>
        </button>
    <?php endif; ?>
    <p class="mod-playgroundtour__note"><?php echo Text::_('MOD_PLAYGROUNDTOUR_NOTE'); ?><br><?php echo Text::_('MOD_PLAYGROUNDTOUR_NOTE_START'); ?></p>
    <dialog id="<?php echo $id; ?>" class="mod-playgroundtour__dialog" aria-label="<?php echo $title; ?>">
        <div class="mod-playgroundtour__bar">
            <span class="mod-playgroundtour__title"><?php echo $title; ?></span>
            <a href="<?php echo $link; ?>" target="_blank" rel="noopener" data-playgroundtour-tab><?php echo Text::_('MOD_PLAYGROUNDTOUR_NEW_TAB'); ?></a>
            <button type="button" class="mod-playgroundtour__close" data-playgroundtour-close>
                <span aria-hidden="true">×</span>
                <span class="visually-hidden"><?php echo Text::_('MOD_PLAYGROUNDTOUR_CLOSE'); ?></span>
            </button>
        </div>
        <iframe data-src="<?php echo $link; ?>" aria-label="<?php echo Text::_('MOD_PLAYGROUNDTOUR_FRAME'); ?>" lang="<?php echo $tour['lang']; ?>" allow="clipboard-read; clipboard-write; fullscreen"></iframe>
    </dialog>
</div>
