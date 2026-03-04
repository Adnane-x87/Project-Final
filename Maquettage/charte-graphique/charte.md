# Charte Graphique — ProMatch

Ce document définit l'identité visuelle et les standards UI du projet ProMatch. L'objectif est de garantir une cohérence esthétique et fonctionnelle à travers toutes les interfaces (Espace Public et Tableau de Bord Admin).

---

## 1. Identité & Logo

### Le Logo

- **Concept** : Professionnalisme, Dynamisme, Gazon.
- **Police du Logo** : Plus Jakarta Sans (ExtraBold).
- **Symbole** : Un carré arrondi (`rounded-xl`) avec la lettre "P" sur fond `brand-600`.
- **Texte** : "Pro" (Noir/Slate-900) "Match" (Vert/Brand-600).

### Utilisation du Logo

- **Header Public** : Hauteur fixe (`h-32`), positionné avec un décalage négatif pour un effet d'envergure. (Chemin : `./public/images/logo.png`)
- **Sidebar Admin** : Centré, bordure inférieure légère. (Chemin : `../public/images/logo.png`)

---

## 2. Palette de Couleurs

Le projet utilise une palette principale basée sur un **Vert Naturel** et des tons de **Slate** pour la neutralité.

### Couleurs Primaires (Brand)

| Nom           | Code HEX  | Utilisation                   |
| :------------ | :-------- | :---------------------------- |
| **Brand 50**  | `#f0f9f1` | Fonds légers, alertes succès  |
| **Brand 300** | `#8dca9e` | Bordures interactives         |
| **Brand 500** | `#4da565` | Couleur d'accent principale   |
| **Brand 600** | `#3d8a54` | Over (Hov), Boutons actifs    |
| **Brand 700** | `#327145` | Texte sur fond clair (Succès) |

### Couleurs Neutres (Slate)

| Nom           | Code HEX  | Utilisation                            |
| :------------ | :-------- | :------------------------------------- |
| **Slate 50**  | `#f8fafc` | Fond de page principal                 |
| **Slate 200** | `#e2e8f0` | Bordures de cartes et d'inputs         |
| **Slate 500** | `#64748b` | Texte secondaire, icônes               |
| **Slate 900** | `#0f172a` | Texte principal, titres, boutons noirs |

---

## 3. Typographie

**Police de caractère principale** : `Plus Jakarta Sans`
Lien Google Fonts : `https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap`

### Échelle Typographique

- **Titres H1** : `text-4xl` (Public) ou `text-2xl` (Admin), Bold/ExtraBold.
- **Sous-titres** : `text-lg` ou `text-xl`, SemiBold.
- **Corps de texte** : `text-sm` (Standard Admin) ou `text-base` (Standard Public).
- **Petites légendes** : `text-xs`, Medium/SemiBold.

---

## 4. Composants UI (Standards)

### Boutons

- **Forme** : Coins arrondis prononcés (`rounded-xl` ou `rounded-2xl`).
- **Styles** :
  - **Principal** : Fond `brand-500`, Texte blanc, Ombre portée légère.
  - **Secondaire** : Bordure `slate-200`, Fond blanc, Texte `slate-700`.
  - **Danger** : Fond `rose-500`, Texte blanc.

### Cartes (Cards)

- **Style** : Fond blanc, bordure `slate-200`, sans ombre ou ombre très subtile (`shadow-sm`).
- **Padding** : Standard `p-6` pour le contenu principal.

### Inputs (Champs de saisie)

- **Style** : Bordure `slate-300`, coins `rounded-lg`.
- **Focus** : Bordure `brand-500`, anneau (`ring`) `brand-100`.

---

## 5. Icônes

Nous utilisons les icônes **Heroicons** (Outline) pour leur clarté et leur modernité.

- **Taille Standard** : `w-5 h-5`.
- **Stroke Width** : `2px`.
- **Couleur** : `slate-400` par défaut, `brand-600` pour les états actifs.

---

## 6. Espacements et Grille

- **Grille de base** : 4px (Utilisation des utilitaires Tailwind : `p-4`, `m-6`, `gap-3`).
- **Marges de page** : `max-w-7xl` avec un padding horizontal `px-4 sm:px-6 lg:px-8`.
