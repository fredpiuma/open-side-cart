# Open Side Cart for WooCommerce

A free and open source side cart (drawer) for WooCommerce, with every feature
unlocked and no license activation, remote updater or telemetry.

It is a fork of Woocommerce Side Cart Premium 4.9.1 by XootiX, redistributed under
the GPL. See [CREDITS.md](CREDITS.md) for details. Not affiliated with XootiX.

## Features

- Slide-out cart drawer, floating basket and cart shortcode
- Free shipping bar and rewards progress bar (discounts, gifts, checkpoints)
- Coupon form, shipping calculator and payment buttons in the cart
- Suggested products, quick view (including variable products) and variation editing in the cart
- Save for later (with optional integration with the Easy Login plugin)
- Button themes, header/body/footer layouts and a live preview in the settings

## Requirements

- WordPress 6.5+ (uses the `Requires Plugins` header)
- WooCommerce

## Installation

1. Download a release zip (or run `git archive --format=zip --prefix=open-side-cart/ -o open-side-cart.zip HEAD`).
2. In WordPress, go to *Plugins → Add New → Upload Plugin* and upload the zip.
3. Activate it and open the **Side Cart** menu to configure.

## Coming from the XootiX plugin

Option names, hooks, CSS classes and template paths were renamed, so:

- Settings from the free or premium XootiX plugin are **not** imported; configure the fork again.
- Custom code using `xoo_wsc_*` hooks or `.xoo-wsc-*` classes must be updated to `osc_*` / `.osc-*`.
- Template overrides live in `your-theme/templates/open-side-cart/`.

The fork can be active at the same time as the official plugins, though running two side carts is not recommended.

## License

[GPL-2.0-or-later](LICENSE).
