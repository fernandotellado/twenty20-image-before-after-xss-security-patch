# Twenty20 XSS Temporary Security Patch

A lightweight WordPress plugin that mitigates the **Stored Cross-Site Scripting (XSS)** vulnerability found in the **Twenty20 Image Before-After** plugin (reported on WPScan: [e54804c7-68a9-4c4c-94f9-1c3c9b97e8ca](https://wpscan.com/vulnerability/e54804c7-68a9-4c4c-94f9-1c3c9b97e8ca/)).

## Summary

The vulnerability allows an attacker with access to post content (e.g., an author or contributor) to inject malicious JavaScript code through the `[twenty20]` shortcode attributes.  
Because these attributes were not properly sanitized or escaped, any JavaScript injected into parameters like `before`, `after`, `img1`, or `img2` could be stored and executed in the browser of any visitor viewing the affected post.

This patch plugin sanitizes all shortcode attributes before they are passed to the original Twenty20 output function.

## How it works

- Hooks into the `do_shortcode_tag` process for the `[twenty20]` shortcode.  
- Validates and sanitizes all attributes:
  - `img1` and `img2`: only numeric IDs or valid URLs are allowed.  
  - `offset`: converted to a float (0–1 range).  
  - `orientation`: only `'horizontal'` or `'vertical'` values accepted.  
  - `before_label`, `after_label`, and other text values are escaped with `sanitize_text_field()`.  
- Passes the cleaned attributes to the original Twenty20 function, preserving full functionality.

This means you can continue using the shortcode as normal while preventing any stored JavaScript injection attempts.

## Example

Safe shortcode usage:

```php
[twenty20 img1="123" img2="456" before="Before" after="After" offset="0.5"]
```

Any injected code, such as:

```php
[twenty20 img1="javascript:alert(1)" img2="..." before="<script>alert(1)</script>"]
```

will be sanitized and rendered harmless.

## Compatibility

- Tested with **Twenty20 version 2.0.4**  
- Compatible with **WordPress 6.x+**  
- Requires **PHP 7.4 or higher**

## Notes

This is a **temporary patch** intended to protect your site until the official Twenty20 plugin is updated.  
Once the official plugin includes a security fix, deactivate and delete this patch.

---

**Author:** Fernando Tellado  
**Website:** [https://servicios.ayudawp.com](https://servicios.ayudawp.com)  
**License:** GPL-3.0-or-later
