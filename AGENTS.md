# Open Side Cart for WooCommerce

Plugin WordPress de carrinho lateral (drawer) para WooCommerce, fork livre e de código aberto do Woocommerce Side Cart Premium 4.9.1 (XootiX). Arquivo principal: `open-side-cart.php`; slug e text domain: `open-side-cart`; prefixo de código: `osc`.

## Versões e releases

Toda melhoria ou correção entregue vira uma versão nova publicada no GitHub. Não termine a tarefa sem o release.

1. Escolha a versão (SemVer): correção → `X.Y.Z+1`; recurso novo ou melhoria → `X.Y+1.0`; quebra de compatibilidade → `X+1.0.0`.
2. Atualize a versão em todos estes lugares: cabeçalho `Version:` de `open-side-cart.php` e a constante `OSC_VERSION` no mesmo arquivo.
3. Acrescente a entrada `## X.Y.Z — AAAA-MM-DD` no topo do `CHANGELOG.md`, em inglês como as entradas existentes, dizendo o que mudou para quem usa o plugin.
4. Confira `php -l` nos arquivos PHP alterados.
5. Commit, push da `main`, e crie o release com as notas tiradas do CHANGELOG:
   `gh release create vX.Y.Z --title "Open Side Cart X.Y.Z" --notes-file <notas.md> --latest`
   (não anexe arquivos: o workflow `.github/workflows/release.yml` gera e anexa `open-side-cart.zip` e `open-side-cart-X.Y.Z.zip`).
6. Acompanhe o workflow: `gh run list --workflow release.yml -L 1` e `gh run watch <id> --exit-status`.
7. Confirme: `gh release view vX.Y.Z --json assets -q '.assets[].name'` lista os dois zips, e
   `curl -sIL -o /dev/null -w "%{http_code}" https://github.com/fredpiuma/open-side-cart/releases/latest/download/open-side-cart.zip` responde `200`.
8. Se o workflow falhar por erro no próprio workflow: corrija na `main` e recrie release e tag
   (`gh release delete vX.Y.Z --yes --cleanup-tag` + `gh release create`), porque o workflow usa o código da tag.
