# Images de contenu — organisation par page

Chaque page du site public a son dossier. Cherchez d’abord le dossier de la page, puis le rôle de l’image (`hero`, `mission`, `icons`, …).

```
content/
  _shared/icons/          UI commun (menu, search, chevrons…)
  home/
    hero/visual.jpg       Illustration hero active
    mission/mission.jpg   Bloc mission
    icons/*.svg           Icônes Lucide de la page (liens durs → resources/svg/lucide)
    archive/              Anciennes variantes (récupération)
  about/
    identity.jpg, team.jpg, story.jpg
    icons/  archive/
  products/
    certidocs.jpg, identity.jpg, network.jpg
    icons/  archive/
  news/
    article-1.jpg, article-2.jpg
    icons/
  expertise/              Visuels hub + thématiques
    icons/
  technical-inspection/
    hub.jpg, schema-12-points.jpg, scelle-bulles.jpg
    process/              Étapes terrain / PV
    equipment/            Banc, tablette, serveur…
    icons/
  centres/icons/
  resources/icons/
  contact/icons/
```

## Icônes

- Source runtime : `resources/svg/lucide/{name}.svg` (composant `x-public.icon`).
- Classement par page : `public/images/content/{page}/icons/*.svg` — **mêmes fichiers** (hardlinks Windows). Modifier l’un = modifier l’autre.
- UI global : `content/_shared/icons/`.

## Brand

Logos / favicon : `public/images/brand/` (hors `content/`).
