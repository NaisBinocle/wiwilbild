# Wiwibild V2 — Design System / Charte Graphique

> Extrait de la maquette Figma V2 "Matériaux Premium"
> Source : https://www.figma.com/design/Ld3lfR9nzltFnXwaKgtDPM

---

## 1. Palette de couleurs

### Couleurs principales
| Token          | Hex       | Usage |
|----------------|-----------|-------|
| `primary`      | `#FF99DA` | CTAs, logo "!", highlights, badges bestseller |
| `primary-light`| `#FFD6EE` | Hover léger, badges fond |
| `primary-soft` | `#FFF0F8` | Fond très léger (hover cards) |
| `secondary`    | `#7B74D0` | Accents, badges, prix, boutons secondaires |
| `secondary-soft`| `#EEEDFA`| Fond léger secondaire |

### Couleurs sombres (sections dark)
| Token          | Hex       | Usage |
|----------------|-----------|-------|
| `primary-dark` | `#362C49` | Nav, sections dark, footer fond |
| `navy-deep`    | `#241C34` | Footer profond, hero overlay |

### Couleurs de texte
| Token          | Hex       | Usage |
|----------------|-----------|-------|
| `foreground`   | `#262626` | Titres, texte fort |
| `text-dark`    | `#404040` | Texte standard fort |
| `body`         | `#525252` | Texte courant |
| `body-light`   | `#737373` | Texte secondaire |
| `muted`        | `#A3A3A3` | Texte discret, placeholders |

### Couleurs utilitaires
| Token          | Hex       | Usage |
|----------------|-----------|-------|
| `border`       | `#E5E5E5` | Bordures, séparateurs |
| `surface`      | `#FAFAFA` | Fond sections alternées |
| `surface-alt`  | `#F5F5F5` | Fond cards, inputs |
| `background`   | `#FFFFFF` | Fond principal |
| `success`      | `#16A34A` | Badges promo, statut OK |
| `success-light`| `#BBF7D0` | Fond badges succès |
| `warning`      | `#F59E0B` | Étoiles avis |
| `warning-dark` | `#D97706` | Badges "meilleure vente" |
| `info`         | `#3B82F6` | Liens, badges info |
| `info-light`   | `#BFDBFE` | Fond badges info |

---

## 2. Typographie

### Familles
| Font              | Usage |
|-------------------|-------|
| **Playfair Display** (ExtraBold) | Titres héros, grandes accroches |
| **Inter** (Regular, Medium, SemiBold, Bold, ExtraBold, Black) | Corps de texte, UI, boutons, navigation |
| **Inter Italic** | Mot d'emphase "premium" dans le hero (style script/cursif) |

### Échelle de tailles
| Taille | Usage |
|--------|-------|
| `40px` | Hero principal (Playfair, ExtraBold) |
| `32px` | Sous-titres héros |
| `28px` | Titres de sections majeurs |
| `24px` | Titres de sections (h2) |
| `22px` | Logo "wwb!" |
| `20px` | Titres cartes, footer logo |
| `18px` | Sous-titres, prix forts |
| `16px` | Boutons CTAs, body large |
| `15px` | Descriptions |
| `14px` | Body standard, nav links |
| `13px` | Body compact, badges, breadcrumbs |
| `12px` | Labels, eyebrows, captions |
| `11px` | Small text, sous-labels |
| `10px` | Micro badges |

### Letter-spacing
| Valeur | Usage |
|--------|-------|
| `-1px` | Titres héros (grandes tailles, resserrées) |
| `-0.5px` | Titres sections (h2) |
| `0.2px` | Body |
| `0.3px` | Labels |
| `1px` | Badges uppercase |
| `1.5px` | Logo, eyebrows uppercase |
| `2px` | Labels uppercase forts |

### Line-heights
| Valeur | Usage |
|--------|-------|
| `46px` | Hero (40px font) |
| `38.4px` | Grand titre (32px) |
| `30.8px` | Titre section (24-28px) |
| `28px` | Sous-titre |
| `25.5-25.6px` | Paragraphes grands |
| `22.4px` | Paragraphes standard |
| `19.5-19.6px` | Body compact |
| `21px` | Body standard |

---

## 3. Espacements

### Layout
| Propriété | Valeur |
|-----------|--------|
| Page max-width | `1440px` |
| Padding latéral | `80px` |
| Padding latéral mobile | `20px` |
| Content max-width | `1280px` |

### Sections
| Propriété | Valeur |
|-----------|--------|
| Padding vertical sections | `64-80px` |
| Gap entre sections | `0` (alternance fond) |
| Gap intra-section | `24-32px` |

### Grilles
| Propriété | Valeur |
|-----------|--------|
| Gap cartes produit | `20-24px` |
| Gap testimonials | `24-28px` |
| Gap blog cards | `20px` |

---

## 4. Border-radius

| Token | Valeur | Usage |
|-------|--------|-------|
| `radius-sm` | `6px` | Badges, pills petites |
| `radius-md` | `10px` | Boutons, inputs |
| `radius-lg` | `12px` | Cartes produit, sections |
| `radius-xl` | `20px` | Cards larges, images hero |
| `radius-2xl` | `28px` | Grandes cartes catégorie |
| `radius-pill` | `36px` ou `100px` | Pills, tags, filtres |

---

## 5. Composants clés

### 5.1 Top Bar (barre de confiance)
- Fond `#F5F5F5` ou `surface`
- 3 items centrés avec icônes : livraison, avis, téléphone
- Texte 12px Inter Medium, couleur `body-light`
- Séparateurs verticaux

### 5.2 Navigation
- Fond blanc, sticky
- Logo "wwb!" — Inter ExtraBold 22px, tracking 1.5px, "!" en `primary`
- Links : Inter Medium 14px, couleur `foreground`, hover `primary`
- Lien actif : souligné `primary`
- Droite : icônes search + user (20px) + bouton panier rose arrondi

### 5.3 Hero
- Image plein écran (photo archi/maison moderne)
- Overlay gradient dark
- Titre : Playfair Display ExtraBold 40px, blanc, tracking -1px
- Mot clé en italique (style cursif)
- Sous-titre : Inter Regular 15px, blanc/90%
- 2 CTAs côte à côte : rose primary + outline blanc
- Badges de confiance dessous : 4 icônes + texte

### 5.4 Carte produit
```
┌──────────────────────┐
│  [Image produit]     │  280px height, radius-lg
│  ┌─────┐             │
│  │BADGE│             │  "COUP DE COEUR" ou "500+ VENDUS"
│  └─────┘             │
├──────────────────────┤
│ Titre produit        │  14px SemiBold
│ ★★★★★ 4.8 (124)     │  stars warning, count muted
│ 42,90 € /m²         │  18px Bold secondary
│ 68 € en négoce      │  12px barré muted
└──────────────────────┘
```
- Bordure : 1px `border`, radius 20px
- Hover : shadow + translateY(-2px)
- Badge : 10px uppercase bold, fond primary/warning

### 5.5 Carte témoignage
- Fond blanc, bordure, radius 12px
- Étoiles en haut
- Texte en italique 13px
- Auteur : photo ronde 40px + nom SemiBold + titre/ville Muted

### 5.6 Section dark (Visualiseur IA, B2B Pro)
- Fond `primary-dark` (#362C49) ou `navy-deep` (#241C34)
- Titres blancs Playfair Display
- Texte `#A3A3A3` ou blanc/70%
- CTAs rose primary

### 5.7 Carte catégorie / ambiance
- Image grande, radius 20-28px
- Overlay gradient noir
- Titre blanc en bas
- Sous-catégories en pills blancs

### 5.8 Section guide / blog
- Cards avec image (radius 12px), titre dessous
- Layout 3 colonnes, gap 20px

### 5.9 Footer
- Fond `navy-deep` (#241C34)
- Logo blanc + description `muted`
- 4 colonnes : Produits, Services, À propos, Support
- Séparateur `#362C49`
- Bottom : copyright + liens légaux

---

## 6. Effets

| Effet | Valeur |
|-------|--------|
| Shadow hover cards | `0 8px 24px rgba(0,0,0,0.08)` |
| Shadow modals | `0 12px 40px rgba(0,0,0,0.15)` |
| Transition default | `0.2s ease` |
| Transform hover | `translateY(-2px)` |
| Gradient hero | `linear-gradient(to right, rgba(0,0,0,0.7), transparent)` |

---

## 7. Breakpoints responsifs

| Breakpoint | Usage |
|------------|-------|
| `1280px` | Large desktop → grille 4 col |
| `1024px` | Tablet landscape → grille 3 col, padding 40px |
| `768px` | Tablet portrait → grille 2 col, stack vertical |
| `480px` | Mobile → grille 1 col, padding 20px |

---

## 8. Iconographie
- Style : Outline, stroke 1.5px
- Taille standard : 16-20px
- Couleur : hérite du texte parent (`currentColor`)
- Format : SVG inline
