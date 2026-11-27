# Open Side Cart for WooCommerce

A free and open source side cart (drawer) for WooCommerce, with every feature
unlocked and no license activation, remote updater or telemetry.

It is a fork of Woocommerce Side Cart Premium 4.9.1 by XootiX, redistributed under
the GPL. See [CREDITS.md](CREDITS.md) for details. Not affiliated with XootiX.

## Installation

**[Download the plugin (latest release zip)](https://github.com/fredpiuma/open-side-cart/releases/latest/download/open-side-cart.zip)**

This zip contains only the plugin files. Don't install from GitHub's "Source code (zip)": it includes development files and the folder gets the wrong name.

### From the WordPress dashboard

1. Download the zip from the link above.
2. Go to **Plugins → Add New → Upload Plugin**, choose the file and click **Install Now**.
3. Activate **Open Side Cart for WooCommerce** and open the **Side Cart** menu to configure.

To update, upload the zip of the new version the same way and confirm replacing the installed version.

### With WP-CLI

```bash
wp plugin install https://github.com/fredpiuma/open-side-cart/releases/latest/download/open-side-cart.zip --force --activate
```

The same command installs and updates: `--force` replaces the installed version with the latest one, since the plugin has no automatic updates from the dashboard.

Requires WordPress 6.5+ (uses the `Requires Plugins` header) and WooCommerce.

See [CHANGELOG.md](CHANGELOG.md) for what changed in each version.

## Features

- Slide-out cart drawer, floating basket and cart shortcode
- Free shipping bar and rewards progress bar (discounts, gifts, checkpoints)
- Coupon form, shipping calculator and payment buttons in the cart
- Suggested products, quick view (including variable products) and variation editing in the cart
- Save for later (with optional integration with the Easy Login plugin)
- Button themes, header/body/footer layouts and a live preview in the settings

## Translations

The plugin ships with a **Brazilian Portuguese (pt_BR)** translation, loaded
automatically when the site language is Português do Brasil.

Translation files live in `languages/`:

- `open-side-cart.pot`: template with every translatable string;
- `open-side-cart-pt_BR.po`: source of the translation, editable with Poedit or Loco Translate;
- `open-side-cart-pt_BR.mo` and `open-side-cart-pt_BR.l10n.php`: compiled files that WordPress reads.

To add a language or adjust a string on your site, use Loco Translate. Keep the
file in its "Custom" or "System" location so updates don't overwrite it; those
locations take precedence over the files bundled with the plugin. Contributions of
new languages are welcome as pull requests.

## Coming from the XootiX plugin

Option names, hooks, CSS classes and template paths were renamed, so:

- Settings from the free or premium XootiX plugin are **not** imported; configure the fork again.
- Custom code using `xoo_wsc_*` hooks or `.xoo-wsc-*` classes must be updated to `osc_*` / `.osc-*`.
- Template overrides live in `your-theme/templates/open-side-cart/`.

The fork can be active at the same time as the official plugins, though running two side carts is not recommended.

## License

[GPL-2.0-or-later](LICENSE).
