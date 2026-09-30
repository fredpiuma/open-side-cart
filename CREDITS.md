# Credits

## Upstream

Open Side Cart for WooCommerce is a fork of **Woocommerce Side Cart Premium 4.9.1**
by XootiX (https://xootix.com), redistributed under the terms of the GNU General
Public License, version 2 or later. The upstream code is imported unmodified in the
first commit of this repository so the full history of changes is auditable.

Upstream Copyright (C) XootiX.
Modifications Copyright (C) 2026 Frederico de Castro.

This project is not affiliated with, endorsed by or supported by XootiX.
"WooCommerce" and "WordPress" are trademarks of their respective owners.

## Changes from upstream

- License activation, remote updater and usage-tracking code removed.
- Renamed prefixes (`xoo_wsc` / `xoo-wsc` → `osc`, framework `xoo` → `osc_fw` / `osc-fw`),
  text domain and option names, so the fork can run alongside the official plugins.
- Masonry loaded from the bundled copy instead of a CDN.
- Upstream 4.x settings migrations dropped (the fork starts with its own options).

## Third-party libraries

| Library | Version | License | Path |
|---|---|---|---|
| Font Awesome Free | 5.15.4 | Icons CC BY 4.0, fonts SIL OFL 1.1, code MIT | `library/fontawesome5` |
| fontawesome-iconpicker | — | MIT | `library/fontawesome-iconpicker` |
| Magic CSS | — | MIT | `library/magic` |
| Masonry | 4.2.2 | MIT | `library/masonry` |
| lightSlider | 1.1.3 | MIT | `assets/library/lightslider` |
| tsParticles Confetti | — | MIT | `assets/library/confetti` |
| jquery.serializeJSON | — | MIT / GPL | `admin/assets/osc-serializejson.js`, `includes/osc-framework/admin/assets/js/osc-fw-admin-serializejson.js` |

## Assets of uncertain origin

The icon fonts `assets/css/fonts/Woo-Side-Cart.*` and
`includes/osc-framework/admin/assets/css/fonts/OSC-Admin.*` and the images under
`assets/images/` and `admin/assets/images/` come from the upstream package, which
does not state a separate license for them. They are distributed here on the
understanding that the package as a whole is GPL. Replacing them with assets of
known license is tracked as future work.
