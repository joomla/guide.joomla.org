# mod_playgroundtour

A site module that puts the guided tour of a tutorial on its page: a screenshot of the tour's first step with a play
button. The button opens a dialog of almost the whole page, with the
[Joomla Playground](https://joomla-projects.github.io/joomla-playground/) and the tour in an iframe.

The tour, the screenshot and the module's texts are in the language of the page (de-DE, es-ES, fr-FR, it-IT, nl-NL,
pt-PT, pt-BR; English for the other languages).

## Settings

- **Tour:** *Automatic* (the default) finds the tutorial by the page's path, in any of its languages. On pages that
  are no tutorial the module shows nothing, so one module, assigned to all pages, serves every tutorial. Or choose
  one tour, for a module in a single article (`{loadmoduleid 123}`).
- **Display:** the screenshot with a play button, or a plain button.
- **Playground** and **Blueprints:** where the tours are. The default loads the blueprints the Playground publishes:
  `https://joomla-projects.github.io/joomla-playground/?blueprint-url=blueprints/tours/<language>/<name>.json`.
  Blueprints can also be a full address, such as a `raw.githubusercontent.com` folder of a commit of
  joomla-projects/joomla-playground (`.../<commit>/web/public/blueprints/tours`).

The playground loads the first time the dialog opens and keeps running until the page is left, so closing the dialog
loses nothing. *Open in a new tab* in the dialog's title bar starts the tour in a tab of its own.

The playground runs in an iframe on another site only since its service worker leaves out Joomla's
`X-Frame-Options: SAMEORIGIN` header (joomla-projects/joomla-playground, "Playground: work inside an iframe on
another site").

## Generated files

`tours.json` (every tour: its tutorial's number and paths in all languages, its title and number of steps per
language), the Tour options in `mod_playgroundtour.xml` and the screenshots in `media/images/<language>/<name>.jpg`
are generated from the tours by `tools/build-module.mjs` and `tools/screenshot-tours.mjs` of the guided tours
project. A tour without a screenshot gets a drawn popup with its title instead.

## Package

```
git archive -o mod_playgroundtour.zip HEAD:extensions/mod_playgroundtour
```
